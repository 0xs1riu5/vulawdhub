/**
 * Created by howiezhang on 16/12/8.
 */
(function () {
    'use strict';

    angular
        .module('app')
        .factory('APIWithDrawService', APIWithDrawService);

    APIWithDrawService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIWithDrawService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIWithDrawService' );
        service.get     = _get;
        service.list    = _list;
        service.cancel  = _cancel;
        service.submit  = _submit;
        return service;

        function _get(params) {
            return this.fetch( '/v2/ecapi.withdraw.info', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.withdraw : null;
            });
        }

        function _cancel(params) {
            return this.fetch( '/v2/ecapi.withdraw.cancel', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.withdraw : null;
            });
        }

        function _submit(params) {
            return this.fetch( '/v2/ecapi.withdraw.submit', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.withdraw : null;
            });
        }

        function _list(params) {
            return this.fetch( '/v2/ecapi.withdraw.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.withdraws : null;
            });
        }
    }

})();
