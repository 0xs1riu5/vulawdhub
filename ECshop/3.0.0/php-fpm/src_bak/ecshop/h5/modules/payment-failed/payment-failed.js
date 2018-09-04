(function () {

  'use strict';

  angular
  .module('app')
  .config(config);

  config.$inject = ['$stateProvider', '$urlRouterProvider'];

  function config( $stateProvider, $urlRouterProvider ) {

    $stateProvider
    .state('payment-failed', {
      needAuth: true,
      url:'/payment-failed?order&reason',
      title: "支付失败",
      templateUrl: 'modules/payment-failed/payment-failed.html'
    });

  }

})();
