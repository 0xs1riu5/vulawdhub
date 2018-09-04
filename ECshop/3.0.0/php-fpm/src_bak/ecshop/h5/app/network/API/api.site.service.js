(function () {
    'use strict';

    angular
    .module('app')
    .factory('APISiteService', APISiteService);

    APISiteService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APISiteService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APISiteService' );
        service.get = _get;
        return service;

        function _get(params) {
            return this.fetch( '/v2/ecapi.site.get', params, false, function(res){
                return res.data&&res.data.site_info ? res.data.site_info : null;
            });
        }

    }

})();
