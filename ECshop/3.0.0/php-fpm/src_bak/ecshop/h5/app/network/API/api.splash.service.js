(function () {
    'use strict';

    angular
    .module('app')
    .factory('APISplashService', APISplashService);

    APISplashService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APISplashService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APISplashService' );
        service.preview = _preview;
        service.list = _list;
        return service;

        function _preview(params) {
            return this.fetch( '/v2/ecapi.splash.preview', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.splash : null;
            });
        }

        function _list(params) {
            return this.fetch( '/v2/ecapi.splash.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.splashes : null;
            });
        }
    }

})();
