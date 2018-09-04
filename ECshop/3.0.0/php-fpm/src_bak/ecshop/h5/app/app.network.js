(function () {

    'use strict';

    // http://stackoverflow.com/a/27437672
    angular
    .module('app')
    .config( AppNetworkConfig );

    AppNetworkConfig.$inject = ['$httpProvider', '$provide'];

    function sortObjectKeys(obj) {
        var keys = Object.keys(obj);
        keys.sort();
        return keys;
    }

    function AppNetworkConfig($httpProvider, $provide) {


        var sign_key = "arc4random()";
        var xxtea_key = "getprogname()";

        $httpProvider.interceptors.push('AppNetworkProxyInterceptor');
        $httpProvider.interceptors.push('AppNetworkHeaderInterceptor');

        $provide
        .factory('AppNetworkProxyInterceptor', AppNetworkProxyInterceptor);

        AppNetworkProxyInterceptor.$inject = ['$rootScope', '$cookieStore', '$location', '$q', 'CONSTANTS', 'ENUM', 'AppAuthenticationService']

        function AppNetworkProxyInterceptor($rootScope, $cookieStore, $location, $q, CONSTANTS, ENUM, AppAuthenticationService) {
            return {
                request: function(config) {
                    if (config.url.charAt(0) == '/') {
                        config.url = CONSTANTS.API_HOST + config.url;
                    }
                    return config;
                },
                requestError: function (rejection) {
                    $rootScope.toast('网络错误，请稍后再试');
                    return rejection;
                },
                response: function(response) {
                    if ( response && response.data ) {
                        if ( response.data.error_code && ENUM.ERROR_CODE.OK != response.data.error_code ) {
                            if (GLOBAL_CONFIG.DEBUG) {
                                console.error("[Error] Code: " + response.data.error_code + ", Message: " + response.data.error_desc);
                            }

                            var errorMessage = response.data.error_desc;
                            if ( !errorMessage ) {
                                errorMessage = '网络错误 ' + response.data.error_code;
                            }

                            $rootScope.toast(errorMessage);

                            if (response.data.error_code === ENUM.ERROR_CODE.TOKEN_INVALID ||
                                response.data.error_code === ENUM.ERROR_CODE.TOKEN_EXPIRED) {
                               AppAuthenticationService.kickout();
                            }
                        }
                    }
                    return response;
                },
                responseError: function (rejection) {
                    var errorMessage = '网络错误 (' + rejection.status + '), ' + rejection.statusText;

                    $rootScope.toast(errorMessage);

                    rejection.data = {
                        error_code: ENUM.ERROR_CODE.UNKNOWN,
                        error_desc: errorMessage
                    };
                    return rejection;
                }
            }
        }

        $provide.factory('AppNetworkHeaderInterceptor',AppNetworkHeaderInterceptor);

        AppNetworkHeaderInterceptor.$inject = ['$rootScope', 'AppAuthenticationService', 'ENUM'];

        function AppNetworkHeaderInterceptor($rootScope, AppAuthenticationService, ENUM){

            if ($rootScope.activeCalls == undefined) {
                $rootScope.activeCalls = 0;
            }

            return  {
                request: function (config) {
                    $rootScope.activeCalls += 1;

                    //config.headers['X-ECAPI-UserAgent'] = 'Platform/Wechat, Device/Webview';
                    config.headers['X-ECAPI-UserAgent'] = 'Platform/Wechat';
                    config.headers['X-ECAPI-UDID']      = null;
                    config.headers['X-ECAPI-Ver']       = "1.1.0";
                    config.headers['X-ECAPI-Sign']      = null;

                    var token = AppAuthenticationService.getToken();
                    if ( token ) {
                        config.headers['X-ECAPI-Authorization'] = token;
                    }

                    if (GLOBAL_CONFIG.DEBUG) {
                        console.log("[HTTP] Request, " + config.method + " '" + config.url + "'");
                    }
                    var params = config.data || {};
                    if (params != undefined && GLOBAL_CONFIG.ENCRYPTED) {

                        var resultKeys = sortObjectKeys(params);
                        var resultStr = "";

                        for (var i = 0; i < resultKeys.length; ++i) {
                            if (i > 0) {
                                resultStr += "&";
                            }
                            var resultKey = resultKeys[i];
                            var resultValue = params[resultKey];

                            resultValue = encodeURIComponent(resultValue)
                                .replace("!", "%21")
                                .replace("*", "%2A")
                                .replace("(", "%28")
                                .replace(")", "%29")
                                .replace(")", "%27")
                                .replace("~", "%7E");

                            resultStr += resultKey + "=" + resultValue;
                        }

                        var timestamp = Date.parse(new Date()) / 1000 + "";
                        var encryptedData = XXTEA.encryptToBase64(resultStr, xxtea_key);

                        var uriEncodedData = encodeURIComponent(encryptedData)
                            .replace("!", "%21")
                            .replace("*", "%2A")
                            .replace("(", "%28")
                            .replace(")", "%29")
                            .replace(")", "%27")
                            .replace("~", "%7E");

                        var formData = timestamp  + resultStr;
                        var signData = CryptoJS.HmacSHA256(formData, sign_key, {
                            asBytes: false
                        });

                        var sign = signData.toString(CryptoJS.enc.Hex);

                        config.headers["X-ECAPI-Sign"] = sign + "," + timestamp;

                        if(encryptedData && encryptedData.length > 0){
                            config.data = {
                                'x': encryptedData
                            };
                        }

                    }


                    return config;
                },
                requestError: function (rejection) {
                    $rootScope.activeCalls -= 1;

                    if (GLOBAL_CONFIG.DEBUG) {
                        console.error("[HTTP] Error");
                    }

                    return rejection;
                },
                response: function (response) {
                    $rootScope.activeCalls -= 1;

                    if (GLOBAL_CONFIG.DEBUG) {
                        console.log("[HTTP] Response, " + response.config.method + " '" + response.config.url + "', " + response.status + " " + response.statusText);
                    }

                    var headers = response.headers();
                    var ErrorCode = parseInt(headers['x-ecapi-errorcode']);
                    var ErrorDesc = headers['x-ecapi-errordesc'];
                    var NewAuthorization = headers['x-ecapi-new-authorization'];
                    if ( GLOBAL_CONFIG.ENCRYPTED) {
                        if (response.data && response.data.data) {
                            var raw = XXTEA.decryptFromBase64(response.data.data, xxtea_key);
                            var json = JSON.parse(raw);
                            if (json) {
                                delete response.data.data;
                                response.data.error_code = ErrorCode;
                                response.data.error_desc = ErrorDesc;
                                if(NewAuthorization){
                                    AppAuthenticationService.setToken(NewAuthorization);
                                }
                                for (var key in json) {
                                    response.data[key] = json[key];
                                }
                            }
                        }
                    }

                    return response;
                },
                responseError: function (rejection) {
                    $rootScope.activeCalls -= 1;

                    if (GLOBAL_CONFIG.DEBUG) {
                        console.error("[HTTP] Error");
                    }
                    $rootScope.toast('网络错误，请稍后再试');
                    return rejection;
                }
            }

        }

    }

})();