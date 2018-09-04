(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIReviewService', APIReviewService);

    APIReviewService.$inject  = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIReviewService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIReviewService' );
        service.productSubtotal = _productSubtotal;
        service.productList = _productList;
        return service;

        function _productSubtotal(params) {
            return this.fetch( '/v2/ecapi.review.product.subtotal', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.subtotal : null;
            });
        }

        function _productList(params) {
            return this.fetch( '/v2/ecapi.review.product.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.reviews : null;
            });
        }
    }

})();
