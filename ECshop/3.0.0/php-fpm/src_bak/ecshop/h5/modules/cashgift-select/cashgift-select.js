(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('cashgift-select', {
				needAuth: true,
				url: '/cashgift-select?cashgift&total',
				title: "选择红包",
				templateUrl: 'modules/cashgift-select/cashgift-select.html',
			});

	}

})();