(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('cart-select', {
				needAuth: true,
				url: '/cart-select',
				title: "购物车",
				templateUrl: 'modules/cart-select/cart-select.html',
			});

	}

})();