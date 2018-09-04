(function () {

  'use strict';

  angular
  .module('app')
  .controller('ExpressSelectController', ExpressSelectController);

  ExpressSelectController.$inject = ['$scope', '$http', '$window', '$rootScope', '$state', '$stateParams', 'API', 'ENUM', 'ExpressSelectService'];

  function ExpressSelectController($scope, $http, $window, $rootScope, $state,$stateParams, API, ENUM, ExpressSelectService) {

    $scope.selectedId = ExpressSelectService.expressId || null;
    $scope.goodsIds = ExpressSelectService.goodsIds || [];
    $scope.goodsNumbers = ExpressSelectService.goodsNumbers || [];
    $scope.region = ExpressSelectService.region || null;

    $scope.vendors = [];
    $scope.touchVendor = _touchVendor;

    function _touchVendor( vendor ) {
      $rootScope.$emit('expressChanged', vendor);
      $scope.goBack();
    }

    function _reload() {

      var goodsIds = $scope.goodsIds;
      var goodsNumbers = $scope.goodsNumbers;
      var goods = [];

      for ( var i = 0; i < goodsIds.length && i < goodsNumbers.length; ++i ) {
        goods.push({
          goods_id: goodsIds[i],
          num:goodsNumbers[i]
        })
      }

      var products = [];
      for (var key in $scope.selectedGoods) {
        var good = $scope.selectedGoods[key];
        var shoppingProduct = {goods_id: good.product.id, num: good.amount};
        products.push(shoppingProduct);
      }

      API.shipping
      .vendorList({
        shop: 1,
        products: JSON.stringify(goods),
        address: $scope.region
      })
      .then(function(vendors) {
          $scope.vendors = vendors;
      });
    }

    _reload();
  }


})();
