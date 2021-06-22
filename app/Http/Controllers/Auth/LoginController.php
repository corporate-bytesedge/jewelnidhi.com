<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\User;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('guest')->except('logout');
    }

    public function getCheckUser() {
        dd('======');
    }

    public function username1()
    {
         
        $loginType = request()->input('username');
        
        $this->username = filter_var($loginType, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        request()->merge([$this->username => $loginType]);
         
       return property_exists($this, 'username') ? $this->username : 'email';
    }

    
    protected function attemptLogin1(Request $request)
    {
        
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
    protected function credentials1(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    public function login1(Request $request) {
        
        $request->session()->regenerate();
        
        if($request->username == '' ) {
            return response()->json(['status'=>false,'msg'=>'Enter your email or mobile no.']);
        } else if($request->password == '' ) {
            return response()->json(['status'=>false,'msg'=>'Enter your password']);
        } 
        if ($this->attemptLogin1($request)) {
            
            $user = User::where($this->username(),$request->username)->first();
            
            if($this->username() == 'phone') {
                
               
                if($user->mobile->verified == 0 && $user->is_active == 1) {

                    $data = array('status'=>false,'msg'=>'Your mobile no. is not verified');
    
                } else if($user->mobile->verified == 0 && $user->is_active == 0) {
                    
                    $data = array('status'=>false,'msg'=>'These credentials do not match our records.');
                    
                }  else if($user->mobile->verified == 1 && $user->is_active == 0) {
    
                    $data = array('status'=>false,'msg'=>'You dont have permission to login your account by admin.');
                    
                }
                else {
                    return $this->authenticated($request, $this->guard()->user());
                }
            } else {

                if($user->verified == 0 && $user->is_active == 1) {
             
                    $data = array('status'=>false,'msg'=>'The selected username is invalid or you need to activate your account.');
        
                } else  if($user->is_active == 0 && $user->verified == 1) {
                    $data = array('status'=>false,'msg'=>'You dont have permission to login your account by admin');
                    
                } else  if($user->is_active == 0 && $user->verified == 0) {
                    $data = array('status'=>false,'msg'=>'Invalid username/email and password');
                 
                } else {
                    return $this->authenticated($request, $this->guard()->user());
                }

            }
            
            return response()->json($data);
        } else {
            return response()->json(['status'=>false,'msg'=>'These credentials do not match our records.']);
        }
        
        
    }
    
      
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function authenticated1(Request $request, $user)
    {  

        $data = array('status'=>true,'msg'=>'Login successfully');
      
        return response()->json($data);
    }

    
    protected function validateLogin1(Request $request)
    {
         
  
        $this->validate($request, [
            $this->username() => [ //Make an custom array
                'required','string',
                
            ],
            'is_active' => '1',
            'password' => 'required|string',
        ], $this->validationErrors());
    }
    /**
     * Get the validation errors for login.
     *
     * @return array
     */
    public function validationErrors1()
    {
        return [
            $this->username() . '.exists' => __('The selected username is invalid or you need to activate your account.')
        ];
    }

    
}
