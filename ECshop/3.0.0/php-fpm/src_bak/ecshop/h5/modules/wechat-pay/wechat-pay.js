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
			.state('wechat-pay', {
				needAuth: true,
				url: '/wechat-pay?order',
				title: "微信支付",
				controller: "WeChatPayController",
				templateUrl: 'modules/wechat-pay/wechat-pay.html'
			});
	}

})();