(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('my-cashgift', {
				needAuth: true,
				url: '/my-cashgift',
				title: "我的红包",
				templateUrl: 'modules/my-cashgift/my-cashgift.html',
			});

	}

})();