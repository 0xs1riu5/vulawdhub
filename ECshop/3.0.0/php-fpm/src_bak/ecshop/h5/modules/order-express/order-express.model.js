(function () {

	'use strict';

	angular
		.module('app')
		.factory('OrderExpressModel', OrderExpressModel);

	OrderExpressModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function OrderExpressModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var service = {};
		service.order = null;
		service.info = null;
		service.clear = _clear;
		service.reload = _reload;
		return service;

		function _clear() {
			this.order = null;
			this.info = null;
		}

		function _reload() {
			var _this = this;
			return API.shipping.statusGet({
					order_id: this.order.id,
					tracking_no: null
				})
				.then(function (info) {
					_this.info = info;
					return info ? true : false;
				});
		}
	}

})();