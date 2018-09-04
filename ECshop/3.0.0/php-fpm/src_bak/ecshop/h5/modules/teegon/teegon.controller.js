(function () {

	'use strict';

	angular
		.module('app')
		.controller('TeegonController', TeegonController);

	TeegonController.$inject = ['$scope', '$http', '$location', '$state', '$rootScope', '$timeout', '$stateParams', 'API', 'ENUM', 'PaymentModel','$window'];

	function TeegonController($scope, $http, $location, $state, $rootScope, $timeout, $stateParams, API, ENUM, PaymentModel,$window) {

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

		$scope.teegonTypes = [];

		function _isSelected(type) {
			if (!$scope.selectedType) {
				return false;
			}
			if (type.channel == $scope.selectedType.channel) {
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

			switch ($scope.selectedType.channel) {
				case 'chinapay':
				{
					var callbackUrl = encodeURIComponent($window.location.protocol+"//"+$window.location.host+$window.location.pathname);
					var params = {order:$scope.paymentModel.order.id,code:"teegon.wap",channel:'chinapay',referer:callbackUrl};
					API.payment.pay(params)
						.then(function(res) {
							if ( res.data.teegon && res.data.teegon.url ) {
								$window.location.href = res.data.teegon.url;
							}
							return true ;
						});
					break;
				}
				case 'wxpay':
				{
					var callbackUrl = encodeURIComponent($window.location.protocol+"//"+$window.location.host+$window.location.pathname);
					var params = {order:$scope.paymentModel.order.id,code:"teegon.wap",channel:'wxpay_jsapi',referer:callbackUrl};
					API.payment.pay(params)
						.then(function(res) {
							if ( res.data.teegon && res.data.teegon.url ) {
								$window.location.href = res.data.teegon.url;
							}
							return true ;
						});
					break;
				}
			}
		}


		function _reload() {

			var teegonWxPay = {"name":"微信支付","desc":"天工收银微信支付","channel":"wxpay"};
			var teegonUnionPay = {"name":"天工收银银联支付","desc":"天工收银银联支付","channel":"chinapay"};
			$scope.teegonTypes.push(teegonUnionPay);
			$scope.teegonTypes.push(teegonWxPay);
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