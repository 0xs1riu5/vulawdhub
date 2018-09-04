(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('my-favorite', {
				needAuth: true,
				url: '/my-favorite',
				title: "我的收藏",
				templateUrl: 'modules/my-favorite/my-favorite.html',
			});

	}

})();