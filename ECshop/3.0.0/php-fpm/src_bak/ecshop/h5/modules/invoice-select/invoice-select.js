(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('invoice-select', {
				needAuth: true,
				url: '/invoice-select',
				title: "发票信息",
				templateUrl: 'modules/invoice-select/invoice-select.html',
			});

	}

})();