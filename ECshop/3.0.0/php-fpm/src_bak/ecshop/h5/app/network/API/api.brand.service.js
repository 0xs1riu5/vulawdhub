(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIBrandService', APIBrandService);

    APIBrandService.$inject =  ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIBrandService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIBrandService' );
        service.list = _list;
        return service;

        function _list( params ) {
            return this.fetch( '/v2/ecapi.brand.list', params, true, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.brands : null;
            });
        }
    }

})();
