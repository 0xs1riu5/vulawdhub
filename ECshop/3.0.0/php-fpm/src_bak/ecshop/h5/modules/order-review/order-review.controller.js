/**
 * Created by howiezhang on 16/10/19.
 */
(function () {

	'use strict';

	angular
		.module('app')
		.controller('OrderReviewController', OrderReviewController);

	OrderReviewController.$inject = ['$scope', '$http', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM'];

	function OrderReviewController($scope, $http, $window, $timeout, $location, $state, $stateParams, API, ENUM) {

		var orderId = $stateParams.order;

		$scope.order = {
			id: orderId
		};

		$scope.isLoading = false;
		$scope.isLoaded = false;
		$scope.isAnonymous = 0;

		$scope.touchGood = _touchGood;
		$scope.touchMedium = _touchMedium;
		$scope.touchBad = _touchBad;
		$scope.touchSetAnonymous = _touchSetAnonymous;
		$scope.touchSubmit = _touchSubmit;

		function _reload() {

			$scope.isLoading = true;
			$scope.isLoaded = false;

			API.order.get({
				order: orderId,
			}).then(function (order) {
				$scope.order = order;
				$scope.isLoading = false;
				$scope.isLoaded = true;
			});
		}

		function _touchGood(goods) {
			if (!goods.review) {
				goods.review = {};
			}
			goods.review.goods = goods.id;
			goods.review.grade = ENUM.ORDER_GRADE.GOOD;
			if (!goods.review.content) {
				goods.review.content = "";
			}

		}

		function _touchMedium(goods) {
			if (!goods.review) {
				goods.review = {};
			}
			goods.review.goods = goods.id;
			goods.review.grade = ENUM.ORDER_GRADE.MEDIUM;
			if (!goods.review.content) {
				goods.review.content = "";
			}
		}

		function _touchBad(goods) {

			if (!goods.review) {
				goods.review = {};
			}
			goods.review.goods = goods.id;
			goods.review.grade = ENUM.ORDER_GRADE.BAD;
			if (!goods.review.content) {
				goods.review.content = "";
			}
		}

		function _touchSetAnonymous() {
			$scope.isAnonymous = !$scope.isAnonymous;
		}

		function _touchSubmit() {
			var review = [];
			for (var i = 0; i < $scope.order.goods.length; i++) {
				var good = $scope.order.goods[i];
				if (good.review) {

					if (!good.review.grade) {
						$scope.toast('请选择评价');
						return;
					}
					review.push(good.review);
				}
			}

			if (review.length < $scope.order.goods.length) {
				$scope.toast('请评价全部商品');
				return;
			}

			var params = {};
			params.order = orderId;
			params.review = JSON.stringify(review);
			if($scope.isAnonymous){
				params.is_anonymous = 1;
			}
			else{
				params.is_anonymous = 0;
			}


			API.order.review(params)
				.then(function (succeed) {
					if (succeed) {
						$state.go('review-success', {
							order: $scope.order.id
						});
					}
				})
		}

		_reload();
	}

})();