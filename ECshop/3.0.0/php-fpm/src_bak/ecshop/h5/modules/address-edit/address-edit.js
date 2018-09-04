(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('address-edit', {
				needAuth: true,
				url: '/address-edit',
				title: '收货地址',
				templateUrl: 'modules/address-edit/address-edit.html',
			});

	}

})();