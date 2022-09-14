<?php

namespace App\Http\Controllers\Fsp;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizationController extends Controller
{
    public function checkValidCode($fsp, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$fsp->ver_code_send_at) return false;
        if ($fsp->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($fsp->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {
        if (auth()->guard('fsp')->check()) {
            $fsp = auth()->guard('fsp')->user();
            if (!$fsp->status) {
                Auth::guard('fsp')->logout();
            }elseif (!$fsp->ev) {
                if (!$this->checkValidCode($fsp, $fsp->ver_code)) {
                    $fsp->ver_code = verificationCode(6);
                    $fsp->ver_code_send_at = Carbon::now();
                    $fsp->save();
                    sendEmail($fsp, 'EVER_CODE', [
                        'code' => $fsp->ver_code
                    ]);
                }
                $pageTitle = 'Email verification form';
                return view('fsp.auth.authorization.email', compact('fsp', 'pageTitle'));
            }elseif (!$fsp->sv) {
                if (!$this->checkValidCode($fsp, $fsp->ver_code)) {
                    $fsp->ver_code = verificationCode(6);
                    $fsp->ver_code_send_at = Carbon::now();
                    $fsp->save();
                    sendSms($fsp, 'SVER_CODE', [
                        'code' => $fsp->ver_code
                    ]);
                }
                $pageTitle = 'SMS verification form';
                return view('fsp.auth.authorization.sms', compact('fsp', 'pageTitle'));
            }elseif (!$fsp->tv) {
                $pageTitle = 'Google Authenticator';
                return view('fsp.auth.authorization.2fa', compact('fsp', 'pageTitle'));
            }else{
                return redirect()->route('fsp.dashboard');
            }

        }

        return redirect()->route('fsp.login');
    }

    public function sendVerifyCode(Request $request)
    {
        $fsp = Auth::guard('fsp')->user();


        if ($this->checkValidCode($fsp, $fsp->ver_code, 2)) {
            $target_time = $fsp->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($fsp, $fsp->ver_code)) {
            $fsp->ver_code = verificationCode(6);
            $fsp->ver_code_send_at = Carbon::now();
            $fsp->save();
        } else {
            $fsp->ver_code = $fsp->ver_code;
            $fsp->ver_code_send_at = Carbon::now();
            $fsp->save();
        }



        if ($request->type === 'email') {
            sendEmail($fsp, 'EVER_CODE',[
                'code' => $fsp->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            sendSms($fsp, 'SVER_CODE', [
                'code' => $fsp->ver_code
            ]);
            $notify[] = ['success', 'SMS verification code sent successfully'];
            return back()->withNotify($notify);
        } else {
            throw ValidationException::withMessages(['resend' => 'Sending Failed']);
        }
    }

    public function emailVerification(Request $request)
    {
        $request->validate([
            'email_verified_code'=>'required'
        ]);


        $email_verified_code = str_replace(' ','',$request->email_verified_code);
        $fsp = Auth::guard('fsp')->user();

        if ($this->checkValidCode($fsp, $email_verified_code)) {
            $fsp->ev = 1;
            $fsp->ver_code = null;
            $fsp->ver_code_send_at = null;
            $fsp->save();
            return redirect()->route('fsp.dashboard');
        }
        throw ValidationException::withMessages(['email_verified_code' => 'Verification code didn\'t match!']);
    }

    public function smsVerification(Request $request)
    {
        $request->validate([
            'sms_verified_code' => 'required',
        ]);


        $sms_verified_code =  str_replace(' ','',$request->sms_verified_code);

        $fsp = Auth::guard('fsp')->user();
        if ($this->checkValidCode($fsp, $sms_verified_code)) {
            $fsp->sv = 1;
            $fsp->ver_code = null;
            $fsp->ver_code_send_at = null;
            $fsp->save();
            return redirect()->route('fsp.dashboard');
        }
        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);
    }
    public function g2faVerification(Request $request)
    {
        $fsp = auth()->guard('fsp')->user();
        $request->validate([
            'code' => 'required',
        ]);
        $code = str_replace(' ','',$request->code);
        $response = verifyG2fa($fsp,$code);
        if ($response) {
            $notify[] = ['success','Verification successful'];
        }else{
            $notify[] = ['error','Wrong verification code'];
        }
        return back()->withNotify($notify);
    }
}
