(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('my-score', {
				needAuth: true,
				url: '/my-score?tab',
				title: "我的积分",
				templateUrl: 'modules/my-score/my-score.html',
			});

	}

})();