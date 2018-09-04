/**
 * Created by howiezhang on 16/10/19.
 */
(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('order-review', {
				needAuth: true,
				url: '/order-review?order',
				title: "订单评价",
				templateUrl: 'modules/order-review/order-review.html',
			});

	}

})();