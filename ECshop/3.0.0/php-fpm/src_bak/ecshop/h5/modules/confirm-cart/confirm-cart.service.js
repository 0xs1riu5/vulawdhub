(function () {

    'use strict';

    angular
    .module('app')
    .factory('ConfirmCartService', ConfirmCartService);

    ConfirmCartService.$inject = ['$http', '$q', '$timeout', '$rootScope', 'CacheFactory', 'ENUM'];

    function ConfirmCartService($http, $q, $timeout, $rootScope, CacheFactory, ENUM) {

        var service = {};

        service.goods = [];
        service.invoiceType = null;
        service.invoiceTitle = null;
        service.invoiceContent = null;
        service.express = null;
        service.cashgift = null;
        service.consignee = null;
        service.coupon = null;
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
            this.goods = [];
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
