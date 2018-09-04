(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('signup', {
				needAuth: false,
				url: '/signup',
				title: "用户注册",
				templateUrl: 'modules/signup/signup.html',
			});
	}

})();