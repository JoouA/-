function loginController($scope, $http, $location, $rootScope) {
        $scope.eveversion = $rootScope.EVE_VERSION;
	if ($scope.html5 == null ) { $scope.html5 = -1 ;}
	$scope.testAUTH("/main");
	$('body').removeClass().addClass('hold-transition login-page');
	$scope.tryLogin = function(){
		$scope.loginMessageInfo="";
			$http({
			method: 'POST',
			url: '/api/auth/login',
			data: {"username":$scope.username,"password":$scope.password,"html5":$scope.html5}})
				.then(
				function successCallback(response) {
					if (response.status == '200' && response.statusText == 'OK'){
					blockUI();
					$scope.testAUTH("/main");}
				},
				function errorCallback(response) {
					if (response.status == '400' && response.statusText == 'Bad Request'){
					$scope.loginMessageInfo='用户名或密码错误，默认登录名：admin,密码：eve,或在官方网站上查找密码重置说明。'}
					else console.log("Unknown Error. Why did API doesn't respond?")
				}
			);
		}
}
