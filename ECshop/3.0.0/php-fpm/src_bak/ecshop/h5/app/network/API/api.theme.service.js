(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIThemeService', APIThemeService);

    APIThemeService.$inject = ['$http'];

    function APIThemeService($http) {

        var service = {};
        return service;
    }

})();
