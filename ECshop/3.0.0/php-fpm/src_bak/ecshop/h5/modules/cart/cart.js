(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('cart', {
				needAuth: true,
				url: '/cart',
				title: "购物车",
				templateUrl: 'modules/cart/cart.html',
			});

	}

})();