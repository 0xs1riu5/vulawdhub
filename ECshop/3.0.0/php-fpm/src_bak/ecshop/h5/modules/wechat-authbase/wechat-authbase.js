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
			.state('wechat-authbase', {
				needAuth: false,
				url: '/wechat-authbase',
				title: "微信登录",
				controller: "WeChatAuthBaseController",
				templateUrl: 'modules/wechat-authbase/wechat-authbase.html'
			});

	}

})();