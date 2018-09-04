(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('payment', {
				needAuth: false,
				url: '/payment',
				title: "收银台",
				templateUrl: 'modules/payment/payment.html',
			});

	}

})();