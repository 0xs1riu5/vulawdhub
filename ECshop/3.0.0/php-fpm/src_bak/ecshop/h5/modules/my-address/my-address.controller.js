(function () {

	'use strict';

	angular
		.module('app')
		.controller('MyAddressController', MyAddressController);

	MyAddressController.$inject = ['$scope', '$http', '$rootScope', '$stateParams', '$location', '$state', 'API', 'ENUM', 'AddressEditModel', 'MyAddressModel'];

	function MyAddressController($scope, $http, $rootScope, $stateParams, $location, $state, API, ENUM, AddressEditModel, MyAddressModel) {

		$scope.touchCreate = _touchCreate;
		$scope.touchModify = _touchModify;

		$scope.myAddressModel = MyAddressModel;
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


		$scope.myAddressModel.reload();
	}

})();