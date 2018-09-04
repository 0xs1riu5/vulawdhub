(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('balance-history', {
				needAuth: true,
				url: '/balance-history',
				title: "资金明细",
				templateUrl: 'modules/balance-history/balance-history.html',
			});

	}

})();