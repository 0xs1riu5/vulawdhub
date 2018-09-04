(function () {

	'use strict';

	angular
		.module('app')
		.controller('OrderExpressController', OrderExpressController);

	OrderExpressController.$inject = ['$scope', '$http', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM', 'OrderExpressModel'];

	function OrderExpressController($scope, $http, $window, $timeout, $location, $state, $stateParams, API, ENUM, OrderExpressModel) {
		$scope.orderExpressModel = OrderExpressModel;
		$scope.orderExpressModel.reload();
	}

})();