(function () {

    'use strict';

    angular
    .module('app')
    .factory('APICashgiftService', APICashgiftService);

    APICashgiftService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APICashgiftService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APICashgiftService' );
        service.list = _list;
        service.available = _available;
        return service;

        function _list(params) {
            return this.fetch( '/v2/ecapi.cashgift.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.cashgifts : null;
            });
        }

        function _available(params) {
            return this.fetch( '/v2/ecapi.cashgift.available', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.cashgifts : null;
            });
        }
    }

})();
