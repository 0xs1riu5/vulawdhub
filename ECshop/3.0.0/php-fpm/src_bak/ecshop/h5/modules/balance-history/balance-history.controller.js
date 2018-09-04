(function () {

	'use strict';

	angular
		.module('app')
		.controller('BalanceHistoryController', BalanceHistoryController);

	BalanceHistoryController.$inject = ['$scope', '$http', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM', 'BalanceModel'];

	function BalanceHistoryController($scope, $http, $window, $timeout, $location, $state, $stateParams, API, ENUM, BalanceModel) {

		$scope.TAB_ALL = 0;
		$scope.TAB_INCOME = 1;
		$scope.TAB_EXPENDITURE = 2;

		$scope.currentTab = $scope.TAB_ALL;
		$scope.myBalanceModel = BalanceModel;

		if ($stateParams.tab == 'all') {
			$scope.currentTab = $scope.TAB_ALL;
			$scope.myBalanceModel.status = null;
		} else if ($stateParams.tab == 'income') {
			$scope.currentTab = $scope.TAB_INCOME;
			$scope.myBalanceModel.status = ENUM.BALANCE_STATUS.IN;
		} else if ($stateParams.tab == 'expenditure') {
			$scope.currentTab = $scope.TAB_EXPENDITURE;
			$scope.myBalanceModel.status = ENUM.BALANCE_STATUS.OUT;
		} else {
			$scope.currentTab = $scope.TAB_ALL;
			$scope.myBalanceModel.status = null;
		}

		$scope.touchTabAll = _touchTabAll;
		$scope.touchTabIncome = _touchTabIncome;
		$scope.touchTabExpenditure = _touchTabExpenditure;

		function _touchTabAll() {
			if ($scope.currentTab != $scope.TAB_ALL) {
				$scope.currentTab = $scope.TAB_ALL;
				$scope.myBalanceModel.status = null;
				$scope.myBalanceModel.reload();
			}
		}

		function _touchTabIncome() {
			if ($scope.currentTab != $scope.TAB_INCOME) {
				$scope.currentTab = $scope.TAB_INCOME;
				$scope.myBalanceModel.status = ENUM.BALANCE_STATUS.IN;
				$scope.myBalanceModel.reload();
			}
		}

		function _touchTabExpenditure() {
			if ($scope.currentTab != $scope.TAB_EXPENDITURE) {
				$scope.currentTab = $scope.TAB_EXPENDITURE;
				$scope.myBalanceModel.status = ENUM.BALANCE_STATUS.OUT;
				$scope.myBalanceModel.reload();
			}
		}

		$scope.myBalanceModel.reload();
	}

})();