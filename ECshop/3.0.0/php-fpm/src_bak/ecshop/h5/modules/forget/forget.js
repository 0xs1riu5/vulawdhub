(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('forget', {
				needAuth: false,
				url: '/forget',
      title: "找回密码",
				templateUrl: 'modules/forget/forget.html',
			});

	}

})();