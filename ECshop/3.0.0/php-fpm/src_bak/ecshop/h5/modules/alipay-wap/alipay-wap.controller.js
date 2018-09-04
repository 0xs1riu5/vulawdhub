/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

    'use strict';

    angular
        .module('app')
        .controller('AliPayWapController', AliPayWapController);

    AliPayWapController.$inject = ['$scope', '$http','$rootScope', '$window','$state', '$location', '$stateParams', 'PaymentModel', 'API', '$sce','ConfigModel'];

    function AliPayWapController($scope, $http,$rootScope, $window, $state, $location, $stateParams, PaymentModel, API, $sce,ConfigModel) {

        $scope.paymentModel = PaymentModel;
        var orderId = $stateParams.order;
        if ( !orderId ) {
            $state.go('payment-failed', {
                order: orderId,
                reason: "参数错误"
            });
            return;
        }

        $rootScope.$on('onPaySuccess', function( event,reason ) {
            $state.go('payment-success', {
                order:orderId
            });
        });

        $rootScope.$on('onPayFailed', function( event,reason ) {
            $state.go('payment-failed', {
                order:orderId,
                reason:"支付宝支付失败"
            });
        });

        var callbackUrl = encodeURIComponent($window.location.protocol+"//"+$window.location.host+$window.location.pathname);
        var params = {order:orderId,code:"alipay.wap",referer:callbackUrl};
        API.payment.pay(params)
            .then(function(res) {
                if ( res.data.alipay&&res.data.alipay.html  ) {
                    $scope.alipay_html = $sce.trustAsHtml(res.data.alipay.html);
                }
                return true ;
            });

    }

})();
