(function () {

	'use strict';
 
	angular
		.module('app')
		.controller('ForgetController', ForgetController);

	ForgetController.$inject = ['$scope', '$http', '$window', '$location', '$state', '$stateParams', 'API', 'ENUM', 'AppAuthenticationService', '$interval'];

	function ForgetController($scope, $http, $window, $location, $state, $stateParams, API, ENUM, AppAuthenticationService, $interval) {

		$scope.TAB_MOBILE = 0;
		$scope.TAB_EMAIL = 1;

		$scope.currentTab = $scope.TAB_MOBILE;

		if ($stateParams.tab == 'mobile') {
			$scope.currentTab = $scope.TAB_MOBILE;
		} else if ($stateParams.tab == 'email') {
			$scope.currentTab = $scope.TAB_EMAIL;
		} else {
			$scope.currentTab = $scope.TAB_MOBILE;
		}

		$scope.touchTabMobile = _touchTabMobile;
		$scope.touchTabEmail = _touchTabEmail;

		$scope.toucheMobileCode = _toucheMobileCode;
		$scope.touchforgetMobile = _touchforgetMobile;
		$scope.touchBackLogin = _touchBackLogin;

		$scope.forgetMobile = {show:true};

		function _touchTabMobile() {
			if ($scope.currentTab != $scope.TAB_MOBILE) {
				$scope.currentTab = $scope.TAB_MOBILE;
				$scope.forgetMobile = {show:true};
			}
		}

		function _touchTabEmail() {
			if ($scope.currentTab != $scope.TAB_EMAIL) {
				$scope.currentTab = $scope.TAB_EMAIL;
				$scope.forgetMobile = {show:false};
			}
		}

    	$scope.state=
            {
	            username : "",
	            password : "",
	            password2 : "",
	            email : "",
	            mobile : "",
	            mobile_code : "",
	            mobile_password : "",
	            mobile_password2 : "",  
            };

		$scope.paracont = "获取验证码";
		$scope.paraclass = "mobile-code";
		$scope.paraevent = true;

		function timeout() {
			var second = 60,  
				timePromise = undefined; 

			timePromise = $interval(function(){
	          if(second<=0){
	            $interval.cancel(timePromise);  
	            timePromise = undefined;  

	            second = 60;  
	            $scope.paracont = "重发验证码";  
	            $scope.paraclass = "mobile-code";  
	            $scope.paraevent = true;
	          }
	          else
	          {
	            $scope.paracont = second + "s";  
	            $scope.paraclass = "not-mobile-code";  
	            $scope.paraevent = false;
	            second--;  
	          }  
			},1000,100);
		}

    	function _toucheMobileCode() {

			if ( !$scope.paraevent ) {return;}

    		var mobile = $scope.state.mobile;

	   		if ( !mobile || mobile.length < 1 ) {
                $scope.toast('请输入手机号码');
                return;
            }

            var params = {};
            params.mobile = mobile;
            params.code = "86";

	        API.auth.mobile.
	        send(params)
            .then(function(res) {
                if ( res.data && ENUM.ERROR_CODE.OK == res.data.error_code ) {
                	timeout();
                }
                else{
                    $scope.toast(res.data.error_desc);
                }
            });
    	}

    	function _touchforgetMobile() {
    		var mobile = $scope.state.mobile;
    		var code = $scope.state.mobile_code;
    		var password = $scope.state.mobile_password;
    		var password2 = $scope.state.mobile_password2;

    		if ( !mobile || mobile.length < 1 ) {
                $scope.toast('请输入手机号码');
                return;
            }

			if ( !code || code.length != 6 ) {
                $scope.toast('请输入正确的手机验证码');
                return;
            }

            if ( !password || password.length < 6 ) {
                $scope.toast('请输入正确的密码');
                return;
            }

			if ( password != password2 ) {
                $scope.toast('请输入正确的密码');
                $scope.password2 = '';
                return;
			}

            var params = {};
            params.mobile = mobile;
            params.code = code;
            params.password = password;

			if(AppAuthenticationService.getReferences()){
                params.invite_code =  parseInt(AppAuthenticationService.getReferences());
            }

			API.auth.mobile.reset(params).then(function(res) {
				if ( res.data && ENUM.ERROR_CODE.OK == res.data.error_code ) {
				    $scope.toast('找回密码成功');
                    $scope.goBack();
                }
                else{
                    $scope.toast(res.data.error_desc);
                }
            });
    	}

		$scope.success = false;
		$scope.email = '';
		$scope.input = {
			email: ""
		};

		$scope.touchSendMail = touchSendMail;

		function touchSendMail() {

			var email = $scope.input.email;
			if (!email || email.length < 4) {
				$scope.toast('请输入正确的邮箱');
				return;
			}

			API.auth.default.reset({
				email: email
			}).then(function (res) {
				var success = ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
				$scope.success = success;

				if ( success ) {
					$scope.email = email;
				}
				else
				{
					$scope.toast(res.data.error_desc);
				}
			})
		}

		function _touchBackLogin() {
			$scope.goBack();
		}
	}

})();