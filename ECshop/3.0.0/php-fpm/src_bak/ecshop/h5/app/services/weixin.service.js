(function () {

    'use strict';

    angular
    .module('app')
    .factory('WeixinService', WeixinService);

    WeixinService.$inject = ['$http', '$location', '$q', 'CONSTANTS', 'APIPaymentService', '$rootScope', 'AppAuthenticationService'];

    function WeixinService($http, $location, $q, CONSTANTS, APIPaymentService, $rootScope, AppAuthenticationService) {

        var service = {};
        service.pay = pay;
        return service;

        function obj2string(o){
            var r=[];
            if(typeof o=="string"){
                return "\""+o.replace(/([\'\"\\])/g,"\\$1").replace(/(\n)/g,"\\n").replace(/(\r)/g,"\\r").replace(/(\t)/g,"\\t")+"\"";
            }
            if(typeof o=="object"){
                if(!o.sort){
                    for(var i in o){
                        r.push(i+":"+obj2string(o[i]));
                    }
                    if(!!document.all&&!/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)){
                        r.push("toString:"+o.toString.toString());
                    }
                    r="{"+r.join()+"}";
                }else{
                    for(var i=0;i<o.length;i++){
                        r.push(obj2string(o[i]))
                    }
                    r="["+r.join()+"]";
                }
                return r;
            }
            return o.toString();
        }

        function pay(order) {
            var openid = AppAuthenticationService.getOpenId();
            var param = {order: order, code: "wxpay.web",openid:openid};
            return APIPaymentService.pay(param).then(function (res) {

                if(res && res.data && res.data.wxpay){
                    var result = res.data;
                   if(GLOBAL_CONFIG.DEBUG){
                         $rootScope.toast(JSON.stringify(result));
                    }
                    return wx.chooseWXPay({
                        timestamp: result.wxpay.timestamp.toString(), // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                        nonceStr: result.wxpay.nonce_str, // 支付签名随机串，不长于 32 位
                        package: result.wxpay.packages, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                        signType: 'MD5', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                        paySign: result.wxpay.sign, // 支付签名
                        success: function (res) {
                            if(GLOBAL_CONFIG.DEBUG){
                                var err_msg = obj2string(res);
                                $rootScope.toast(err_msg);
                            }

                            if(res.errMsg == "chooseWXPay:ok"){
                                $rootScope.$emit('onPaySuccess', res.errMsg);
                            }
                            else{
                                $rootScope.$emit('onPayFailed', res.errMsg);
                            }

                            // 支付成功后的回调函数
                            return true;
                        },
                        cancel:function(res){
                            if(GLOBAL_CONFIG.DEBUG){
                                $rootScope.toast(JSON.stringify(res));
                            }
                                //支付取消
                                $rootScope.$emit('onPayFailed', res.errMsg);
                            },
                        // 支付失败回调函数
                        fail: function(res){
                            $rootScope.$emit('onPayFailed', res.errMsg);
                                if(GLOBAL_CONFIG.DEBUG){
                                    $rootScope.toast(JSON.stringify(res));
                                }
                            }
                    });

                }
                else{
                    return false;
                }


            });

        }

    }

})();