(function () {

	'use strict';

	angular
		.module('app')
		.factory('AddressSelectModel', AddressSelectModel);

	AddressSelectModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'API', 'ENUM'];

	function AddressSelectModel($http, $q, $timeout, $rootScope, CacheFactory, API, ENUM) {

		var service = {};
		service.isLoading = false;
		service.isLoaded = false;
		service.consignees = null;
		service.clear = _clear;
		service.reload = _reload;
		return service;

		function _clear() {
			this.consignees = null;
		}

		function _reload() {
			this.isLoading = false;
			this.isLoaded = false;

			var _this = this;
			return API.consignee.list({

				})
				.then(function (consignees) {
					_this.consignees = consignees;
					_this.isLoading = false;
					_this.isLoaded = true;
				})
		}
	}

})();