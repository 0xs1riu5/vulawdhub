(function () {

	'use strict';

	angular
		.module('app')
		.factory('BalanceModel', BalanceModel);

	BalanceModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function BalanceModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var PER_PAGE = 10;

		var service = {};
		service.isEmpty     = false;
		service.isLoaded    = false;
		service.isLoading   = false;
		service.isLastPage  = false;
		service.fetch       = _fetch;
		service.reload      = _reload;
        service.get         = _get;
		service.loadMore    = _loadMore;

		service.balances    = [];
        service.balanceAmount = 0;

		return service;

		function _reload() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;

			this.balances = null;
			this.isEmpty = false;
			this.isLoaded = false;
			this.isLastPage = false;

			this.fetch(1, PER_PAGE);
            this.get();
		}

		function _loadMore() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;
			if (this.isLastPage)
				return;

			if (this.balances && this.balances.length) {
				this.fetch((this.balances.length / PER_PAGE) + 1, PER_PAGE);
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

			API.balance.list(params).then(function (balances) {
				_this.balances = _this.balances ? _this.balances.concat(balances) : balances;
				_this.isEmpty = (_this.balances && _this.balances.length) ? false : true;
				_this.isLoaded = true;
				_this.isLoading = false;
				_this.isLastPage = (balances && balances.length < perPage) ? !_this.isEmpty : false;
			});
		}

		function _get() {

			if (!AppAuthenticationService.getToken())
				return;


			var params = {
			};


			var _this = this;

			API.balance.get(params).then(function (amount) {
				_this.balanceAmount = amount;
			});
		}

	}

})();