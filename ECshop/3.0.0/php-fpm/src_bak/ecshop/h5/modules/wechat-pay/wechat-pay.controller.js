/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

    'use strict';

    angular
        .module('app')
        .controller('WeChatPayController', WeChatPayController);

    WeChatPayController.$inject = ['$scope', '$http','$rootScope', '$window','$state', '$location', '$stateParams', 'WeixinService', 'API', 'ENUM','ConfigModel'];

    function WeChatPayController($scope, $http,$rootScope, $window, $state, $location, $stateParams, WeixinService, API, ENUM,ConfigModel) {

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
                reason:"微信支付失败"
            });
        });

        var config = ConfigModel.getConfig();
        var wechat = config['wxpay.web'];
        if ( !wechat ) {
            $state.go('payment-failed', {
                order: orderId,
                reason: "配置错误"
            });
            return;
        };

        wx.config({
            debug: GLOBAL_CONFIG.DEBUG, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: wechat.app_id, // 必填，公众号的唯一标识
            timestamp: wechat.timestamp, // 必填，生成签名的时间戳
            nonceStr: wechat.nonceStr, // 必填，生成签名的随机串
            signature: wechat.signature,// 必填，签名，见附录1
            jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });

        wx.ready( function() {
            WeixinService.pay(orderId).then(function(res){

            })
        });

        wx.error(function(res){
            if(GLOBAL_CONFIG.DEBUG){
                $rootScope.toast(JSON.stringify(res));
            }
        });

    }

})();
