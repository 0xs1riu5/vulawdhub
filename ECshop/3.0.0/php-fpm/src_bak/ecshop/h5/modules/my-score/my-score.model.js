(function () {

	'use strict';

	angular
		.module('app')
		.factory('MyScoreModel', MyScoreModel);

	MyScoreModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function MyScoreModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var PER_PAGE = 10;

		var service = {};
		service.isEmpty = false;
		service.isLoaded = false;
		service.isLoading = false;
		service.isLastPage = false;
		service.score = null;
		service.rule = null;
		service.history = null;
		service.count = _count;
		service.fetch = _fetch;
		service.reload = _reload;
		service.loadMore = _loadMore;
		return service;

		function _count() {

			if (!AppAuthenticationService.getToken())
				return;

			var _this = this;

			API.score
				.get({})
				.then(function (data) {
					_this.score = data.score;
					_this.rule = data.rule;
				});
		}

		function _reload() {

			if (!AppAuthenticationService.getToken())
				return;

			if (this.isLoading)
				return;

			this.history = null;
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

			if (this.history && this.history.length) {
				this.fetch((this.history.length / PER_PAGE) + 1, PER_PAGE);
			} else {
				this.fetch(1, PER_PAGE);
			}
		}

		function _fetch(page, perPage) {

			this.isLoading = true;

			var _this = this;

			var params = {
				page: page,
				per_page: perPage
			};

			if (this.status) {
				params.status = this.status;
			}

			API.score.historyList(params).then(function (history) {
				_this.history = _this.history ? _this.history.concat(history) : history;
				_this.isEmpty = (_this.history && _this.history.length) ? false : true;
				_this.isLoaded = true;
				_this.isLoading = false;
				_this.isLastPage = (history && history.length < perPage) ? !_this.isEmpty : false;
			});
		}

	}

})();