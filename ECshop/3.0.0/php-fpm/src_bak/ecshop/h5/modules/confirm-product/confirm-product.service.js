(function () {

    'use strict';

    angular
    .module('app')
    .factory('ConfirmProductService', ConfirmProductService);

    ConfirmProductService.$inject = ['$http', '$q', '$timeout', 'CacheFactory', 'ENUM', '$rootScope'];

    function ConfirmProductService($http, $q, $timeout, CacheFactory, ENUM, $rootScope) {

        var service = {};

        service.product = null;
        service.properties = null;
        service.amount = null;
        service.invoiceType = null;
        service.invoiceTitle = null;
        service.invoiceContent = null;
        service.shippingVendor = null;
        service.cashgift = null;
        service.consignee = null;
        service.coupon = null;
        service.express = null;
        service.clear = _clear;
        service.input = {
            score: 0,
            comment: ""
        };

        $rootScope.$on('consigneeChanged', function( event, consignee ) {
            service.consignee = consignee;
        });

        $rootScope.$on('expressChanged', function( event, vendor ) {
            service.express = vendor;
        });

        $rootScope.$on('invoiceChanged', function( event, invoice ) {
            service.invoiceTitle = invoice.title;
            service.invoiceType = invoice.type;
            service.invoiceContent = invoice.content;
        });

        $rootScope.$on('cashgiftChanged', function( event, cashgift ) {
            service.cashgift = cashgift;
        });

        return service;

        function _clear() {
            this.product = null;
            this.properties = null;
            this.amount = null;
            this.invoiceType = null;
            this.invoiceTitle = null;
            this.invoiceContent = null;
            this.express = null;
            this.cashgift = null;
            this.consignee = null;
            this.coupon = null;
            this.input = {
                score: 0,
                comment: ""
            };
        }
    }

})();
