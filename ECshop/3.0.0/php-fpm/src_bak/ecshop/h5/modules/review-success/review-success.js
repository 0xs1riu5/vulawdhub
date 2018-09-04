(function () {

  'use strict';

  angular
  .module('app')
  .config(config);

  config.$inject = ['$stateProvider', '$urlRouterProvider'];

  function config( $stateProvider, $urlRouterProvider ) {

    $stateProvider
    .state('review-success', {
      needAuth: true,
      url:'/review-success?order',
      title: "评价成功",
      templateUrl: 'modules/review-success/review-success.html'
    });

  }

})();
