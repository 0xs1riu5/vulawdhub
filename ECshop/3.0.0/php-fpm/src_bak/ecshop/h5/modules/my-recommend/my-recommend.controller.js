(function () {

    'use strict';

    angular
    .module('app')
    .controller('MyRecommendController', MyRecommendController);

    MyRecommendController.$inject = ['$scope', '$http', '$window', '$location', '$state', '$rootScope', 'API', 'MyRecommendModel'];

    function MyRecommendController($scope, $http, $window, $location, $state, $rootScope, API, MyRecommendModel) {


        $scope.myRecommendModel = MyRecommendModel;
        $scope.myRecommendModel.reload();



    }

})();
