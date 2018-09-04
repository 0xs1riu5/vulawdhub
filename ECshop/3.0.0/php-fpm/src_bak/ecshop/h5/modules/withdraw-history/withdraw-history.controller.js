(function () {

	'use strict';

	angular
		.module('app')
		.controller('WithDrawHistoryController', WithDrawHistoryController);

	WithDrawHistoryController.$inject = ['$scope', '$http', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM', 'WithDrawHistoryModel'];

	function WithDrawHistoryController($scope, $http, $window, $timeout, $location, $state, $stateParams, API, ENUM, WithDrawHistoryModel) {


		$scope.cancellingWithDraw = null;
		$scope.showDialog = false;

		$scope.withDrawHistoryModel = WithDrawHistoryModel;

		$scope.touchDialogCancel = _touchDialogCancel;
		$scope.touchDialogConfirm = _touchDialogConfirm;

		$scope.touchCancel = _touchCancel;


		function _touchCancel(withdraw) {
			$scope.cancellingWithDraw = withdraw;
			$scope.showDialog = true;
		}

		function _touchDialogCancel() {
			$scope.cancellingWithDraw = null;
			$scope.showDialog = false;
		}

		function _touchDialogConfirm() {
			API.withdraw.cancel({
				id: $scope.cancellingWithDraw.id
			}).then(function (withdraws) {
				$scope.withDrawHistoryModel.reload();
				$scope.cancellingWithDraw = null;
				$scope.showDialog = false;
			});
		}

		$scope.withDrawHistoryModel.reload();
	}

})();