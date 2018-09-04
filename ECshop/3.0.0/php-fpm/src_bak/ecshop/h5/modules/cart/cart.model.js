(function () {

	'use strict';

	angular
		.module('app')
		.factory('CartModel', CartModel);

	CartModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function CartModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var service = {};
		service.isEmpty = false;
		service.isLoaded = false;
		service.isLoading = false;
		service.isLastPage = false;
		service.total = 0;
		service.subtotal = {};
		service.groups = null;
		service.count = _count;
		service.add = _add;
		service.delete = _delete;
		service.update = _update;
		service.reload = _reload;
		service.reloadIfNeeded = _reloadIfNeeded;
		return service;

		function _count() {
			var count = 0;
			if (this.groups) {
				var groups = this.groups;
				for (var i = 0; i < groups.length; ++i) {
					var group = groups[i];
					for (var j = 0; j < group.goods.length; ++j) {
						count += group.goods[j].amount;
					}
				}
			}
			this.total = count;
		}

		function _add(product, attrs, amount) {
			if (!AppAuthenticationService.getToken())
				return;

			var _this = this;
			return API.cart.add({
					product: product,
					property: JSON.stringify(attrs),
					amount: amount
				})
				.then(function (succeed) {
					if (succeed) {
						_this.reload();
					}
					return succeed;
				});
		}

		function _delete(goodsId) {
			if (!AppAuthenticationService.getToken())
				return;

			var _this = this;
			return API.cart.delete({
					good: goodsId
				})
				.then(function (succeed) {
					if (succeed) {
						var groups = _this.groups;
						for (var i = 0; i < groups.length; ++i) {
							var group = groups[i];
							for (var j = 0; j < group.goods.length; ++j) {
								var goods = group.goods[j];
								if (goods.id == goodsId) {
									group.goods.splice(j, 1);
									break;
								}
							}
						}
						_this.isEmpty = (_this.groups && _this.groups.length) ? false : true;
						_this.count();
					}
					return succeed;
				});
		}

		function _update(goodsId, amount) {
			if (!AppAuthenticationService.getToken())
				return;

			var _this = this;
			return API.cart.update({
				good: goodsId,
				amount: amount
			}).then(function (succeed) {
				if (succeed) {
					var groups = _this.groups;
					for (var i = 0; i < groups.length; ++i) {
						var group = groups[i];
						for (var j = 0; j < group.goods.length; ++j) {
							var goods = group.goods[j];
							if (goods.id == goodsId) {
								goods.amount = amount;
								break;
							}
						}
					}
					_this.isEmpty = (_this.groups && _this.groups.length) ? false : true;
					_this.count();
				}
				return succeed;
			});
		}

		function _reload() {
			if (!AppAuthenticationService.getToken()) {
				// 置空
				this.total = 0;
				service.total = 0;
				return;
			}

			var _this = this;
			return API.cart.get({

				})
				.then(function (groups) {
					if (groups) {
						_this.groups = groups;
						_this.isEmpty = (_this.groups && _this.groups.length) ? false : true;
						_this.isLoaded = true;
						_this.isLoading = false;
						_this.count();
					}

					API.order
						.subtotal().then(function (subtotal) {
							_this.subtotal = subtotal;
						});

					return groups ? true : false;
				});
		}

		function _reloadIfNeeded() {
			// if (!this.isLoaded) {
			this.reload();
			// }
		}
	}

})();