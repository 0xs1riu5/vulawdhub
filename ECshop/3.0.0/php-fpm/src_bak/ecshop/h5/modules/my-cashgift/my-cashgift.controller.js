(function () {

	'use strict';

	angular
		.module('app')
		.controller('MyCashgiftController', MyCashgiftController);

	MyCashgiftController.$inject = ['$scope', '$http', '$location', '$stateParams', '$state', 'MyCashgiftModel', 'ENUM'];

	function MyCashgiftController($scope, $http, $location, $stateParams, $state, MyCashgiftModel, ENUM) {

		$scope.TAB_AVAILABLE = 0;
		$scope.TAB_EXPIRED = 1;
		$scope.TAB_USED = 2;

		$scope.currentTab = $scope.TAB_AVAILABLE;
		$scope.myCashgiftModel = MyCashgiftModel;

		if ($stateParams.tab == 'available') {
			$scope.currentTab = $scope.TAB_AVAILABLE;
			$scope.myCashgiftModel.status = ENUM.CASHGIFT_STATUS.AVAILABLE;
		} else if ($stateParams.tab == 'expired') {
			$scope.currentTab = $scope.TAB_EXPIRED;
			$scope.myCashgiftModel.status = ENUM.CASHGIFT_STATUS.EXPIRED;
		} else if ($stateParams.tab == 'used') {
			$scope.currentTab = $scope.TAB_USED;
			$scope.myCashgiftModel.status = ENUM.CASHGIFT_STATUS.USED;
		} else {
			$scope.currentTab = $scope.TAB_AVAILABLE;
			$scope.myCashgiftModel.status = ENUM.CASHGIFT_STATUS.AVAILABLE;
		}

		$scope.touchTabAvailable = _touchTabAvailable;
		$scope.touchTabExpired = _touchTabExpired;
		$scope.touchTabUsed = _touchTabUsed;

		function _touchTabAvailable() {
			if ($scope.currentTab != $scope.TAB_AVAILABLE) {
				$scope.currentTab = $scope.TAB_AVAILABLE;
				$scope.myCashgiftModel.status = ENUM.CASHGIFT_STATUS.AVAILABLE;
				$scope.myCashgiftModel.reload();
			}
		}

		function _touchTabExpired() {
			if ($scope.currentTab != $scope.TAB_EXPIRED) {
				$scope.currentTab = $scope.TAB_EXPIRED;
				$scope.myCashgiftModel.status = ENUM.CASHGIFT_STATUS.EXPIRED;
				$scope.myCashgiftModel.reload();
			}
		}

		function _touchTabUsed() {
			if ($scope.currentTab != $scope.TAB_USED) {
				$scope.currentTab = $scope.TAB_USED;
				$scope.myCashgiftModel.status = ENUM.CASHGIFT_STATUS.USED;
				$scope.myCashgiftModel.reload();
			}
		}

		$scope.myCashgiftModel.reload();
	}

})();