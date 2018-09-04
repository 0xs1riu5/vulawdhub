(function () {

	'use strict';

	angular
		.module('app')
		.controller('PaymentController', PaymentController);

	PaymentController.$inject = ['$scope', '$http', '$location', '$state', '$rootScope', '$timeout', '$stateParams', 'API', 'ENUM', 'PaymentModel'];

	function PaymentController($scope, $http, $location, $state, $rootScope, $timeout, $stateParams, API, ENUM, PaymentModel) {

		if (!PaymentModel.order) {
			$timeout(function () {
				$rootScope.goHome();
			}, 1);
			return;
		}

		$scope.selectedType = null;
		$scope.paymentModel = PaymentModel;

		$scope.isSelected = _isSelected;
		$scope.touchSelect = _touchSelect;
		$scope.touchSubmit = _touchSubmit;
		$scope.touchDetail = _touchDetail;

		function _isSelected(type) {
			if (!$scope.selectedType) {
				return false;
			}
			if (type.code == $scope.selectedType.code) {
				return true;
			}
			return false;
		}

		function _touchSelect(type) {
			$scope.selectedType = type;
		}

		function _touchSubmit() {
			if (!$scope.selectedType) {
				$scope.toast('请选择支持方式');
				return;
			}

			switch ($scope.selectedType.code) {
			case 'alipay.app':
			case 'alipay.wap':
				{
					$state.go('alipay-wap', {
						order: PaymentModel.order.id
					});
					break;
				}
			case 'wxpay.app':
			case 'wxpay.web':
				{
					if ($rootScope.isWeixin()) {
						$state.go('wechat-pay', {
							order: PaymentModel.order.id
						});
					} else {
						$scope.toast('暂不支持此方式');
					}

					break;
				}
			case 'unionpay.app':
				{
					$scope.toast('暂不支持此方式');
					break;
				}

			case 'teegon.wap':
			{
				$state.go('teegon', {
					order: PaymentModel.order.id
				});
				break;
			}
			default:
				{
					$scope.toast('暂不支持此方式');
					break;
				}
			}
		}

		function _touchDetail() {
			$state.go('order-detail', {
				order: $scope.paymentModel.order.id
			});
		}

		function _reload() {
			$scope.paymentModel
				.reload()
				.then(function (succeed) {
					if (succeed) {
						
					}
				});
		}

		_reload();
	}

})();