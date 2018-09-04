(function () {

    'use strict';

    angular
    .module('app')
    .controller('SigninController', SigninController);

    SigninController.$inject = ['$scope', '$http', '$window', '$location', '$state', '$rootScope', 'API', 'ENUM','ConfigModel'];

    function SigninController($scope, $http, $window, $location, $state, $rootScope, API, ENUM,ConfigModel) {

    	$scope.username = "";
    	$scope.password = "";

    	$scope.touchSignin = _touchSignin;
    	$scope.touchSignup = _touchSignup;
    	$scope.touchForget = _touchForget;
    	$scope.touchWeixin = _touchWeixin;
        $scope.touchQQ    = _touchQQ;
        $scope.isWeixin    = _isWeixin;

    	function _touchSignin() {
    		var username = $scope.username;
    		var password = $scope.password;

            if ( !username || username.length < 3 ) {
                $scope.toast('用户名太短');
                return;
            }

            if ( !username || username.length > 25 ) {
                $scope.toast('用户名太长');
                return;
            }

            if ( !password || password.length < 4 ) {
                $scope.toast('请输入正确的密码');
                return;
            }

			API.auth.base
			.signin({username:username, password:password})
			.then(function(success){
                if (success) {
                    $scope.toast('登录成功');
                    $scope.goHome();
                }
                else{
                    $scope.toast('用户名或密码错误');
                }
			});
    	}

    	function _touchSignup() {
            $state.go('signup', {});
    	}

    	function _touchForget() {
            $state.go('forget', {});
    	}

    	function _touchWeixin() {
			$state.go('wechat-auth', {});
    	}

        function _touchQQ() {
            $state.go('qq-auth', {});
        }

        function _isWeixin() {

            var config = ConfigModel.getConfig();
            var wechat = config['wechat.web'];
            return wechat && $rootScope.isWeixin();
        }
    }

})();
