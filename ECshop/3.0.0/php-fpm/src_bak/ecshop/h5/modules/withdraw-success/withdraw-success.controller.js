(function () {

	'use strict';

	angular
		.module('app')
		.controller('WithDrawSuccessController', WithDrawSuccessController);

	WithDrawSuccessController.$inject = ['$scope', '$http', '$location', '$state', '$stateParams'];

	function WithDrawSuccessController($scope, $http, $location, $state, $stateParams) {

		$scope.withdraw     = $stateParams.withdraw;
		$scope.member_memo  = $stateParams.member_memo;


	}

})();