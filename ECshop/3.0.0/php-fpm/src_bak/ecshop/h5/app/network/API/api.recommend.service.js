(function () {
    'use strict';

    angular
    .module('app')
    .factory('APIRecommendService', APIRecommendService);

    APIRecommendService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM'];

    function APIRecommendService($http, $q, $timeout, CacheFactory, ENUM) {

        var service = new APIEndpoint( $http, $q, $timeout, CacheFactory, 'APIRecommendService' );
        service.categoryList    = _categoryList;
        service.productList     = _productList;
        service.brandList       = _brandList;
        service.shopList        = _shopList;
        service.bonusInfo       = _bonusInfo;
        return service;

        function _categoryList(params) {
            return this.fetch( '/v2/ecapi.recommend.category.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.categories : null;
            });
        }

        function _productList(params) {
            return this.fetch( '/v2/ecapi.recommend.product.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.products : null;
            });
        }

        function _brandList(params) {
            return this.fetch( '/v2/ecapi.recommend.brand.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.brands : null;
            });
        }

        function _shopList(params) {
            return this.fetch( '/v2/ecapi.recommend.shop.list', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.shops : null;
            });
        }

        function _bonusInfo(params) {
            return this.fetch( '/v2/ecapi.recommend.bonus.info', params, false, function(res){
                return ENUM.ERROR_CODE.OK == res.data.error_code ? res.data.bonus_info : null;
            });
        }

    }

})();
