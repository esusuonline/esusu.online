<?php

namespace App\Http\Controllers\Fsp\Auth;

use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Models\FspPasswordReset;
use App\Models\Fsp;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

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
    

    public function __construct()
    {
        $this->middleware('fsp.guest');
    }

    public function showResetForm(Request $request, $token = null)
    {

        $email = session('fpass_email');
        $token = session()->has('token') ? session('token') : $token;
        if (FspPasswordReset::where('token', $token)->where('email', $email)->count() != 1) {
            $notify[] = ['error', 'Invalid token'];
            return redirect()->route('fsp.password.request')->withNotify($notify);
        }
        return view('fsp.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $email, 'pageTitle' => 'Reset Password']
        );
    }

    public function reset(Request $request)
    {

        session()->put('fpass_email', $request->email);
        $request->validate($this->rules(), $this->validationErrorMessages());
        $reset = FspPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        if (!$reset) {
            $notify[] = ['error', 'Invalid verification code'];
            return redirect()->route('fsp.login')->withNotify($notify);
        }

        $fsp = Fsp::where('email', $reset->email)->first();
        $fsp->password = bcrypt($request->password);
        $fsp->save();



        $fspIpInfo = getIpInfo();
        $fspBrowser = osBrowser();
        sendEmail($fsp, 'PASS_RESET_DONE', [
            'operating_system' => @$fspBrowser['os_platform'],
            'browser' => @$fspBrowser['browser'],
            'ip' => @$fspIpInfo['ip'],
            'time' => @$fspIpInfo['time']
        ]);

        $notify[] = ['success', 'Password changed successfully'];
        return redirect()->route('fsp.login')->withNotify($notify);
    }



    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required','confirmed',$password_validation],
        ];
    }

}
