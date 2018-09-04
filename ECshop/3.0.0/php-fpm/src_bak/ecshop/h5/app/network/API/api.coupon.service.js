(function () {
    'use strict';

    angular
    .module('app')
    .factory('APICouponService', APICouponService);

    APICouponService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APICouponService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APICouponService' );
        service.list = _list;
        service.available = _available;
        return service;

        function _list( params ) {
            return this.fetch( '/v2/ecapi.coupon.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.coupons : null;
            });
        }

        function _available( params ) {
            return this.fetch( '/v2/ecapi.coupon.available', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.coupons : null;
            });
        }

    }

})();
