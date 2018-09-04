(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIMessageService', APIMessageService);

    APIMessageService.$inject =  ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIMessageService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIMessageService' );
        service.systemList = _systemList;
        service.orderList = _orderList;
        service.count = _count;
        return service;

        function _systemList(params) {
            return this.fetch( '/v2/ecapi.message.system.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.messages : null;
            });
        }

        function _orderList(params) {
            return this.fetch( '/v2/ecapi.message.order.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.messages : null;
            });
        }

        function _count(params) {
            return this.fetch( '/v2/ecapi.message.count', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.count : 0;
            });
        }

    }

})();
