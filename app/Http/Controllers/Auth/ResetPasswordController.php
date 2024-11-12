<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserResetRequest;
use App\Models\User;
use DB;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    public function resetForm(Request $request, $token = null)
    {
        $isTokenExists = DB::tabel('password_reset')
            ->where('token', $token)
            ->first();

        if ($isTokenExists) {
            return redirect()->back()->with('fail', 'Token non valido. Richiedere un\' altro link per resettare la password!');
        } else {
            return view('auth.passwords.reset', ['token' => $token]);
        }
    }

    /**
     * Write code on Method
     *
     * @param  UserResetRequest  $request  The validated user store request.
     * @return RedirectResponse A redirect response based on the user's role.
     */
    public function reset(UserResetRequest $request): RedirectResponse
    {
        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();

        $dbToken = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        $user = User::where('email', Encrypter::encrypt($dbToken->email))->first();

        $updatePassword = $user->update([
            'password' => Hash::make($request->password),
        ]);

        if (! $updatePassword) {
            return back()->withInput()->with('fail', 'Token non valido!');
        }

        DB::table('password_resets')->where(['email' => $dbToken->email])->delete();

        return Redirect::route(route: 'login')->with('message', 'La tua password è stata modificata correttamente!');
    }
}
