var wimControllers = angular.module('wimControllers', ['ngFileUpload']);

wimControllers.controller('LoginController', ['userService', '$location', '$scope', '$http', function (userService, $location, $scope, $http) {

	$scope.login = function() {
        userService.login(
            $scope.email, $scope.password,
            function(response){
                $location.path('/');
            },
            function(response){
                alert('Something went wrong with the login process. Try again later!');
            }
        );
    }

    $scope.email = '';
    $scope.password = '';

    if(userService.checkIfLoggedIn())
        $location.path('/');

}]);

wimControllers.controller('SignupController', [ 'userService', '$scope', '$http', '$location', function (userService, $scope, $http, $location) {

	$scope.signup = function(file) {
		userService.signup(
			$scope.username, $scope.password, $scope.email, $scope.FirstName, $scope.LastName, $scope.birthday, $scope.gender, $scope.city, $scope.state, $scope.interests, $scope.bio, file,
			function(response){
				alert('Great! You are signed up! Welcome to WIM(afy), ' + $scope.FirstName + '!');
				$location.path('/').replace();
			},
			function(response){
			alert('Something went wrong with the signup process. Try again later.');
			}
		);
	}
	
	$scope.username = '';
	$scope.password = '';
	$scope.email = '';
	$scope.FirstName = '';
	$scope.LastName = '';
	$scope.birthday = '';
	$scope.gender = '';
	$scope.city = '';
	$scope.state = '';
	$scope.interests = '';
	$scope.bio = '';
	file = '';
	
	if(userService.checkIfLoggedIn())
		$location.path('/');
	
}]);

/*wimControllers.controller('fileUpload', ['$scope', 'Upload', function($scope, Upload){
	$scope.fileUpload = function(){
		if($scope.form.file.$valid && $scope.file){
			$scope.upload($scope.file);
		}
	};	
}]);
*/
wimControllers.controller('MainController', ['$scope', '$http', function ($scope, $http) {
    
}]);
