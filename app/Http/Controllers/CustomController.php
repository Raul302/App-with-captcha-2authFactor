<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\User;


// Library to Hash or encrypt the password to save in DB
use Illuminate\Support\Facades\Hash;

// Library to comprobate Recaptcha
use Symfony\Component\HttpFoundation\IpUtils;
use Illuminate\Support\Facades\Http;

// Library to send OTP email way 
use Illuminate\Support\Facades\Mail;


use App\Mail\OtpMail;


use Illuminate\Support\Facades\Auth;


class CustomController extends Controller
{
    


    public function logout (Request $request) {

        $user = Auth::user(); 
        $user->isverified = 0;
        $user->otp = null;
        $user->save();

        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
    public function verifyAuth( Request $request ){

        

         // [ Validate laravel ] ,   if validation fails,  exception will be thrown and the proper error response will automatically be sent back to the user.
         $validated =    $request ->validate ([
            'otp' => 'required|integer']);

            $code = $request->get('otp');

         

            $user_id = $request->session()->get('2fa:user:id');
            $credentials = $request->session()->get('2fa:user:credentials');
            $attempt = $request->session()->get('2fa:auth:attempt',false);

            // $request->session()->put('2fa:auth:attemp:running', true);


            if (!$user_id || !$attempt) {
                return redirect('/login');
            }
        
            $user = User::find($user_id);
        
            if (!$user) {
                return redirect('/login');
            }

            // First, check if the code is the same that saved in user field
            if($user->otp == $code) {

                // Second , check if password and email is correct , then make auth
                if(Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])){ 
                    $user = Auth::user(); 
                    $user->isverified = 1;
                    $user->save();

                    $request->session()->put('2fa:auth:attempt', 'logged');
                    $request->session()->remove('2fa:user:id');
                    $request->session()->remove('2fa:user:credentials');
                    $request->session()->remove('2fa:auth:attempt');
                    $request->session()->remove('2fa:auth:attemp:running');


                    return redirect("/logged")->with(['message' => 'Succesful Authorization!']);
           
                } 
                else{ 
                    return redirect('/login')->withErrors([
                        'message' => __('Credentials invalid.'),
                    ]);
                } 


            } else {

                return redirect('/login')->withErrors([
                    'message' => __('The provided otp are incorrect.'),
                ]);

            }



            

    }

     // Separator
    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


    // Method to send the otp by email
    public function send_otp( $id, $request ){

       

        $user = User::find($id);

        // Generate random token
        $code = rand(100000,999999);
       

        // Keep temporaly the data
        $request->session()->put('2fa:user:id', $user->id);
        $request->session()->put('2fa:user:credentials', $request->only('email', 'password'));
        $request->session()->put('2fa:auth:attempt', true);
        $user->otp = $code;
        $user->save();
        

        Mail::to('Luis_1547@hotmail.com')->send(new OtpMail($code));

        



    }

     // Separator
    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


    // Method created to comprobate that captcha is correct and reuse in login and register
    public function captcha_comprobation($captcha , $ip){
          // Recaptcha comprobation after all validations ( this is in the google documentation to verify)
          $url = "https://www.google.com/recaptcha/api/siteverify";

          $body = [
              'secret' => config('services.recaptcha.secret'),
              'response' => $captcha,
              'remoteip' => IpUtils::anonymize($ip) //anonymize the ip to be GDPR compliant. Otherwise just pass the default ip address
          ];
  
          $response = Http::asForm()->post($url, $body);
  
          $result = json_decode($response);
  
          if($response->successful() && $result->success == true){
            return true;
          } else {
            return false;
          }
    }


     // Separator
    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


    // Method to register  anda validated  user data with front end information 
    public function register ( Request $request){

        // [ Validate laravel ] ,   if validation fails,  exception will be thrown and the proper error response will automatically be sent back to the user.
        $validated =    $request ->validate ([
            'username' => 'required|unique:users,username|max:20',
            'email' => 'required|email|unique:users,email',
            'g-recaptcha-response' => 'required',
            'password' => [
                'required',
                'min:8 ',
                'regex:/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/',
            ]
        ],[
            'username.unique' => 'Something was wrong.',
            'email.unique' => 'Something was wrong.',
        ]);

      
        if ( $this->captcha_comprobation($request->get('g-recaptcha-response'),$request->ip())) {

            // if all OK so save user and return

             // Creating the variable type model where we will save the data from the form 
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->save()){
        //     // If user is save then return to the same page but return a succesful message
            return redirect("/register")->with(['message' => 'Succesful Registration!']);
        }

        // if recaptcha comprobation fail return message to do again
        } else {
            return redirect("/register")->with(['message' => 'Please Complete the Recaptcha Again to proceed!']);
        }

    }

    // Separator
    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


    // Method to login the user , check if credentials is valid ,save in a cookie encrypted , load two auth view and send otp email 
    public function login (  Request $request ) {

       // [ Validate laravel ] ,   if validation fails,  exception will be thrown and the proper error response will automatically be sent back to the user.
        $validated =    $request ->validate ([
            'email' => 'required|email',
            'g-recaptcha-response' => 'required',
            'password' => [
                'required',
                'min:8 ',
                'regex:/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/',
            ]
        ]);

        // Check if all fields is ok and captcha too
        if ( $this->captcha_comprobation($request->get('g-recaptcha-response'),$request->ip())) {


             // Check if user exist
        if (User::where('email', $request->email )->exists()) {

            // if exist change hidden password to visible just to compare passwords
            $user = User::where('email',$request->email)->get()->makeVisible('password')->first();
            // $user = $user->makeVisible('password');
            if(Hash::check($request->password, $user->password)) {

                // They match, So if the email and password is correct then save in session

                $request->session()->put('2fa:user:guest','guest');


                // First, save credentials,OTP and send to email
                $this->send_otp($user->id,$request);

                // After send the otp and keep the credentials , we must show the two auth view to get the otp
                return redirect("/two-auth");

            } else {
                // They don't match
                return redirect("/login")->with(['message' => 'Credentials Invalids!']);

            }
            

        } else {
            return redirect("/login")->with(['message' => 'Credentials Invalids!']);

        }

        } else {
            return redirect("/login")->with(['message' => 'Please Complete the Recaptcha Again to proceed!']);

        }

    }
}
