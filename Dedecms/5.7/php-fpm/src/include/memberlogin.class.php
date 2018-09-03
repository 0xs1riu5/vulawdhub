<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 会员登录类
 *
 * @version        $Id: userlogin.class.php 1 15:59 2010年7月5日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

// 使用缓存助手
helper('cache');
/**
 *  检查用户名的合法性
 *
 * @access    public
 * @param     string  $uid  用户UID
 * @param     string  $msgtitle  提示标题
 * @param     string  $ckhas  检查是否存在
 * @return    string
 */
function CheckUserID($uid, $msgtitle='用户名', $ckhas=TRUE)
{
    global $cfg_mb_notallow,$cfg_mb_idmin,$cfg_md_idurl,$cfg_soft_lang,$dsql;
    if($cfg_mb_notallow != '')
    {
        $nas = explode(',', $cfg_mb_notallow);
        if(in_array($uid, $nas))
        {
            return $msgtitle.'为系统禁止的标识！';
        }
    }
    if($cfg_md_idurl=='Y' && preg_match("/[^a-z0-9]/i",$uid))
    {
        return $msgtitle.'必须由英文字母或数字组成！';
    }

    if($cfg_soft_lang=='utf-8')
    {
        $ck_uid = utf82gb($uid);
    }
    else
    {
        $ck_uid = $uid;
    }

    for($i=0; isset($ck_uid[$i]); $i++)
    {
        if(ord($ck_uid[$i]) > 0x80)
        {
            if(isset($ck_uid[$i+1]) && ord($ck_uid[$i+1])>0x40)
            {
                $i++;
            }
            else
            {
                return $msgtitle.'可能含有乱码，建议你改用英文字母和数字组合！';
            }
        }
        else
        {
            if(preg_match("/[^0-9a-z@\.-]/i",$ck_uid[$i]))
            {
                return $msgtitle.'不能含有 [@]、[.]、[-]以外的特殊符号！';
            }
        }
    }
    if($ckhas)
    {
        $row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid LIKE '$uid' ");
        if(is_array($row)) return $msgtitle."已经存在！";
    }
    return 'ok';
}

/**
 *  保存一则消息记录
 *
 * @access    public
 * @param     string  $mid  用户MID
 * @param     string  $mid  用户ID
 * @param     string  $msg  保存消息
 * @return    string
 */
function PutSnsMsg($mid, $userid, $msg)
{
    global $dsql;
    $msg = addslashes($msg);
    $query = "INSERT INTO `#@__member_snsmsg`(`mid`, `userid`, `sendtime`, `msg`) VALUES('$mid', '$userid', '".time()."', '$msg'); ";
    $rs = $dsql->ExecuteNoneQuery($query);
    return $rs;
}

/**
 *  检查用户是否被禁言
 *
 * @return    void
 */
function CheckNotAllow()
{
    global $dsql, $cfg_ml, $cfg_mb_spacesta;
    if(empty($cfg_ml->M_ID)) return ;
    if($cfg_ml->M_Spacesta == -2)
    {
        ShowMsg("你已经被禁言，请与管理员联系！", "-1");
        exit();
    }else if($cfg_ml->M_Spacesta == -10)
    {
        ShowMsg("系统开启了邮件审核机制，因此你的帐号需要审核后才能发信息！", "-1");
        exit();
    }
    else if($cfg_ml->M_Spacesta < 0)
    {
        ShowMsg('系统开启了审核机制，因此你的帐号需要管理员审核后才能发信息！', '-1');
        exit();
    }
}

/**
 * 网站会员登录类
 *
 * @package          MemberLogin
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class MemberLogin
{
    var $M_ID;
    var $M_LoginID;
    var $M_MbType;
    var $M_Money;
    var $M_Scores;
    var $M_UserName;
    var $M_Rank;
     var $M_Face;
    var $M_LoginTime;
    var $M_KeepTime;
    var $M_Spacesta;
    var $fields;
    var $isAdmin;
    var $M_UpTime;
    var $M_ExpTime;
    var $M_HasDay;
    var $M_JoinTime;
    var $M_Honor = '';
    var $memberCache='memberlogin';

    //php5构造函数
    function __construct($kptime = -1, $cache=FALSE)
    {
        global $dsql;
        if($kptime==-1){
            $this->M_KeepTime = 3600 * 24 * 7;
        }else{
            $this->M_KeepTime = $kptime;
        }
        $formcache = FALSE;
        $this->M_ID = $this->GetNum(GetCookie("DedeUserID"));
        $this->M_LoginTime = GetCookie("DedeLoginTime");
        $this->fields = array();
        $this->isAdmin = FALSE;
        if(empty($this->M_ID))
        {
            $this->ResetUser();
        }else{
            $this->M_ID = intval($this->M_ID);
            
            if ($cache)
            {
                $this->fields = GetCache($this->memberCache, $this->M_ID);
                if( empty($this->fields) )
                {
                    $this->fields = $dsql->GetOne("Select * From `#@__member` where mid='{$this->M_ID}' ");
                } else {
                    $formcache = TRUE;
                }
            } else {
                $this->fields = $dsql->GetOne("Select * From `#@__member` where mid='{$this->M_ID}' ");
            }
                
            if(is_array($this->fields)){
                #api{{
                if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
                {
                    if($data = uc_get_user($this->fields['userid']))
                    {
                        if(uc_check_avatar($data[0]) && !strstr($this->fields['face'],UC_API))
                        {
                            $this->fields['face'] = UC_API.'/avatar.php?uid='.$data[0].'&size=middle';
                            $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET `face`='".$this->fields['face']."' WHERE `mid`='{$this->M_ID}'");
                        }
                    }
                }
                #/aip}}
            
                //间隔一小时更新一次用户登录时间
                if(time() - $this->M_LoginTime > 3600)
                {
                    $dsql->ExecuteNoneQuery("update `#@__member` set logintime='".time()."',loginip='".GetIP()."' where mid='".$this->fields['mid']."';");
                    PutCookie("DedeLoginTime",time(),$this->M_KeepTime);
                }
                $this->M_LoginID = $this->fields['userid'];
                $this->M_MbType = $this->fields['mtype'];
                $this->M_Money = $this->fields['money'];
                $this->M_UserName = $this->fields['uname'];
                $this->M_Scores = $this->fields['scores'];
                $this->M_Face = $this->fields['face'];
                $this->M_Rank = $this->fields['rank'];
                $this->M_Spacesta = $this->fields['spacesta'];
                $sql = "Select titles From #@__scores where integral<={$this->fields['scores']} order by integral desc";
                $scrow = $dsql->GetOne($sql);
                $this->fields['honor'] = $scrow['titles'];
                $this->M_Honor = $this->fields['honor'];
                if($this->fields['matt']==10) $this->isAdmin = TRUE;
                $this->M_UpTime = $this->fields['uptime'];
                $this->M_ExpTime = $this->fields['exptime'];
                $this->M_JoinTime = MyDate('Y-m-d',$this->fields['jointime']);
                if($this->M_Rank>10 && $this->M_UpTime>0){
                    $this->M_HasDay = $this->Judgemember();
                }
                if( !$formcache )
                {
                    SetCache($this->memberCache, $this->M_ID, $this->fields, 1800);
                }
            }else{
                $this->ResetUser();
            }
        }
    }

    function MemberLogin($kptime = -1)
    {
        $this->__construct($kptime);
    }
    
    /**
     *  删除缓存,每次登录时和在修改用户资料的地方会清除
     *
     * @access    public
     * @param     string
     * @return    string
     */
    function DelCache($mid)
    {
        DelCache($this->memberCache, $mid);
    }
    
    /**
     *  判断会员是否到期
     *
     * @return    string
     */
    function Judgemember()
    {
        global $dsql,$cfg_mb_rank;
        $nowtime = time();
        $mhasDay = $this->M_ExpTime - ceil(($nowtime - $this->M_UpTime)/3600/24) + 1;
        if($mhasDay <= 0){
           $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET uptime='0',exptime='0',
                                         rank='$cfg_mb_rank' WHERE mid='".$this->fields['mid']."';");
        }
        return $mhasDay;
    }

    /**
     *  退出cookie的会话
     *
     * @return    void
     */
    function ExitCookie()
    {
        $this->ResetUser();
    }

    /**
     *  验证用户是否已经登录
     *
     * @return    bool
     */
    function IsLogin()
    {
        if($this->M_ID > 0) return TRUE;
        else return FALSE;
    }

    /**
     *  检测用户上传空间
     *
     * @return    int
     */
    function GetUserSpace()
    {
        global $dsql;
        $uid = $this->M_ID;
        $row = $dsql->GetOne("SELECT sum(filesize) AS fs FROM `#@__uploads` WHERE mid='$uid'; ");
        return $row['fs'];
    }

    /**
     *  检查用户空间信息
     *
     * @return    void
     */
    function CheckUserSpace()
    {
        global $cfg_mb_max;
        $uid = $this->M_ID;
        $hasuse = $this->GetUserSpace();
        $maxSize = $cfg_mb_max * 1024 * 1024;
        if($hasuse >= $maxSize)
        {
            ShowMsg('你的空间已满，不允许上传新文件！','-1');
            exit();
        }
    }

    /**
     *  更新用户信息统计表
     *
     * @access    public
     * @param     string  $field  字段信息
     * @param     string  $uptype  更新类型
     * @return    string
     */
    function UpdateUserTj($field, $uptype='add')
    {
        global $dsql;
        $mid = $this->M_ID;
        $arr = $dsql->GetOne("Select * `#@__member_tj` where mid='$mid' ");
        if(!is_array($arr))
        {
            $arr = array('article'=>0,'album'=>0,'archives'=>0,'homecount'=>0,'pagecount'=>0,'feedback'=>0,'friend'=>0,'stow'=>0);
        }
        extract($arr);
        if(isset($$field))
        {
            if($uptype=='add')
            {
                $$field++;
            }
            else if($$field > 0)
            {
                $$field--;
            }
        }
        $inquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
               VALUES ('$mid','$article','$album','$archives','$homecount','$pagecount','$feedback','$friend','$stow'); ";
        $dsql->ExecuteNoneQuery("Delete From `#@__member_tj` where mid='$mid' ");
        $dsql->ExecuteNoneQuery($inquery);
    }

    //
    /**
     *  重置用户信息
     *
     * @return    void
     */
    function ResetUser()
    {
        $this->fields = '';
        $this->M_ID = 0;
        $this->M_LoginID = '';
        $this->M_Rank = 0;
        $this->M_Face = "";
        $this->M_Money = 0;
        $this->M_UserName = "";
        $this->M_LoginTime = 0;
        $this->M_MbType = '';
        $this->M_Scores = 0;
        $this->M_Spacesta = -2;
        $this->M_UpTime = 0;
        $this->M_ExpTime = 0;
        $this->M_JoinTime = 0;
        $this->M_HasDay = 0;
        DropCookie('DedeUserID');
        DropCookie('DedeLoginTime');
    }

    /**
     *  获取整数值
     *
     * @access    public
     * @param     string  $fnum  处理的数值
     * @return    string
     */
    function GetNum($fnum){
        $fnum = preg_replace("/[^0-9\.]/", '', $fnum);
        return $fnum;
    }

    /**
     *  用户登录
     *  把登录密码转为指定长度md5数据
     *
     * @access    public
     * @param     string  $pwd  需要加密的密码
     * @return    string
     */
    function GetEncodePwd($pwd)
    {
        global $cfg_mb_pwdtype;
        if(empty($cfg_mb_pwdtype)) $cfg_mb_pwdtype = '32';
        switch($cfg_mb_pwdtype)
        {
            case 'l16':
                return substr(md5($pwd), 0, 16);
            case 'r16':
                return substr(md5($pwd), 16, 16);
            case 'm16':
                return substr(md5($pwd), 8, 16);
            default:
                return md5($pwd);
        }
    }
    
    /**
     *  把数据库密码转为特定长度
     *  如果数据库密码是明文的，本程序不支持
     *
     * @access    public
     * @param     string
     * @return    string
     */
    function GetShortPwd($dbpwd)
    {
        global $cfg_mb_pwdtype;
        if(empty($cfg_mb_pwdtype)) $cfg_mb_pwdtype = '32';
        $dbpwd = trim($dbpwd);
        if(strlen($dbpwd)==16)
        {
            return $dbpwd;
        }
        else
        {
            switch($cfg_mb_pwdtype)
            {
                case 'l16':
                    return substr($dbpwd, 0, 16);
                case 'r16':
                    return substr($dbpwd, 16, 16);
                case 'm16':
                    return substr($dbpwd, 8, 16);
                default:
                    return $dbpwd;
            }
        }
    }
    
    /**
     *  检查用户是否合法
     *
     * @access    public
     * @param     string  $loginuser  登录用户名
     * @param     string  $loginpwd  用户密码
     * @return    string
     */
    function CheckUser(&$loginuser, $loginpwd)
    {
        global $dsql;

        //检测用户名的合法性
        $rs = CheckUserID($loginuser,'用户名',FALSE);

        //用户名不正确时返回验证错误，原登录名通过引用返回错误提示信息
        if($rs!='ok')
        {
            $loginuser = $rs;
            return '0';
        }

        //matt=10 是管理员关连的前台帐号，为了安全起见，这个帐号只能从后台登录，不能直接从前台登录
        $row = $dsql->GetOne("SELECT mid,matt,pwd,logintime FROM `#@__member` WHERE userid LIKE '$loginuser' ");
        if(is_array($row))
        {
            if($this->GetShortPwd($row['pwd']) != $this->GetEncodePwd($loginpwd))
            {
                return -1;
            }
            else
            {
                //管理员帐号不允许从前台登录
                if($row['matt']==10) {
                    return -2;
                }
                else {
                    $this->PutLoginInfo($row['mid'], $row['logintime']);
                    return 1;
                }
            }
        }
        else
        {
            return 0;
        }
    }

    /**
     *  保存用户cookie
     *
     * @access    public
     * @param     string  $uid  用户ID
     * @param     string  $logintime  登录限制时间
     * @return    void
     */
    function PutLoginInfo($uid, $logintime=0)
    {
        global $cfg_login_adds, $dsql;
        //登录增加积分(上一次登录时间必须大于两小时)
        if(time() - $logintime > 7200 && $cfg_login_adds > 0)
        {
            $dsql->ExecuteNoneQuery("Update `#@__member` set `scores`=`scores`+{$cfg_login_adds} where mid='$uid' ");
        }
        $this->M_ID = $uid;
        $this->M_LoginTime = time();
        $loginip = GetIP();
        $inquery = "UPDATE `#@__member` SET loginip='$loginip',logintime='".$this->M_LoginTime."' WHERE mid='".$uid."'";
        $dsql->ExecuteNoneQuery($inquery);
        if($this->M_KeepTime > 0)
        {
            PutCookie('DedeUserID',$uid,$this->M_KeepTime);
            PutCookie('DedeLoginTime',$this->M_LoginTime,$this->M_KeepTime);
        }
        else
        {
            PutCookie('DedeUserID',$uid);
            PutCookie('DedeLoginTime',$this->M_LoginTime);
        }
    }

    /**
     *  获得会员目前的状态
     *
     * @access    public
     * @param     string  $dsql  数据库连接
     * @return    string
     */
    function GetSta($dsql)
    {
        $sta = '';
        if($this->M_Rank==0)
        {
            $sta .= "你目前的身份是：普通会员";
        }else{
            $row = $dsql->GetOne("Select membername From `#@__arcrank` where rank='".$this->M_Rank."'");
            $sta .= "你目前的身份是：".$row['membername'];
            $rs = $dsql->GetOne("Select id From `#@__admin` where userid='".$this->M_LoginID."'");
            if(!is_array($rs)){
                if($this->M_Rank>10 && $this->M_HasDay>0) $sta .= " 剩余天数: <font color='red'>".$this->M_HasDay."</font>  天 ";
                elseif($this->M_Rank>10) $sta .= " <font color='red'>会员升级已经到期</font> ";
          }
        }
        $sta .= " 拥有金币：{$this->M_Money} 个， 积分：{$this->M_Scores} 分。";
        return $sta;
    }
    
    /**
     *  记录会员操作日志
     *
     * @access    public
     * @param     string  $type 记录类型
     * @param     string  $title 记录标题
     * @param     string  $note记录描述
     * @param     string  $aid涉及到的内容的id
     * @return    string
     */
    function RecordFeeds($type, $title, $note, $aid)
    {
        global $dsql,$cfg_mb_feedcheck;
        //确定是否需要记录
        if (in_array($type,array('add','addsoft','feedback','addfriends','stow'))){
            $ntime = time();
            $title = htmlspecialchars(cn_substrR($title,255));
            if(in_array($type,array('add','addsoft','feedback','stow')))
            {
                $rcdtype = array('add'=>' 成功发布了', 'addsoft'=>' 成功发布了软件',
                                 'feedback'=>' 评论了文章','stow'=>' 收藏了');
                //内容发布处理
                $arcrul = " <a href='/plus/view.php?aid=".$aid."'>".$title."</a>";
                $title = htmlspecialchars($rcdtype[$type].$arcrul, ENT_QUOTES);
            } else if ($type == 'addfriends')
            {
                //添加好友处理
                $arcrul = " <a href='/member/index.php?uid=".$aid."'>".$aid."</a>";
                $title = htmlspecialchars(' 与'. $arcrul."成为好友", ENT_QUOTES);
            }
            $note = Html2Text($note);
            $aid = (isset($aid) && is_numeric($aid) ? $aid : 0);
            $ischeck = ($cfg_mb_feedcheck == 'Y')? 0 : 1;
            $query = "INSERT INTO `#@__member_feed` (`mid`, `userid`, `uname`, `type`, `aid`, `dtime`,`title`, `note`, `ischeck`) 
                        Values('$this->M_ID', '$this->M_LoginID', '$this->M_UserName', '$type', '$aid', '$ntime', '$title', '$note', '$ischeck'); ";
            $rs = $dsql->ExecuteNoneQuery($query);
            return $rs;
        } else {
            return FALSE;
        }
    }
}//End Class