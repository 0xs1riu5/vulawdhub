(function () {
	'use strict';

	angular
		.module('app')
		.factory('APIShippingService', APIShippingService);

	APIShippingService.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'ENUM'];

	function APIShippingService($http, $q, $timeout, $rootScope, CacheFactory, ENUM) {

		var service = new APIEndpoint($http, $q, $timeout, CacheFactory, 'APIShippingService');
		service.vendorList = _vendorList;
		service.statusGet = _statusGet;
		return service;

		function _vendorList(params) {
			return this.fetch('/v2/ecapi.shipping.vendor.list', params, false, function (res) {
				return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.vendors : null;
			});
		}

		function _statusGet(params) {
			return this.fetch('/v2/ecapi.shipping.status.get', params, false, function (res) {
				return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data : null;
			});
		}
	}

})();