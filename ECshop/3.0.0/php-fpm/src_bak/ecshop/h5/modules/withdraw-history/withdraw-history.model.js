(function () {

	'use strict';

	angular
		.module('app')
		.factory('WithDrawHistoryModel', WithDrawHistoryModel);

	WithDrawHistoryModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function WithDrawHistoryModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var PER_PAGE = 10;

		var service = {};
		service.isEmpty = false;
		service.isLoaded = false;
		service.isLoading = false;
		service.isLastPage = false;
		service.status = null;
		service.withdraws = [];
		service.fetch = _fetch;
		service.reload = _reload;
		service.loadMore = _loadMore;

		return service;

		function _reload() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;

			this.withdraws = null;
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

			if (this.withdraws && this.withdraws.length) {
				this.fetch((this.withdraws.length / PER_PAGE) + 1, PER_PAGE);
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

			if (null != this.status) {
				params.status = this.status;
			}

			var _this = this;
			API.withdraw.list(params).then(function (withdraws) {
				_this.withdraws = _this.withdraws ? _this.withdraws.concat(withdraws) : withdraws;
				_this.isEmpty = (_this.withdraws && _this.withdraws.length) ? false : true;
				_this.isLoaded = true;
				_this.isLoading = false;
				_this.isLastPage = (withdraws && withdraws.length < perPage) ? !_this.isEmpty : false;
			});
		}

	}

})();