(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('index', {
				needAuth: false,
				url: '?token',
				title: "首页",
				templateUrl: 'modules/home/home.html'
			});


		$stateProvider
			.state('home', {
				needAuth: false,
				url: '/home',
				title: "首页",
				templateUrl: 'modules/home/home.html',
			});

	}

})();