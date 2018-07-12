<?php

// 格式化内容
function wapFormatContent($content, $url = false, $from_url = '', $urlTxt = false)
{
    $content = htmlspecialchars_decode($content);
    $content = real_strip_tags($content);
    if ($url || true) {
        if ($urlTxt) {
            $content = preg_replace('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/ue',
            "'<a class=\"reallink c_a\" href=\"'.U('w3g/Index/urlalert').'&from_url={$from_url}&url='.urlencode('\\1').'\">访问链接+</a>\\2'", $content);
        } else {
            $content = preg_replace('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/ue',
            "'<a class=\"reallink c_a\" href=\"'.U('w3g/Index/urlalert').'&from_url={$from_url}&url='.urlencode('\\1').'\">\\1</a>\\2'", $content);
        }
    }
    //表情处理
    $content = preg_replace_callback("/(\[.+?\])/is", _w3g_parse_expression, $content);
    $content = preg_replace_callback("/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is", replaceEmot, $content);
    $content = preg_replace_callback("/#([^#]*[^#^\s][^#]*)#/is", wapFormatTopic, $content);
    $content = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u", wapFormatUser, $content);

    return $content;
}

// 格式化评论
function wapFormatComment($content, $url = false, $from_url = '')
{
    $content = real_strip_tags($content);
    if ($url) {
        $content = preg_replace('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/ue',
            "'<a  class=\"c_a\" href=\"'.U('w3g/Index/urlalert').'&from_url={$from_url}&url='.urlencode('\\1').'\">\\1</a>\\2'",
            $content);
    }
    $content = preg_replace_callback("/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is", replaceEmot, $content);
    $content = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u", wapFormatUser, $content);

    return $content;
}

function wapFormatCommentAt($content)
{
    $content = preg_replace('#<a[^>]*>\s*(@.+)\s*</a>#isU', '\\1', $content);
    $content = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u", wapFormatUser, $content);

    return $content;
}

// 话题格式化回调
function wapFormatTopic($data)
{
    return "<a class='c_a' href=".U('w3g/Index/doSearch', array('key' => t($data[1]))).'>'.$data[0].'</a>';
}

// 用户连接格式化回调
function wapFormatUser($name)
{
    $info = D('User', 'home')->getUserByIdentifier($name[1], 'uname');
    if ($info) {
        return "<a class='c_a' href=".U('w3g/Index/profile', array('uid' => $info['uid'])).'>'.$name[0].'</a>';
    } else {
        return "$name[0]";
    }
}

// 短地址
function getContentUrl($url)
{
    return getShortUrl($url[1]).' ';
}

function is_iphone()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_agents = array('iphone', 'ipad', 'ipod');
    $is_iphone = false;
    foreach ($mobile_agents as $device) {
        if (stristr($user_agent, $device)) {
            $is_iphone = true;
            break;
        }
    }

    return $is_iphone;
}

function is_android()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_agents = array('android');
    $is_android = false;
    foreach ($mobile_agents as $device) {
        if (stristr($user_agent, $device)) {
            $is_android = true;
            break;
        }
    }

    return $is_android;
}

/**
 * 表情替换 [格式化分享与格式化评论专用].
 *
 * @param array $data
 */
function _w3g_parse_expression($data)
{
    if (preg_match('/#.+#/i', $data[0])) {
        return $data[0];
    }
    $allexpression = model('Expression')->getAllExpression();
    $info = $allexpression[$data[0]];
    if ($info) {
        return preg_replace("/\[.+?\]/i", "<img src='".__THEME__.'/image/expression/new/'.$info['filename']."' />", $data[0]);
    } else {
        return $data[0];
    }
}

//解析UBB标签 - wap版和web版解析应该不同
function bbcodewap($message)
{
    $msglower = strtolower($message);

    if (strpos($msglower, '[/url]') !== false) {
        $message = preg_replace("/\[url(=((https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|thunder|qqdl|synacast){1}:\/\/|www\.|mailto:)?([^\r\n\[\"']+?))?\](.+?)\[\/url\]/ies", "parseurl('\\1', '\\5', '\\2')", $message);
    }

    if (strpos($msglower, '[/email]') !== false) {
        $message = preg_replace("/\[email(=([a-z0-9\-_.+]+)@([a-z0-9\-_]+[.][a-z0-9\-_.]+))?\](.+?)\[\/email\]/ies", "parseemail('\\1', '\\4')", $message);
    }

    $nest = 0;
    while (strpos($msglower, '[table') !== false && strpos($msglower, '[/table]') !== false) {
        $message = preg_replace("/\[table(?:=(\d{1,4}%?)(?:,([\(\)%,#\w ]+))?)?\]\s*(.+?)\s*\[\/table\]/ies", "parsetable('\\1', '\\2', '\\3')", $message);
        if (++$nest > 4) {
            break;
        }
    }

    $message = str_replace(array(
            '[/color]', '[/backcolor]', '[/size]', '[/font]', '[/align]', '[b]', '[/b]', '[s]', '[/s]', '[hr]', '[/p]',
            '[i=s]', '[i]', '[/i]', '[u]', '[/u]', '[list]', '[list=1]', '[list=a]',
            '[list=A]', "\r\n[*]", '[*]', '[/list]', '[indent]', '[/indent]', '[/float]',
    ), array(
            '</font>', '</font>', '</font>', '</font>', '</div>', '<strong>', '</strong>', '<strike>', '</strike>', '<hr class="l" />', '</p>', '<i class="pstatus">', '<i>',
            '</i>', '<u>', '</u>', '<ul>', '<ul type="1" class="litype_1">', '<ul type="a" class="litype_2">',
            '<ul type="A" class="litype_3">', '<li>', '<li>', '</ul>', '<blockquote>', '</blockquote>', '</span>',
    ), preg_replace(array(
            "/\[color=([#\w]+?)\]/i",
            "/\[color=((rgb|rgba)\([\d\s,]+?\))\]/i",
            "/\[backcolor=([#\w]+?)\]/i",
            "/\[backcolor=((rgb|rgba)\([\d\s,]+?\))\]/i",
            "/\[size=(\d{1,2}?)\]/i",
            "/\[size=(\d{1,2}(\.\d{1,2}+)?(px|pt)+?)\]/i",
            "/\[font=([^\[\<]+?)\]/i",
            "/\[align=(left|center|right)\]/i",
            "/\[p=(\d{1,2}|null), (\d{1,2}|null), (left|center|right)\]/i",
            "/\[float=left\]/i",
            "/\[float=right\]/i",

    ), array(
            '<font color="\\1">',
            '<font style="color:\\1">',
            '<font style="background-color:\\1">',
            '<font style="background-color:\\1">',
            '\\1', //"<font size=\"\\1\">",
            '\\1', //"<font style=\"font-size:\\1\">",
            '\\2', //"<font face=\"\\1\">",
            '<div align="\\1">',
            '<p style="line-height:\\1px;text-indent:\\2em;text-align:\\3">',
            '<span style="float:left;margin-right:5px">',
            '<span style="float:right;margin-left:5px">',
    ), $message));

    $message = preg_replace("/\s?\[postbg\]\s*([^\[\<\r\n;'\"\?\(\)]+?)\s*\[\/postbg\]\s?/is", '', $message);

    if (strpos($msglower, '[/quote]') !== false) {
        $message = preg_replace("/\s?\[quote\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s?/is", '<div class="quote"><blockquote>\\1</blockquote></div>', $message);
    }

    if (strpos($msglower, '[/free]') !== false) {
        $message = preg_replace("/\s*\[free\][\n\r]*(.+?)[\n\r]*\[\/free\]\s*/is", '\\1', $message);
    }

    // if(!defined('IN_MOBILE')) {
    if (strpos($msglower, '[/media]') !== false) {
        $message = preg_replace("/\[media=([\w,]+)\]\s*([^\[\<\r\n]+?)\s*\[\/media\]/ies", $allowmediacode ? "parsemedia('\\1', '\\2')" : "bbcodeurl('\\2', '<a href=\"{url}\" target=\"_blank\">{url}</a>')", $message);
    }
    if (strpos($msglower, '[/audio]') !== false) {
        $message = preg_replace("/\[audio(=1)*\]\s*([^\[\<\r\n]+?)\s*\[\/audio\]/ies", $allowmediacode ? "parseaudio('\\2', 400)" : "bbcodeurl('\\2', '<a href=\"{url}\" target=\"_blank\">{url}</a>')", $message);
    }
    if (strpos($msglower, '[/flash]') !== false) {
        $message = preg_replace("/\[flash(=(\d+),(\d+))?\]\s*([^\[\<\r\n]+?)\s*\[\/flash\]/ies", $allowmediacode ? "parseflash('\\2', '\\3', '\\4');" : "bbcodeurl('\\4', '<a href=\"{url}\" target=\"_blank\">{url}</a>')", $message);
    }

    $message = preg_replace("/\[attach\](.+?)\[\/attach\]/is", '<img src="attach.php?aid=\\1" />', $message);

    $message = str_replace('[img]static/image/common/back.gif[/img]', '', $message);

    $message = str_replace('[img=700,28]static/image/hrline/4.gif[/img]', '', $message);

    $neter_mo = array(
      "/\[img=([0-9]+),([0-9]+)\](.+?)\[\/img\]/is",
      "/\[img\](.+?)\[\/img\]/is",
      "/\[hide\](.+?)\[\/hide\]/is",
    );

    $neter_str = array(
      '<img width="\\1" height="\\2" src="\\3" />',
      '<img src="\\1" />',
      '<div class="hide">\\1</div>',
    );

    $message = preg_replace($neter_mo, $neter_str, $message);
    $message = h($message);
    /* # 表情解析 */
    $message = emoji($message);

    return $message;
}

/**
 * 解析获得表情img.
 *
 * @param string $data 内容数据
 *
 * @return string
 *
 * @author Medz Seven <lovevipdsw@vip.qq.com>
 **/
function emoji($data)
{
    return preg_replace_callback('/\[\\w+?\]/is', function ($emojiName) {
        is_array($emojiName) and $emojiName = array_pop($emojiName);
        $emoji = model('Expression')->getAllExpression();
        $emoji = $emoji[$emojiName];

        return '<img alt="'.$emoji['title'].'" src="'.__THEME__.'/image/expression/'.$emoji['type'].'/'.$emoji['filename'].'">';
    }, $data);
}
