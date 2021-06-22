<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\User;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
         return back();
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {

        $request->session()->regenerate();
         
        if($request->username == '' ) {
            return response()->json(['status'=>false,'msg'=>'Enter your email or mobile no.']);
        } else if($request->password == '' ) {
            return response()->json(['status'=>false,'msg'=>'Enter your password']);
        } 
        
        if ($this->attemptLogin($request)) {
   
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
    protected function validateLogin(Request $request)
    {
        
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
         
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {  
        if($user->role_id == '1') {
            
            $data = array('status'=>true,'msg'=>'','role'=>'super_admin');
        } else if($user->role_id == '2') {
            
            $data = array('status'=>true,'msg'=>'','role'=>'vendor');
        } else {
            $data = array('status'=>true,'msg'=>'','role'=>'');
        } 
        return response()->json($data);
        
      
        
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
