/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

	'use strict';

	angular
		.module('app')
		.controller('WeChatAuthBaseController', WeChatAuthBaseController);

	WeChatAuthBaseController.$inject = ['$scope', '$http', '$window', '$location', '$timeout'];

	function WeChatAuthBaseController($scope, $http, $window, $location, $timeout) {

		var callbackUrl = encodeURIComponent($window.location.protocol+"//"+$window.location.host+$window.location.pathname);

		var scope = "snsapi_base";

		var locationRef = GLOBAL_CONFIG.API_HOST + "/v2/ecapi.auth.web?vendor=1"+"&scope="+scope+"&referer=" + callbackUrl;

		$window.location.href = locationRef;
	}

})();