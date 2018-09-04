(function(global) {

    'use strict';

    angular
    .module('app')
    .factory('API', API);

    API.$inject = [
        'APIAccessService',
        'APIAreacodeService',
        'APIArticleService',
        'APIAuthBaseService',
        'APIAuthDefaultService',
        'APIAuthMobileService',
        'APIAuthSocialService',
        'APIAuthWebService',
        'APIBannerService',
        'APIBrandService',
        'APICardpageService',
        'APICartService',
        'APICashgiftService',
        'APICategoryService',
        'APIConfigService',
        'APIConsigneeService',
        'APICouponService',
        'APIInvoiceService',
        'APIMessageService',
        'APINoticeService',
        'APIOrderService',
        'APIPaymentService',
        'APIProductService',
        'APIPushService',
        'APIRecommendService',
        'APIRegionService',
        'APIReviewService',
        'APIScoreService',
        'APISearchService',
        'APIShippingService',
        'APIShopService',
        'APISiteService',
        'APISplashService',
        'APIThemeService',
        'APIUserService',
        'APIVersionService',
        'APIBonusService',
        'APIBalanceService',
        'APIWithDrawService'
    ];

    function API(
        APIAccessService,
        APIAreacodeService,
        APIArticleService,
        APIAuthBaseService,
        APIAuthDefaultService,
        APIAuthMobileService,
        APIAuthSocialService,
        APIAuthWebService,
        APIBannerService,
        APIBrandService,
        APICardpageService,
        APICartService,
        APICashgiftService,
        APICategoryService,
        APIConfigService,
        APIConsigneeService,
        APICouponService,
        APIInvoiceService,
        APIMessageService,
        APINoticeService,
        APIOrderService,
        APIPaymentService,
        APIProductService,
        APIPushService,
        APIRecommendService,
        APIRegionService,
        APIReviewService,
        APIScoreService,
        APISearchService,
        APIShippingService,
        APIShopService,
        APISiteService,
        APISplashService,
        APIThemeService,
        APIUserService,
        APIVersionService,
        APIBonusService,
        APIBalanceService,
        APIWithDrawService) {

        return {
            access: APIAccessService,
            areacode: APIAreacodeService,
            article: APIArticleService,
            auth: {
                base: APIAuthBaseService,
                default: APIAuthDefaultService,
                mobile: APIAuthMobileService,
                social: APIAuthSocialService,
                web: APIAuthWebService
            },
            banner: APIBannerService,
            brand: APIBrandService,
            cardpage: APICardpageService,
            cart: APICartService,
            cashgift: APICashgiftService,
            category: APICategoryService,
            config: APIConfigService,
            consignee: APIConsigneeService,
            coupon: APICouponService,
            invoice: APIInvoiceService,
            message: APIMessageService,
            notice: APINoticeService,
            order: APIOrderService,
            payment: APIPaymentService,
            product: APIProductService,
            push: APIPushService,
            recommend: APIRecommendService,
            region: APIRegionService,
            review: APIReviewService,
            score: APIScoreService,
            search: APISearchService,
            shipping: APIShippingService,
            shop: APIShopService,
            site: APISiteService,
            splash: APISplashService,
            theme: APIThemeService,
            user: APIUserService,
            version: APIVersionService,
            bonus:APIBonusService,
            balance:APIBalanceService,
            withdraw:APIWithDrawService
        };
    }

    angular
    .module('app')
    .constant('ENUM', {

        // 错误代码
        "ERROR_CODE": {
            "OK": 0, // 正常
            "UNKNOWN_ERROR": 10000, // 内部错误
            "TOKEN_INVALID": 10001, // Token 无效
            "TOKEN_EXPIRED": 10002, // Token 过期
            "SIGN_INVALID": 10003, // Sign 无效
            "SIGN_EXPIRED": 10004, // Sign 过期
        },

        // 排序键
        "SORT_KEY": {
            "DEFAULT": 0, // 默认
            "PRICE": 1, // 价格从低到高
            "POPULAR": 2, // 人气
            "CREDIT": 3, // 信用
            "SALE": 4, // 销量
            "DATE": 5 // 上架时间
        },

        // 排序值
        "SORT_VALUE": {
            "DEFAULT": 0, // 默认排序
            "ASC": 1, // 升序
            "DESC": 2 // 降序
        },

        // 帐号类型
        "SOCIAL_VENDOR": {
            "UNKNOWN": 0, // 未知
            "WEIXIN": 1, // 微信
            "WEIBO": 2, // 微博
            "QQ": 3, // QQ
            "TAOBAO": 4 // 淘宝
        },

        // 卡片组类型
        "CARDGROUP_LAYOUT": {
            // + 规则布局（A类）
            "A1H": "A1H", //通栏（高）
            "A1S": "A1S", //通栏（矮）
            "A2H": "A2H", //垂直二等分（高）
            "A2S": "A2S", //垂直二等分（矮）

            // ＋ 规则布局（A类） 新增卡片组
            "A2XS": "A2XS", // 垂直二等分（更矮）
            "A2XH": "A2XH", // 垂直二等分（更高）
            "A2XXH": "A2XXH", // 垂直二等分（更更高）
            "A3XH": "A3XH", // 垂直三等分（更高）

            "A3H": "A3H", //垂直三等分（高）
            "A3S": "A3S", //垂直三等分（矮）
            "A4H": "A4H", //垂直四等分（高）
            "A4S": "A4S", //垂直四等分（矮）
            "A5H": "A5H", //垂直五等分（高）
            "A5S": "A5S", //垂直五等分（矮）

            // + 不规则布局（B类）
            "B1L": "B1L", //左一右二
            "B1R": "B1R", //左二右一（镜像）
            "B2L": "B2L", //左一右三
            "B2R": "B2R", //左三右一（镜像）
            "B3L": "B3L", //左一右四
            "B3R": "B3R", //左四右一（镜像）

            // ＋ 不规则布局（B类）新增卡片组
            "B4L": "B4L", //左一右一
            "B4R": "B4R", //左一右一（镜像）
            "B5L": "B5L", //左一右二
            "B5R": "B5R", //左二右一（镜像）

            // + 自定义布局（C类）
            "C1H": "C1H", //滚动横幅（高）
            "C1S": "C1S", //滚动横幅（矮）
            "C2": "C2", // 宫格横幅
            "C3": "C3", // 公告横幅
        },

        // 卡片类型
        "CARD_STYLE": {
            "V1T": "V1T", // 垂直样式1（从上到下）
            "V1B": "V1B", // 垂直样式1（从下到上）
            "V2T": "V2T", // 垂直样式2（从上到下）
            "V2B": "V2B", // 垂直样式2（从下到上）
            "H1L": "H1L", // 水平样式1（从左到右）
            "H1R": "H1R", // 水平样式1（从右到左）
            "H2L": "H2L", // 水平样式1（从左到右）
            "H2R": "H2R", // 水平样式1（从右到左）
            "Z1": "Z1", // 其他样式1
            "Z2": "Z2", // 其他样式2（大图）
            "Z3": "Z3", // 其他样式3

            // 新增卡片样式
            "V3T": "V3T", // 垂直样式3（从上到下）
            "V3B": "V3B", // 垂直样式3（从下到上）
            "V4T": "V4T", // 垂直样式4（从上到下）
            "V4B": "V4B", // 垂直样式4（从下到上）
            "Z2P": "Z2P", // 其他样式2 (大图)
            "Z2L": "Z2L", // 其他样式2 (带标签大图)
        },

        // 红包状态
        "CASHGIFT_STATUS": {
            "AVAILABLE": 0, // 未过期
            "EXPIRED": 1, // 过期
            "USED": 2 // 已使用
        },

        // 平台类型，ECN 1.2.3
        "PLATFORM_TYPE": {
            "B2C": 0, // 单店
            "B2B2C": 1 // 多店
        },

        // 平台厂商，ECN 1.2.3
        "PLATFORM_VENDOR": {
            "UNKNOWN": 0, // 未知
            "ECSHOP": 1,
            "ECSTORE": 2,
            "ECMALL": 3,
            "MAGENTO": 4
        },

        // 优惠券状态
        "COUPON_STATUS": {
            "AVAILABLE": 0, // 未过期
            "EXPIRED": 1, // 过期
            "USED": 2 // 已使用
        },

        // 积分类型
        "MESSAGE_TYPE": {
            "SYSTEM"        : 1, // 系统
            "ORDER"         : 2 // 订单
        },

        // 订单状态
        "ORDER_STATUS": {
            "CREATED"       : 0, // 待付款
            "PAID"          : 1, // 待发货
            "DELIVERING"    : 2, // 发货中
            "DELIVERIED"    : 3, // 已收货，待评价
            "FINISHED"      : 4, // 已完成
            "CANCELLED"     : 5  // 已取消
        },

        // 订单评价
        "ORDER_GRADE": {
            "BAD"           : 1, // 差评
            "MEDIUM"        : 2, // 中评
            "GOOD"          : 3  // 好评
        },

        // 促销状态
        "PRODUCT_ACTIVITY_STATUS": {
            "PREPARING"     : 0, // 未开始
            "ONGOING"       : 1, // 已开始
            "FINISHED"      : 2  // 已结束
        },

        // 评价类型
        "REVIEW_GRADE": {
            "ALL"           : 0, // 全部
            "BAD"           : 1, // 差评
            "MEDIUM"        : 2, // 中评
            "GOOD"          : 3  // 好评
        },

        // 积分状态
        "SCORE_STATUS": {
            "INCOME"        : 1, // 收入
            "EXPENDITURE"   : 2  // 支出
        },

        // 关键词
        "KEYWORD_TYPE": {
            "PRODUCT"   : 1,    // 商品
            "SHOP"      : 2     // 店铺
        },

        // 用户性别
        "PROFILE_GENDER": {
            "UNKNOWN"   : 0,    // 保密
            "MALE"      : 1,    // 男
            "FEMALE"    : 2     // 女
        },

        // 用户性别
        "BONUS_STATUS": {
            "WAIT"   : 0,    // 等待处理
            "FINISH"      : 1,    // 已分成
            "CANCEL"    : 2,     // 已取消
            "REVOKE"    : 3     // 已撤销
        },

        "BONUS_TYPE" :
        {
            "SIGNUP" : 0, 	//  注册分成
            "ORDER"  : 1 	//  订单分成
        },

        "WITHDRAW_STATUS" :
        {
            "WAIT" 			: 0, 	//  待处理     待处理的时候  可以做取消操作
            "FINISH"  		: 1, 	//  已完成
            "CANCEL"  		: 2, 	//  已取消	  (目前ecshop后台没有已取消的状态)
            "FAILED"  		: 3 	//  已失败
        },

        "BALANCE_STATUS" :
        {
            "IN"          : 1,         // 收入
            "OUT"  		: 2         // 支出
        }
    })
    .run(['$rootScope', 'ENUM', function($rootScope, ENUM) {
        $rootScope.ENUM = ENUM;
    }]);

    function APIEndpoint($http, $q, $timeout, CacheFactory, name) {
        this.$http = $http;
        this.$q = $q;
        this.$timeout = $timeout;
        this.CacheFactory = CacheFactory;

        this.name = name;
        this.cache = this.CacheFactory.get(this.name);

        if (!this.cache) {
            this.cache = this.CacheFactory.createCache(name, {
                deleteOnExpire: 'aggressive',
                recycleFreq: 60 * 1000,
                maxAge: 10 * 60 * 1000
            });
        }
    }

    APIEndpoint.prototype.readCache = function(key) {
        if (!this.cache)
            return null;

        var cacheKey = key;
        var cacheData = this.cache.get(cacheKey);

        if (GLOBAL_CONFIG.DEBUG) {
            console.log("[Cache] Read '" + this.name + "'");
        }

        return cacheData;
    }

    APIEndpoint.prototype.writeCache = function(key, data) {
        if (!this.cache)
            return;

        var cacheKey = key;
        var cacheData = data;

        if (GLOBAL_CONFIG.DEBUG) {
            console.log("[Cache] Write '" + this.name + "'");
        }

        this.cache.put(cacheKey, cacheData);
    }

    APIEndpoint.prototype.deleteCache = function(key) {
        if (!this.cache)
            return;

        var cacheKey = key;

        if (GLOBAL_CONFIG.DEBUG) {
            console.log("[Cache] Delete '" + this.name + "'");
        }

        this.cache.remove(cacheKey);
    }

    APIEndpoint.prototype.clearCache = function() {
        if (!this.cache)
            return;

        if (GLOBAL_CONFIG.DEBUG) {
            console.log("[Cache] Clear all");
        }

        this.cache.removeAll();
    }

    APIEndpoint.prototype.fetch = function(endpoint, params, useCache, transform) {
        if (!endpoint)
            return;

        var api = this;
        var cacheKey = endpoint;

        if (params) {
            for (var paramKey in params) {
                cacheKey += '|' + paramKey + ':' + params[paramKey];
            }
        }

        if (useCache) {
            var cacheData = this.readCache(cacheKey);
            if (cacheData && cacheData.length) {
                var deferred = this.$q.defer();
                this.$timeout(function() {
                    deferred.resolve(cacheData);
                }, 1);
                return deferred.promise;
            }
        }

        return this.$http.post(endpoint, params).then(function(res) {
            var result = typeof transform === 'function' ? transform(res) : res;
            if (useCache) {
                api.writeCache(cacheKey, result);
            }
            return result;
        });
    }

    global.APIEndpoint = APIEndpoint;

})(this);