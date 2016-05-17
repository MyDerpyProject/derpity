<?php
//Dingo uses it own router to avoild conflicts with the main application router	
$api = app('Dingo\Api\Routing\Router');
//Creating a version group allows for the same endpoint to be used on multiple versions
$api->version('v1', function ($api) {
	//POST routes used when application accesses the API
	$api->post('auth/login', 'App\Api\V1\Controllers\AuthController@login');
	$api->post('auth/signup', 'App\Api\V1\Controllers\AuthController@signup');
	$api->post('auth/recovery', 'App\Api\V1\Controllers\AuthController@recovery');
	$api->post('auth/reset', 'App\Api\V1\Controllers\AuthController@reset');
	
	//GET example of protected route
	$api->get('protected', ['middleware' => ['api.auth'], function () {		
		return \App\User::all();
    }]);
	//GET example of free route
	$api->get('free', function() {
		return \App\User::all();
	});
});