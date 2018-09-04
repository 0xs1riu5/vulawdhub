(function () {

	'use strict';

	angular
		.module('app')
		.controller('ArticleController', ArticleController);

	ArticleController.$inject = ['$scope', '$state', '$stateParams', 'API', '$window'];

	function ArticleController($scope, $state, $stateParams, API, $window) {

		$scope.touchArticle = touchArticle;

		$scope.articles = [];

		var article_id = $stateParams.id;

		if ($stateParams.title) {
			$scope.title = $stateParams.title;
		} else {
			$scope.title = "使用帮助";
		}

		$scope.loading = false;
		$scope.loaded = false;
		$scope.more = true;

		var per_page = 10;
		var currentPage = 1;

		$scope.reload = _reload;
		$scope.loadMore = _loadMore;

		_reload();

		function _reload() {

			if ($scope.loading) {
				return;
			}

			var params = {};

			currentPage = 1;

			if (article_id) {
				params = {
					page: currentPage,
					per_page: per_page,
					id: article_id
				};
			} else {
				params = {
					page: currentPage,
					per_page: per_page,
					id: 0
				};
			}

			API.article.list(params).then(function (data) {
				if (data) {
					$scope.articles = data.articles;
					$scope.loading = false;
					$scope.loaded = true;
					$scope.more = parseInt(data.paged.more)
				}

			})

		}

		function _loadMore() {

			if ($scope.loading) {
				return;
			}

			var params = {};
			if (article_id) {
				params = {
					page: currentPage,
					per_page: per_page,
					id: article_id
				};
			} else {
				params = {
					page: currentPage,
					per_page: per_page,
					id: 0
				};
			}

			API.article.list(params).then(function (data) {
				if (data) {
					$scope.articles = $scope.articles.concat(data.articles);
					$scope.loading = false;
					$scope.loaded = true;
					$scope.more = parseInt(data.paged.more);
				}

			})

		}

		function touchArticle(article) {

			if (article.url) {
				$window.location.href = article.url;
			} else {
				$state.go('article', {
					id: article.id,
					title: article.title
				});
			}
		}
	}

})();