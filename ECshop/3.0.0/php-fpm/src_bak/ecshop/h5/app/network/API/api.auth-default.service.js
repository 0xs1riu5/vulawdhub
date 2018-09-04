(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIAuthDefaultService', APIAuthDefaultService);

    APIAuthDefaultService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'AppAuthenticationService', 'ENUM'];

    function APIAuthDefaultService($http, $q, $timeout, CacheFactory, AppAuthenticationService, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIAuthDefaultService' );
        service.signup = _signup;
        service.reset = _reset;
        return service;

        function _signup( params ) {
            return this.fetch( '/v2/ecapi.auth.default.signup', params, false, function(res){
                if ( res.data && ENUM.ERROR_CODE.OK == res.data.error_code ) {
                    if ( res.data.token && res.data.user ) {
                        AppAuthenticationService.setCredentials( res.data.token, res.data.user );
                        return res;
                    }
                }
                return res;
            });
        }

        function _reset( params ) {
            return this.fetch( '/v2/ecapi.auth.default.reset', params, false, function(res){
            	return res;
            });
        }

    }

})();
