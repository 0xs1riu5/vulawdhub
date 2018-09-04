/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

	'use strict';

	angular
		.module('app')
		.controller('WeChatAuthController', WeChatAuthController);

	WeChatAuthController.$inject = ['$scope', '$http', '$window', '$location', '$timeout'];

	function WeChatAuthController($scope, $http, $window, $location, $timeout) {

		var callbackUrl = encodeURIComponent($window.location.protocol+"//"+$window.location.host+$window.location.pathname);

		var scope = "snsapi_userinfo";

		var locationRef = GLOBAL_CONFIG.API_HOST + "/v2/ecapi.auth.web?vendor=1"+"&scope="+scope+"&referer=" + callbackUrl;

		$window.location.href = locationRef;
	}

})();