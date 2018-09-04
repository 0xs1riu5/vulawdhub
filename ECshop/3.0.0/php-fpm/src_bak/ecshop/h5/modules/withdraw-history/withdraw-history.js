(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('withdraw-history', {
				needAuth: true,
				url: '/withdraw-history',
				title: "提现记录",
				templateUrl: 'modules/withdraw-history/withdraw-history.html'
			});

	}

})();