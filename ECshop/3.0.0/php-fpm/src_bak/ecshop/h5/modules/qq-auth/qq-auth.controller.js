/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

	'use strict';

	angular
		.module('app')
		.controller('QQAuthController', QQAuthController);

	QQAuthController.$inject = ['$scope', '$http', '$window', '$location', '$timeout'];

	function QQAuthController($scope, $http, $window, $location, $timeout) {

		var callbackUrl = encodeURIComponent($window.location.protocol+"//"+$window.location.host+$window.location.pathname);

		var locationRef = GLOBAL_CONFIG.API_HOST + "/v2/ecapi.auth.web?vendor=3"+"&referer=" + callbackUrl;

		$window.location.href = locationRef;
	}

})();