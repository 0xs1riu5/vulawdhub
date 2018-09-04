/**
 * Created by howiezhang on 16/12/8.
 */
(function () {
    'use strict';

    angular
        .module('app')
        .factory('APIBonusService', APIBonusService);

    APIBonusService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIBonusService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIBonusService' );
        service.get = _get;
        service.list = _list;
        return service;

        function _get(params) {
            return this.fetch( '/v2/ecapi.recommend.bonus.info', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.bonus_info : null;
            });
        }

        function _list(params) {
            return this.fetch( '/v2/ecapi.recommend.bonus.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.bonues : null;
            });
        }
    }

})();
