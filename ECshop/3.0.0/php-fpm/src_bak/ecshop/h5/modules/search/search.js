(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('search', {
				needAuth: false,
				url: '/search',
				title: "搜索",
				templateUrl: 'modules/search/search.html',
			});

	}

})();