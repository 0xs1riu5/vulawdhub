<?php
require '../../common.inc.php';
$_groupid == 1 or exit('Access Denied');
require DT_ROOT.'/api/weixin/init.inc.php';
switch($action) {
	case 'image':
	break;
	case 'voice':
	break;
	case 'video':
	break;
	case 'location':
	break;
	default:
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo DT_CHARSET;?>"/>
<title>Media</title>
</head>
<body style="margin:0;background:#EBF0F6;">
<?php if($action == 'voice') { ?>
<object width="300" height="16" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab">
<param name="src" value="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=<?php echo $access_token;?>&media_id=<?php echo $mediaid;?>">
<param name="controller" value="true">
<param name="type" value="video/quicktime">
<param name="autoplay" value="true">
<param name="bgcolor" value="black">
<param name="pluginspage" value="http://www.apple.com/quicktime/download/index.html">
<embed src="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=<?php echo $access_token;?>&media_id=<?php echo $mediaid;?>" width="300" height="16" controller="true" align="middle" bgcolor="black" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/index.html"></embed>
</object>
<?php } else if($action == 'video') { ?>
<object width="300" height="400" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab">
<param name="src" value="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=<?php echo $access_token;?>&media_id=<?php echo $mediaid;?>">
<param name="controller" value="true">
<param name="type" value="video/quicktime">
<param name="autoplay" value="true">
<param name="bgcolor" value="black">
<param name="pluginspage" value="http://www.apple.com/quicktime/download/index.html">
<embed src="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=<?php echo $access_token;?>&media_id=<?php echo $mediaid;?>" width="300" height="400" controller="true" align="middle" bgcolor="black" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/index.html"></embed>
</object>
<?php } else if($action == 'location') { ?>
<script type="text/javascript" charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script type="text/javascript">
function init(){
    var center=new qq.maps.LatLng(<?php echo $latitude;?>,<?php echo $longitude;?>);
    var map=new qq.maps.Map(document.getElementById("container"),{
        center:center,
        zoom:<?php echo $zoom ? intval($zoom) : 17;?>
    });
    setTimeout(function(){
        var marker=new qq.maps.Marker({
            position:center,
			animation:qq.maps.MarkerAnimation.DROP,
            map:map
        });
    },2000);
}
window.onload=init;
</script>
<div style="width:450px;height:400px" id="container"></div>
<?php } ?>
</body>
</html>