(function () {

	'use strict';

	angular
		.module('app')
		.factory('MyAddressModel', MyAddressModel);

	MyAddressModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function MyAddressModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var PER_PAGE = 10;

		var service = {};
		service.isEmpty = false;
		service.isLoaded = false;
		service.isLoading = false;
		service.isLastPage = false;
		service.consignees = null;
		service.reload = _reload;
		return service;

		function _reload() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;

			this.consignees = null;
			this.isEmpty = false;
			this.isLoaded = false;
			this.isLastPage = false;

			var _this = this;
			API.consignee
				.list({})
				.then(function (consignees) {
					_this.consignees = consignees;
					_this.isEmpty = (_this.consignees && _this.consignees.length) ? false : true;
					_this.isLoaded = true;
					_this.isLoading = false;
					_this.isLastPage = true;
				});
		}

	}

})();