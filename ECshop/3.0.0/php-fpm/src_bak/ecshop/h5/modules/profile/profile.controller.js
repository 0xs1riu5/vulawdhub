(function () {

	'use strict';

	angular
		.module('app')
		.controller('ProfileController', ProfileController);

	ProfileController.$inject = ['$scope', '$http', '$rootScope', '$location', '$state', 'API', 'AppAuthenticationService', 'CartModel', 'ConfigModel'];

	function ProfileController($scope, $http, $rootScope, $location, $state, API, AppAuthenticationService, CartModel, ConfigModel) {

		$scope.touchAllOrders 		= _touchAllOrders;
		$scope.touchOrderCreated 	= _touchOrderCreated;
		$scope.touchOrderPayed 		= _touchOrderPayed;
		$scope.touchOrderDelivering = _touchOrderDelivering;
		$scope.touchOrderDelivered 	= _touchOrderDelivered;

		$scope.touchFav 		= _touchFav;
		$scope.touchAddress 	= _touchAddress;
		$scope.touchScore 		= _touchScore;
		$scope.touchCashGift 	= _touchCashGift;
		$scope.touchHelps 		= _touchHelps;
		$scope.touchPassword 	= _touchPassword;
		$scope.touchSignin 		= _touchSignin;
		$scope.touchSignout 	= _touchSignout;
		$scope.touchBonus 		= _touchBonus;
		$scope.touchBalance 	= _touchBalance;
		$scope.isSignIn 		= _isSignIn;

		$scope.isWeixin  = _isWeixin;

		$scope.cartModel = CartModel;

		$scope.user = AppAuthenticationService.getUser();
		$scope.userName = "";

		ConfigModel.fetch();

		var config = ConfigModel.getConfig();
		$scope.authorize = config.authorize;

		function _touchAllOrders() {
			$state.go('my-order', {
				tab: 'all'
			});
		}

		function _touchOrderCreated() {
			$state.go('my-order', {
				tab: 'created'
			});
		}

		function _touchOrderPayed() {
			$state.go('my-order', {
				tab: 'paid'
			});
		}

		function _touchOrderDelivering() {
			$state.go('my-order', {
				tab: 'delivering'
			});
		}

		function _touchOrderDelivered() {
			$state.go('my-order', {
				tab: 'delivered'
			});
		}

		function _touchFav() {
			$state.go('my-favorite', {});
		}

		function _touchAddress() {
			$state.go('my-address', {});
		}

		function _touchScore() {
			$state.go('my-score', {
				tab: 'all'
			});
		}

		function _touchCashGift() {
			$state.go('my-cashgift', {});
		}

		function _touchHelps() {
			$state.go('article', {});
		}

		function _touchPassword() {
			$state.go('change-password', {});
		}

		function _touchSignin() {
			if (!AppAuthenticationService.getToken()) {
				$scope.goSignin();
			}
		}

		function _touchSignout() {
			if (AppAuthenticationService.getToken()) {
				API.auth.base
					.signout()
					.then(function (success) {
						if (success) {
							$scope.goHome();
							$scope.toast('注销成功');
						} else {
							$scope.toast('注销失败');
						}
					});
			}
		}

		function _reloadUser() {
			if ( _isSignIn() ) {
				API.user.profileGet().then(function (user) {
					AppAuthenticationService.setUser(user);
					$scope.user = user;
					if(user.nickname){
						$scope.userName = user.nickname;
					} else {
                        $scope.userName = user.username;
					}
				})
			};
		}

		function _reload() {
			_reloadUser();
			$scope.cartModel.reloadIfNeeded();
		}

		function _isWeixin() {
			return $rootScope.isWeixin();
		}

		function _touchBonus(){
			$state.go('bonus', {});
		}

		function _touchBalance(){
			$state.go('my-balance', {});
		}

		function _isSignIn(){
			return AppAuthenticationService.getToken()
		}



		_reload();
	}

})();