(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('express-select', {
				needAuth: true,
				url: '/express-select',
				title: "配送方式",
				templateUrl: 'modules/express-select/express-select.html',
			});
	}

})();