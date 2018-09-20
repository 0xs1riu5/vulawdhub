<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 陈一枭
 * 创建日期: 5/11/14
 * 创建时间: 9:44 PM
 * 版权所有 嘉兴想天信息科技有限公司(www.ourstu.com)
 */

namespace Common\Model;


/**内容处理模型，专门用于预处理各类文本
 * Class ContentHandlerModel
 * @package Common\Model
 * @auth 陈一枭
 */
class ContentHandlerModel
{

    /**处理@
     * @auth 陈一枭
     */
    public function handleAtWho($content, $url = '', $args = array(), $app_name = '', $escap_first = false)
    {
        $uids = get_at_uids($content);

        $uids = array_unique($uids);
        $sender = query_user(array('nickname'));
        $first = true;
        foreach ($uids as $uid) {
            if ($escap_first && $first) {
                $first = false;
                continue;
            }
            //$user = UCenterMember()->find($uid);
            $title = $sender['nickname'] . '@了您';
            $message = L('_COMMENTS_WITH_COLON_') . mb_substr(op_t($content), 0, 50, 'utf-8');
            if ($url == '') { //如果未设置来源的url，则自动跳转到来源页面
                $url = $_SERVER['HTTP_REFERER'];
            }

            D('Common/Message')->sendMessage($uid, $title, $message, $url, $args);
        }
    }

    /**在编辑的时候过滤内容
     * @param $content
     * @return mixed
     */
    public function filterHtmlContent($content)
    {
        $content = html($content);
        $content = $this->beforeSave($content);
        $content = filter_base64($content);
        //检测图片src是否为图片并进行过滤
        $content = filter_image($content);
        hook('filterHtmlContent', array('content' => &$content));
        return $content;
    }

    /**显示html内容,一般用于显示编辑器内容，会加入弹窗和at效果
     * @param $content
     */
    public function displayHtmlContent($content)
    {
        $content = parse_popup($content);
        $content = parse_at_users($content);
        $content=sensitive_text($content);
        return $content;

    }

    /**限制图片数量
     * @param $content
     * @param int $count
     * @return mixed
     */
    public function limitPicture($content, $count = 10)
    {

        //默认最多显示10张图片
        $maxImageCount = $count;
        //正则表达式配置
        $beginMark = 'BEGIN0000hfuidafoidsjfiadosj';
        $endMark = 'END0000fjidoajfdsiofjdiofjasid';
        $imageRegex = '/<img(.*?)\\>/i';
        $reverseRegex = "/{$beginMark}(.*?){$endMark}/i";
        //如果图片数量不够多，那就不用额外处理了。
        $imageCount = preg_match_all($imageRegex, $content, $res);
        if ($imageCount <= $maxImageCount) {
            return $content;
        }
        //清除伪造图片
        $content = preg_replace($reverseRegex, "<img$1>", $content);
        //临时替换图片来保留前$maxImageCount张图片
        $content = preg_replace($imageRegex, "{$beginMark}$1{$endMark}", $content, $maxImageCount);
        //替换多余的图片
        $content = preg_replace($imageRegex, "[图片]", $content);
        //将替换的东西替换回来
        $content = preg_replace($reverseRegex, "<img$1>", $content);
        //返回结果
        return $content;
    }


    public function beforeSave($content)
    {
        $content = preg_replace('/\<embed[^>]*?src=\"[^>]*?\.swf[^>]*?\>/', '', $content);
        return $content;
    }


    public function renderVideo($content)
    {

        require_once('./ThinkPHP/Library/Vendor/Collection/phpQuery.php');
        \phpQuery::newDocument($content);
        foreach (pq("embed") as $embed) {
            $link = pq($embed)->attr('src');
            $rs = $this->getVideoInfo($link);
            // 替换视频地址
            if ($rs['flash_url']) {
                $content = str_replace($link, $rs['flash_url'], $content);
            } else {
                $link = addcslashes(quotemeta($link), '/');
                $content = preg_replace('/' . $link . '/', '', $content);
            }
        }

        return $content;
    }


    public function getVideoInfo($link)
    {
        $return = S('video_info_' . md5($link));
        if (empty($return)) {
            require_once('./ThinkPHP/Library/Vendor/Collection/phpQuery.php');
            preg_match("/(youku.com|ku6.com|sohu.com|sina.com.cn|qq.com|tudou.com|yinyuetai.com|iqiyi.com|bilibili.com)/i", $link, $hosts);
            $host = $hosts[1];
            $content = get_content_by_url($link);
            if ('youku.com' == $host) {
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                $flash_url = pq("#link2")->attr('value');

                // 获取缩略图
                preg_match("/sid\/(.*?)\//", $flash_url, $id);
                // $json = get_content_by_url('http://v.youku.com/player/getPlayList/VideoIDS/' . $id[1]);
                $json = get_content_by_url('http://play.youku.com/play/get.json?vid=' . $id[1] . '&ct=10&ran=1951');
                $json = json_decode($json, true);
                $img_url = $json['data']['video']['logo'];

            } elseif ('ku6.com' == $host) {
                \phpQuery::$defaultCharset = GBK;
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                $flash_url = pq(".ckl_input")->eq(0)->attr('value');
                $title = iconv("GBK", "UTF-8", $title);
                // 获取缩略图
                preg_match("/show\/(.*?).html/", $link, $id);
                $json = get_content_by_url('http://v.ku6.com/fetch.htm?t=getVideo4Player&vid=' . $id[1]);
                $json = json_decode($json, true);
                $img_url = $json['data']['bigpicpath'];

            } elseif ('tudou.com' == $host) {
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                preg_match('/iid:(.*?)\s+,icode/s', $content, $program);
                $programId = intval($program[1]);
                if (strpos($link, 'www.tudou.com/albumplay') !== false) {
                    preg_match("/albumplay\/([\w\-\.]+)[\/|\.]/", $link, $album);
                    $albumId = $album[1];
                    $flash_url = 'http://www.tudou.com/a/' . $albumId . '/&iid=' . $programId . '/v.swf';
                } elseif (strpos($link, 'www.tudou.com/programs') !== false) {
                    $flash_url = 'http://www.tudou.com/v/' . $programId . '/v.swf';
                } elseif (strpos($link, 'www.tudou.com/listplay') !== false) {
                    preg_match("/listplay\/([\w\-\.]+)\//", $link, $list);
                    $listId = $list[1];
                    $flash_url = 'http://www.tudou.com/l/' . $listId . '/&iid=' . $programId . '/v.swf';
                }
                //获取缩略图
                $json = get_content_by_url('http://api.tudou.com/v6/video/info?app_key=myKey&format=json&itemCodes=' . $programId);
                $json = json_decode($json, true);
                $img_url = $json['results'][0]['bigPicUrl'];

            } elseif ('sohu.com' == $host) {
                \phpQuery::$defaultCharset = GBK;
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                $title = iconv("GBK", "UTF-8", $title);
                $flash_url = pq("[property='og:videosrc']")->attr('content');
                // 获取缩略图
                preg_match("/com\/(.*?)\/v.swf/", $flash_url, $id);
                $json = get_content_by_url('http://hot.vrs.sohu.com/vrs_flash.action?vid=' . $id[1]);
                $json = json_decode($json, true);
                $img_url = $json['data']['coverImg'];

            } elseif ('qq.com' == $host) {

                $contentType = 'text/html;charset=gbk';
                \phpQuery::newDocument($content, $contentType);
                preg_match("/<title>(.*)<\/title>/", $content,$title);
                preg_match("/<link rel=\"canonical\"(.*?)[^>]*>/i", $content, $data);
                $str = substr($data[0], 0 ,-2);
                $strVid = basename($str);
                $vid = explode('.', $strVid);

                $flash_url = 'http://static.video.qq.com/TPout.swf?vid=' . $vid[0] . '&auto=0';
                $title = $title[1];

                // 获取缩略图
                $img_url = 'http://vpic.video.qq.com/d/' . $vid[1] . '_ori_1.jpg';


            } elseif ('sina.com.cn' == $host) {
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                preg_match("/swfOutsideUrl:\'(.+?)\'/i", $content, $flashvar);
                $flash_url = $flashvar[1];
                //获取缩略图
                preg_match("/pic[\s]*:[\s]*[\"|\']?[\s]*([^'|\"]+)?/", $content, $mch1);
                $img_url = $mch1[1];

            } elseif ('yinyuetai.com' == $host) {
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                $flash_url = pq("[property='og:videosrc']")->attr('content');
                //获取缩略图
                $img_url = pq("[property='og:image']")->attr('content');


            } elseif ('iqiyi.com' == $host) {
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                $obj = pq("#videoArea")->find('div')->eq(0);
                $temp1 = $obj->attr('data-player-videoid');
                preg_match("/iqiyi.com\/(.*).html/i", $link, $temp2);
                $temp2 = $temp2[1];
                $temp3 = $obj->attr('data-player-albumid');
                $temp4 = $obj->attr('data-player-tvid');
                $flash_url = 'http://player.video.qiyi.com/' . $temp1 . '/0/0/' . $temp2 . '.swf-albumId=' . $temp3 . '-tvId=' . $temp4;

                //获取缩略图
                //$img_url = pq("[itemprop='thumbnailUrl']")->attr('content') ;
                //  $img_url = pq("[itemprop='image']")->attr('content') ;
                // 奇艺网有跨站过滤，使用默认图片
                $img_url = get_pic_src('/Public/images/iqiyi.jpg');


            } elseif ('bilibili.com' == $host) {
                $content = $this->gzdecode($content);
                \phpQuery::newDocument($content);
                $title = pq("title")->html();
                //获取缩略图
                $img_url = pq("[class='cover_image']")->attr('src');
                //获取视频地址
                $url_js = pq('#bofqi')->find('script')->eq(0)->html();
                preg_match("/(cid=\w*)/", $url_js, $url_cid);
                preg_match("/aid=\w*/", $url_js, $url_aid);
                $url_cid = substr($url_cid[0], strpos($url_cid[0], "=") + 1);
                $url_aid = substr($url_aid[0], strpos($url_aid[0], "=") + 1);
                $flash_url = "http://static.hdslb.com/play.swf" . "?cid=" . $url_cid . "&aid=" . $url_aid;
                //下载视频
//                $link_bao = explode('bilibili.com',$link);
//                $link_bi = $link_bao[0]."ibilibili.com".$link_bao[1];
//                $content_bi = get_content_by_url($link_bi);
//                \phpQuery::newDocument($content_bi);
//                $obj_bi = pq("#firstLi")->find('a')->eq(3);
//                $id_bi = $obj_bi->attr('onclick');
//                preg_match("/[0-9]*/", $id_bi, $cid_bi);


            }
            $return['title'] = text($title);
            $return['flash_url'] = urldecode($flash_url);
            $return['img_url'] = urldecode($img_url);
            S('video_info_' . md5($link), $return, 60 * 60);
        }
        return $return;
    }

    protected function gzdecode($data)
    {
        $flags = ord(substr($data, 3, 1));
        $headerlen = 10;
        $extralen = 0;
        $filenamelen = 0;
        if ($flags & 4) {
            $extralen = unpack('v', substr($data, 10, 2));
            $extralen = $extralen [1];
            $headerlen += 2 + $extralen;
        }
        if ($flags & 8) // Filename
            $headerlen = strpos($data, chr(0), $headerlen) + 1;
        if ($flags & 16) // Comment
            $headerlen = strpos($data, chr(0), $headerlen) + 1;
        if ($flags & 2) // CRC at end of file
            $headerlen += 2;
        $unpacked = @gzinflate(substr($data, $headerlen));
        if ($unpacked === FALSE)
            $unpacked = $data;
        return $unpacked;
    }


}