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
			.state('alipay-wap', {
				needAuth: true,
				url: '/alipay-wap?order',
				title: "微信支付",
				controller: "AliPayWapController",
				templateUrl: 'modules/alipay-wap/alipay-wap.html'
			});
	}

})();