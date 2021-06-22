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

     

    public function username()
    {
         
        $loginType = request()->input('username');
        
        $this->username = filter_var($loginType, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        request()->merge([$this->username => $loginType]);
         
       return property_exists($this, 'username') ? $this->username : 'email';
    }
    
    public function login1(Request $request) {




        
        
        if (\Auth::attempt([$this->username() => $request->username,'password' => $request->password]))
        {
            
            $user = User::where($this->username(),$request->username)->first();
             
            if($user->verified == 0 && $user->is_active == 1) {
             
                $data = array('status'=>false,'msg'=>'The selected username is invalid or you need to activate your account.');

            } else  if($user->is_active == 0 && $user->verified == 1) {
                $data = array('status'=>false,'msg'=>'You dont have permission to login your account by admin');
                
            } else  if($user->is_active == 0 && $user->verified == 0) {
                $data = array('status'=>false,'msg'=>'Invalid username/email and password');
             
            } else {
                $data = array('status'=>true,'msg'=>'Login successfully');
            }
            
        } else {
                $data = array('status'=>false,'msg'=>'Invalid username/email and password');
        }  
        return response()->json($data);
    }   
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function authenticateddfd(Request $request, $user)
    {  

        if($user->verified==0 && $user->is_active==1) {
             
            $data = array('status'=>false,'msg'=>'The selected username is invalid or you need to activate your accounts.');
             
            
        } else  if($user->is_active==0 && $user->verified==1) {
            $data = array('status'=>false,'msg'=>'You dont have permission to login your account by admin');
           
             
        } else  if($user->is_active==0 && $user->verified==0) {
            $data = array('status'=>false,'msg'=>'Invalid username/email and password');
            
             
        } else {
             $data = array('status'=>true,'msg'=>'Login successfully');
             
        }
         
         
         return response()->json($data);
        
    }
    protected function validateLogin(Request $request)
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
    public function validationErrors()
    {
        return [
            $this->username() . '.exists' => __('The selected username is invalid or you need to activate your account.')
        ];
    }
}
