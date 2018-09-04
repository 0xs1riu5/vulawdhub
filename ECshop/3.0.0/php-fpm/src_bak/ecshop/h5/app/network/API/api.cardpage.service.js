(function () {

    'use strict';

    angular
    .module('app')
    .factory('APICardpageService', APICardpageService);

    APICardpageService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APICardpageService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIBrandService' );
        service.preview = _preview;
        service.get = _get;
        return service;

        function _preview( params ) {
            return this.fetch( '/v2/ecapi.cardpage.preview', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.cardpage : null;
            });
        }

        function _get( params ) {
            return this.fetch( '/v2/ecapi.cardpage.get', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.cardpage : null;
            });
        }
    }

})();
