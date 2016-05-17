<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Signup Fields
    |--------------------------------------------------------------------------
    |
    | Here, you can specify what fields you want to store for your user. The 
    | AuthController@signup method will automatically search for current 
    | request data fields using names that are contained in this array.
    |
    */
    'signup_fields' => [
        'username', 'password', 'email', 'FirstName', 'LastName', 'birthday', 'gender', 'city', 'state', 'interests', 'bio', 'ProfilePic'		
    ],

    /*
    |--------------------------------------------------------------------------
    | Signup Fields Rules
    |--------------------------------------------------------------------------
    |
    | Here you can put the rules you want to use for the validator instance
    | in the signup method.
    |
    */
    'signup_fields_rules' => [
		'username' => 'required|unique:users',
		'password' => 'required|min:8',
		'email' => 'required|email|unique:users',
		'FirstName' => 'required',
		'LastName' => 'required',
		'birthday' => 'required',
		'gender' => 'required',
		'city' => 'required',
		'state' => 'required',
		'interests',
		'bio' => "max:500",
		'ProfilePic'
    ],

    /*
    |--------------------------------------------------------------------------
    | Signup Token Release
    |--------------------------------------------------------------------------
    |
    | If this field is "true", an authentication token will be automatically
    | released after signup. Otherwise, the signup method will return a simple
    | success message.
    |
    */
    'signup_token_release' => env('API_SIGNUP_TOKEN_RELEASE', true),

    /*
    |--------------------------------------------------------------------------
    | Password Reset Token Release
    |--------------------------------------------------------------------------
    |
    | If this field is "true", an authentication token will be automatically
    | released after password reset. Otherwise, the signup method will return a 
    | simple success message.
    |
    */
    'reset_token_release' => env('API_RESET_TOKEN_RELEASE', true),

    /*
    |--------------------------------------------------------------------------
    | Recovery Email Subject
    |--------------------------------------------------------------------------
    |
    | The email address you want use to send the recovery email. 
    |
    */
    'recovery_email_subject' => env('API_RECOVERY_EMAIL_SUBJECT', true),

];
