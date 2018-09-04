(function () {

	'use strict';

	angular
		.module('app')
		.factory('MyRecommendModel', MyRecommendModel);

	MyRecommendModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

	function MyRecommendModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

		var service = {};
		service.reload = _reload;
		service.fetch = _fetch;

		return service;

		function _reload() {

			if (!AppAuthenticationService.getToken())
				return;

			this.fetch();
		}



		function _fetch() {

			if (!AppAuthenticationService.getToken())
				return;

			var _this = this;
			var params = {
			};

			API.recommend.bonusInfo(params).then(function (bonus_info) {
				_this.bonus_info = bonus_info;
			});

		}

	}

})();