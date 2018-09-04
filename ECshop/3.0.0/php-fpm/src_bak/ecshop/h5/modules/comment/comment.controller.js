(function () {

  'use strict';

  angular
  .module('app')
  .controller('CommentController', CommentController);

  CommentController.$inject = ['$scope', '$http', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM'];

  function CommentController($scope, $http, $window, $timeout, $location, $state, $stateParams, API, ENUM) {

      var PER_PAGE = 10;

      var productId = $stateParams.product;

      $scope.TAB_ALL = 0;
      $scope.TAB_BAD = 1;
      $scope.TAB_MEDIUM = 2;
      $scope.TAB_GOOD = 3;

      $scope.currentPage = 0;
      $scope.currentTab = $scope.TAB_ALL;
      $scope.comments = null;

      $scope.touchTabAll = _touchTabAll;
      $scope.touchTabBad = _touchTabBad;
      $scope.touchTabMedium = _touchTabMedium;
      $scope.touchTabGood = _touchTabGood;

      $scope.loadMore = _loadMore;

      $scope.formatGrade = _formatGrade;

      $scope.isEmpty = false;
      $scope.isLoaded = false;
      $scope.isLoading = false;
      $scope.isLastPage = false;

      function _touchTabAll() {
          $scope.currentTab = $scope.TAB_ALL;
          _reload();
      }

      function _touchTabBad() {
          $scope.currentTab = $scope.TAB_BAD;
          _reload();
      }

      function _touchTabMedium() {
          $scope.currentTab = $scope.TAB_MEDIUM;
          _reload();
      }

      function _touchTabGood() {
          $scope.currentTab = $scope.TAB_GOOD;
          _reload();
      }

      function _formatGrade( grade ) {
        if ( ENUM.REVIEW_GRADE.BAD == grade ) {
          return '差评';
        } else if ( ENUM.REVIEW_GRADE.MEDIUM == grade ) {
          return '中评';
        } else if ( ENUM.REVIEW_GRADE.GOOD == grade ) {
          return '好评';
        }
        return '中评';
      }

      function _loadMore() {
        if ( $scope.isLoading )
          return;
        if ( $scope.isLastPage )
          return;

        if ( $scope.comments && $scope.comments.length ) {
          _fetch( ($scope.comments.length / PER_PAGE) + 1, PER_PAGE );
        } else {
          _fetch( 1, PER_PAGE );
        }
      }

      function _fetch( page, perPage ) {
        var grade = ENUM.REVIEW_GRADE.ALL;

        if ( $scope.currentTab == $scope.TAB_ALL ) {
          grade = ENUM.REVIEW_GRADE.ALL;
        } else if ( $scope.currentTab == $scope.TAB_BAD ) {
          grade = ENUM.REVIEW_GRADE.BAD;
        } else if ( $scope.currentTab == $scope.TAB_MEDIUM ) {
          grade = ENUM.REVIEW_GRADE.MEDIUM;
        } else if ( $scope.currentTab == $scope.TAB_GOOD ) {
          grade = ENUM.REVIEW_GRADE.GOOD;
        }

        $scope.isLoading = true;
        API.review.productList({
          product:productId,
          grade:grade,
          page:page,
          per_page:perPage
        }).then(function(comments){
          $scope.comments = $scope.comments ? $scope.comments.concat(comments) : comments;
          $scope.isEmpty = ($scope.comments && $scope.comments.length) ? false : true;
          $scope.isLoaded = true;
          $scope.isLoading = false;
          $scope.isLastPage = (comments && comments.length < perPage) ? !$scope.isEmpty : false;
        });
      }

      function _reload() {
        if ( $scope.isLoading )
          return;

        $scope.comments = null;
        $scope.isEmpty = false;
        $scope.isLoaded = false;

        _fetch( 1, PER_PAGE );
      }

      _reload();
  }

})();
