(function () {

	'use strict';

	angular
		.module('app')
		.controller('MyScoreController', MyScoreController);

	MyScoreController.$inject = ['$scope', '$http', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM', 'PaymentModel', 'MyScoreModel'];

	function MyScoreController($scope, $http, $window, $timeout, $location, $state, $stateParams, API, ENUM, PaymentModel, MyScoreModel) {

		$scope.TAB_ALL = 0;
		$scope.TAB_INCOME = 1;
		$scope.TAB_EXPENDITURE = 2;

		$scope.currentTab = $scope.TAB_ALL;
		$scope.myScoreModel = MyScoreModel;
		$scope.paymentModel = PaymentModel;

		if ($stateParams.tab == 'all') {
			$scope.currentTab = $scope.TAB_ALL;
			$scope.myScoreModel.status = null;
		} else if ($stateParams.tab == 'income') {
			$scope.currentTab = $scope.TAB_INCOME;
			$scope.myScoreModel.status = ENUM.SCORE_STATUS.INCOME;
		} else if ($stateParams.tab == 'expenditure') {
			$scope.currentTab = $scope.TAB_EXPENDITURE;
			$scope.myScoreModel.status = ENUM.SCORE_STATUS.EXPENDITURE;
		} else {
			$scope.currentTab = $scope.TAB_ALL;
			$scope.myScoreModel.status = null;
		}

		$scope.touchTabAll = _touchTabAll;
		$scope.touchTabIncome = _touchTabIncome;
		$scope.touchTabExpenditure = _touchTabExpenditure;

		function _touchTabAll() {
			if ($scope.currentTab != $scope.TAB_ALL) {
				$scope.currentTab = $scope.TAB_ALL;
				$scope.myScoreModel.status = null;
				$scope.myScoreModel.reload();
			}
		}

		function _touchTabIncome() {
			if ($scope.currentTab != $scope.TAB_INCOME) {
				$scope.currentTab = $scope.TAB_INCOME;
				$scope.myScoreModel.status = ENUM.SCORE_STATUS.INCOME;
				$scope.myScoreModel.reload();
			}
		}

		function _touchTabExpenditure() {
			if ($scope.currentTab != $scope.TAB_EXPENDITURE) {
				$scope.currentTab = $scope.TAB_EXPENDITURE;
				$scope.myScoreModel.status = ENUM.SCORE_STATUS.EXPENDITURE;
				$scope.myScoreModel.reload();
			}
		}

		$scope.myScoreModel.count();
		$scope.myScoreModel.reload();
	}

})();