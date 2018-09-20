<?php
namespace Common\Widget;

use Think\Controller;

class UeditorWidget extends Controller
{

    public function editor($id = 'myeditor', $name = 'content',$default='',$width='100%',$height='200px',$config='',$style='',$param='')
    {

        $this->assign('id',$id);
        $this->assign('name',$name);
        $this->assign('default',$default);
        $this->assign('width',$width);
        $this->assign('height',$height);
        $this->assign('style',$style);
        if($config=='')
        {
            $config="toolbars:[['source','|','bold','italic','underline','fontsize','forecolor','fontfamily','backcolor','|','insertimage','insertcode','link','emotion','scrawl','wordimage']]";
        }
        if($config == 'all'){
            $config='';
        }
        empty($param['zIndex']) && $param['zIndex'] = 977;
        $config.=(empty($config)?'':',').'zIndex:'.$param['zIndex'];
        is_bool(strpos($width,'%')) && $config.=',initialFrameWidth:'.str_replace('px','',$width);
        is_bool(strpos($height,'%')) && $config.=',initialFrameHeight:'.str_replace('px','',$height);
        $config.=',autoHeightEnabled: false';
        $this->assign('config',$config);
        $this->assign('param',$param);
        cookie('video_get_info',U('Core/Public/getVideo'));

        $this->display(T('Application://Common@Widget/ueditor'));
    }

}
