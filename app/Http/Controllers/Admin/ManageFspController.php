<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\GeneralSetting;
use App\Models\PaidLog;
use App\Models\Fsp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ManageFspController extends Controller
{
    protected $pageTitle;

    protected function filterFsps($scope = null)
    {
        $request    = request();
        $fsps     = Fsp::query();

        if($scope){
            $fsps = $fsps->$scope();
        }
        $pageTitle  = $this->pageTitle;

        $search     = $request->search;

        if($search){
            $fsps = $fsps->where(function ($fsp) use ($search) {
                $fsp->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $fsps         = $fsps->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage   = 'No Fsp found';

        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('admin.fsps.list', compact('pageTitle', 'emptyMessage', 'fsps', 'scope', 'mobile_code', 'countries'));
    }

    public function allFsps()
    {
        $this->pageTitle = 'All Fsps';
        return $this->filterFsps();
    }

    public function activeFsps()
    {
        $this->pageTitle = 'Active Fsps';
        return $this->filterFsps('active');
    }

    public function bannedFsps()
    {
        $this->pageTitle = 'Banned Fsps';
        return $this->filterFsps('banned');
    }

    public function emailUnverifiedFsps()
    {
        $this->pageTitle = 'Email Unverified Fsps';
        return $this->filterFsps('emailUnverified');
    }
    
    public function cacUnverifiedFsps()
    {
        $this->pageTitle = 'CAC Unverified Fsps';
        return $this->filterFsps('cacUnverified');
    }


    public function emailVerifiedFsps()
    {
        $this->pageTitle = 'Email Verified Fsps';
        return $this->filterFsps('emailVerified');
    }

    public function smsUnverifiedFsps()
    {
        $this->pageTitle = 'SMS Unverified Fsps';
        return $this->filterFsps('smsUnverified');
    }


    public function smsVerifiedFsps()
    {
        $this->pageTitle = 'SMS Verified Fsps';
        return $this->filterFsps('smsVerified');
    }

    public function detail($id)
    {
        $pageTitle = 'Fsp Detail';
        $fsp = Fsp::findOrFail($id);

        $widget['today_loan_collection_count'] = PaidLog::where('fsp_id', $id)->whereDate('created_at', now()->format('Y-m-d'))->where('loan_id', '!=', 0)->count();
        $widget['today_loan_collection_amount'] = PaidLog::where('fsp_id', $id)->whereDate('created_at', now()->format('Y-m-d'))->where('loan_id', '!=', 0)->sum('amount');
        $widget['today_savings_collection_count'] = PaidLog::where('fsp_id', $id)->whereDate('created_at', now()->format('Y-m-d'))->where('savings_id', '!=', 0)->count();
        $widget['today_savings_collection_amount'] = PaidLog::where('fsp_id', $id)->whereDate('created_at', now()->format('Y-m-d'))->where('savings_id', '!=', 0)->sum('amount');

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.fsps.detail', compact('pageTitle', 'fsp', 'widget', 'countries'));
    }


    public function update(Request $request, $id)
    {
        $fsp       = Fsp::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'firstname' => 'required|max:50',
            'lastname'  => 'required|max:50',
            'email'     => 'required|email|max:90|unique:fsps,email,' . $fsp->id,
            'mobile'    => 'required',
            'country'   => 'required',
        ]);
        $countryCode         = $request->country;
        $fsp->mobile       = $request->mobile;
        $fsp->country_code = $countryCode;
        $fsp->firstname    = $request->firstname;
        $fsp->lastname     = $request->lastname;
        $fsp->email        = $request->email;
        $fsp->address      = [
                            'address' => $request->address,
                            'city'    => $request->city,
                            'state'   => $request->state,
                            'zip'     => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $fsp->status       = $request->status ? 1 : 0;
        $fsp->ev           = $request->ev ? 1 : 0;
        $fsp->sv           = $request->sv ? 1 : 0;
        $fsp->ts           = $request->ts ? 1 : 0;
        $fsp->tv           = $request->tv ? 1 : 0;
        $fsp->cac_status    = $request->cac_status ? 2 : 1;
        $fsp->save();

        $notify[]                     = ['success', 'Fsp detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function fspLoginHistory($id)
    {
        $fsp = Fsp::findOrFail($id);
        $pageTitle = 'Fsp Login History - ' . $fsp->username;
        $emptyMessage = 'No Fsps login found.';
        $login_logs = $fsp->login_logs()->orderBy('id','desc')->with('fsp')->paginate(getPaginate());
        return view('admin.fsps.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function showEmailSingleForm($id)
    {
        $fsp = Fsp::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $fsp->username;
        return view('admin.fsps.email_single', compact('pageTitle', 'fsp'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $fsp = Fsp::findOrFail($id);
        sendGeneralEmail($fsp->email, $request->subject, $request->message, $fsp->username);
        $notify[] = ['success', $fsp->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function showEmailAllForm()
    {
        $pageTitle = 'Send Email To All Fsps';
        return view('admin.fsps.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Fsp::where('status', 1)->cursor() as $fsp) {
            sendGeneralEmail($fsp->email, $request->subject, $request->message, $fsp->username);
        }

        $notify[] = ['success', 'All Fsps will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        $fsp = Fsp::findOrFail($id);
        Auth::guard('fsp')->login($fsp);
        return redirect()->route('fsp.dashboard');
    }

    public function emailLog($id)
    {
        $fsp = Fsp::findOrFail($id);
        $pageTitle = 'Email log of '.$fsp->username;
        $logs = EmailLog::where('fsp_id',$id)->with('fsp')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.fsps.email_log', compact('pageTitle','logs','emptyMessage','fsp'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $pageTitle = 'Email details';
        return view('admin.fsps.email_details', compact('pageTitle','email'));
    }

    public function showRegistrationForm(){
        $pageTitle = 'Fsp Register';
       
        return view('admin.fsps.register', compact('pageTitle', 'mobile_code', 'countries'));
    }

    public function register(Request $request){
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));

        $request->validate([
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:fsps',
            'mobile' => 'required|string|max:50',
            'password' => ['required','confirmed',$password_validation],
            'username' => 'required|alpha_num|unique:fsps|min:6',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
        ]);

        // $exist = Fsp::where('mobile',$request->mobile_code.$request->mobile)->first();
        // if ($exist) {
        //     $notify[] = ['error', 'The mobile number already exists'];
        //     return back()->withNotify($notify)->withInput();
        // }

        //Fsp Create
        $fsp = new Fsp();
        $fsp->firstname = $request->firstname;
        $fsp->lastname = $request->lastname;
        $fsp->email = strtolower(trim($request->email));
        $fsp->password = Hash::make($request->password);
        $fsp->username = trim($request->username);
        $fsp->country_code = $request->country_code;
        $fsp->mobile = $request->mobile_code.$request->mobile;
        $fsp->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => $request->country,
            'city' => ''
        ];
        $fsp->status = 1;
        $fsp->ev = $general->ev ? 0 : 1;
        $fsp->sv = $general->sv ? 0 : 1;
        $fsp->ts = 0;
        $fsp->tv = 1;
        $fsp->save();

        $notify[] = ['success', 'New Fsp added successfully']; 
        return back()->withNotify($notify);
    }

    public function checkUser(Request $request){
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = Fsp::where('email',$request->email)->first();
            $exist['type'] = 'email';
        }
        // if ($request->mobile) {
        //     $exist['data'] = Fsp::where('mobile',$request->mobile)->first();
        //     $exist['type'] = 'mobile';
        // }
        if ($request->username) {
            $exist['data'] = Fsp::where('username',$request->username)->first();
            $exist['type'] = 'username';
        }
        return response($exist);
    }
}
