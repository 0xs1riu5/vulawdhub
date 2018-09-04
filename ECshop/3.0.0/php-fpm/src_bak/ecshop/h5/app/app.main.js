(function () {

	'use strict';

	angular
		.module('app')
		.controller('AppController', AppController);

	AppController.$inject = ['$scope', '$location', '$localStorage', '$window', '$rootScope', '$state', '$controller', 'CONSTANTS', 'API', 'ENUM', 'ngToast', 'CartModel', 'AppAuthenticationService','ConfigModel'];

	function AppController($scope, $location, $localStorage, $window, $rootScope, $state, $controller, CONSTANTS, API, ENUM, ngToast, CartModel, AppAuthenticationService,ConfigModel) {

		function _isIE() {
			var isIE = !!navigator.userAgent.match(/MSIE/i);
			return isIE;
		}

		function _isMobile($window) {
			// Adapted from http://www.detectmobilebrowsers.com
			var ua = navigator.userAgent || navigator.vendor;
			// Checks for iOs, Android, Blackberry, Opera Mini, and Windows mobile devices
			return (/iPhone|iPod|iPad|Silk|Android|BlackBerry|Opera Mini|IEMobile/).test(ua);
		}

		function _isWeixin() {
			if(!CONSTANTS.FOR_WEIXIN){
				return false;
			}
			// Adapted from http://www.detectmobilebrowsers.com
			var ua = navigator.userAgent.toLowerCase() || navigator.vendor.toLowerCase();
			return (ua.match(/MicroMessenger/i) == "micromessenger") ? true : false;
		}

		function _isBrowser() {
			return true;
		}

		function _getQueryString(name) {
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
			var absUrl = $window.location.search.substr(1);
			var r = absUrl.match(reg);
			if (r != null) return unescape(r[2]);
			return null;
		}

		$scope.app = {
			name: GLOBAL_CONFIG.APP_NAME,
			desc: GLOBAL_CONFIG.APP_DESC,
			keywords: GLOBAL_CONFIG.APP_KEYWORDS,
			settings: {
				showNavbar: true,
				showTabbar: true,
			},
			useragent: {
				isIE: _isIE(),
				isWeixin: _isWeixin(),
				isMobile: _isMobile(),
				isBrowser: _isBrowser(),
			}
		};

		$rootScope.goBack = function () {
			if (!$window.history || $window.history.length <= 1) {
				$state.go('home', {});
			} else {
				$window.history.back();
			}
		};

		$rootScope.goHome = function () {
			$state.go('home', {

			});
		};

		$rootScope.goCategory = function () {
			$state.go('category', {

			});
		};

		$rootScope.goCart = function () {
			$state.go('cart', {

			});
		};

		$rootScope.goProfile = function () {
			$state.go('profile', {

			});
		};

		$rootScope.goMyOrder = function () {
			$state.go('my-order', {
				tab: 'all'
			});
		};

		$rootScope.goMyFavorite = function () {
			$state.go('my-favorite', {

			});
		};

		$rootScope.goMyCashgift = function () {
			$state.go('my-cashgift', {

			});
		};

		$rootScope.goMyAddress = function () {
			$state.go('my-address', {

			});
		};

		$rootScope.goSignin = function () {
			//var config = ConfigModel.getConfig();
			//if (_isWeixin()&& config && config['wxpay.web']) {
			//	$state.go('wechat-auth', {
			//	});
			//} else
			{
				$state.go('signin', {
				});
			}

		};

		$rootScope.isWeixin = _isWeixin;

		$rootScope.toast = function (msg) {
			ngToast.create({
				content: '<a class="error-toast">' + msg + '</a>',
				timeout: 2000,
				dismissOnTimeout: true
			});
		}

		CartModel.reloadIfNeeded();

		var query = $location.search();
		if (query) {
			var weixinToken 	= _getQueryString('token');
			var weixinOpenId 	= _getQueryString('openid');
			var references		= _getQueryString("u");

            if(references){
                AppAuthenticationService.setReferences(references);
            }

			if (weixinToken ) {
				AppAuthenticationService.setToken(weixinToken);
			}

			if(weixinOpenId){
                AppAuthenticationService.setOpenId(weixinOpenId);
			}
		}
	}

})();