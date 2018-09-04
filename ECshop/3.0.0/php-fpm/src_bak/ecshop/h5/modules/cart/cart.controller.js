(function () {

	'use strict';

	angular
		.module('app')
		.controller('CartController', CartController);

	CartController.$inject = ['$scope', '$http', '$location', '$state', 'ConfirmCartService', 'CartModel'];

	function CartController($scope, $http, $location, $state, ConfirmCartService, CartModel) {

		$scope.showDialog = false;

		$scope.deletingGoods = null;
		$scope.touchDelete = _touchDelete;
		$scope.touchDialogCancel = _touchDialogCancel;
		$scope.touchDialogConfirm = _touchDialogConfirm;

		$scope.touchProduct = _touchProduct;
		$scope.touchSubmit = _touchSubmit;

		$scope.touchAmountSub = _touchAmountSub;
		$scope.touchAmountAdd = _touchAmountAdd;

		$scope.touchSelect = _touchSelect;
		$scope.touchSelectAll = _touchSelectAll;
		$scope.isSelected = _isSelected;
		$scope.isSelectedAll = false;
		$scope.selectedGoods = [];
		$scope.selectedAmount = 0;
		$scope.selectedPrice = 0;

		$scope.cartModel = CartModel;

		function _touchDelete(goods) {
			$scope.deletingGoods = goods;
			$scope.showDialog = true;
		}

		function _touchDialogCancel() {
			$scope.showDialog = false;
			$scope.deletingGoods = null;
		}

		function _touchDialogConfirm() {
			if (!$scope.deletingGoods)
				return;

			$scope
				.cartModel
				.delete($scope.deletingGoods.id)
				.then(function (deletedGoods) {

					if (deletedGoods) {
						var selectedGoods = $scope.selectedGoods;
						for (var i = 0; i < selectedGoods.length; ++i) {
							var goods = selectedGoods[i];
							if (goods.id == $scope.deletingGoods.id) {
								selectedGoods.splice(i, 1);
								break;
							}
						}
						$scope.toast('删除成功');
						$scope.showDialog = false;
						$scope.deletingGoods = null;
						_recomputePrice();
					}

				})

		}

		function _touchProduct(product) {
			$state.go('product', {
				product: product.id
			});
		}

		function _touchSubmit() {
			if (!$scope.selectedGoods || !$scope.selectedGoods.length) {
				$scope.toast('请先选择商品');
				return;
			}

			ConfirmCartService.clear();
			ConfirmCartService.goods = $scope.selectedGoods;
			$state.go('confirm-cart', {});
		}

		function _touchAmountAdd(target) {
			$scope
				.cartModel
				.update(target.id, target.amount + 1)
				.then(function (succeed) {
					if (succeed) {
						// TODO:
					}
					_recomputePrice();
				});
		}

		function _touchAmountSub(target) {
			if (target.amount <= 1) {
				return;
			}

			$scope
				.cartModel
				.update(target.id, target.amount - 1)
				.then(function (succeed) {
					if (succeed) {
						// TODO:
					}
					_recomputePrice();
				});
		}

		function _touchSelect(target) {
			var selectedGoods = $scope.selectedGoods;
			var targetIndex = -1;
			for (var i = 0; i < selectedGoods.length; ++i) {
				var goods = selectedGoods[i];
				if (goods.id == target.id) {
					targetIndex = i;
					break;
				}
			}

			if (-1 == targetIndex) {
				selectedGoods.push(target);
			} else {
				selectedGoods.splice(targetIndex, 1);
			}

			_recomputePrice();
		}

		function _touchSelectAll() {
			var groups = $scope.cartModel.groups;
			var selectedGoods = [];
			var isSelectedAll = $scope.isSelectedAll ? false : true;
			if (isSelectedAll) {
				for (var i = 0; i < groups.length; ++i) {
					var group = groups[i];
					for (var j = 0; j < group.goods.length; ++j) {
						var goods = group.goods[j];
						selectedGoods.push(goods);
					}
				}
			}
			$scope.selectedGoods = selectedGoods;
			$scope.isSelectedAll = isSelectedAll;

			_recomputePrice();
		}

		function _isSelected(goods) {
			var selectedGoods = $scope.selectedGoods;
			for (var i = 0; i < selectedGoods.length; ++i) {
				if (goods.id == selectedGoods[i].id) {
					return true;
				}
			}
			return false;
		}

		function _recomputePrice() {
			var amount = 0;
			var price = 0;
			var goods = $scope.selectedGoods;

			for (var i = 0; i < goods.length; ++i) {
				amount += goods[i].amount;
				price += goods[i].amount * goods[i].price;
			}

			$scope.selectedAmount = amount;
			$scope.selectedPrice = price;
		}

		function _reload() {
			$scope
				.cartModel
				.reload();
		}

		_reload();
	}

})();