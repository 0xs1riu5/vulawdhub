(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIRegionService', APIRegionService);

    APIRegionService.$inject  = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIRegionService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIRegionService' );
        service.list = _list;
        return service;

        function _list(params) {
            return this.fetch( '/v2/ecapi.region.list', params, true, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.regions : null;
            });
        }
    }

})();
