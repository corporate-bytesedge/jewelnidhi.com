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
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	
	public function checkUser(Request $request) {
		$response = array();
		$user = User::where("phone",$request->forgot_mobile)->first();
		if(!empty($user)) {
			
			  if($user->mobile->verified == 0 && $user->is_active == 1) {
				  $response['status'] = 0;
				  $response['msg'] = "Your mobile no. is not verified";
			} else if($user->mobile->verified == 0 && $user->is_active == 0) {
				  $response['status'] = 0;
				  $response['msg'] = "These credentials do not match our records.";                    
			}  else if($user->mobile->verified == 1 && $user->is_active == 0) {
				  $response['status'] = 0;
				  $response['msg'] = "You dont have permission to login your account";
			}  else {
				  $response['status'] = 1;
				  $response['msg'] = "valid user";
				  \Session::put('FORGOTMOBILE', $request->forgot_mobile);
				  $this->sendOtp($request->forgot_mobile);
			}  
		}else{
			$response['status'] = 0;
			$response['msg'] = "The mobile number is not registered";
		}
		
		echo json_encode($response); 
	}
	
	public function sendOtp($phone) {

        $response = array();
        
        if (isset($phone) && $phone =="" ) {
            // $response['error'] = 0;
            // $response['message'] = 'Invalid mobile number';
            // $response['loggedIn'] = 1;
        } else {
            //$otp = mt_rand(10000,99999);
			$otp = 12345;
            \Session::put('FORGOTOTP', $otp);
            // $response['error'] = 1;
            // $response['message'] = 'Your OTP is created.';
            // $response['OTP'] = $otp;
            // $response['loggedIn'] = 1;
			
			/*-------------send opt in sms to user --------*/
			/*code from smscountry*/
			//Please Enter Your Details
			/* $smsc_user="jewelnidhi"; //your username
			$smsc_password="@Hithyshi269"; //your password
			$mobilenumbers="91".$phone; //enter Mobile numbers comma seperated
			$message = $otp." is your OTP to register in jewelnidhi.com. jewelnidhi"; //enter Your Message
			$senderid="JWLNDI"; //Your senderid
			$messagetype="N"; //Type Of Your Message
			$DReports="Y"; //Delivery Reports
			$url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
			$message = urlencode($message);
			$ch = curl_init();
			if (!$ch){die("Couldn't initialize a cURL handle");}
			$ret = curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt ($ch, CURLOPT_POSTFIELDS,
			"User=$smsc_user&passwd=$smsc_password&mobilenumber=$mobilenumbers&message=$message&mtype=$messagetype&DR=$DReports");
			$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//If you are behind proxy then please uncomment below line and provide your proxy ip with port.
			// $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
			$curlresponse = curl_exec($ch); // execute
			if(curl_errno($ch))
			echo 'curl error : '. curl_error($ch);
			if (empty($ret)) {
			// some kind of an error happened
			die(curl_error($ch));
			curl_close($ch); // close cURL handler
			} else {
			$info = curl_getinfo($ch);
			curl_close($ch); // close cURL handler
			//echo $curlresponse; //echo "Message Sent Succesfully" ;
			}
			 */
			/*----------ending send opt sms ---------*/
			
			
        }
        //echo json_encode($response);
    }
	
	public function verifyForgotOtp(Request $request) {
        $response = array();
        $enteredOtp = $request->input('otp');
		$OTP = $request->session()->get('FORGOTOTP');
		if($OTP == $enteredOtp) {
			\Session::forget('FORGOTOTP');
			$response['status'] = 1;
			$response['msg'] = "Your number is verified.";
		} else {
			$response['status'] = 0;
			$response['msg'] = "OTP does not match.";
		}
		echo json_encode($response);
    }
	
	public function changePassword(Request $request) {
		$response = array();
        $new_password = $request->input('new_password');
		$new_password_confirmation = $request->input('new_password_confirmation');
		if($new_password !== $new_password_confirmation) {
			$response['status'] = 0;
			$response['msg'] = "Password does not match.";
			$response['result'] = strcmp($new_password, $new_password_confirmation);
			$response['new_password'] = gettype($new_password);
			$response['new_password_confirmation'] = gettype($new_password_confirmation);
			
		} else {
			$forgot_mobile = $request->session()->get('FORGOTMOBILE');
			$user = User::where("phone",$forgot_mobile)->first();
			$user_data = [
				"password" => bcrypt($new_password)
			];
			$user->fill($user_data)->save();
			\Session::forget('FORGOTMOBILE');
			$response['status'] = 1;
			$response['msg'] = "Password has been changed.";
		}
		echo json_encode($response);
    }
}
