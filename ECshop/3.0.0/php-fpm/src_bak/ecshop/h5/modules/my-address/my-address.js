(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('my-address', {
				needAuth: true,
				url: '/my-address',
				title: "地址管理",
				templateUrl: 'modules/my-address/my-address.html',
			});

	}

})();