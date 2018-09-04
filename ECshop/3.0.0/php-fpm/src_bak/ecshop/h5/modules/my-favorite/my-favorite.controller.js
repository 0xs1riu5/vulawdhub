(function () {

    'use strict';

    angular
        .module('app')
        .controller('MyFavoriteController', MyFavoriteController);

    MyFavoriteController.$inject = ['$scope', '$state', 'API', 'ENUM', 'MyFavoriteModel'];

    function MyFavoriteController($scope, $state, API, ENUM, MyFavoriteModel) {

      $scope.myFavoriteModel = MyFavoriteModel;

      $scope.touchProduct = _touchProduct;
      $scope.touchDelete = _touchDelete;

      function _touchProduct( product ) {
        $state.go('product', { product:product.id });
      }

      function _touchDelete( product ) {
        $scope.myFavoriteModel.delete( product.id );
      }

      $scope.myFavoriteModel.reload();
    }

})();
