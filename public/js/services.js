var wimServices = angular.module('wimServices', [
	'LocalStorageModule',
	'ngFileUpload'
]);

wimServices.factory('userService', ['$http', 'Upload', 'localStorageService', function($http, Upload, localStorageService) {

	function checkIfLoggedIn(){
		if(localStorageService.get('token'))
			return true;
		else
			return false;
	}

	function signup(username, password, email, FirstName, LastName, birthday, gender, city, state, interests, bio, file, onSuccess, onError) {
		Upload.upload({
			url: '/api/auth/signup',
			data: {
				username: username,
				password: password,
				email: email,
				FirstName: FirstName,
				LastName: LastName,
				birthday: birthday,
				gender: gender,
				city: city,
				state: state,
				interests: interests,
				bio: bio,
				ProfilePic: file,
			}
		}).
		then(function(response) {
			localStorageService.set('token', response.data.token);
			onSuccess(response);
		}, function(response) {
			onError(response);
		});
	}

	function login(email, password, onSuccess, onError){

        $http.post('/api/auth/login', 
        {
            email: email,
            password: password
        }).
        then(function(response) {

            localStorageService.set('token', response.data.token);
            onSuccess(response);

        }, function(response) {

            onError(response);

        });

    }

    function logout(){

        localStorageService.remove('token');

    }

    function getCurrentToken(){
        return localStorageService.get('token');
    }

    return {
        checkIfLoggedIn: checkIfLoggedIn,
        signup: signup,
        login: login,
        logout: logout,
        getCurrentToken: getCurrentToken
    }

}]);

wimServices.factory([ function(){
	

}]);
