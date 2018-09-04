(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIShopService', APIShopService);

    APIShopService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIShopService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIShopService' );
        return service;

    }

})();
