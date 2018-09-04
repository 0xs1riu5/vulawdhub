(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIAuthMobileService', APIAuthMobileService);

    APIAuthMobileService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'AppAuthenticationService', 'ENUM'];

    function APIAuthMobileService($http, $q, $timeout, CacheFactory, AppAuthenticationService, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIAuthMobileService' );
        service.send = _send;
        service.verify = _verify;
        service.signup = _signup;
        service.reset = _reset;
        return service;

        function _send( params ) {
	        return this.fetch( '/v2/ecapi.auth.mobile.send', params, false, function(res){
                return res;
            });
        }

        function _verify( params ) {
            return this.fetch( '/v2/ecapi.auth.mobile.verify', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }

        function _signup( params ) {
            return this.fetch( '/v2/ecapi.auth.mobile.signup', params, false, function(res){
                if ( res.data && ENUM.ERROR_CODE.OK == res.data.error_code ) {
                    if ( res.data.token && res.data.user ) {
                        AppAuthenticationService.setCredentials( res.data.token, res.data.user );
                    }
                }
                return res;
            });
        }

        function _reset( params ) {
            return this.fetch( '/v2/ecapi.auth.mobile.reset', params, false, function(res){
                return res;
            });
        }

    }

})();
