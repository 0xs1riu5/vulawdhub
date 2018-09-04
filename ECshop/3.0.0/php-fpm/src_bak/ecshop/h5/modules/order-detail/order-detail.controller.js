(function () {

	'use strict';

	angular
		.module('app')
		.controller('OrderDetailController', OrderDetailController);

	OrderDetailController.$inject = ['$scope', '$http', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM', 'PaymentModel', 'OrderExpressModel'];

	function OrderDetailController($scope, $http, $window, $timeout, $location, $state, $stateParams, API, ENUM, PaymentModel, OrderExpressModel) {

		var orderId = $stateParams.order;

		$scope.order = {
			id: orderId
		};

		$scope.isLoading = false;
		$scope.isLoaded = false;

		$scope.touchPay = _touchPay;
		$scope.touchCancel = _touchCancel;
		$scope.touchConfirm = _touchConfirm;
		$scope.touchExpress = _touchExpress;
		$scope.touchComment = _touchComment;
		$scope.touchProduct = _touchProduct;

		$scope.showDialog = false;
		$scope.touchDialogCancel = _touchDialogCancel;
		$scope.touchDialogConfirm = _touchDialogConfirm;

		$scope.paymentModel = PaymentModel;
		$scope.orderExpressModel = OrderExpressModel;

		function _touchPay() {
			if (!$scope.order)
				return;

			$scope.paymentModel.clear();
			$scope.paymentModel.order = $scope.order;
			$state.go('payment', {});
		}

		function _touchCancel() {
			if (!$scope.order)
				return;

			$scope.showDialog = true;
		}

		function _touchDialogCancel() {
			$scope.showDialog = false;
		}

		function _touchDialogConfirm() {
			API.order.cancel({
				order: orderId,
				reason: 1
			}).then(function (order) {
				$scope.toast('取消成功');
				$scope.showDialog = false;
				_reload();
			});
		}

		function _touchConfirm() {
			API.order.confirm({
				order: orderId,
			}).then(function (order) {
				$scope.toast('确认成功');
				_reload();
			});
		}

		function _touchExpress() {
			$scope.orderExpressModel.clear();
			$scope.orderExpressModel.order = $scope.order;

			$state.go('order-express', {
				order: $scope.order.id
			});
		}

		function _touchComment() {
			$state.go('order-review', {
				order: $scope.order.id
			});
		}

		function _touchProduct(product) {
			$state.go('product', {
				product: product.id
			});
		}

		function _reload() {
			$scope.isLoading = true;
			$scope.isLoaded = false;
			API.order.get({
				order: orderId,
			}).then(function (order) {
				$scope.order = order;
				var promos = order.promos;
				$scope.promos = [];

				// score:积分  cashgift:红包  preferential:优惠金额(折扣价格)  goods_reduction:商品减免   order_reduction:(订单减免)   coupon_reduction:(优惠券减免)
				for (var key in promos) {
					if (promos[key].promo == 'score') {
						promos[key].name = "积分";
					} else if (promos[key].promo == 'cashgift') {
						promos[key].name = "红包";
					} else if (promos[key].promo == 'preferential') {
						promos[key].name = "优惠金额";
					} else if (promos[key].promo == 'goods_reduction') {
						promos[key].name = "商品减免";
					} else if (promos[key].promo == 'order_reduction') {
						promos[key].name = "订单减免";
					} else if (promos[key].promo == 'coupon_reduction') {
						promos[key].name = "优惠券减免";
					}
				}

				$scope.promos = promos;
				$scope.isLoading = false;
				$scope.isLoaded = true;
			});
		}

		_reload();
	}

})();