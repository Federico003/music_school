<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Mail;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use ESolution\DBEncryption\Encrypter;

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

        //dd($request);

        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    //dd($value);
                    $encryptedValue = Encrypter::encrypt($value);

                    $existingRecord = User::where('email', $encryptedValue)->first();

                    if (!$existingRecord) {
                        $fail('L\'indirizzo email non è registrato nel sistema');
                    }
                }
            ]
        ]);

          $user = User::where('email', Encrypter::encrypt($request->email))->first();

          //dd($user->email);

          $token = Str::random(64);

          $oldToken = DB::table('password_resets')
                        ->where('email', $user->email)
                        ->first();

           if($oldToken){
                DB::table('password_resets')
                    ->where('email', $user->email)
                    ->update([
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ]);
           }else{
                DB::table('password_resets')->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
           }


          $sendedmail = Mail::send('auth.passwords.mail.email', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password');
          });

          if($sendedmail){
            return redirect()->back()->with('success', 'Ti abbiamo mandato un email con il link per il reset della password!');
          }else{
            return redirect()->back()->with('fail', 'Qualcosa è andato storto, non siamo riusciti a inviarti la mail con il link per il reset della password. Riprova più tardi.');
          }
    }
}
