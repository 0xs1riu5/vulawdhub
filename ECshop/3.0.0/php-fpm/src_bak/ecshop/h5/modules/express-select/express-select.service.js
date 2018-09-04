(function () {

    'use strict';

    angular
    .module('app')
    .factory('ExpressSelectService', ExpressSelectService);

    ExpressSelectService.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'ENUM'];

    function ExpressSelectService($http, $q, $timeout, $rootScope, CacheFactory, ENUM) {

        var service = {};
        service.expressId = null;
        service.goodsIds = null;
        service.goodsNumbers = null;
        service.region = null;
        service.clear = _clear;
        return service;

        function _clear() {
            this.expressId = null;
            this.goodsIds = null;
            this.goodsNumbers = null;
            this.region = null;
        }
    }

})();
