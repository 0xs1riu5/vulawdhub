(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIAreacodeService', APIAreacodeService);

    APIAreacodeService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIAreacodeService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIAreacodeService' );
        service.list = _list;
        return service;

        function _list( params ) {
            return this.fetch( '/v2/ecapi.areacode.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.area_code : null;
            });
        }
    }

})();
