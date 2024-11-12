<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use DB;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function showLinkRequestForm()
    {
        return view('auth.passwords.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $encryptedValue = Encrypter::encrypt($value);
                    $existingRecord = User::where('email', $encryptedValue)->first();

                    if (! $existingRecord) {
                        $fail('L\'indirizzo email non è registrato nel sistema');
                    }
                },
            ],
        ]);

        $user = User::where('email', Encrypter::encrypt($request->email))->first();
        $token = Str::random(64);

        $oldToken = DB::table('password_resets')
            ->where('email', $user->email)
            ->first();

        if ($oldToken) {
            DB::table('password_resets')
                ->where('email', $user->email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
        } else {
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
        }

        $sendedmail = Mail::send('auth.passwords.mail.email', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        if ($sendedmail) {
            return redirect()->back()->with('success', 'Ti abbiamo mandato un email con il link per il reset della password!');
        } else {
            return redirect()->back()->with('fail', 'Qualcosa è andato storto, non siamo riusciti a inviarti la mail con il link per il reset della password. Riprova più tardi.');
        }
    }
}
