(function() {

    'use strict';

    angular
        .module('app')
        .factory('MyFavoriteModel', MyFavoriteModel);

    MyFavoriteModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

    function MyFavoriteModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

        var PER_PAGE = 10;

        var service = {};
        service.isEmpty = false;
        service.isLoaded = false;
        service.isLoading = false;
        service.isLastPage = false;
        service.products = null;
        service.fetch = _fetch;
        service.reload = _reload;
        service.loadMore = _loadMore;
        service.delete = _delete;
        return service;

        function _delete(productId) {
            if (!AppAuthenticationService.getToken())
                return;

            var _this = this;
            API.product.unlike({
                    product: productId
                })
                .then(function(success) {
                    _this.reload();
                });
        }

        function _reload() {

            if (!AppAuthenticationService.getToken())
                return;

            if (this.isLoading)
                return;

            this.products = null;
            this.isEmpty = false;
            this.isLoaded = false;
            this.isLastPage = false;

            this.fetch(1, PER_PAGE);
        }

        function _loadMore() {

            if ( this.isLoading )
                return;
            if ( this.isLastPage )
                return;

            if (this.products && this.products.length) {
                this.fetch( (this.products.length / PER_PAGE) + 1, PER_PAGE );
            } else {
                this.fetch( 1, PER_PAGE );
            }
        }

        function _fetch( page, perPage ) {

            this.isLoading = true;

            var _this = this;
            API.product
                .likedList({
                    page: page,
                    per_page: perPage
                }).then(function(products) {
                    _this.products = _this.products ? _this.products.concat(products) : products;
                    _this.isEmpty = (_this.products && _this.products.length) ? false : true;
                    _this.isLoaded = true;
                    _this.isLoading = false;
                    _this.isLastPage = (products && products.length < perPage) ? !_this.isEmpty : false;
                });
        }

    }

})();