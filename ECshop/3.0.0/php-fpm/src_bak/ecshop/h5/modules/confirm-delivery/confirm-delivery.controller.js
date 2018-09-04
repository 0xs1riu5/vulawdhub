(function () {

	'use strict';

	angular
		.module('app')
		.controller('ConfirmDeliveryController', ConfirmDeliveryController);

	ConfirmDeliveryController.$inject = ['$scope', '$http', '$location', '$state', '$stateParams'];

	function ConfirmDeliveryController($scope, $http, $location, $state, $stateParams) {

		var orderId = $stateParams.order;
		$scope.isDelivery = true;

		$scope.reason = $stateParams.reason;
		$scope.touchDetail = function () {
			$state.go('order-detail', {
				order: orderId
			});
		}

		$scope.touchComment = function() {
			$state.go('order-review', {
				order: orderId
			});
		}
	}

})();