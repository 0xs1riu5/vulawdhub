(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIInvoiceService', APIInvoiceService);

    APIInvoiceService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIInvoiceService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIInvoiceService' );
        service.typeList = _typeList;
        service.contentList = _contentList;
        service.statusGet = _statusGet;
        return service;

        function _typeList(params) {
            return this.fetch( '/v2/ecapi.invoice.type.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.types : null;
            });
        }

        function _contentList(params) {
            return this.fetch( '/v2/ecapi.invoice.content.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.contents : null;
            });
        }

        function _statusGet(param) {
            return this.fetch( '/v2/ecapi.invoice.status.get', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }
    }

})();
