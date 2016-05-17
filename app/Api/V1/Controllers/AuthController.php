<?php
namespace App\Api\V1\Controllers;
use JWTAuth;
use Validator;
use Config;
use Log;
use App\User;
use Input;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

class AuthController extends Controller
{
    use Helpers;
    //This method is used to receive POSTs from the login form
    public function login(Request $request)
    {
        //If the request does not contain an email field change the required email field to userName
        if( $request->only('email') == array('email' => NULL,) ) {
            $login = 'username';
        }else{
            $login = 'email';
        }
        
        //Retrieve the required values from the the request for login
        $credentials = $request->only([$login, 'password']);
        //Establish the required rules for validation
        $validator = Validator::make($credentials, [
            $login => 'required',
            'password' => 'required',
        ]);
        //If the validator does not receive the required inputs send errors
        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        //If after attempting to validate with the database and a match can not be made, send Unauthorized error
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }
        //If while trying to validate a server error occurs, send server error
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }
        //If successful validation, return token
        return response()->json(compact('token'));
    }

    //This method is used to receive POSTs from the signup form
   public function signup(Request $request)
    {
        
        $newRequest = $request;
        //If the user supplies a profile picture
        if( $request->only('ProfilePic') != array('ProfilePic' => NULL) ) {
            //Make a copy of the incoming request to pull out the picture file info
            $newRequest = new Request ($request->all());
            //Pull out the picture file and it's original name
            $image = $request->file('ProfilePic');
            $imageName = "/public/profile_pics/" . implode(" ",$request->only('username')) . ".png";
            
            //Replace the temp file in the newRequest with the original picture file name
            $newRequest->merge(array('ProfilePic' => $imageName));
            //Push the picture file to the desired storage location
            $request->file('ProfilePic')->move(base_path()."/public/profile_pics", $imageName); 
			}
        //If no picture is uploaded, set a default picture
        if( $request->only('ProfilePic') == array('ProfilePic' => NULL) ) {
            //Assign default picture name
            $imageName = "/public/profile_pics/default.png";
            
            //Insert default picture name into request array
            $newRequest->merge(array('ProfilePic' => $imageName));
        }
        //Retrieves fields that we want from the config/boilerplate.php file
        $signupFields = Config::get('boilerplate.signup_fields');
        $hasToReleaseToken = Config::get('boilerplate.signup_token_release');
        //Assigns the requested fields to the supplied values for those fields
        $userData = $newRequest->only($signupFields);
        //Validates the user supplied values according to the to the signup field rules
        $validator = Validator::make($userData, Config::get('boilerplate.signup_fields_rules'));
        //If it does not pass the validation throw an error
        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        //Unguard allows access to the Users model which is used to create a new user and then locked back down
        User::unguard();
        $user = User::create($userData);
        User::reguard();
        //If the user has not been assigned an id, return an error
        if(!$user->id) {
            return $this->response->error('could_not_create_user', 500);
        }
        //If the user has been granted a token login the user
        if($hasToReleaseToken) {
            return $this->login($newRequest);
        }
        
        //Return the created instance of the model(I think, or maybe just returning a boolean response...can't tell)
        return $this->response->created();
    }
    public function recovery(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required'
        ]);
        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(Config::get('boilerplate.recovery_email_subject'));
        });
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->response->noContent();
            case Password::INVALID_USER:
                return $this->response->errorNotFound();
        }
    }
    public function reset(Request $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
        $validator = Validator::make($credentials, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);
        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        
        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        switch ($response) {
            case Password::PASSWORD_RESET:
                if(Config::get('boilerplate.reset_token_release')) {
                    return $this->login($request);
                }
                return $this->response->noContent();
            default:
                return $this->response->error('could_not_reset_password', 500);
        }
    }
}