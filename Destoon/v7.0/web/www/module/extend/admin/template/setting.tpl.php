<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
$menus = array (
    array('模块设置'),
    array('更新数据', '?moduleid=3&file=html'),
);
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"/>
<input type="hidden" name="setting[oauth]" value="<?php echo $oauth;?>"/>
<input type="hidden" name="setting[weixin]" value="<?php echo $weixin;?>"/>
<div id="Tabs0" style="display:">
<div class="tt">通用设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">列表页地址规则</td>
<td>
<select name="setting[list_url]">
<option value="0"<?php if($list_url == 0) echo ' selected';?>>例 (动态) list.php?catid=1&amp;page=2</option>
<option value="1"<?php if($list_url == 1) echo ' selected';?>>例 (伪静态) list-1-2.html</option> 
<option value="2"<?php if($list_url == 2) echo ' selected';?>>例 (伪静态) list/1/</option>
</select>
</td>
</tr>
<tr>
<td class="tl">内容页地址规则</td>
<td>
<select name="setting[show_url]">
<option value="0"<?php if($show_url == 0) echo ' selected';?>>例 (动态) show.php?itemid=1&amp;page=2</option>
<option value="1"<?php if($show_url == 1) echo ' selected';?>>例 (伪静态) show-1-2.html</option> 
<option value="2"<?php if($show_url == 2) echo ' selected';?>>例 (伪静态) show/1/</option>
</select>
</td>
</tr>
</table>
<a name="mobile"></a>
<div class="tt">手机版设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">手机版功能</td>
<td>
<input type="radio" name="setting[mobile_enable]" value="1"  <?php if($mobile_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[mobile_enable]" value="0"  <?php if(!$mobile_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">手机版绑定域名</td>
<td><input name="setting[mobile_domain]" type="text" size="30" value="<?php echo $mobile_domain;?>"/><?php tips('例如 http://m.destoon.com/<br/>请将此域名绑定至网站mobile目录');?></td>
</tr>
<tr> 
<td class="tl">手机版网站简称</td>
<td><input name="setting[mobile_sitename]" type="text" size="10" value="<?php echo $mobile_sitename;?>"/><?php tips('建议控制在5个汉字以内，留空默认显示网站名称');?></td>
</tr>
<tr> 
<td class="tl">手机版首页幻灯广告位ID</td>
<td><input name="setting[mobile_pid]" type="text" size="5" value="<?php echo $mobile_pid;?>" id="mobile_pid"/> <a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=ad'+(Dd('mobile_pid').value>0 ? '&action=list&pid='+Dd('mobile_pid').value : ''), '幻灯广告');" class="t">[广告管理]</a> <?php tips('请建立一个幻灯广告位，并填写广告位ID，填0表示不显示幻灯广告');?></td>
</tr>
<tr>
<td class="tl">手机访问自动跳转</td>
<td>
<input type="radio" name="setting[mobile_goto]" value="1"  <?php if($mobile_goto) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[mobile_goto]" value="0"  <?php if(!$mobile_goto) echo 'checked';?>/> 关闭
</td>
</tr><tr>
<td class="tl">手机版页面动画效果</td>
<td>
<input type="radio" name="setting[mobile_ajax]" value="1"  <?php if($mobile_ajax) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[mobile_ajax]" value="0"  <?php if(!$mobile_ajax) echo 'checked';?>/> 关闭<?php tips('开启之后页面有类似APP的滚动切换效果，但是会导致百度等第三方联盟广告无法显示');?>
</td>
</tr>
<tr> 
<td class="tl">苹果APP下载地址</td>
<td><input name="setting[mobile_ios]" type="text" size="60" value="<?php echo $mobile_ios;?>"/></td>
</tr>
<tr> 
<td class="tl">安卓APP下载地址</td>
<td><input name="setting[mobile_adr]" type="text" size="60" value="<?php echo $mobile_adr;?>"/></td>
</tr>
</table>
<a name="spread"></a>
<div class="tt">排名推广</div>
<table cellspacing="0" class="tb">
<tr> 
<td class="tl">排名推广绑定域名</td>
<td><input name="setting[spread_domain]"  type="text" size="30" value="<?php echo $spread_domain;?>"/><?php tips('例如 http://spread.destoon.com/<br/>请将此域名绑定至网站spread目录');?></td>
</tr>
<tr> 
<td class="tl">排名默认起价</td>
<td><input name="setting[spread_price]"  type="text" size="5" value="<?php echo $spread_price;?>"/></td>
</tr>
<tr>
<td class="tl">加价幅度</td>
<td><input name="setting[spread_step]"  type="text" size="5" value="<?php echo $spread_step;?>"/><?php tips('如果设置了加价幅度，则出价必须是起价加加价幅度的倍数');?></td>
</tr>
<tr>
<td class="tl">最多可购买月数</td>
<td><input name="setting[spread_month]"  type="text" size="5" value="<?php echo $spread_month;?>"/><?php tips('以月为单位 最少为1个月');?></td>
</tr>
<tr>
<td class="tl">同一月单词最多购买次数</td>
<td><input name="setting[spread_max]"  type="text" size="5" value="<?php echo $spread_max;?>"/></td>
</tr>
<tr>
<td class="tl">购买排名需要审核</td>
<td>
<input type="radio" name="setting[spread_check]" value="1"  <?php if($spread_check) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[spread_check]" value="0"  <?php if(!$spread_check) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">历史排名列表</td>
<td>
<input type="radio" name="setting[spread_list]" value="1"  <?php if($spread_list) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[spread_list]" value="0"  <?php if(!$spread_list) echo 'checked';?>/> 关闭 <?php tips('如果选择关闭，只显示最新的第一页推广记录，并且不显示分页');?>
</td>
</tr>
<tr>
<td class="tl">购买排名使用</td>
<td>
<input type="radio" name="setting[spread_currency]" value="money"  <?php if($spread_currency == 'money') echo 'checked';?>/> <?php echo $DT['money_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[spread_currency]" value="credit"  <?php if($spread_currency == 'credit') echo 'checked';?>/> <?php echo $DT['credit_name'];?>
</td>
</tr>
</table>

<a name="ad"></a>
<div class="tt">广告设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">广告功能</td>
<td>
<input type="radio" name="setting[ad_enable]" value="1"  <?php if($ad_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ad_enable]" value="0"  <?php if(!$ad_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">广告绑定域名</td>
<td><input name="setting[ad_domain]"  type="text" size="30" value="<?php echo $ad_domain;?>"/><?php tips('例如 http://ad.destoon.com/<br/>请将此域名绑定至网站ad目录');?></td>
</tr>
<tr>
<td class="tl">广告位预览</td>
<td>
<input type="radio" name="setting[ad_view]" value="1"  <?php if($ad_view) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ad_view]" value="0"  <?php if(!$ad_view) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">广告在线购买</td>
<td>
<input type="radio" name="setting[ad_buy]" value="1"  <?php if($ad_buy) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ad_buy]" value="0"  <?php if(!$ad_buy) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">购买广告使用</td>
<td>
<input type="radio" name="setting[ad_currency]" value="money"  <?php if($ad_currency == 'money') echo 'checked';?>/> <?php echo $DT['money_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ad_currency]" value="credit"  <?php if($ad_currency == 'credit') echo 'checked';?>/> <?php echo $DT['credit_name'];?>
</td>
</tr>
</table>

<a name="announce"></a>
<div class="tt">公告设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">公告功能</td>
<td>
<input type="radio" name="setting[announce_enable]" value="1"  <?php if($announce_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[announce_enable]" value="0"  <?php if(!$announce_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">公告绑定域名</td>
<td><input name="setting[announce_domain]"  type="text" size="30" value="<?php echo $announce_domain;?>"/><?php tips('例如 http://announce.destoon.com/<br/>请将此域名绑定至网站announce目录');?></td>
</tr>
</table>

<a name="link"></a>
<div class="tt">友情链接</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">友情链接功能</td>
<td>
<input type="radio" name="setting[link_enable]" value="1"  <?php if($link_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_enable]" value="0"  <?php if(!$link_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">友情链接绑定域名</td>
<td><input name="setting[link_domain]"  type="text" size="30" value="<?php echo $link_domain;?>"/><?php tips('例如 http://link.destoon.com/<br/>请将此域名绑定至网站link目录');?></td>
</tr>
<tr>
<td class="tl">友情链接在线申请</td>
<td>
<input type="radio" name="setting[link_reg]" value="1"  <?php if($link_reg) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_reg]" value="0"  <?php if(!$link_reg) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">链接说明</td>
<td><textarea name="setting[link_request]" id="link_request" style="width:500px;height:50px;"><?php echo $link_request;?></textarea><br/>支持HTML语法， 空格 &amp;nbsp; 换行  &lt;br/&gt;
</td> 
</tr>
</table>

<a name="comment"></a>
<div class="tt">评论设置</div>
<table cellspacing="0" class="tb">
<tr> 
<td class="tl">评论绑定域名</td>
<td><input name="setting[comment_domain]"  type="text" size="30" value="<?php echo $comment_domain;?>"/><?php tips('例如 http://comment.destoon.com/<br/>请将此域名绑定至网站comment目录');?></td>
</tr>
<tr>
<td class="tl">允许评论的模块</td>
<td><?php echo module_checkbox('setting[comment_module][]', $comment_module, '1,2,3');?></td>
</tr>
<tr>
<td class="tl">第三方评论系统</td>
<td>
<select name="setting[comment_api]" id="comment_api" onchange="if(this.value){Ds('comment_api_1');Dh('comment_api_0');}else{Dh('comment_api_1');Ds('comment_api_0');}">
<option value=""<?php if($comment_api == '') echo ' selected';?>>不使用</option>
<option value="changyan"<?php if($comment_api == 'changyan') echo ' selected';?>>畅言 - changyan.kuaizhan.com</option>
<option value="duoshuo"<?php if($comment_api == 'duoshuo') echo ' selected';?>>多说 - duoshuo.com</option>
</select>
</td>
</tr>
<tbody id="comment_api_1" style="display:<?php echo $comment_api ? '' : 'none';?>">
<tr>
<td class="tl">APP ID</td>
<td><input name="setting[comment_api_id]"  type="text" size="50" value="<?php echo $comment_api_id;?>"/><?php tips('畅言:填写代码里的appid<br/>多说:填写代码中的short_name');?></td>
</tr>
<tr>
<td class="tl">APP KEY</td>
<td><input name="setting[comment_api_key]"  type="text" size="50" value="<?php echo $comment_api_key;?>"/><?php tips('畅言:填写代码里的conf，prod_开头<br/>多说:留空不填');?></td>
</tr>
</tbody>
<tbody id="comment_api_0" style="display:<?php echo $comment_api ? 'none' : '';?>">
<tr>
<td class="tl">内容页显示评论列表</td>
<td>
<input type="radio" name="setting[comment_show]" value="1"  <?php if($comment_show == 1) echo 'checked';?>> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_show]" value="0"  <?php if($comment_show == 0) echo 'checked';?>> 关闭
</td>
</tr>
<tr>
<td class="tl">允许评论的会员组</td>
<td><?php echo group_checkbox('setting[comment_group][]', $comment_group);?></td>
</tr>
<tr>
<td class="tl">允许支持反对的会员组</td>
<td><?php echo group_checkbox('setting[comment_vote_group][]', $comment_group);?></td>
</tr>
<tr>
<td class="tl">审核评论</td>
<td>
<input type="radio" name="setting[comment_check]" value="2"  <?php if($comment_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_check]" value="1"  <?php if($comment_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_check]" value="0"  <?php if($comment_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布评论启用验证码</td>
<td>
<input type="radio" name="setting[comment_captcha_add]" value="2"  <?php if($comment_captcha_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_captcha_add]" value="1"  <?php if($comment_captcha_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_captcha_add]" value="0"  <?php if($comment_captcha_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">信息发布者删除评论</td>
<td><?php echo module_checkbox('setting[comment_user_del][]', $comment_user_del, '1,2,3');?></td>
</tr>
<tr>
<td class="tl">管理员前台删除评论</td>
<td>
<input type="radio" name="setting[comment_admin_del]" value="1"  <?php if($comment_admin_del == 1) echo 'checked';?>> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_admin_del]" value="0"  <?php if($comment_admin_del == 0) echo 'checked';?>> 关闭
</td>
</tr>
<tr>
<td class="tl">评论支持反对</td>
<td>
<input type="radio" name="setting[comment_vote]" value="1"  <?php if($comment_vote) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_vote]" value="0"  <?php if(!$comment_vote) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">评论内容字数限制</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_min]" value="<?php echo $comment_min;?>"/> 至
<input type="text" size="5" name="setting[comment_max]" value="<?php echo $comment_max;?>"/> 字节
</td>
</tr>
<tr>
<td class="tl">两次评论时间间隔</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_time]" value="<?php echo $comment_time;?>"/> 秒
</td>
</tr>
<tr>
<td class="tl">每页显示评论个数</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_pagesize]" value="<?php echo $comment_pagesize;?>"/> 条
</td>
</tr>
<tr>
<td class="tl">单会员或IP每日限评</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_limit]" value="<?php echo $comment_limit;?>"/> 次
</td>
</tr>
<tr>
<td class="tl">发布评论增加<?php echo $DT['credit_name'];?></td>
<td>&nbsp;
<input type="text" size="5" name="setting[credit_add_comment]" value="<?php echo $credit_add_comment;?>"/>
</td>
</tr>
<tr>
<td class="tl">评论删除扣除<?php echo $DT['credit_name'];?></td>
<td>&nbsp;
<input type="text" size="5" name="setting[credit_del_comment]" value="<?php echo $credit_del_comment;?>"/>
</td>
</tr>
<tr>
<td class="tl">匿名评论昵称</td>
<td>&nbsp;
<input type="text" size="10" name="setting[comment_am]" value="<?php echo $comment_am;?>"/>
</td>
</tr>
</tbody>
</table>
</div>

<a name="guestbook"></a>
<div class="tt">留言设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">留言功能</td>
<td>
<input type="radio" name="setting[guestbook_enable]" value="1"  <?php if($guestbook_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[guestbook_enable]" value="0"  <?php if(!$guestbook_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">留言绑定域名</td>
<td><input name="setting[guestbook_domain]"  type="text" size="30" value="<?php echo $guestbook_domain;?>"/><?php tips('例如 http://guestbook.destoon.com/<br/>请将此域名绑定至网站guestbook目录');?></td>
</tr>
<tr> 
<td class="tl">留言类型</td>
<td><input name="setting[guestbook_type]"  type="text" size="60" value="<?php echo $guestbook_type;?>"/></td>
</tr>
<tr>
<td class="tl">留言验证码</td>
<td>
<input type="radio" name="setting[guestbook_captcha]" value="1"  <?php if($guestbook_captcha) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[guestbook_captcha]" value="0"  <?php if(!$guestbook_captcha) echo 'checked';?>/> 关闭
</td>
</tr>
</table>

<a name="gift"></a>
<div class="tt">积分换礼设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">积分换礼功能</td>
<td>
<input type="radio" name="setting[gift_enable]" value="1"  <?php if($gift_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[gift_enable]" value="0"  <?php if(!$gift_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">两次兑换时间间隔</td>
<td><input type="text" size="5" name="setting[gift_time]" value="<?php echo $gift_time;?>"/> 秒</td>
</tr>
<tr> 
<td class="tl">积分换礼绑定域名</td>
<td><input name="setting[gift_domain]"  type="text" size="30" value="<?php echo $gift_domain;?>"/><?php tips('例如 http://gift.destoon.com/<br/>请将此域名绑定至网站gift目录');?></td>
</tr>
</table>

<a name="vote"></a>
<div class="tt">投票设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">投票功能</td>
<td>
<input type="radio" name="setting[vote_enable]" value="1"  <?php if($vote_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vote_enable]" value="0"  <?php if(!$vote_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">投票绑定域名</td>
<td><input name="setting[vote_domain]"  type="text" size="30" value="<?php echo $vote_domain;?>"/><?php tips('例如 http://vote.destoon.com/<br/>请将此域名绑定至网站vote目录');?></td>
</tr>
</table>

<a name="poll"></a>
<div class="tt">票选设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">票选功能</td>
<td>
<input type="radio" name="setting[poll_enable]" value="1"  <?php if($poll_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[poll_enable]" value="0"  <?php if(!$poll_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">票选绑定域名</td>
<td><input name="setting[poll_domain]"  type="text" size="30" value="<?php echo $poll_domain;?>"/><?php tips('例如 http://poll.destoon.com/<br/>请将此域名绑定至网站poll目录');?></td>
</tr>
</table>

<a name="form"></a>
<div class="tt">表单设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">表单功能</td>
<td>
<input type="radio" name="setting[form_enable]" value="1"  <?php if($form_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[form_enable]" value="0"  <?php if(!$form_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">表单绑定域名</td>
<td><input name="setting[form_domain]"  type="text" size="30" value="<?php echo $form_domain;?>"/><?php tips('例如 http://form.destoon.com/<br/>请将此域名绑定至网站form目录');?></td>
</tr>
</table>

<a name="archiver"></a>
<div class="tt">无图版设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">无图版功能</td>
<td>
<input type="radio" name="setting[archiver_enable]" value="1"  <?php if($archiver_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[archiver_enable]" value="0"  <?php if(!$archiver_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">无图版绑定域名</td>
<td><input name="setting[archiver_domain]"  type="text" size="30" value="<?php echo $archiver_domain;?>"/><?php tips('例如 http://archiver.destoon.com/<br/>请将此域名绑定至网站archiver目录');?></td>
</tr>
</table>

<a name="feed"></a>
<div class="tt">RSS设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">RSS功能</td>
<td>
<input type="radio" name="setting[feed_enable]"  value="2" <?php if($feed_enable==2){ ?>checked <?php } ?>/> 完全开启
<input type="radio" name="setting[feed_enable]"  value="1" <?php if($feed_enable==1){ ?>checked <?php } ?>/> 部分开启
<input type="radio" name="setting[feed_enable]" value="0"  <?php if(!$feed_enable){ ?>checked <?php } ?>/> 关闭<?php tips('选择完全开启将允许用户自定义条件订阅<br/>选择部分开启仅支持按模型订阅');?>
</td>
</tr>
<tr> 
<td class="tl">RSS绑定域名</td>
<td><input name="setting[feed_domain]"  type="text" size="30" value="<?php echo $feed_domain;?>"/><?php tips('例如 http://feed.destoon.com/<br/>请将此域名绑定至网站feed目录');?></td>
</tr>
<tr> 
<td class="tl">RSS输出数量</td>
<td><input name="setting[feed_pagesize]"  type="text" size="10" value="<?php echo $feed_pagesize;?>"/></td>
</tr>
</table>

<a name="sitemaps"></a>
<div class="tt">Sitemaps</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">生成Sitemaps</td>
<td>
<input type="radio" name="setting[sitemaps]" value="1"  <?php if($sitemaps == 1) echo 'checked';?>> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[sitemaps]" value="0"  <?php if($sitemaps == 0) echo 'checked';?>> 关闭
</td>
</tr>
<tr>
<td class="tl">内容页更新频率</td>
<td>
<select name="setting[sitemaps_changefreq]">
<option value="always"<?php echo $sitemaps_changefreq == 'always' ? ' selected' : ''?>>一直</option>
<option value="hourly"<?php echo $sitemaps_changefreq == 'hourly' ? ' selected' : ''?>>时</option>
<option value="daily"<?php echo $sitemaps_changefreq == 'daily' ? ' selected' : ''?>>日</option>
<option value="weekly"<?php echo $sitemaps_changefreq == 'weekly' ? ' selected' : ''?>>周</option>
<option value="monthly"<?php echo $sitemaps_changefreq == 'monthly' ? ' selected' : ''?>>月</option>
<option value="yearly"<?php echo $sitemaps_changefreq == 'yearly' ? ' selected' : ''?>>年</option>
<option value="never"<?php echo $sitemaps_changefreq == 'never' ? ' selected' : ''?>>从不</option>
</select>
&nbsp;
<select name="setting[sitemaps_priority]">
<option value="1.0"<?php echo $sitemaps_priority == '1.0' ? ' selected' : ''?>>1.0</option>
<option value="0.9"<?php echo $sitemaps_priority == '0.9' ? ' selected' : ''?>>0.9</option>
<option value="0.8"<?php echo $sitemaps_priority == '0.8' ? ' selected' : ''?>>0.8</option>
<option value="0.7"<?php echo $sitemaps_priority == '0.7' ? ' selected' : ''?>>0.7</option>
<option value="0.6"<?php echo $sitemaps_priority == '0.6' ? ' selected' : ''?>>0.6</option>
<option value="0.5"<?php echo $sitemaps_priority == '0.5' ? ' selected' : ''?>>0.5</option>
<option value="0.4"<?php echo $sitemaps_priority == '0.4' ? ' selected' : ''?>>0.4</option>
<option value="0.3"<?php echo $sitemaps_priority == '0.3' ? ' selected' : ''?>>0.3</option>
<option value="0.2"<?php echo $sitemaps_priority == '0.2' ? ' selected' : ''?>>0.2</option>
<option value="0.1"<?php echo $sitemaps_priority == '0.1' ? ' selected' : ''?>>0.1</option>
</select>
</td>
</tr>
<tr>
<td class="tl">允许生成的模块</td>
<td><?php echo module_checkbox('setting[sitemaps_module][]', $sitemaps_module, '1,2,3');?></td>
</tr>
<tr>
<td class="tl">更新周期</td>
<td><input type="text" size="5" name="setting[sitemaps_update]" value="<?php echo $sitemaps_update;?>"/> 分钟</td>
</tr>
<tr>
<td class="tl">生成数量</td>
<td><input type="text" size="5" name="setting[sitemaps_items]" value="<?php echo $sitemaps_items;?>"/></td>
</tr>
<tr>
<td class="tl">URL地址</td>
<td>
<a href="<?php echo DT_PATH.'sitemaps.xml';?>" target="_blank"><?php echo DT_PATH.'sitemaps.xml';?></a>
<?php
	$mods = explode(',', $MOD['sitemaps_module']);
	foreach($MODULE as $m) {
		if($m['domain'] && !$m['islink'] && in_array($m['moduleid'], $mods)) {
			if($m['moduleid'] == 4 && $CFG['com_domain']) continue;
			echo '<br/><a href="'.$m['linkurl'].'sitemaps.xml" target="_blank">'.$m['linkurl'].'sitemaps.xml</a>';
		}
	}
?>
</td>
</tr>
<tr>
<td class="tl">上次更新</td>
<td><?php echo timetodate(filemtime(DT_ROOT.'/sitemaps.xml'));?>&nbsp;&nbsp; <a href="?moduleid=<?php echo $moduleid;?>&file=sitemap&action=sitemaps" class="t">立即更新</a></td>
</tr>
<tr>
<td class="tl">详细了解Sitemaps?</td>
<td><a href="<?php echo DT_PATH;?>api/redirect.php?url=http://www.google.com/support/webmasters/bin/topic.py?topic=8476" target="_blank">http://www.google.com/support/webmasters/bin/topic.py?topic=8476</a></td>
</tr>
</table>
</div>

<a name="baidunews"></a>
<div class="tt">百度新闻(Baidu News) - 互联网新闻开放协议</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">生成百度新闻</td>
<td>
<input type="radio" name="setting[baidunews]" value="1"  <?php if($baidunews == 1) echo 'checked';?>> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[baidunews]" value="0"  <?php if($baidunews == 0) echo 'checked';?>> 关闭
</td>
</tr>
<tr>
<td class="tl">负责人员的Email</td>
<td><input type="text" size="30" name="setting[baidunews_email]" value="<?php echo $baidunews_email;?>"/></td>
</tr>
<tr>
<td class="tl">更新周期</td>
<td><input type="text" size="5" name="setting[baidunews_update]" value="<?php echo $baidunews_update;?>"/> 分钟</td>
</tr>
<tr>
<td class="tl">生成数量</td>
<td><input type="text" size="5" name="setting[baidunews_items]" value="<?php echo $baidunews_items;?>"/> 100个之内</td>
</tr>
<tr>
<td class="tl">URL地址</td>
<td><a href="<?php echo DT_PATH.'baidunews.xml';?>" target="_blank"><?php echo DT_PATH.'baidunews.xml';?></a></td>
</tr>
<tr>
<td class="tl">上次更新</td>
<td><?php echo timetodate(filemtime(DT_ROOT.'/baidunews.xml'));?>&nbsp;&nbsp; <a href="?moduleid=<?php echo $moduleid;?>&file=sitemap&action=baidunews" class="t">立即更新</a></td>
</tr>
<tr>
<td class="tl">详细了解百度新闻?</td>
<td><a href="<?php echo DT_PATH;?>api/redirect.php?url=http://news.baidu.com/newsop.html" target="_blank">http://news.baidu.com/newsop.html</a></td>
</tr>
</table>
</div>
<div class="sbt">
<input type="submit" name="submit" value="保 存" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;
</div>
</form>
<script type="text/javascript">
var tab = <?php echo $tab;?>;
var scr = '<?php echo $action;?>'
$(function(){
	if(tab) Tab(tab);
	if(scr) $('html,body').animate({scrollTop:$("[name='"+scr+"']").offset().top}, 500);
});
</script>
<?php include tpl('footer');?>