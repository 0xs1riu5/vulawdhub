(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIUserService', APIUserService);

    APIUserService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIUserService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIUserService' );
        service.profileGet = _profileGet;
        service.profileFields = _profileFields;
        service.profileUpdate = _profileUpdate;
        service.passwordUpdate = _passwordUpdate;
        return service;

        function _profileGet(params) {
            return this.fetch( '/v2/ecapi.user.profile.get', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.user : null;
            });
        }

        function _profileFields(params) {
            return this.fetch( '/v2/ecapi.user.profile.fields', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.signup_field : null;
            });
        }

        function _profileUpdate(params) {
            return this.fetch( '/v2/ecapi.user.profile.update', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.user : null;
            });
        }

        function _passwordUpdate(params) {
            return this.fetch( '/v2/ecapi.user.password.update', params, false, function(res){
            	return res;
            });
        }
    }

})();
