<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');

    }

    public function showRegistrationForm()
    {
        $pageTitle = "Sign Up";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('user.auth.register', compact('pageTitle','mobile_code','countries'));
    }
    
    public function checkBvn(Request $request){

        $bvn = $request->fsp_bvn;

        $curl = curl_init();
        
        // HAsh THE BVN
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fsi.ng/api/bvnr/encrypt',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
              "BVN": "' . $bvn . '"
          }',
          

            CURLOPT_HTTPHEADER => array(
              'Accept: application/json',
              'Content-Type: application/json',
              'Sandbox-key: QsaXwLyDX1PvZI8Ew1AiCW0sCNbaqBi41661348442'
            ),
          ));
  
          $response = curl_exec($curl);
        //   $res = json_encode($response);
          
        //   curl_close($curl);
        //   return $res;
        
        
        // GET DETAILS

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://fsi.ng/api/bvnr/VerifySingleBVN',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => '"37643632623062303762326637383430170a00eb8149ee3eda479f57de1bebbd66b6996b75da5a46ab6e8ebebebd5e503ad53ad160791a8189b891ad084c786fb71baa1efa03b6d9cc1cf54c8d43f004"',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Sandbox-key: QsaXwLyDX1PvZI8Ew1AiCW0sCNbaqBi41661348442'
          ),
        ));
        
        $response = curl_exec($curl);
        
        // curl_close($curl);
        // echo $response;
        
        // DECRYPT DETAILS
        

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://fsi.ng/api/bvnr/decrypt',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'"376436326230623037623266373834300836fdf0a81016cc1a6dc3fde19a907cd3e30d8b3a8c0f60efc810ffd8b8b700a8dad0f1f594768b94e23802093e4151bc74ef89606425d6d0b89afa2416b9e1c8928bdb7f05d461351b340da1a74f0604bab1ae1b594988e40d39db6bfe88ff6a47d01b8a1ef3a11427d2fdf9d0c310057ea75d223d8abfbb33fd088bddfcf0c8de90cb7b624d76744c4aaf03ac156ba06db924c4a80d37aeb8b71495f00c15bf59cf8c135ee0cebb8bd3354de9890d4e313970973ea2c1519e9d9299bbbc73e5b7a6e98ed5feb249da584fe82e883ba0c9601e2636f17840c01741fb7c6f9372e95bea9ff2f5d54f657a6efa291b25a5c8a362edce7ce068bee7f8a9525fd343767554a69d794681954bbc4727718634f38102fd1f26e4158be7ab1c5bf2e643203519c137dff361c43baf4a1039594e30a15bf7d6de1aa425c3de822abff9"',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Sandbox-key: QsaXwLyDX1PvZI8Ew1AiCW0sCNbaqBi41661348442'
          ),
        ));
        
        $response = curl_exec($curl);
        
        $res = json_decode($response);
        curl_close($curl);
        // return $response;
        return response()->json([
            'message' => $res,
        ]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:users',
            'mobile' => 'required|string|max:50',
            'password' => ['required','confirmed',$password_validation],
            'username' => 'required|alpha_num|unique:users|min:6',
            'captcha' => 'sometimes|required',
            // 'bvn' => 'required',
            // 'alt_mobile' => 'required',
            // 'dob' => 'required',
            'account_type' => 'required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree
        ]);
        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        // $exist = User::where('mobile',$request->mobile_code.$request->mobile)->first();
        // if ($exist) {
        //     $notify[] = ['error', 'The mobile number already exists'];
        //     return back()->withNotify($notify)->withInput();
        // }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $general = GeneralSetting::first();


        $referBy = session()->get('reference');
        if ($referBy) {
            $referUser = User::where('username', $referBy)->first();
        } else {
            $referUser = null;
        }
        //User Create
        $user = new User();
        $user->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $user->bvn = isset($data['bvn']) ? $data['bvn'] : null;
        $user->alt_mobile = isset($data['alt_mobile']) ? $data['alt_mobile'] : null;
        $user->dob = isset($data['dob']) ? $data['dob'] : null;
        $user->account_type = isset($data['account_type']) ? $data['account_type'] : null;
        $user->email = strtolower(trim($data['email']));
        $user->password = Hash::make($data['password']);
        $user->username = trim($data['username']);
        $user->ref_by = $referUser ? $referUser->id : 0;
        $user->country_code = $data['country_code'];
        $user->mobile = $data['mobile_code'].$data['mobile'];
        $user->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $user->status = 1;
        $user->ev = $general->ev ? 0 : 1;
        $user->sv = $general->sv ? 0 : 1;
        $user->ts = 0;
        $user->tv = 1;
        $user->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail',$user->id);
        $adminNotification->save();


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
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


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->first();
            $exist['type'] = 'email';
        }
        // if ($request->mobile) {
        //     $exist['data'] = User::where('mobile',$request->mobile)->first();
        //     $exist['type'] = 'mobile';
        // }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->first();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered()
    {
        return redirect()->route('user.dashboard');
    }

}
