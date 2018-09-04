(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('order-express', {
				needAuth: true,
				url: '/order-express/?order',
				title: "物流信息",
				templateUrl: 'modules/order-express/order-express.html',
			});

	}

})();