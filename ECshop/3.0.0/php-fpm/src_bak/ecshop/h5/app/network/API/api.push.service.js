(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIPushService', APIPushService);

    APIPushService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIPushService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIPushService' );
        service.update = _update;
        return service;

        function _update( params ) {
            return this.fetch( '/v2/ecapi.push.update', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res : null;
            });
        }
    }

})();
