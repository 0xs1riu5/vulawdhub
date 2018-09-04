<?php
//
use App\Helper\Token;

$app->get('/', function () use ($app) {
    return 'Hi';
});

//Other
$app->group(['namespace' => 'App\Http\Controllers\v2', 'prefix' => 'v2'], function($app)
{

    $app->get('article.{id:[0-9]+}', 'ArticleController@show');

    $app->get('notice.{id:[0-9]+}', 'NoticeController@show');

    $app->post('order.notify.{code}', 'OrderController@notify');

    $app->get('product.intro.{id:[0-9]+}', 'GoodsController@intro');
    
    $app->get('product.share.{id:[0-9]+}', 'GoodsController@share');

    $app->get('ecapi.auth.web', 'UserController@webOauth');

    $app->get('ecapi.auth.web.callback/{vendor:[0-9]+}', 'UserController@webCallback');

});

//Guest
$app->group(['namespace' => 'App\Http\Controllers\v2','prefix' => 'v2', 'middleware' => ['xss']], function($app)
{
    $app->post('ecapi.access.dns', 'AccessController@dns');
    
    $app->post('ecapi.access.batch', 'AccessController@batch');

    $app->post('ecapi.category.list', 'GoodsController@category');

    $app->post('ecapi.product.list', 'GoodsController@index');
    
    $app->post('ecapi.home.product.list', 'GoodsController@home');

    $app->post('ecapi.search.product.list', 'GoodsController@search');

    $app->post('ecapi.review.product.list', 'GoodsController@review');

    $app->post('ecapi.review.product.subtotal', 'GoodsController@subtotal');

    $app->post('ecapi.recommend.product.list', 'GoodsController@recommendList');

    $app->post('ecapi.product.accessory.list', 'GoodsController@accessoryList');

    $app->post('ecapi.product.get', 'GoodsController@info');

    $app->post('ecapi.auth.signin', 'UserController@signin');

    $app->post('ecapi.auth.social', 'UserController@auth');

    $app->post('ecapi.auth.default.signup', 'UserController@signupByEmail');

    $app->post('ecapi.auth.mobile.signup', 'UserController@signupByMobile');

    $app->post('ecapi.user.profile.fields', 'UserController@fields');

    $app->post('ecapi.auth.mobile.verify', 'UserController@verifyMobile');

    $app->post('ecapi.auth.mobile.send', 'UserController@sendCode');

    $app->post('ecapi.auth.mobile.reset', 'UserController@resetPasswordByMobile');

    $app->post('ecapi.auth.default.reset', 'UserController@resetPasswordByEmail');

    $app->post('ecapi.cardpage.get', 'CardPageController@view');

    $app->post('ecapi.cardpage.preview', 'CardPageController@preview');

    $app->post('ecapi.config.get', 'ConfigController@index');

    $app->post('ecapi.article.list', 'ArticleController@index');

    $app->post('ecapi.brand.list', 'BrandController@index');

    $app->post('ecapi.search.keyword.list', 'SearchController@index');

    $app->post('ecapi.region.list', 'RegionController@index');

    $app->post('ecapi.invoice.type.list', 'InvoiceController@type');

    $app->post('ecapi.invoice.content.list', 'InvoiceController@content');

    $app->post('ecapi.invoice.status.get', 'InvoiceController@status');

    $app->post('ecapi.notice.list', 'NoticeController@index');

    $app->post('ecapi.banner.list', 'BannerController@index');

    $app->post('ecapi.version.check', 'VersionController@check');

    $app->post('ecapi.recommend.brand.list', 'BrandController@recommend');

    $app->post('ecapi.message.system.list', 'MessageController@system');

    $app->post('ecapi.message.count', 'MessageController@unread');

    $app->post('ecapi.site.get', 'SiteController@index');

    $app->post('ecapi.splash.list', 'SplashController@index');

    $app->post('ecapi.splash.preview', 'SplashController@view');

    $app->post('ecapi.theme.list', 'ThemeController@index');

    $app->post('ecapi.theme.preview', 'ThemeController@view');

    $app->post('ecapi.search.category.list', 'GoodsController@categorySearch');

    $app->post('ecapi.order.reason.list', 'OrderController@reasonList');

    $app->post('ecapi.search.shop.list', 'ShopController@search');

    $app->post('ecapi.recommend.shop.list', 'ShopController@recommand');

    $app->post('ecapi.shop.list', 'ShopController@index');

    $app->post('ecapi.shop.get', 'ShopController@info');

    $app->post('ecapi.areacode.list', 'AreaCodeController@index');


});

//Authorization
$app->group(['prefix' => 'v2', 'namespace' => 'App\Http\Controllers\v2', 'middleware' => ['token', 'xss']], function($app)
{
    $app->post('ecapi.user.profile.get', 'UserController@profile');

    $app->post('ecapi.user.profile.update', 'UserController@updateProfile');

    $app->post('ecapi.user.password.update', 'UserController@updatePassword');

    $app->post('ecapi.order.list', 'OrderController@index');

    $app->post('ecapi.order.get', 'OrderController@view');

    $app->post('ecapi.order.confirm', 'OrderController@confirm');

    $app->post('ecapi.order.cancel', 'OrderController@cancel');

    $app->post('ecapi.order.price', 'OrderController@price');

    $app->post('ecapi.product.like', 'GoodsController@setLike');

    $app->post('ecapi.product.unlike', 'GoodsController@setUnlike');

    $app->post('ecapi.product.liked.list', 'GoodsController@likedList');

    $app->post('ecapi.order.review', 'OrderController@review');

    $app->post('ecapi.order.subtotal', 'OrderController@subtotal');

    $app->post('ecapi.payment.types.list', 'OrderController@paymentList');

    $app->post('ecapi.payment.pay', 'OrderController@pay');

    $app->post('ecapi.shipping.vendor.list', 'ShippingController@index');

    $app->post('ecapi.shipping.status.get', 'ShippingController@info');

    $app->post('ecapi.consignee.list', 'ConsigneeController@index');

    $app->post('ecapi.consignee.update', 'ConsigneeController@modify');

    $app->post('ecapi.consignee.add', 'ConsigneeController@add');

    $app->post('ecapi.consignee.delete', 'ConsigneeController@remove');

    $app->post('ecapi.consignee.setDefault', 'ConsigneeController@setDefault');

    $app->post('ecapi.score.get', 'ScoreController@view');

    $app->post('ecapi.score.history.list', 'ScoreController@history');

    $app->post('ecapi.cashgift.list', 'CashGiftController@index');

    $app->post('ecapi.cashgift.available', 'CashGiftController@available');

    $app->post('ecapi.push.update', 'MessageController@updateDeviceId');
    //cart
    $app->post('ecapi.cart.add', 'CartController@add');

    $app->post('ecapi.cart.clear', 'CartController@clear');

    $app->post('ecapi.cart.delete', 'CartController@delete');

    $app->post('ecapi.cart.get', 'CartController@index');

    $app->post('ecapi.cart.update', 'CartController@update');

    $app->post('ecapi.cart.checkout', 'CartController@checkout');

    $app->post('ecapi.cart.promos', 'CartController@promos');

    $app->post('ecapi.product.purchase', 'GoodsController@purchase');

    $app->post('ecapi.product.validate', 'GoodsController@checkProduct');

    $app->post('ecapi.message.order.list', 'MessageController@order');

    $app->post('ecapi.shop.watch', 'ShopController@watch');

    $app->post('ecapi.shop.unwatch', 'ShopController@unwatch');

    $app->post('ecapi.shop.watching.list', 'ShopController@watchingList');

    $app->post('ecapi.coupon.list', 'CouponController@index');

    $app->post('ecapi.coupon.available', 'CouponController@available');

    $app->post('ecapi.recommend.bonus.list', 'AffiliateController@index');
    $app->post('ecapi.recommend.bonus.info', 'AffiliateController@info');

    $app->post('ecapi.withdraw.submit', 'AccountController@submit');
    $app->post('ecapi.withdraw.cancel', 'AccountController@cancel');
    $app->post('ecapi.withdraw.list', 'AccountController@index');
    $app->post('ecapi.withdraw.info', 'AccountController@getDetail');

    $app->post('ecapi.balance.get', 'AccountController@surplus');
    $app->post('ecapi.balance.list', 'AccountController@accountDetail');
});
