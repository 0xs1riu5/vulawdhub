(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('address-select', {
				needAuth: true,
				url: '/address-select?address',
				title: '选择地址',
				templateUrl: 'modules/address-select/address-select.html',
			});

	}

})();