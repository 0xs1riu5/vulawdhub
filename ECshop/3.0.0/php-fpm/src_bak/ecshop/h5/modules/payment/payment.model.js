(function () {

    'use strict';

    angular
    .module('app')
    .factory('PaymentModel', PaymentModel);

    PaymentModel.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'AppAuthenticationService', 'API', 'ENUM'];

    function PaymentModel($http, $q, $timeout, $rootScope, CacheFactory, AppAuthenticationService, API, ENUM) {

        var service = {};
        service.isEmpty = false;
        service.isLoaded = false;
        service.isLoading = false;
        service.types = null;
        service.order = null;
        service.reload = _reload;
        service.clear = _clear;
        return service;

        function _clear() {
            // this.isEmpty = false;
            // this.isLoaded = false;
            // this.isLoading = false;
            // this.types = null;
            this.order = null;
        }

        function _reload() {
            if ( !AppAuthenticationService.getToken() )
                return;

            var _this = this;
            return API.payment.typeList({

                })
                .then(function(types) {
                    if ( types ) {
                        _this.types = types;
                        _this.isEmpty = (_this.groups && _this.groups.length) ? false : true;
                        _this.isLoaded = true;
                        _this.isLoading = false;
                    }

                    return types ? true : false;
                });
        }
    }

})();
