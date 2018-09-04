(function () {

	'use strict';

	angular
		.module('app')
		.controller('ConfirmCartController', ConfirmCartController);

	ConfirmCartController.$inject = ['$scope', '$http', '$location', '$state', '$rootScope', '$timeout', '$stateParams', 'API', 'ENUM', 'ConfirmCartService', 'ExpressSelectService', 'InvoiceSelectService', 'PaymentModel'];

	function ConfirmCartController($scope, $http, $location, $state, $rootScope, $timeout, $stateParams, API, ENUM, ConfirmCartService, ExpressSelectService, InvoiceSelectService, PaymentModel) {

		if (!ConfirmCartService.goods || !ConfirmCartService.goods.length) {
			$timeout(function () {
				$rootScope.goHome();
			}, 1);
			return;
		}

		$scope.touchAddress = _touchAddress;
		$scope.touchExpress = _touchExpress;
		$scope.touchInvoice = _touchInvoice;
		$scope.touchCashgift = _touchCashgift;
		$scope.touchSubmit = _touchSubmit;
		$scope.refreshScore = _refreshScore;
		$scope.refreshComment = _refreshComment;

		$scope.goods = ConfirmCartService.goods;
		$scope.consignee = ConfirmCartService.consignee;
		$scope.invoiceType = ConfirmCartService.invoiceType;
		$scope.invoiceTitle = ConfirmCartService.invoiceTitle;
		$scope.invoiceContent = ConfirmCartService.invoiceContent;
		$scope.cashgift = ConfirmCartService.cashgift;
		$scope.express = ConfirmCartService.express;
		$scope.coupon = ConfirmCartService.coupon;
		$scope.all_discount = 0;

		$scope.input = {
			score: 0,
			comment: ""
		};

		$scope.input.score = ConfirmCartService.input.score;
		if($scope.input.score == 0){
			$scope.input.score = "";
		}
		$scope.input.comment = ConfirmCartService.input.comment;

		$scope.scoreInfo = null;
		$scope.priceInfo = null;

		$scope.canPurchase = _checkCanPurchase();
		$scope.maxUseScore = 0;

		$scope.formatPromo = _formatPromo;

		function _touchAddress() {
			$state.go('address-select', {
				address: $scope.consignee ? $scope.consignee.id : null
			});
		}

		function _touchExpress() {

			if (!$scope.consignee) {
				$scope.toast('请选择地址');
				return;
			}

			var goods = $scope.goods;
			var goodsIds = [];
			var numbers = [];

			for (var i = 0; i < goods.length; ++i) {
				goodsIds.push(goods[i].product.id);
				numbers.push(goods[i].amount);
			}

			ExpressSelectService.clear();
			ExpressSelectService.expressId = $scope.express ? $scope.express.id : null;
			ExpressSelectService.goodsIds = goodsIds;
			ExpressSelectService.goodsNumbers = numbers;
			ExpressSelectService.region = $scope.consignee.id;

			$state.go('express-select', {});
		}

		function _touchInvoice() {
			InvoiceSelectService.clear();
			InvoiceSelectService.type = $scope.invoiceType ? $scope.invoiceType : null;
			InvoiceSelectService.title = $scope.invoiceTitle;
			InvoiceSelectService.content = $scope.invoiceContent ? $scope.invoiceContent : null;

			$state.go('invoice-select', {});
		}

		function _touchCashgift() {

			var goods = $scope.goods;
			var consignee = $scope.consignee;
			var express = $scope.express;
			var cashgift = $scope.cashgift;

			var totalPrice = 0;

			for (var key in goods) {
				totalPrice += goods[key].price;
			}

			$state.go('cashgift-select', {
				cashgift: cashgift ? cashgift.id : null,
				total: totalPrice
			});
		}

		function _touchSubmit() {

			var goods = $scope.goods;
			var consignee = $scope.consignee;
			var express = $scope.express;
			var coupon = $scope.coupon;
			var cashgift = $scope.cashgift;
			var score = $scope.input.score;
			var comment = $scope.input.comment;
			var invoiceType = $scope.invoiceType;
			var invoiceTitle = $scope.invoiceTitle;
			var invoiceContent = $scope.invoiceContent;

			var goodsIds = [];

			if (!goods || !goods.length) {
				$scope.toast('商品信息不存在')
				return;
			}

			for (var i = 0; i < goods.length; ++i) {
				goodsIds.push(goods[i].id);
			}

			if (!goodsIds || !goodsIds.length) {
				$scope.toast('商品信息不存在')
				return;
			}

			if (!consignee) {
				$scope.toast('请填写地址')
				return;
			}

			if (!express) {
				$scope.toast('请选择快递')
				return;
			}

			var params = {
				shop: 1,
				consignee: consignee ? consignee.id : null,
				cart_good_id: goodsIds ? JSON.stringify(goodsIds) : null,
				shipping: express ? express.id : null,
				invoice_type: invoiceType ? invoiceType.id : null,
				invoice_title: invoiceTitle,
				invoice_content: invoiceContent ? invoiceContent.id : null,
				coupon: coupon ? coupon.id : null,
				cashgift: cashgift ? cashgift.id : null,
				score: score,
				comment: comment
			};
			API.cart
				.checkout(params)
				.then(function (order) {
					if (order) {
						ConfirmCartService.clear();
						ExpressSelectService.clear();

						PaymentModel.clear();
						PaymentModel.order = order;
						$state.go('payment', {
							order: order.id
						});
					}
				});
		}

		function _checkCanPurchase() {
			if (!$scope.goods || !$scope.goods.length)
				return false;
			if (!$scope.consignee)
				return false;
			if (!$scope.express)
				return false;

			return true;
		}

		function _formatPromo(key) {
			if (key == 'score') {
				return "积分";
			} else if (key == 'cashgift') {
				return "红包";
			} else if (key == 'preferential') {
				return "优惠金额";
			} else if (key == 'goods_reduction') {
				return "商品减免";
			} else if (key == 'order_reduction') {
				return "订单减免";
			} else if (key == 'coupon_reduction') {
				return "优惠券减免";
			} else {
				return "其他优惠";
			}
		}

		function _reloadConsignee() {
			var param = {};
			API.consignee.list(param).then(function (consignees) {

				if (consignees) {
					$scope.consigneeList = consignees;

					if (!$scope.consignee) {
						for (var key in $scope.consigneeList) {
							if ($scope.consigneeList[key].is_default) {
								$scope.consignee = $scope.consigneeList[key];
								$rootScope.$emit('consigneeChanged', $scope.consignee);
							}
						}

					}

				}

			})
		}

		function _reloadScore() {

			$scope.maxUseScore = 0;

			for (var i = 0; i < $scope.goods.length; ++i) {
				$scope.maxUseScore += $scope.goods[i].product.score*$scope.goods[i].amount;
			}

			API.score
				.get({})
				.then(function (info) {
					$scope.scoreInfo = info;
					$scope.refreshScore();
				})
		}

		function _refreshScore() {

			if ($scope.timer) {
				$timeout.cancel($scope.timer);
				$scope.timer = null;
			}

			$scope.timer = $timeout(function () {

				 var maxScore = $scope.scoreInfo.score > $scope.maxUseScore ? $scope.maxUseScore : $scope.scoreInfo.score;

				 if ($scope.input.score > maxScore) {
				 	$scope.input.score = maxScore;
				 }

				 if ($scope.input.score < 0) {
				 	$scope.input.score = 0;
				 }
				ConfirmCartService.input.score = $scope.input.score;
				_reloadPrice();

				$scope.timer = null;

			}, 200)
		}

		function _refreshComment(){
			ConfirmCartService.input.comment = $scope.input.comment;
		}


		function _reloadPrice() {

			var goods = $scope.goods;
			var consignee = $scope.consignee;
			var express = $scope.express;
			var coupon = $scope.coupon;
			var cashgift = $scope.cashgift;
			var score = $scope.input.score;

			if (!goods || !goods.length) {
				$scope.toast('商品信息不存在');
				return;
			}

			if (!consignee) {
				return;
			}

			var products = [];

			for (var i = 0; i < goods.length; ++i) {
				products.push({
					goods_id: goods[i].product.id,
					property: goods[i].attrs.split(','),
					num: goods[i].amount
				});
			}

			var params = {};

			params.order_product = JSON.stringify(products);

			if (consignee) {
				params.consignee = consignee.id;
			}

			if (express) {
				params.shipping = express.id;
			}

			if (cashgift) {
				params.cashgift = cashgift.id;
			}

			if (coupon) {
				params.coupon = coupon.id;
			}

			if (score) {
				params.score = score;
			}

			API.order.price(params)
				.then(function (priceInfo) {
					$scope.priceInfo = priceInfo;
					$scope.all_discount = priceInfo.discount_price;
					for(var promo in priceInfo.promos){
						$scope.all_discount += parseFloat(priceInfo.promos[promo].price);
					}


				});
		}

		function _reload() {
			_reloadPrice();
			_reloadScore();
			_reloadConsignee();
		}

		_reload();
	}

})();