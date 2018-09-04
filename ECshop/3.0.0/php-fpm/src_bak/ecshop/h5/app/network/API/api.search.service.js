(function () {
    'use strict';

    angular
    .module('app')
    .factory('APISearchService', APISearchService);

    APISearchService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APISearchService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APISearchService' );
        service.keywordList = _keywordList;
        service.shopList = _shopList;
        service.productList = _productList;
        service.categoryList = _categoryList;
        return service;

        function _keywordList(params) {
            return this.fetch( '/v2/ecapi.search.keyword.list', params, true, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.keywords : null;
            });
        }

        function _shopList(params) {
            return this.fetch( '/v2/ecapi.search.shop.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.shops : null;
            });
        }

        function _productList(params) {
            return this.fetch( '/v2/ecapi.search.product.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.products : null;
            });
        }

        function _categoryList(params) {
            return this.fetch( '/v2/ecapi.search.category.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res : null;
            });
        }
    }

})();
