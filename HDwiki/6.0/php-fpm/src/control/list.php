<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( $get, $post);
		$this->load('doc');
		$this->load('user');
	}
	
	function dodefault(){
	    $this->isMobile() ? $this->dowaprecentchange() : $this->dorecentchange();
	}

	function dorecentchange(){
		$page = max(1, intval($this->get[2]));
		$start_limit = ($page - 1) * $this->setting['list_recentupdate'];
		$count=$this->db->fetch_total('doc','1');
		$list=$_ENV['doc']->get_list_cache(1,'',$start_limit,$this->setting['list_recentupdate']);
		$total=$_ENV['doc']->get_list_total(1,'');
		$count=($total==0)?min($count,100):min($total,$count,100);		
		
		$temcount=count($list);
		for($i=0;$i<$temcount;$i++){
			$list[$i]['time']=$list[$i]['lastedit'];
		}
		$departstr=$this->multi($count, $this->setting['list_recentupdate'], $page,'list-recentchange');
		$this->view->assign('navtitle',$this->view->lang['recentUpdate'].'-');
		$this->view->assign("departstr",$departstr);
		$this->view->assign('type','recentupdate');
		$this->view->assign('list',$list);
		//$this->view->display('list');
        $_ENV['block']->view('list');
	}

    /**
     * @brief 移动端list页面展示接口
     */
    function dowaprecentchange() {
        $list = array();
        $order_flag = $this->get[2] ? $this->get[2] : 'hot';
        switch($order_flag) {
            case 'hot' :    // 热门词条
                $list = $this->get_focus_list(2);
				$navtitle = $this->view->lang['hotDoc'];
                break;
            case 'wond' :   // 精彩词条
                $list = $this->get_focus_list(3);
				$navtitle = $this->view->lang['wonderDoc'];
                break;
            case 'digg' :   // 推荐词条
                $list = $this->get_focus_list(1);
				$navtitle = $this->view->lang['focusDoc'];
                break;
            case 'new' :    // 最新词条
                $list = $this->get_new_list();
				$navtitle = $this->view->lang['recentUpdate'];
                break;
            case 'week' :   // 周排行
                $list = $this->get_week_list();
				$navtitle = $this->view->lang['weekUserList'];
                break;
            case 'cup' :    // 总贡献
                $list = $this->get_cup_list();
				$navtitle = $this->view->lang['weekUserList'];
                break;
            case 'eye' :    // 人气
                $list = $this->get_eye_list();
				$navtitle = $this->view->lang['popularity'];
                break;
            }
			$this->view->assign('navtitle',$navtitle.'-');
            if (isset($this->get[3]) && $this->get[3] > 1) {
                exit(json_encode($this->toutf8($list)));
            }

            $this->view->assign('list', $list);
            $this->view->assign('order_flag', $order_flag);
            $_ENV['block']->view('wap-list');
    }

    /**
     * @brief 正对gbk版本对文本进行转码操作
     * @param array $a
     * @return array
     */
    private function toutf8($a) {
        if (empty($a) || WIKI_CHARSET == 'UTF-8'){
            return $a;
        }
        if (is_string($a)){
            $a = iconv('gbk', 'utf-8', $a);
        } else if (is_array($a)){
            foreach($a as $k => $v){
                $k2 = iconv('gbk', 'utf-8', $k);
                if ($k2 != $k) {
                    unset($a[$k]);
                    $k = $k2;
                }
                $a[$k] = $this->toutf8($v);
            }
        }
        return $a;
    }

    /**
     * @biref 为热门，精彩，推荐三种词条提供数据
     * @$doctype '类别(热门[2]，精彩[3]，推荐[1])'
     * @param array
     */
    public function get_focus_list($doctype) {
        $this->get[3] = empty($this->get[3]) ? NULL : $this->get[3];
        $page = max(1, intval($this->get[3]));
        $start_limit = ($page - 1) * $this->setting['list_focus'];
        return $_ENV['doc']->get_focus_list($start_limit, $this->setting['list_focus'], $doctype);
    }
    
    /**
     * @brief 为移动端最新提供数据
     */
    public function get_new_list() {
        $page = max(1, intval($this->get[3]));
        $start_limit = ($page - 1) * $this->setting['list_recentupdate'];
        $list = $_ENV['doc']->get_list_cache(1, '', $start_limit, $this->setting['list_recentupdate']);

        $temcount = count($list);
        for($i = 0; $i < $temcount; $i++) {
            $list[$i]['time'] = $list[$i]['lastedit'];
        }
        return $list;
    }

    /**
     * @brief 为移动端周排行提供数据
     */
    public function get_week_list() {
        $page = max(1, intval($this->get[3]));
        $start_limit = ($page - 1) * $this->setting['list_weekuser'];
        $list=$_ENV['user']->get_week_user($start_limit,$this->setting['list_weekuser']);
        return $list;
    }
    
    /**
     * @brief 为移动端总贡献提供数据
     */
    public function get_cup_list() {
        $page = max(1, intval($this->get[3]));
        $start_limit = ($page - 1) * $this->setting['list_allcredit'];
        return $_ENV['user']->get_top_user($start_limit,$this->setting['list_allcredit']);
    }

    /**
     * @brief 为移动端人气提供数据
     */
    public function get_eye_list() {
        $page = max(1, intval($this->get[3]));
        $start_limit = ($page - 1) * $this->setting['list_popularity'];
        return $_ENV['user']->get_popularity_user($start_limit,$this->setting['list_popularity']);
    }

	function doletter(){
		if(!preg_match('/^\w|\*$/i',$this->get[2])){
			$this->message($this->view->lang['parameterError'],'BACK',0);
		}
		$this->get[3] = empty($this->get[3]) ? NULL : $this->get[3];
		$page = max(1, intval($this->get[3]));
		$start_limit = ($page - 1) * $this->setting['list_letter'];
		$letter=strtolower($this->get[2]);
		$count=$this->db->fetch_total('doc',"letter='$letter'");
		$list=$_ENV['doc']->get_list_cache(0,$letter,$start_limit,$this->setting['list_letter']);
		$total=$_ENV['doc']->get_list_total(0,$letter);
		$count=($total==0)?min($count,100):min($total,$count,100);
		
		$departstr=$this->multi($count, $this->setting['list_letter'], $page,'list-letter-'.$letter);
		$this->view->assign('navtitle',$this->view->lang['letterOrderView']."{$letter}-");
		$this->view->assign("departstr",$departstr);
		$this->view->assign('letter',strtoupper($letter));
		$this->view->assign('type','letter');
		$this->view->assign('list',$list);
		//$this->view->display('list');
		$_ENV['block']->view('list');
	}
	
	function dopopularity(){
		$page = max(1, intval($this->get[2]));
		$start_limit = ($page - 1) * $this->setting['list_popularity'];
		$total=100;
		$count=$_ENV['user']->get_total_num('',0);
		$count=($count<$total)?$count:$total;
		$list=$_ENV['user']->get_popularity_user($start_limit,$this->setting['list_popularity']);
		$departstr=$this->multi($count, $this->setting['list_popularity'], $page,'list-popularity');
		$this->view->assign('navtitle',$this->view->lang['popularity'].'-');
		$this->view->assign("departstr",$departstr);
		$this->view->assign('type','popularity');
		$this->view->assign('list',$list);
		//$this->view->display('list');
		$_ENV['block']->view('list');
	}
	
	function doallcredit(){
		$page = max(1, intval($this->get[2]));
		$start_limit = ($page - 1) * $this->setting['list_allcredit'];
		$total=100;
		$count=$_ENV['user']->get_total_num('',0);
		$count=($count<$total)?$count:$total;	
		$list=$_ENV['user']->get_top_user($start_limit,$this->setting['list_allcredit']);		
		$departstr=$this->multi($count, $this->setting['list_allcredit'], $page,'list-allcredit');
		$this->view->assign('navtitle',$this->view->lang['allcredit'].'-');
		$this->view->assign("departstr",$departstr);
		$this->view->assign('type','allcredit');
		$this->view->assign('list',$list);
		//$this->view->display('list');
		$_ENV['block']->view('list');
	}

	function doweekuserlist(){
		$page = max(1, intval($this->get[2]));
		$start_limit = ($page - 1) * $this->setting['list_weekuser'];
		$total=100;
		$count=$_ENV['user']->get_week_num();
		$count=($count<$total)?$count:$total;
		$list=$_ENV['user']->get_week_user($start_limit,$this->setting['list_weekuser']);		
		$departstr=$this->multi($count, $this->setting['list_weekuser'], $page,'list-weekuserlist');
		
		$this->view->assign('navtitle',$this->view->lang['weekUserList'].'-');
		$this->view->assign("departstr",$departstr);
		$this->view->assign('type','weekuserlist');
		$this->view->assign('list',$list);
		//$this->view->display('list');
		$_ENV['block']->view('list');
	}
	
	function dofocus(){
		$doctype = $this->get[2];
		switch($doctype){
			case 2:
				$type = 'hot';
				$navtitle = $this->view->lang['hotDoc'];
				break;
			case 3:
				$type = 'champion';
				$navtitle = $this->view->lang['wonderDoc'];
				break;
			default:
				$doctype = 1;
				$navtitle = $this->view->lang['focusDoc'];
				$type = 'focus';
		}
		$url = 'list-focus-'.$doctype;
		$this->get[3] = empty($this->get[3]) ? NULL : $this->get[3];
		$page = max(1, intval($this->get[3]));
		$start_limit = ($page - 1) * $this->setting['list_focus'];
		$total=100;
		$num=10;
		$count=$this->db->fetch_total('focus',"type=$doctype");
		$count=($count<$total)?$count:$total;
		$list=$_ENV['doc']->get_focus_list($start_limit,$this->setting['list_focus'],$doctype);
		$departstr=$this->multi($count, $this->setting['list_focus'], $page,$url);
		$this->view->assign('navtitle',$navtitle);
		$this->view->assign("departstr",$departstr);
		$this->view->assign('type',$type);
		$this->view->assign('list',$list);
		//$this->view->display('list');
		$_ENV['block']->view('list');
	}
	
	function dorss(){
		$typename = '';
		if($this->get[2]){
			switch($this->get[2]){
				case 'focus':
					$typename = $this->view->lang['focusDoc'];
					break;
				case 'hot':
					$typename = $this->view->lang['hotDoc'];
					break;
				case '':
					$typename = $this->view->lang['wonderDoc'];
					break;
			}
			if($this->get[2]=='focus'){
				$doclist=$_ENV['doc']->get_rss('focus',0,$this->setting['list_focus'],1);
			}else if($this->get[2]=='hot'){
				$doclist=$_ENV['doc']->get_rss('focus',0,$this->setting['list_focus'],2);
			}else if($this->get[2]=='champion'){
				$doclist=$_ENV['doc']->get_rss('focus',0,$this->setting['list_focus'],3);
			}else{
				$doclist=$_ENV['doc']->get_rss('doc',0,$this->setting['list_'.$this->get[2]] );
			}
			header("Content-Type: application/xml");
			echo "<?xml version=\"1.0\" encoding='".WIKI_CHARSET."'?>\n";
			echo "<?xml-stylesheet type=\"text/css\" href=\"".WIKI_URL."/style/".$GLOBALS['theme']."/rss_style.css\"?>\n";
			echo "<rss version=\"2.0\">\n";
			echo "<channel>\n";
			echo "<title>".$typename."</title>\n";
			echo "<link>".WIKI_URL."/".$this->setting['seo_prefix']."list-".$this->get[2]."</link>\n";
			echo "<description>".$typename."</description>\n";
			echo "<generator>".WIKI_URL."</generator>\n";
			echo "<lastBuildDate>".$this->date($this->time)."</lastBuildDate>\n";
			echo "<ttl>60</ttl>\n";
			if (is_array($doclist)){
				foreach ($doclist AS $doc) {
				echo "<item>\n";
				echo "<title>".$doc['title']."</title>\n";
				echo"<link>".WIKI_URL."/".$this->setting['seo_prefix']."doc-view-".$doc['did']."</link>\n";
				echo "<author>".$doc['author']."</author>\n";
				echo "<description><![CDATA[".$doc['content']."]]></description>\n";
				echo "<pubDate>".$doc['time']."</pubDate>\n";
				echo "</item>\n";
				}
			}
			echo "</channel>\n";
			echo "</rss>\n";
		}
	}
}		
?>