<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use App\Models\Savings;
use App\Models\SavingsCreditRating;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {

        $pageTitle = "Sign In";
        return view('user.auth.login', compact('pageTitle'));
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if(isset($request->captcha)){
            if(!captchaVerify($request->captcha, $request->captcha_secret)){
                $notify[] = ['error',"Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);


        return $this->sendFailedLoginResponse($request);
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $customRecaptcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }

        $request->validate($validation_rule);

    }

    public function logout()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return redirect()->route('user.login')->withNotify($notify);
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            $this->guard()->logout();
            $notify[] = ['error','Your account has been deactivated.'];
            return redirect()->route('user.login')->withNotify($notify);
        }

        $user = auth()->user();
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;
        
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();
        
        // return "Hi";
        
        // ALL IS TESTED FINE   (JUST REMOVE THE ONE COMMENT... THOSE ARE THE WORKING ONES)
        
        // I DO GROUPING COS THE USER CAN HAVE 30 SAVINGS FOR ONE PLAN ID (IF HE CHOSE 30 DAYS. SO I'M SAYING, GROUP THIS NIQUE PLAN... MAYBE PLAN 1, 2 , AND SO, AND LETS SEE WHAT HAPPENED)
        // IN OTHER WORDS, THEY MUST ALL HAVE THE SANE DATA I.E. END DATE, SAME AMOUNT TO BE REALISED AND SAME DATE TO GET COMPLETED COS THEY ALL SHARE SAME PLAN ID.ONLY THE AMOUNT SAVED
        // IS OPTIONAL. AS YOU MAY DECIDE TO SAVE MORE OR LESS AND ALSO, THE NUMBER OF TIMES YOU SAVE CAN BE MORE OR LESS THAN YOU STIPULATED. ALL THIS AFFEECTS YOUR RATINGS
        // all sAVINGS DURATION FOR A PLAN MUST MATCH... THE USER SHOULN'T SPECIFY AGAIN FOR CONSISTENCY SAKE... SO IF IT'S PLAN 2, IT WILL ALWAYS BE 90 DAYS OR SO
        // ALSO, THE DAILY, WEEKLY AND MONTHLY PLANS MEAN, I.E. THE PLAN IS 90 DAYS... YOU WANT TO SAVE EVERY DAY FOR 90 DAYS
        // IF IT IS 3 MONTHS PKAN, THEN, YOU WANT TO SAVE ONCE EVERY MONTH FOR THE NEEXT THREE MONTHS  (MONTHLY SAVINGS)... AND SAME FOR WEEKLY SAVINGS TOO
        
        // GET ALL USER ACTIVE SAVINGS WHERE END DATE IS LESS THAN TODAY and GROUP BY THEIR RESPECTIVE PLANS
        $deactivate_savings = Savings::join('savings_plans', 'savings_plans.id', 'savings.savings_plan_id')
                            ->where('savings.user_id', Auth::id())
                            ->where('savings.status', 1)
                            // ->where('savings_type', 'weekly')
                            ->where('savings_end', '<', date('d-m-Y'))
                            // ->select('savings_end')
                            // ->select('savings_plan_id')
                            ->groupBy('savings_plan_id')
                            ->get();
        
        // return date('d-m-Y', strtotime($deactivate_savings->created_at));
        foreach($deactivate_savings as $index=>$row){
            // echo $details = $row->total_installment . "<br>";
            // echo $row->savings_plan_id . " " . "-> $index <br>";
            // echo $row->savings_plan_id . "<br>";
            
            // Savings::join('savings_plans', 'savings_plans.id', 'savings.savings_plan_id')
            //                 ->where()
            //                 // ->where('savings.user_id', Auth::id())
            //                 // ->where('savings.status', 1)
            //                 // ->where('savings_type', 'weekly')
            //                 // ->where('savings_end', '<', date('d-m-Y'))
            //                 // ->select('savings_end')
            //                 // ->select('savings_plan_id')
            //                 // ->groupBy('savings_plan_id')
            //                 ->get();
            
            // $sum = Savings::where('savings_plan_id', $row)->sum('savings_amount');
            // $sum = Savings::where('savings_plan_id', $row->savings_plan_id)->where('savings.user_id', Auth::id())->where('savings.status', 1)->where('savings_end', '<', date('d-m-Y'))->sum('savings_amount');
            $data = Savings::where('savings_plan_id', $row->savings_plan_id)->where('savings.user_id', Auth::id())->where('savings.status', 1)->where('savings_end', '<', date('d-m-Y'));
            $sum = $data->sum('savings_amount');
            $count = count($data->get());
            
            // echo $sum->savings_amount . "<br>";
            // echo $row->savings_type . " - " .$sum . " - " . $count. "<br>";
            
            // hhh
            $saving_records_end = $row->savings_end ." 00:00:00";
            $saving_records_start = $row->savings_start ." 00:00:00";
            
            $start_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_end);
            $end_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_start);
            $difference_days = round($start_date->diffInDays($end_date));
            
            // hhh end
            
            // ensure that the savings has reached maturity before deactivating
            if($count >= $row->total_installment){
                SavingsCreditRating::create([
                    'user_id' => Auth::id(),
                    'savings_plan_id' => $row->savings_plan_id,
                    'savings_plan_name' => $row->name,
                    'savings_plan_amount' => $row->savings_amount,
                    'savings_target_amount' => $row->savings_amount * $row->total_installment,
                    'total_amount_saved' => $sum,
                    'percentage_of_savings_to_target' => ($sum/($row->savings_amount*$row->total_installment))*100,
                    'savings_plan_duration' => $row->total_installment,
                    'no_of_days_saved' => $count,
                    'percentage_of_days_saved_to_plan_duration' => ($count/$row->total_installment)*100,
                    'savings_type' => $row->savings_type,
                    'savings_amount_average' => $sum/$count,
                    'savings_date_started' => $row->savings_start,
                    'savings_date_ended' => $row->savings_end,
                    'length_of_saving_days' => $difference_days
                ]);
                
                $data->update(['status' => 3]);
                // Savings::where('savings_plan_id', $row->savings_plan_id)->where('savings.user_id', Auth::id())->where('savings.status', 1)->where('savings_end', '<', date('d-m-Y'))->update(['status' => 3]);
            }
            // else{
            //     echo "No";
                
            // }
        }
        
        // ALL IS TESTED FINE ENDS  (JUST REMOVE THE ONE COMMENT... THOSE ARE THE WORKING ONES)
        // for($i = 0; $i < count($deactivate_savings); $i++){
        //         echo $i . "<br>";
        //     }
        // echo die;
        // return $deactivate_savings;
        
        
        // return date('d-m-Y');
        
        // $yesterday = Savings::join('savings_plans', 'savings_plans.id', 'savings.savings_plan_id')
        //                     ->where('savings.user_id', Auth::id())
        //                     ->where('savings.status', 1)
        //                     ->where('savings_type', 'weekly')
        //                     ->where('savings_end', date('d-m-Y', strtotime(' -1 day')))
        //                     ->select('savings_end')
        //                     ->first();
        
        // if($yesterday){
        //     return "Yeah";
        // }else{
        //     return "No";
        // }
                            
        // $savings = Savings::join('savings_plans', 'savings_plans.id', 'savings.savings_plan_id')
        //                     ->where('savings.user_id', Auth::id())
        //                     ->where('savings.status', 1)
        //                     ->where('savings_type', 'weekly')
        //                     ->where('savings_end', )
        //                     ->get();
        // ->where('user_id', Auth::id())->where('savings.status', 1)->where('savings_type', 'weekly')->get();
        // if(count($savings) > 0){
        //     return $savings;
        // }else{
        //     return "No";
        // }

        return redirect()->route('user.dashboard');
    }

}
