(function () {

    'use strict';

    angular
    .module('app')
    .factory('SearchService', SearchService);

    SearchService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM', '$rootScope'];

    function SearchService($http, $q, $timeout, CacheFactory, ENUM, $rootScope) {

        var service = {};

        service.history = null;
        service.clear = _clear;

        $rootScope.$on('searchChanged', function( event, keyword ) {
            if ( !keyword || !keyword.length )
                return;

            var index = service.history ? service.history.indexOf( keyword ) : -1;
            if ( -1 == index ) {
                service.history = service.history || [];
                service.history.push( keyword );
            }
        });

        return service;

        function _clear() {
            this.history = null;
        }
    }

})();
