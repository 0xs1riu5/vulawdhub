(function () {

	'use strict';

	angular
		.module('app')
		.controller('PaymentSuccessController', PaymentSuccessController);

	PaymentSuccessController.$inject = ['$scope', '$http', '$location', '$state', '$stateParams'];

	function PaymentSuccessController($scope, $http, $location, $state, $stateParams) {

		var orderId = $stateParams.order;

		$scope.touchDetail = function () {
			$state.go('order-detail', {
				order: orderId
			});
		}

	}

})();