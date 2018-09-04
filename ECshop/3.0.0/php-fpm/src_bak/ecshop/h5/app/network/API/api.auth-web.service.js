(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIAuthWebService', APIAuthWebService);

    APIAuthWebService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'AppAuthenticationService', 'ENUM'];

    function APIAuthWebService($http, $q, $timeout, CacheFactory, AppAuthenticationService, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIAuthWebService' );
        service.web = _web;
        return service;

        function _web( params ) {
            // return this.fetch( '/v2/ecapi.auth.web', params, false, function(res){
            //     AppAuthenticationService.setCredentials( res.data.token, res.data.user );
            //     return res.data.user;
            // });
        }

    }

})();
