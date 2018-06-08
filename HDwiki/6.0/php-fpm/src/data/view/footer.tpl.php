<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<!--ad start -->

<?php if(isset($advlist[1])&& isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
<div class="ad w-950 clearfix" id="advlist_1">
    <?php echo $advlist[1]['code']?>
</div>
<?php } elseif(isset($advlist[1]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
<div class="ad w-950 clearfix" id="advlist_1">
</div>
<?php } ?>

<!--ad end -->
<footer id="footer" class="footer">
    <div class="wrap clearfix">
        <p id="footer-p" class="copyright"> Copyright @ <a href="http://kaiyuan.hudong.com?hf=hdwiki_copyright_kaiyuan" target="_blank">HDWiKi</a> V <?php echo HDWIKI_VERSION?> &copy;2005-2017 <a href="http://www.baike.com/?hf=hdwiki_copyright_www" target="_blank" class="link_black">baike</a> |
            <a
                href="http://kaiyuan.hudong.com/sq/site_authorize.php?siteurl=<?php echo WIKI_URL?>">HDwiki Licensed</a> <br/>
                <label>Processed in <?php echo $runtime?> second(s), <?php echo $querynum?> queries.</label>
                <?php if(!empty($setting['statcode'])) { ?>
                <label>
<?php echo $setting['statcode']?>
</label>
                <?php } ?>
        </p>
        <p class="currentinfo"> 当前时区GMT
            <?php if(($setting['time_offset']>0)) { ?>+
            <?php } ?><?php echo $setting['time_offset']?> 现在时间是 <?php echo $timenow?> <a href="http://www.miibeian.gov.cn/" target="_blank" class="link_black"><?php echo $setting['site_icp']?> </a></p>

        <ul class="r footernav" id="nav_bot">
            <?php if(!empty($channellist[3])) { ?>
            <?php foreach((array)$channellist[3] as $channel) {?>
            <li><a href="<?php echo $channel['url']?>"><?php echo $channel['name']?></a></li>
            <?php } ?>
            <?php } ?>

            <li><a href="index.php?user-clearcookies">清除Cookies</a></li>
            <li><a href="index.php?doc-innerlink-<?php echo urlencode('联系我们')?>">联系我们</a></li>
        </ul>
    </div>
</footer>
<?php include $this->gettpl('adv');?>
<?php if($adminlogin) { ?>
<script type="text/javascript" src="js/api.js"></script>
<?php } ?>
<?php if($wk_count) { ?>
<script src="http://kaiyuan.hudong.com/count2/count.php?m=count&a=count&key=<?php echo $wk_count['key']?>&domain=<?php echo $wk_count['domain']?>" language="JavaScript"></script>
<?php } ?>
</body>

</html>