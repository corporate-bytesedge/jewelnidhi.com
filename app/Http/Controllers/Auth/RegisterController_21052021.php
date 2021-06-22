<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use App\User;
use App\Mobile;
use App\Http\Controllers\Controller;
use App\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Events\Auth\UserActivationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
 

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
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return back();
        //return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data )
    {
        $validationRules = [
            'name'          => 'required|max:50',
            'password'      => 'confirmed|required|min:6|max:80'
            
        ];
        
        if($data['email'] !='' && $data['phone'] =='') {
             
            $validationRules['email'] = 'unique:users|required|min:5|max:80|email'; 
            $validationRules['phone'] = '';
        } elseif($data['email'] =='' && $data['phone'] !='') {
            $validationRules['phone'] = '';
            $validationRules['email'] = '';
        }elseif($data['email'] =='' && $data['phone'] =='') {
            $validationRules['email'] = 'unique:users|required|min:5|max:80|email';  
            $validationRules['phone'] = 'required';
        }

        if(config('settings.phone_otp_verification')) {
            
            $mobile = Mobile::where('number', $data['phone'])->where('verified', true)->first();
            
            if($mobile) {
                $validator = Validator::make([], []);
                $validator->errors()->add('phone', __('This phone number is already registered.'));
                throw new \Illuminate\Validation\ValidationException($validator);
            }
        }

        return Validator::make($data, $validationRules);
         
        
    
    }

     
     
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (Cart::count() > 0) {
            $this->redirectTo = '/cart';
        }
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    
    protected function create(array $data)
    {
        if($data['is_term_service'] == true ) {
            
            $user_data = [
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => bcrypt($data['password']),
                'wallet_balance' => 250,
                'role_id'  => '3',
                'verified'  => false,
                'is_active' => false,
                'is_term_service' => true,
                'activation_token' => str_random(191)
            ];
            $phone_otp_verification = config('settings.phone_otp_verification');
            if($phone_otp_verification) {
                if($data['phone'] !='') {
                    $user_data['phone'] = $data['phone'];
                }
                
            }
            $user = User::create($user_data);
            

            $wallet = new Wallet([
                'user_id ' => $user->id,
                'wallet_balance' => '250'
            ]);
            $user->wallets()->save($wallet);

            if($phone_otp_verification && $data['phone']!='') {
                $mobile = new Mobile([
                    'number' => $data['phone'],
                    'verified' => '0'
                ]);
                $user->mobile()->save($mobile);
            }

            return $user;

        } else {
            return response()->json([
                'status' => '0',
                'msg' => 'Please accept term and service.',
            ]);
            
        }
        
        
        
        
        

    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
  

        
         
        // if(config('vendor.enable_vendor_signup') && $request->vendor) {
        //     $user->vendor()->create([
        //         'shop_name' => $request->shop_name,
        //         'address' => $request->address,
        //         'city' => $request->city,
        //         'state' => $request->state
        //     ]);
        // }

        // Sending Email

        event(new UserActivationEmail($user));
         
        if($user->verified == false ) {
            if($user->phone !='' ) {
                $phone = $user->phone;
                $this->sendOtp($phone);
            } else {
                $phone = '';
            }
            
            return response()->json(array('status' => '1', 'msg' => 'Registered. Please check your email to activate your account.','phone'=>$phone));
             
        } 
        //$this->guard()->logout();
        //return redirect()->route('login')->withSuccess(__('Registered. Please check your email to activate your account.'));
    }

    public function checkValidReferralCode(Request $request){
        $response = User::checkReferralCodeExist($request->referral_code);
        return response()->json($response);
    }

    public function sendOtp($phone) {

        $response = array();
        $userId = Auth::user()->id;
        $users = User::where('id', $userId)->first();
        if (isset($phone) && $phone =="" ) {
            // $response['error'] = 0;
            // $response['message'] = 'Invalid mobile number';
            // $response['loggedIn'] = 1;
        } else {
            $otp = 12345;
            \Session::put('OTP', $otp);
            // $response['error'] = 1;
            // $response['message'] = 'Your OTP is created.';
            // $response['OTP'] = $otp;
            // $response['loggedIn'] = 1;
        }
        //echo json_encode($response);
    } 

    public function verifyOtp(Request $request) {
        $response = array();
        $enteredOtp = $request->input('otp');
        $userId = Auth::user()->id;
        
        if($userId == "" || $userId == null){
            $response['status'] = 1;
            $response['msg'] = 'You are logged out, Login again.';
            $response['loggedIn'] = 0;
        } else {

            $OTP = $request->session()->get('OTP');
            
            if($OTP == $enteredOtp) {

                User::where('id', $userId)->update(['verified' => 1]);

                Mobile::where('user_id', $userId)->update(['verified' => 1]);
                 

                \Session::forget('OTP');
                $response['status'] = 1;
                $response['isVerified'] = 1;
                $response['loggedIn'] = 1;
                $response['msg'] = "Your number is verified.You dont have permission to login your account by admin";

            } else {

                $response['status'] = 0;
                $response['isVerified'] = 0;
                $response['loggedIn'] = 1;
                $response['msg'] = "OTP does not match.";
            }

        }
            echo json_encode($response);
    }
    
}
