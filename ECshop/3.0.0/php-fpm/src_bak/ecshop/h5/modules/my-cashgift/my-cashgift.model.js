(function () {

	'use strict';

	angular
		.module('app')
		.factory('MyCashgiftModel', MyCashgiftModel);

	MyCashgiftModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function MyCashgiftModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var PER_PAGE = 10;

		var service = {};
		service.isEmpty = false;
		service.isLoaded = false;
		service.isLoading = false;
		service.isLastPage = false;
		service.status = null;
		service.cashgifts = null;
		service.fetch = _fetch;
		service.reload = _reload;
		service.loadMore = _loadMore;
		return service;

		function _reload() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;

			this.cashgifts = null;
			this.isEmpty = false;
			this.isLoaded = false;
			this.isLastPage = false;

			this.fetch(1, PER_PAGE);
		}

		function _loadMore() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;
			if (this.isLastPage)
				return;

			if (this.cashgifts && this.cashgifts.length) {
				this.fetch((this.cashgifts.length / PER_PAGE) + 1, PER_PAGE);
			} else {
				this.fetch(1, PER_PAGE);
			}
		}

		function _fetch(page, perPage) {

			if (!AppAuthenticationService.getToken())
				return;

			this.isLoading = true;

			var params = {
				page: page,
				per_page: perPage
			};


			params.status = this.status;

			var _this = this;
			API.cashgift.list(params).then(function (cashgifts) {
				_this.cashgifts = _this.cashgifts ? _this.cashgifts.concat(cashgifts) : cashgifts;
				_this.isEmpty = (_this.cashgifts && _this.cashgifts.length) ? false : true;
				_this.isLoaded = true;
				_this.isLoading = false;
				_this.isLastPage = (cashgifts && cashgifts.length < perPage) ? !_this.isEmpty : false;
			});
		}

	}

})();