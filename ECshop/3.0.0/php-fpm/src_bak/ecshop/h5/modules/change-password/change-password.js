(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('change-password', {
				needAuth: true,
				url: '/change-password',
				title: "修改密码",
				templateUrl: 'modules/change-password/change-password.html',
			});

	}

})();