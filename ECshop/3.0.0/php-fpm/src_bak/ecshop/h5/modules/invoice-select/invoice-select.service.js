(function () {

    'use strict';

    angular
    .module('app')
    .factory('InvoiceSelectService', InvoiceSelectService);

    InvoiceSelectService.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'ENUM'];

    function InvoiceSelectService($http, $q, $timeout, $rootScope, CacheFactory, ENUM) {

        var service = {};
        service.title = null;
        service.type = null;
        service.content = null;
        service.clear = _clear;
        return service;

        function _clear() {
            this.title = null;
            this.type = null;
            this.content = null;
        }
    }

})();
