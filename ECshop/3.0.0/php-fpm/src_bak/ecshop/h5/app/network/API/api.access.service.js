(function () {

    'use strict';

    angular
    .module('app')
    .factory('APIAccessService', APIAccessService);

    APIAccessService.$inject = ['$http'];

    function APIAccessService($http) {

        var service = {};

        return service;
    }

})();
