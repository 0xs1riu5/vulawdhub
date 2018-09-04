(function () {

	'use strict';

	angular
		.module('app')
		.controller('AddressEditController', AddressEditController);

	AddressEditController.$inject = ['$scope', '$http','API', '$location', '$q', '$window', '$state', '$stateParams', 'AddressEditModel'];

	function AddressEditController($scope, $http,API, $location, $q, $window, $state, $stateParams, AddressEditModel) {

		$scope.touchSave = _touchSave;
		$scope.touchSetDefault = _touchSetDefault;
		$scope.touchPickerShow = _touchPickerShow;
		$scope.touchPickerRegion = _touchPickerRegion;
		$scope.touchPickerCancel = _touchPickerCancel;
		$scope.formatRegions = _formatRegions;
		$scope.touchDelete = _touchDelete;
		$scope.touchCancel = _touchCancel;

		$scope.showPicker = false;
		$scope.pickerData = [];
		$scope.pickerRegions = [];
		$scope.pickerRegionName = null;

		$scope.addressEditModel = AddressEditModel;

		var consignee = $scope.addressEditModel.consignee;
		if (consignee && consignee.id) {
			$scope.name = consignee.name;
			$scope.mobile = consignee.mobile;
			$scope.regions = consignee.regions;
			$scope.address = consignee.address;
			$scope.isDefault = consignee.is_default;
		}

		function _touchCancel(){
			$scope.goBack();
		}

		function _touchSave() {
			var name = $scope.name;
			var mobile = $scope.mobile;
			var address = $scope.address;
			var regions = $scope.regions;

			if (!name || name.length < 2) {
				$scope.toast('请输入姓名');
				return;
			}

			if (!mobile || mobile.length < 5) {
				$scope.toast('请输入电话');
				return;
			}

			if (!regions || !regions.length) {
				$scope.toast('请选择地区');
				return;
			}

			var lastRegion = regions[regions.length - 1];
			if (!lastRegion) {
				$scope.toast('请选择地区');
				return;
			}

			if (!address || address.length < 1) {
				$scope.toast('请输入地址');
				return;
			}

			$scope.addressEditModel
				.save(name, mobile, lastRegion.id, address)
				.then(function (success) {
					if (success) {
						$scope.toast('保存成功');
						$scope.goBack();
					} else {
						$scope.toast('请稍后再试');
					}
				})
		}

		function _touchSetDefault() {
			$scope.isDefault = !$scope.isDefault;
		}

		function _touchDelete(){

			var params = {};
			params.consignee = $scope.addressEditModel.consignee.id;
			API.consignee
				.delete(params)
				.then(function (res) {
					if(res){
						$scope.toast('删除成功');
						$scope.goBack();
					}
					else{
						$scope.toast('删除失败');
					}

				});
		}

		function _touchPickerShow() {
			$scope.pickerData = [];
			$scope.pickerRegions = [];
			$scope.pickerRegionName = null;

			$scope.showPicker = true;

			$scope.addressEditModel
				.reloadIfNeeded()
				.then(function (success) {
					if (success) {
						$scope.pickerData = $scope.addressEditModel.regions;
					} else {
						$scope.toast('请稍后再试');
						$scope.touchPickerCancel();
					}
				});
		}

		function _touchPickerRegion(region) {
			$scope.pickerRegions.push(region);
			$scope.pickerRegionName = _formatRegions($scope.pickerRegions);

			if (region.regions && region.regions.length) {
				$scope.pickerData = region.regions;
			} else {
				$scope.regions = $scope.pickerRegions;
				$scope.showPicker = false;
			}
		}

		function _touchPickerCancel() {
			$scope.showPicker = false;

			$scope.pickerData = [];
			$scope.pickerRegions = [];
			$scope.pickerRegionName = null;
		}

		function _formatRegions(regions) {
			var address = '';

			for (var i = 0; i < regions.length; ++i) {
				address += regions[i].name;
				address += ' ';
			}

			return address;
		}
	}

})();