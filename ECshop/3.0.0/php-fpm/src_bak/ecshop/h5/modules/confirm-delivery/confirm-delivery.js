(function () {

  'use strict';

  angular
  .module('app')
  .config(config);

  config.$inject = ['$stateProvider', '$urlRouterProvider'];

  function config( $stateProvider, $urlRouterProvider ) {

    $stateProvider
    .state('confirm-delivery', {
      needAuth: true,
      url:'/confirm-delivery?order',
      title: "交易成功",
      templateUrl: 'modules/confirm-delivery/confirm-delivery.html'
    });

  }

})();
