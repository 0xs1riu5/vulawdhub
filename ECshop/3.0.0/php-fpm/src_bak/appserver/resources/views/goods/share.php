<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>ECMobile</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="/css/normalize.css">
        <link rel="stylesheet" href="/css/main.css">
        <script src="/js/vendor/modernizr-2.8.3.min.js"></script>
        <script src="/js/vendor/jquery-1.11.3.min.js"></script>
    </head>
    <body>

        <div class="section-app">
            <a href="#" onclick="javascript:tipsClose();">
                <img class="app-close" src="/img/close.png"></img>
            </a>
            <div class="app-icon-border">
                <img class="app-icon" src="/img/logo.png"></img>
            </div>
            <div class="app-intro">
                首次下载ECMobile客户端<br/>
                更多优惠精彩等着你～
            </div>
            <a href="#">
                <div class="app-download">
                    去下载
                </div>
            </a>
        </div>

        <div class="section-info">
            <div class="info-title">
                产品详情
            </div>
            <div class="info-preview">
            <?php if (isset($goods['photos'][0]['large'])): ?>
                <img class="info-photo" src="<?=$goods['photos'][0]['large']?>"></img>
            <?php else: ?>
                <img class="info-photo" src="/img/commodity.png"></img>
            <?php endif ?>
                
                <div class="info-price">
                    <span class="info-money">￥<?=$goods['current_price']?></span>
                    <!-- <span class="info-discount">2.8折</span> -->
                </div>
            </div>
            <div class="info-desc">
                <div class="info-desc-title">
                    <?=$goods['name']?>
                </div>
                <div class="info-desc-attr">
                    <?=$goods['brand']['name']?>
                </div>
            </div>
        </div>

        <div class="section-shop">
            <div class="shop-title">
                店铺信息
            </div>
            <div class="shop-attr">
                <span class="shop-subtitle"><?=$shop['site_info']['name']?></span>
                <span class="shop-rate">
                    <img class="shop-heart" src="/img/heart.png">
                    <img class="shop-heart" src="/img/heart.png">
                    <img class="shop-heart" src="/img/heart.png">
                    <img class="shop-heart" src="/img/heart.png">
                    <img class="shop-heart" src="/img/heart.png">
                </span>
            </div>
            <a href="tel:<?=$shop['site_info']['telephone']?>">
                <div class="shop-contact">
                    <img class="shop-contact-icon" src="/img/service.png"></img> 联系客服
                </div>
            </a>
        </div>

        <div class="section-comment">
            <div class="comment-title">
                产品评价
            </div>
            <?php foreach ($reviews as $value): ?>
                <?php if (isset($value['author']['username'])): ?>
                    <div class="comment-item">
                        <div class="comment-source">
                            <span class="comment-author">
                                <?=$value['author']['username']?>
                            </span>
                            <div class="comment-time">
                                <?=date('Y-m-d H:i:s', $value['created_at'])?>
                            </div>                    
                        </div>
                        <div class="comment-text">
                            <?=$value['content']?>
                        </div>
                        <div class="comment-rate">
                        <?php for ($i=0; $i < $value['grade']; $i++): ?>
                            <img class="comment-star" src="/img/star.png">
                        <?php endfor ?>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>

        </div>

        <div class="section-copyright">
            Copyright (c) 2012-2015 ECNative 版权所有
        </div>

    </body>

    <style>

        body {
            background: rgb(246, 246 ,246);
            overflow-x: hidden;
            width: 100%;
        }

        div {
            box-sizing: border-box;
        }

        a:link {
            text-decoration: none;
        }
         
        a:visited {
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: none;
        }
        
        a:active {
            text-decoration: none;
        }

        .section-app {
            display: block;
            width: 100%;
            height: 44px;
            margin: 0;
            padding: 0;
            background: rgb(71, 71, 71);
            border-top: transparent solid 1px;
        }

        .app-close {
            display: block;
            width: 20px;
            height: 20px;
            margin-top: 10px;
            margin-left: 10px;
            box-sizing: border-box;
            border: transparent; solid 1px;
            border-radius: 15px;
            float: left;
        }

        .app-icon-border {
            display: block;
            width: 32px;
            height: 32px;
            margin-top: 5px;
            margin-left: 15px;
            box-sizing: border-box;
            border: rgb(46, 46, 46) solid 1px;
            border-radius: 10px;            
            background: rgb(46, 46, 46);
            float: left;
        }

        .app-icon {
            display: block;
            width: 20px;
            height: 20px;
            margin-top: 4px;
            margin-left: 4px;
            border: rgb(46, 46, 46) solid 1px;
            border-radius: 6px;            
            background: white;
        }

        .app-intro {
            display: block;
            font-size: 12px;
            font-weight: lighter;
            color: #eee;
            overflow: hidden;
            margin-top: 6px;
            margin-left: 8px;
            margin-right: 8px;
            text-align: left;
            float: left;
        }

        .app-download {
            display: block;
            width: 80px;
            height: 30px;
            line-height: 30px;
            margin-top: 6px;
            margin-right: 10px;
            box-sizing: border-box;
            border: rgb(67, 126, 248) solid 1px;
            border-radius: 6px;
            background: rgb(67, 126, 248);
            text-align: center;
            font-size: 14px;
            font-weight: lighter;
            color: #eee;
            float: right;
        }

        .section-info {
            display: block;
            width: 100%;
            margin: 0;
            padding: 0;
            background: white;
            border-top: rgb(233, 233, 233) solid 1px;
            border-bottom: rgb(233, 233, 233) solid 1px;
        }

        .info-title {
            display: block;
            width: 100%;
            height: 44px;
            line-height: 42px;
            margin: 0;
            padding: 0;
            background: white;
            border-bottom: rgb(233, 233, 233) solid 1px;
            text-align: center;
            font-size: 16px;
            font-weight: lighter;
            color: #333;
        }

        .info-preview {
            display: block;
            position: relative;
            width: 100%;
            height: auto;
            margin: 0;
            padding: 10px;
            background: white;
            box-sizing: border-box;
            text-align: center;
            font-size: 16px;
            font-weight: lighter;
            color: #333;            
        }

        .info-photo {
            display: block;
            width: 100%;
            max-width: 100%;
            min-height: 100px;
            height: auto;
            margin: 0;
            padding: 0;
        }

        .info-price {
            display: block;
            position: absolute;
            width: 100%;
            left: 0px;
            top: auto;
            bottom: 0px;
            margin: 0;
            padding: 4px;
            background-color: rgba(233, 233, 233, 0.7);
            text-align: left;
        }

        .info-money {
            display: block;
            float: left;
            font-size: 18px;
            font-weight: bold;
            color: rgb(231, 27, 28);
            margin-left: 4px;
        }

        .info-discount {
            display: block;
            float: left;
            font-size: 10px;
            font-weight: lighter;
            line-height: 10px;
            margin-top: 3px;
            margin-left: 8px;
            padding: 2px;
            color: white;
            background: rgb(231, 27, 28);
            border-radius: 2px;
        }

        .info-desc {
            display: block;
            width: 100%;
            margin: 0;
            padding: 5px 10px;
            text-align: left;
            font-size: 15px;
            font-weight: lighter;
            color: #666;
        }

        .section-shop {
            display: block;
            width: 100%;
            margin: 0;
            margin-top: 10px;
            padding: 0;
            background: white;
            border-top: rgb(233, 233, 233) solid 1px;
            border-bottom: rgb(233, 233, 233) solid 1px;
        }

        .shop-title {
            display: block;
            margin: 0 0 0 10px;
            padding: 6px 0;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
            color: #666;         
        }

        .shop-attr {
            display: block;
            margin: 0 0 0 10px;
            padding: 0 0 6px 0;
            text-align: left;
            font-size: 15px;
            font-weight: lighter;
            color: #888;                     
        }

        .shop-rate {
            margin-left: 4px;
            text-align: left;
            font-size: 14px;
            font-weight: lighter;
            color: rgb(231, 27, 28);
        }

        .shop-heart {
            display: inline-block;
            width: 16px;
            max-width: 100%;
            height: auto;
        }

        .shop-contact {
            display: block;
            width: 100%;
            margin: 0;
            padding: 10px 0;
            border-top: rgb(233, 233, 233) solid 1px;
            text-align: center;
            font-size: 16px;
            font-weight: lighter;
            color: #666; 
            text-decoration: none;
        }

        .shop-contact-icon {
            display: inline-block;
            width: 19px;
            max-width: 100%;
            height: auto;
            margin: 0 10px;
            font-size: 14px;
            font-weight: lighter;
            color: rgb(231, 27, 28);
        }

        .section-comment {
            display: block;
            width: 100%;
            margin: 0;
            margin-top: 10px;
            padding: 0;
            background: white;
            border-top: rgb(233, 233, 233) solid 1px;
            border-bottom: rgb(233, 233, 233) solid 1px;
        }

        .comment-title {
            display: block;
            margin: 0 0 0 10px;
            padding: 6px 0;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
            color: #666;         
        }

        .comment-item {
            display: block;
            margin: 0 0 10px 0;
            padding: 0px 10px;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
            color: #666;                     
        }

        .comment-source {
            display: block;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .comment-author {
            font-size: 12px;
            font-weight: lighter;
            color: #ccc;                                 
        }

        .comment-time {
            display: block;
            float: right;
            margin: 0;
            padding: 0;
            font-size: 12px;
            font-weight: lighter;
            color: #ccc;                                 
        }

        .comment-text {
            display: block;
            margin: 5px 0 0 0;
            padding: 0;
            font-size: 14px;
            font-weight: normal;
            line-height: 14px;
            color: #999;
        }

        .comment-rate {
            display: block;
            margin: 5px 0 0 0;
            padding: 0;
            font-size: 14px;
            font-weight: normal;
            line-height: 14px;
            color: rgb(233, 170, 0);            
        }

        .comment-star {
            display: inline-block;
            width: 14px;
            max-width: 100%;
            height: auto;
        }

        .section-copyright {
            display: block;
            width: 100%;
            height: 44px;
            line-height: 42px;
            margin: 0;
            margin-top: 10px;
            padding: 0;
            background: white;
            border-top: rgb(233, 233, 233) solid 1px;
            border-bottom: rgb(233, 233, 233) solid 1px;
            text-align: center;
            font-size: 14px;
            font-weight: lighter;
            color: #999;
        }

    </style>

    <script type="text/javascript">

        $(document).ready( function(){

            $(".info-photo").each( function(){
                $(this).css( 'height', $(this).innerWidth() );
            });
        })

        function tipsClose() {
            $(".section-app").hide();
        }

    </script>

</html>
