(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('withdraw-success', {
				needAuth: true,
				url: '/withdraw-success?withdraw&member_memo',
				title: "提现成功",
				templateUrl: 'modules/withdraw-success/withdraw-success.html',
			});

	}

})();