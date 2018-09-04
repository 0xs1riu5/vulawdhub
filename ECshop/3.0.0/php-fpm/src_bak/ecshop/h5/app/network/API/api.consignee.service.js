(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIConsigneeService', APIConsigneeService);

    APIConsigneeService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIConsigneeService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIConsigneeService' );
        service.list = _list;
        service.add = _add;
        service.delete = _delete;
        service.update = _update;
        service.setDefault = _setDefault;
        return service;

        function _list( params ) {
            return this.fetch( '/v2/ecapi.consignee.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.consignees : null;
            });
        }

        function _add( params ) {
            return this.fetch( '/v2/ecapi.consignee.add', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.consignee : null;
            });
        }

        function _delete( params ) {
            return this.fetch( '/v2/ecapi.consignee.delete', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }

        function _update( params ) {
            return this.fetch( '/v2/ecapi.consignee.update', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }

        function _setDefault( params ) {
            return this.fetch( '/v2/ecapi.consignee.setDefault', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }
    }

})();
