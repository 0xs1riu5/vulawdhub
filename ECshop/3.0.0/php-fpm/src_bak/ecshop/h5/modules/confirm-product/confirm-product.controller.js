(function () {

	'use strict';

	angular
		.module('app')
		.controller('ConfirmController', ConfirmController);

	ConfirmController.$inject = ['$scope', '$state', '$rootScope', '$stateParams', 'API', 'ConfirmProductService', 'ExpressSelectService', 'InvoiceSelectService', 'PaymentModel'];

	function ConfirmController($scope, $state, $rootScope, $stateParams, API, ConfirmProductService, ExpressSelectService, InvoiceSelectService, PaymentModel) {

		$scope.touchAddress = _touchAddress;
		$scope.touchExpress = _touchExpress;
		$scope.touchInvoice = _touchInvoice;
		$scope.touchCashgift = _touchCashgift;
		$scope.touchSubmit = _touchSubmit;
		$scope.refreshScore = _refreshScore;
		$scope.refreshComment = _refreshComment;

		$scope.consigneeList = [];

		$scope.consignee = ConfirmProductService.consignee;
		$scope.invoiceType = ConfirmProductService.invoiceType;
		$scope.invoiceTitle = ConfirmProductService.invoiceTitle;
		$scope.invoiceContent = ConfirmProductService.invoiceContent;
		$scope.cashgift = ConfirmProductService.cashgift;
		$scope.express = ConfirmProductService.express;
		$scope.coupon = ConfirmProductService.coupon;
		$scope.all_discount = 0;

		$scope.input = {
			score: 0,
			comment: ""
		};

		$scope.input.score = ConfirmProductService.input.score;
		$scope.input.comment = ConfirmProductService.input.comment;

		if($scope.input.score == 0){
			$scope.input.score = "";
		}

		$scope.rule = "";
		$scope.scoreInfo = null;
		$scope.priceInfo = {};
		$scope.canPurchase = _checkCanPurchase;
		$scope.formatPromo = _formatPromo;
		$scope.maxUseScore = 0;
		$scope.selectedGoods = [];

		if(ConfirmProductService.product){
			var card_good = {};
			card_good.product = ConfirmProductService.product;
			var attrs = ConfirmProductService.attrs;
			card_good.property = "";
			card_good.attrs = [];
			var product_price = parseFloat(card_good.product.current_price);

			var attrsLength = attrs.length;
			for (var i = 0; i < attrsLength; i++) {

				var propertiesLength = card_good.product.properties.length;
				for (var j = 0; j < propertiesLength; j++) {

					var property = card_good.product.properties[j];
					var attrsLength = property.attrs.length;
					for (var k = 0; k < attrsLength; k++) {
						var attrItem = property.attrs[k];
						if (parseInt(attrItem.id) == attrs[i]) {
							if (card_good.property.length > 0) {
								card_good.property += "," + attrItem.attr_name;
							} else {
								card_good.property = attrItem.attr_name;
							}
							card_good.attrs.push(attrItem.id);
							product_price += parseFloat(attrItem.attr_price);
						}
					}
				}
			}

			card_good.amount = ConfirmProductService.amount;
			card_good.price = product_price;

			$scope.selectedGoods.push(card_good);
		}
		else{
			return;
		}

		_reloadConsignee();
		_reloadScore();
		_refreshOrderPrice();

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

			var goodsIds = [];
			var numbers = [];
			for (var key in $scope.selectedGoods) {
				var good = $scope.selectedGoods[key];
				goodsIds.push(good.product.id);
				numbers.push(good.amount);
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
			$state.go('cashgift-select', {
				cashgift: $scope.cashgift ? $scope.cashgift.id : null,
				total: $scope.priceInfo ? $scope.priceInfo.product_price : 0
			});
		}

		function _checkCanPurchase() {
			if (!$scope.selectedGoods || !$scope.selectedGoods.length)
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

						_refreshOrderPrice();
					}

				}

			})
		}

		function _reloadScore() {

			$scope.maxUseScore = 0;

			for (var i = 0; i < $scope.selectedGoods.length; ++i) {
				$scope.maxUseScore += $scope.selectedGoods[i].product.score*$scope.selectedGoods[i].amount;
			}

			API.score.get({})
				.then(function (info) {
					$scope.scoreInfo = info;
				})
		}

		function _refreshScore() {

			 var maxScore = $scope.scoreInfo.score > $scope.maxUseScore ? $scope.maxUseScore : $scope.scoreInfo.score;

			 if ($scope.input.score > maxScore) {
			 	$scope.input.score = maxScore;
			 }

			 if ($scope.input.score < 0) {
			 	$scope.input.score = 0;
			 }

			ConfirmProductService.input.score = $scope.input.score;

			_refreshOrderPrice();
		}

		function _refreshComment(){
			ConfirmProductService.input.comment = $scope.input.comment;
		}

		function _refreshOrderPrice() {

			if (!$scope.consignee) {
				return;
			}

			var products = [];

			for (var key in $scope.selectedGoods) {
				var good = $scope.selectedGoods[key];
				var shoppingProduct = {
					goods_id: good.product.id,
					property: good.attrs,
					num: good.amount,
					total_amount: good.amount
				};
				products.push(shoppingProduct);
			}

			var params = {};
			params.order_product = JSON.stringify(products);
			if ($scope.consignee) {
				params.consignee = $scope.consignee.id;
			}

			if ($scope.express) {
				params.shipping = $scope.express.id;
			}

			if ($scope.cashgift) {
				params.cashgift = $scope.cashgift.id;
			}

			if ($scope.coupon) {
				params.coupon = $scope.coupon.id;
			}

			if ($scope.input.score) {
				params.score = $scope.input.score;
			}

			API.order
				.price(params)
				.then(function (priceInfo) {
					if(priceInfo){
						$scope.priceInfo = priceInfo;
						$scope.all_discount = priceInfo.discount_price;
						for(var promo in priceInfo.promos){
							$scope.all_discount += parseFloat(priceInfo.promos[promo].price);
						}							
					}
					
				})
		}

		function _touchSubmit() {

			var consignee = $scope.consignee;
			var express = $scope.express;

			if (!consignee) {
				$scope.toast('请填写地址')
				return;
			}

			if (!express) {
				$scope.toast('请选择快递')
				return;
			}

			var params = {};

			for (var key in $scope.selectedGoods) {
				var good = $scope.selectedGoods[key];
				params.product = good.product.id;
				params.property = JSON.stringify(good.attrs);
				params.amount = good.amount;
			}

			if ($scope.consignee) {
				params.consignee = $scope.consignee.id;
			}

			if ($scope.express) {
				params.shipping = $scope.express.id;
			}

			if ($scope.cashgift) {
				params.cashgift = $scope.cashgift.id;
			}

			if ($scope.input.score) {
				params.score = $scope.input.score;
			}

			if ($scope.invoiceContent) {
				params.invoice_content = $scope.invoiceContent.id;
			}

			if ($scope.invoice) {
				params.invoice_type = $scope.invoiceType.id;
			}

			if ($scope.invoiceTitle) {
				params.invoice_title = $scope.invoiceTitle;
			}

			if ($scope.input.comment) {
				params.comment = $scope.input.comment;
			}

			API.product.purchase(params)
				.then(function (order) {
					if (order) {
						ConfirmProductService.clear();
						ExpressSelectService.clear();
						PaymentModel.clear();
						PaymentModel.order = order;
						$state.go('payment', {
							order: order.id
						});
					}
				});
		}

	}

})();