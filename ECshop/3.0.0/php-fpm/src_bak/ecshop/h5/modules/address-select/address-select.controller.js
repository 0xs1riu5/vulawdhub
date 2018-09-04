(function () {

	'use strict';

	angular
		.module('app')
		.controller('AddressSelectController', AddressSelectController);

	AddressSelectController.$inject = ['$scope', '$http', '$stateParams', '$rootScope', '$state', 'API', 'ENUM', 'AddressEditModel', 'AddressSelectModel'];

	function AddressSelectController($scope, $http, $stateParams, $rootScope, $state, API, ENUM, AddressEditModel, AddressSelectModel) {

		$scope.selectedId = $stateParams.address;

		$scope.addressSelectModel = AddressSelectModel;

		$scope.touchCreate = _touchCreate;
		$scope.touchModify = _touchModify;
		$scope.touchConsignee = _touchConsignee;

		$scope.addressEditModel = AddressEditModel;

		function _touchCreate() {
			$scope.addressEditModel.clear();
			$scope.addressEditModel.consignee = null;

			$state.go('address-edit', {});
		}

		function _touchModify(consignee) {
			$scope.addressEditModel.clear();
			$scope.addressEditModel.consignee = consignee;

			$state.go('address-edit', {});
		}

		function _touchConsignee(consignee) {
			$rootScope.$emit('consigneeChanged', consignee);
			$scope.goBack();
		}

		$scope.addressSelectModel.clear();
		$scope.addressSelectModel.reload();
	}

})();