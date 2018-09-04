(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('signin', {
				needAuth: false,
				url: '/signin',
				title: "用户登录",
				templateUrl: 'modules/signin/signin.html',
			});

	}

})();