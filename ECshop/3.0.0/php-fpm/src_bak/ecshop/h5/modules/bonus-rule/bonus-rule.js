/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

	'use strict';

	angular
		.module('app')
		.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {

		$stateProvider
			.state('bonus-rule', {
				needAuth: false,
				url: '/bonus-rule',
				title: "分成规则",
				controller: "BonusRuleController",
				templateUrl: 'modules/bonus-rule/bonus-rule.html'
			});
	}

})();