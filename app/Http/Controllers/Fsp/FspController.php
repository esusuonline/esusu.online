<?php

namespace App\Http\Controllers\Fsp;

use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use App\Models\GeneralSetting;
use App\Models\Fsp;
use App\Models\PaidLog;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FspController extends Controller
{
    public function dashboard()
    {
        $pageTitle = 'Dashboard';
        $widget['today_loan_collection_count'] = PaidLog::where('fsp_id', auth('fsp')->id())->whereDate('created_at', now()->format('Y-m-d'))->where('loan_id', '!=', 0)->count();
        $widget['today_loan_collection_amount'] = PaidLog::where('fsp_id', auth('fsp')->id())->whereDate('created_at', now()->format('Y-m-d'))->where('loan_id', '!=', 0)->sum('final_amount'); 
        $widget['today_savings_collection_count'] = PaidLog::where('fsp_id', auth('fsp')->id())->whereDate('created_at', now()->format('Y-m-d'))->where('savings_id', '!=', 0)->count();
        $widget['today_savings_collection_amount'] = PaidLog::where('fsp_id', auth('fsp')->id())->whereDate('created_at', now()->format('Y-m-d'))->where('savings_id', '!=', 0)->sum('final_amount');
        $payments = PaidLog::where('fsp_id', auth('fsp')->id())->where('status', 1)->with('user', 'loan.loanPlan', 'savings.savingsPlan')->latest()->limit(10)->get();
        $emptyMessage = 'No payment history found';
        
        return view('fsp.dashboard', compact('pageTitle', 'widget', 'payments', 'emptyMessage'));
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $fsp = Auth::guard('fsp')->user();
        return view('fsp.profile', compact('pageTitle', 'fsp'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|integer|min:1',
            'city' => 'sometimes|required|max:50',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])],
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);
        $fsp = Auth::guard('fsp')->user();

        $fsp->firstname= $request->firstname;
        $fsp->lastname= $request->lastname;

        $user = Auth::guard('fsp')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, imagePath()['profile']['fsp']['path'], imagePath()['profile']['fsp']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        
        if ($request->hasFile('cac_docs')) {
            try {
                $old = $user->cac_docs ?: null;
                $user->cac_docs = uploadImage($request->cac_docs, imagePath()['cac']['fsp']['path'], imagePath()['cac']['fsp']['size'], $old);
                Fsp::where('id', Auth::guard('fsp')->user()->id)->update(['cac_status' => 1]);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }


        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$fsp->address->country,
            'city' => $request->city,
        ];
        
        $fsp->fill($in)->save();
        

        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);

    }

    public function changePassword()
    {
        $pageTitle = 'Change password';
        $fsp = Auth::guard('fsp')->user();
        return view('fsp.password', compact('pageTitle', 'fsp'));
    }

    public function submitPassword(Request $request)
    {
        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);

        try {
            $fsp = auth()->guard('fsp')->user();
            if (Hash::check($request->current_password, $fsp->password)) {
                $password = Hash::make($request->password);
                $fsp->password = $password;
                $fsp->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $fsp = auth()->guard('fsp')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($fsp->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor';
        return view('fsp.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $fsp = auth()->guard('fsp')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($fsp,$request->code,$request->key);
        if ($response) {
            $fsp->tsc = $request->key;
            $fsp->ts = 1;
            $fsp->save();
            $fspAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($fsp, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$fspAgent['ip'],
                'time' => @$fspAgent['time']
            ], 'fsp');
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $fsp = auth()->guard('fsp')->user();
        $response = verifyG2fa($fsp,$request->code);
        if ($response) {
            $fsp->tsc = null;
            $fsp->ts = 0;
            $fsp->save();
            $fspAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($fsp, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$fspAgent['ip'],
                'time' => @$fspAgent['time']
            ], 'fsp');
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function paymentHistory($filter = null, $adminReceive = null){
        $pageTitle = 'Payment History';
        $emptyMessage = 'No history found';
        $segment = request()->segment(3);

        $payments = PaidLog::where('fsp_id', auth('fsp')->id())->where('status', 1);

        if($filter == 'today'){
            $payments = $payments->whereDate('created_at', now()->format('Y-m-d'));
        }elseif($filter != null){
            $payments = $payments->whereDate('created_at', $filter);
        }

        if($adminReceive != null){
            $payments = $payments->where('admin_receive', $adminReceive);
        }

        if($segment == 'loan'){
            $payments = $payments->where('loan_id', '!=', 0)->with('loan.loanPlan');
        }elseif($segment == 'savings'){
            $payments = $payments->where('savings_id', '!=', 0)->with('savings.savingsPlan');
        }else{
            $payments = $payments->with('loan.loanPlan', 'savings.savingsPlan');
        }
        $payments = $payments->with('user')->latest()->paginate(getPaginate());

        return view('fsp.payment_history', compact('pageTitle', 'emptyMessage', 'payments'));
    }
}
