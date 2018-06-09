<?php


/**
 * FineCMS 公益软件
 *
 * @策划人 李睿
 * @开发组自愿者  邢鹏程 刘毅 陈锦辉 孙华军
 */


class Db extends M_Controller {
	
	private $siteid;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->siteid = (int)$this->input->get('siteid');
    }

    /**
     * 数据维护
     */
    public function index() {
	
		$list = $this->siteid ? $this->system_model->get_site_table($this->siteid) : $this->system_model->get_system_table();
		
		if (IS_POST) {
			$tables = $this->input->post('select');
			if (!$tables) {
                $this->admin_msg(fc_lang('貌似你还没有选择需要操作的表呢'));
            }
			switch ((int)$this->input->post('action')) {
				case 1: // 优化表
					foreach ($tables as $table) {
						$this->db->query("OPTIMIZE TABLE `$table`");
					}
					$result = fc_lang('操作成功，正在刷新...');
                    $this->system_log('优化数据表'); // 记录日志
					break;
				case 2: // 修复表
					foreach ($tables as $table) {
						$this->db->query("REPAIR TABLE `$table`");
					}
					$result = fc_lang('操作成功，正在刷新...');
                    $this->system_log('修复数据表'); // 记录日志
					break;
			}
		}
		
		$menu = array();
		$menu[fc_lang('系统数据库')] = array('admin/db/index', 'database');
		foreach ($this->site_info as $id => $s) {
			$menu[fc_lang('站点【#%s】', $id)] = array('admin/db/index/siteid/'.$id, 'database');
		}

		$this->template->assign(array(
			'menu' => $this->get_menu_v3($menu),
			'list' => $list,
			'result' => $result,
		));
		$this->template->display('db_index.html');
	}
	
	/**
     * 数据恢复
     */
	public function recovery() {
		$this->admin_msg('此功能已废弃，请进入“应用-云商店”下载<br>由【张敏工作室】出品的【数据备份王】');
	}
	
	/**
     * 数据备份
     */
	public function backup() {
        $this->admin_msg('此功能已废弃，请进入“应用-云商店”下载<br>由【张敏工作室】出品的【数据备份王】');
    }


	/**
     * 执行sql
     */
    public function sql() {

        $sql = '';
        $count = $id = 0;

        if (IS_POST) {
            $id = $this->input->post('id');
            $sql = str_replace('{dbprefix}', $this->db->dbprefix, $this->input->post('sql'));
            if (preg_match('/select(.*)into outfile(.*)/i', $sql)) {
                $this->admin_msg(fc_lang('存在非法select'));
            }
            $sql_data = explode(';SQL_FINECMS_EOL', trim(str_replace(array(PHP_EOL, chr(13), chr(10)), 'SQL_FINECMS_EOL', $sql)));
            if ($sql_data) {
                $db = isset($this->site[$id]) && $this->site[$id] ? $this->site[$id] : $this->db;
                foreach($sql_data as $query){
                    if (!$query) {
                        continue;
                    }
                    $queries = explode('SQL_FINECMS_EOL', trim($query));
                    $ret = '';
                    foreach($queries as $query) {
                        $ret.= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
                    }
                    if (!$ret) {
                        continue;
                    }
                    $db->query($ret);
                    $count++;
                }
                if ($count == 1 && stripos($ret, 'select') === 0) {
                    $this->template->assign(array(
                        'result' => $db->query($ret)->result_array(),
                    ));
                }
            }
        }

        $this->template->assign(array(
            'menu' => $this->get_menu_v3(array(
                fc_lang('执行SQL') => array('admin/db/sql', 'database')
            )),
            'id' => $id,
            'sql' => $sql,
            'mcount' => $count,
        ));
        $this->template->display('db_sql.html');
    }

	/**
     * 表结构
     */
    public function tableshow() {
		$name = $this->input->get('name');
		$cache = $this->get_cache('table');
		$this->template->assign('table', $cache[$name]);
		$this->template->display('db_table.html');
	}

}