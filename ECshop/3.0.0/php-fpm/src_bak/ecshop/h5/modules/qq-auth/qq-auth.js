/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('qq-auth', {
				needAuth: false,
				url: '/qq-auth',
				title: "QQ登录",
				controller: "QQAuthController",
				templateUrl: 'modules/qq-auth/qq-auth.html'
			});

	}

})();