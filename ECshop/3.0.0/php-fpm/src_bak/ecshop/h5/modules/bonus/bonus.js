(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('bonus', {
				needAuth: true,
				url: '/bonus',
				title: "我的订单",
				templateUrl: 'modules/bonus/bonus.html',
			});

	}

})();