(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('my-recommend', {
				needAuth: true,
				url: '/my-recommend',
				title: "可用资金",
				templateUrl: 'modules/my-recommend/my-recommend.html',
			});

	}

})();