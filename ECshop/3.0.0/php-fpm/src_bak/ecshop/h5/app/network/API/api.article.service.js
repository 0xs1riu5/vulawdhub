(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIArticleService', APIArticleService);

    APIArticleService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIArticleService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIArticleService' );
        service.list = _list;
        return service;

        function _list( params ) {
            return this.fetch( '/v2/ecapi.article.list', params, false, function(res){
                return (res.data &&(ENUM.ERROR_CODE.OK == res.data.error_code)) ? res.data : null;
            });
        }
    }

})();
