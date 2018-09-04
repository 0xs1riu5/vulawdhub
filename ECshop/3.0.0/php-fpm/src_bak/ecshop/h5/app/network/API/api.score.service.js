(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIScoreService', APIScoreService);

    APIScoreService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIScoreService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIScoreService' );
        service.get = _get;
        service.historyList = _historyList;
        return service;

        function _get(params) {
            return this.fetch( '/v2/ecapi.score.get', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data : null;
            });
        }

        function _historyList(params) {
            return this.fetch( '/v2/ecapi.score.history.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.history : null;
            });
        }
    }

})();
