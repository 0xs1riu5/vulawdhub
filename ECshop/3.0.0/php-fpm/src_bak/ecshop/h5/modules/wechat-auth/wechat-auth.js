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
			.state('wechat-auth', {
				needAuth: false,
				url: '/wechat-auth',
				title: "微信登录",
				controller: "WeChatAuthController",
				templateUrl: 'modules/wechat-auth/wechat-auth.html'
			});

	}

})();