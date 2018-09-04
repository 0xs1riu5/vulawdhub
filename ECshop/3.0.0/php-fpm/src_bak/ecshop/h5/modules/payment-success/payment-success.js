(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('payment-success', {
				needAuth: true,
				url: '/payment-success?order',
				title: "付款成功",
				templateUrl: 'modules/payment-success/payment-success.html',
			});

	}

})();