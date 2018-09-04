(function () {
    'use strict';

    angular
    .module('app')
    .factory('APINoticeService', APINoticeService);

    APINoticeService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APINoticeService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APINoticeService' );
        service.list = _list;
        return service;

        function _list( params ) {
            return this.fetch( '/v2/ecapi.notice.list', params, true, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.notices : null;
            });
        }

    }

})();
