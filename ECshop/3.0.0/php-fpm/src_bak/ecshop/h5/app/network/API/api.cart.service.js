(function () {

    'use strict';

    angular
    .module('app')
    .factory('APICartService', APICartService);

    APICartService.$inject =  ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APICartService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APICartService' );

        service.get = _get;
        service.add = _add;
        service.delete = _delete;
        service.update = _update;
        service.clear = _clear;
        service.promos = _promos;
        service.checkout = _checkout;

        return service;

        function _get( params ) {
            return this.fetch( '/v2/ecapi.cart.get', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.goods_groups : null;
            });
        }

        function _add( params ) {
            return this.fetch( '/v2/ecapi.cart.add', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }

        function _delete( params ) {
            return this.fetch( '/v2/ecapi.cart.delete', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }

        function _update( params ) {
            return this.fetch( '/v2/ecapi.cart.update', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }

        function _clear( params ) {
            return this.fetch( '/v2/ecapi.cart.clear', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? true : false;
            });
        }

        function _promos( params ) {
            return this.fetch( '/v2/ecapi.cart.promos', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.cart_product_promos : null;
            });
        }

        function _checkout( params ) {
            return this.fetch( '/v2/ecapi.cart.checkout', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.order : null;
            });
        }

    }

})();
