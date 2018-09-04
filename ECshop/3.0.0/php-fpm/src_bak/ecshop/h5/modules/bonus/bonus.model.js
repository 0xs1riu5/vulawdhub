(function () {

	'use strict';

	angular
		.module('app')
		.factory('BonusModel', BonusModel);

	BonusModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function BonusModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var PER_PAGE = 10;

		var service = {};

		service.isEmpty     = false;
		service.isLoaded    = false;
		service.isLoading   = false;
		service.isLastPage  = false;
		service.fetch       = _fetch;
        service.getInfo     = _getInfo;
		service.reload      = _reload;
		service.loadMore    = _loadMore;

		service.bonuses = [];

		return service;

		function _reload() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;

			this.bonuses = null;
			this.isEmpty = false;
			this.isLoaded = false;
			this.isLastPage = false;

			this.fetch(1, PER_PAGE);
            return this.getInfo();
		}

		function _loadMore() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;
			if (this.isLastPage)
				return;

			if (this.bonuses && this.bonuses.length) {
				this.fetch((this.bonuses.length / PER_PAGE) + 1, PER_PAGE);
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

			API.bonus.list(params).then(function (bonuses) {
				_this.bonuses = _this.bonuses ? _this.bonuses.concat(bonuses) : bonuses;
				_this.isEmpty = (_this.bonuses && _this.bonuses.length) ? false : true;
				_this.isLoaded = true;
				_this.isLoading = false;
				_this.isLastPage = (bonuses && bonuses.length < perPage) ? !_this.isEmpty : false;
				return true;
			});
		}

		function _getInfo() {

			if (!AppAuthenticationService.getToken())
				return;

			var params = {};

			var _this = this;

			API.bonus.get(params).then(function (bonus_info) {
				_this.bonus_info = bonus_info;
				return true;

			});
		}

	}

})();