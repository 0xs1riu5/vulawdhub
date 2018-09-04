(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIAuthSocialService', APIAuthSocialService);

    APIAuthSocialService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'AppAuthenticationService', 'ENUM'];

    function APIAuthSocialService($http, $q, $timeout, CacheFactory, AppAuthenticationService, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIAuthSocialService' );
        service.social = _social;
        return service;

        function _social( params ) {
            return this.fetch( '/v2/ecapi.auth.social', params, false, function(res){
                if ( res.data && ENUM.ERROR_CODE.OK == res.data.error_code ) {
                    if ( res.data.token && res.data.user ) {
                        AppAuthenticationService.setCredentials( res.data.token, res.data.user );
                        return true;
                    }
                }
                return false;
            });
        }

    }

})();
