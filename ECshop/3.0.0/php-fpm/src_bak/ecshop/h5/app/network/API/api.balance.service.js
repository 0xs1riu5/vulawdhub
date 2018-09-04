/**
 * Created by howiezhang on 16/12/8.
 */
(function () {
    'use strict';

    angular
        .module('app')
        .factory('APIBalanceService', APIBalanceService);

    APIBalanceService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIBalanceService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIBalanceService' );
        service.get = _get;
        service.list = _list;
        return service;

        function _get(params) {
            return this.fetch( '/v2/ecapi.balance.get', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.amount : null;
            });
        }

        function _list(params) {
            return this.fetch( '/v2/ecapi.balance.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.balances : null;
            });
        }
    }

})();
