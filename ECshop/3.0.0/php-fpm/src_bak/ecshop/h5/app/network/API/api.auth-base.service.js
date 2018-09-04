(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIAuthBaseService', APIAuthBaseService);

    APIAuthBaseService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'AppAuthenticationService', 'ENUM'];

    function APIAuthBaseService($http, $q, $timeout, CacheFactory, AppAuthenticationService, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIAuthBaseService' );
        service.signin = _signin;
        service.signout = _signout;
        return service;

        function _signin( params ) {
            return this.fetch( '/v2/ecapi.auth.signin', params, false, function(res){
                if ( res.data && ENUM.ERROR_CODE.OK == res.data.error_code ) {
                    if ( res.data.token && res.data.user ) {
                        AppAuthenticationService.setCredentials( res.data.token, res.data.user );
                        return true;
                    }
                }
                return false;
            });
        }

        function _signout() {
            var deferred = this.$q.defer();
            $timeout(function() {
                AppAuthenticationService.clearCredentials();
                deferred.resolve(true);
            }, 1);
            return deferred.promise;
        }
    }

})();
