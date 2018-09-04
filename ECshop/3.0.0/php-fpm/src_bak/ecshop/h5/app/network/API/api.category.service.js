(function () {

    'use strict';

    angular
    .module('app')
    .factory('APICategoryService', APICategoryService);

    APICategoryService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APICategoryService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APICategoryService' );
        service.list = _list;
        return service;

        function _list( params ) {
            return this.fetch( '/v2/ecapi.category.list', params, true, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.categories : null;
            });
        }
    }

})();
