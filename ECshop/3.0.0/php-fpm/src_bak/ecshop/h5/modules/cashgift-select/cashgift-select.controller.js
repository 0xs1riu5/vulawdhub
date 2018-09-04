(function () {

	'use strict';

	angular
		.module('app')
		.controller('CashgiftSelectController', CashgiftSelectController);

	CashgiftSelectController.$inject = ['$scope', '$http', '$location', '$timeout', '$rootScope', '$stateParams', '$state', 'API', 'ENUM', 'CashgiftSelectModel'];

	function CashgiftSelectController($scope, $http, $location, $timeout, $rootScope, $stateParams, $state, API, ENUM, CashgiftSelectModel) {

		$scope.selectedId = $stateParams.cashgift || null;
		$scope.totalPrice = $stateParams.total || 0;

		$scope.cashgiftSelectModel = CashgiftSelectModel;
		$scope.cashgiftSelectModel.clear();
		$scope.cashgiftSelectModel.totalPrice = $scope.totalPrice;

		$scope.touchClear = _touchClear;
		$scope.touchCashgift = _touchCashgift;
		$scope.touchConfirm = _touchConfirm;

		function _touchClear() {
			$scope.selectedId = null;
		}

		function _touchCashgift(cashgift) {
			$scope.selectedId = cashgift.id;
		}

		function _touchConfirm() {
			var cashgift = null;
			var cashgifts = $scope.cashgiftSelectModel.cashgifts;

			for (var key in cashgifts) {
				if (cashgifts[key].id == $scope.selectedId) {
					cashgift = cashgifts[key];
					break;
				}
			}

			$rootScope.$emit('cashgiftChanged', cashgift);
			$scope.goBack();
		}

		$scope.cashgiftSelectModel.reload();
	}

})();