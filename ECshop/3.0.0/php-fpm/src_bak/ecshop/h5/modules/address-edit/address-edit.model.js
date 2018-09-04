(function () {

	'use strict';

	angular
		.module('app')
		.factory('AddressEditModel', AddressEditModel);

	AddressEditModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'API', 'ENUM', 'AppAuthenticationService'];

	function AddressEditModel($http, $q, $timeout, $rootScope, CacheFactory, API, ENUM, AppAuthenticationService) {

		var service = {};
		service.isLoaded = false;
		service.isLoading = false;
		service.consignee = null;
		service.regions = null;
		service.save = _save;
		service.clear = _clear;
		service.reload = _reload;
		service.reload = _reload;
		service.reloadIfNeeded = _reloadIfNeeded;
		return service;

		function _clear() {
			this.consignee = null;
		}

		function _save(name, mobile, region, address) {
			if (!AppAuthenticationService.getToken())
				return;

			if (!this.consignee || !this.consignee.id) {
				var _this = this;
				return API.consignee.add({
						name: name,
						mobile: mobile,
						tel: mobile,
						zip_code: '',
						region: region,
						address: address
					})
					.then(function (consignee) {
						_this.consignee = consignee;
						return consignee ? true : false;
					});
			} else {
				var _this = this;
				return API.consignee.update({
						consignee: this.consignee.id,
						name: name,
						mobile: mobile,
						tel: mobile,
						zip_code: '',
						region: region,
						address: address
					})
					.then(function (consignee) {
						_this.consignee = consignee;
						return consignee ? true : false;
					});
			}
		}

		function _reload() {
			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;

			this.isLoading = true;

			var _this = this;
			return API.region.list({})
				.then(function (regions) {
					if (regions && regions.length) {
						_this.isLoading = false;
						_this.isLoaded = true;
						_this.regions = regions;
					} else {
						$scope.toast('没有可选地址');
					}
					return regions ? true : false;
				});
		}

		function _reloadIfNeeded() {
			if (!AppAuthenticationService.getToken())
				return;

			if (!this.isLoaded) {
				return this.reload();
			} else {
				var deferred = $q.defer();
				$timeout(function () {
					deferred.resolve(true);
				}, 1);
				return deferred.promise;
			}
		}

	}

})();