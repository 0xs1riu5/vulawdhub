(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIVersionService', APIVersionService);

    APIVersionService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIVersionService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIVersionService' );
        service.check = _check;
        return service;

        function _check(params) {
            return this.fetch( '/v2/ecapi.version.check', params, true, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.version_info : null;
            });
        }

    }

})();
