(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('comment', {
				needAuth: false,
				url: '/comment/?product',
				title: "商品评价",
				templateUrl: 'modules/comment/comment.html',
			});

	}

})();