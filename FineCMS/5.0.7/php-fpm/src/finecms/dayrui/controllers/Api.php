<?php

/**
 * FineCMS 公益软件
 *
 * @策划人 李睿
 * @开发组自愿者  邢鹏程 刘毅 陈锦辉 孙华军
 */
 
class Api extends M_Controller {


    /**
     * 会员登录信息JS调用
     */
    public function member() {
        ob_start();
        $this->template->display('member.html');
        $html = ob_get_contents();
        ob_clean();
		$format = $this->input->get('format');
		// 页面输出
		if ($format == 'jsonp') {
			$data = $this->callback_json(array('html' => $html));
			echo $this->input->get('callback', TRUE).'('.$data.')';
		} elseif ($format == 'json') {
			echo $this->callback_json(array('html' => $html));
		} else {
			echo 'document.write("'.addslashes(str_replace(array("\r", "\n", "\t", chr(13)), array('', '', '', ''), $html)).'");';
		}
        exit;
    }


    /**
     * 自定义信息JS调用
     */
    public function template() {
        $this->api_template();
    }

    /**
     * ajax 动态调用
     */
    public function html() {

        ob_start();
        $this->template->cron = 0;
        $_GET['page'] = max(1, (int)$this->input->get('page'));
        $params = dr_string2array(urldecode($this->input->get('params')));
        $params['get'] = @json_decode(urldecode($this->input->get('get')), TRUE);
        $this->template->assign($params);
        $name = str_replace(array('\\', '/', '..', '<', '>'), '', dr_safe_replace($this->input->get('name', TRUE)));
        $this->template->display(strpos($name, '.html') ? $name : $name.'.html');
        $html = ob_get_contents();
        ob_clean();

        // 页面输出
        $format = $this->input->get('format');
        if ($format == 'html') {
            exit($html);
        } elseif ($format == 'json') {
            echo $this->callback_json(array('html' => $html));
        } elseif ($format == 'js') {
            echo 'document.write("'.addslashes(str_replace(array("\r", "\n", "\t", chr(13)), array('', '', '', ''), $html)).'");';
        } else {
            $data = $this->callback_json(array('html' => $html));
            echo $this->input->get('callback', TRUE).'('.$data.')';
        }
    }

    /**
	 * 更新浏览数
	 */
	public function hits() {
	
	    $id = (int)$this->input->get('id');
	    $dir = dr_safe_replace($this->input->get('module', TRUE));
        $mod = $this->module[$dir];
        if (!$mod) {
            $data = $this->callback_json(array('html' => 0));
            echo $this->input->get('callback', TRUE).'('.$data.')';exit;
        }

        // 获取主表时间段
        $data = $this->db
                     ->where('id', $id)
                     ->select('hits,updatetime')
                     ->get($this->db->dbprefix(SITE_ID.'_'.$dir))
                     ->row_array();
        $hits = (int)$data['hits'] + 1;

        // 更新主表
		$this->db->where('id', $id)->update(SITE_ID.'_'.$dir, array('hits' => $hits));

        // 输出数据
        echo $this->input->get('callback', TRUE).'('.$this->callback_json(array('html' => $hits)).')';exit;
	}


	
	/**
	 * 伪静态测试
	 */
	public function test() {
		header('Content-Type: text/html; charset=utf-8');
		echo '服务器支持伪静态';
	}
	

	
	/**
	 * 自定义数据调用（新版本）
	 */
	public function data2() {

        $data = array();

        // 来路认证
        if (defined('SYS_REFERER') && strlen(SYS_REFERER)) {
            $http = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $_GET['http_referer'];
            if (empty($http)) {
                $data = array('msg' => '来路认证失败（NULL）', 'code' => 0);
            } elseif (strpos($http, SYS_REFERER) === FALSE) {
                $data = array('msg' => '来路认证失败（非法请求）', 'code' => 0);
            }
        }

        if (!$data) {
            // 安全码认证
            $auth = $this->input->get('auth');
            if ($auth != md5(SYS_KEY)) {
                // 授权认证码不正确
                $data = array('msg' => '授权认证码不正确', 'code' => 0);
            } else {
                // 解析数据
                $cache = '';
                $param = $this->input->get('param');
                if (isset($param['cache']) && $param['cache']) {
                    $cache = md5(dr_array2string($param));
                    $data = $this->get_cache_data($cache);
                }
                if (!$data) {

                    if ($param == 'login') {
                        // 登录认证
                        $code = $this->member_model->login(
                            $this->input->get('username'),
                            $this->input->get('password'),
                            0, 1);
                        if (is_array($code)) {
                            $data = array(
                                'msg' => 'ok',
                                'code' => 1,
                                'return' => $this->member_model->get_member($code['uid'])
                            );
                        } elseif ($code == -1) {
                            $data = array('msg' => fc_lang('会员不存在'), 'code' => 0);
                        } elseif ($code == -2) {
                            $data = array('msg' => fc_lang('密码不正确'), 'code' => 0);
                        } elseif ($code == -3) {
                            $data = array('msg' => fc_lang('Ucenter注册失败'), 'code' => 0);
                        } elseif ($code == -4) {
                            $data = array('msg' => fc_lang('Ucenter：会员名称不合法'), 'code' => 0);
                        }
                    } elseif ($param == 'update_avatar') {
                        // 更新头像
                        $uid = (int)$_REQUEST['uid'];
                        $file = $_REQUEST['file'];
                        //
                        // 创建图片存储文件夹
                        $dir = SYS_UPLOAD_PATH.'/member/'.$uid.'/';
                        @dr_dir_delete($dir);
                        if (!is_dir($dir)) {
                            dr_mkdirs($dir);
                        }
                        $file = str_replace(' ', '+', $file);
                        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $file, $result)){
                            $new_file = $dir.'0x0.'.$result[2];
                            if (!@file_put_contents($new_file, base64_decode(str_replace($result[1], '', $file)))) {
                                $data = array(
                                    'msg' => '目录权限不足或磁盘已满',
                                    'code' => 0
                                );
                            } else {
                                $this->load->library('image_lib');
                                $config['create_thumb'] = TRUE;
                                $config['thumb_marker'] = '';
                                $config['maintain_ratio'] = FALSE;
                                $config['source_image'] = $new_file;
                                foreach (array(30, 45, 90, 180) as $a) {
                                    $config['width'] = $config['height'] = $a;
                                    $config['new_image'] = $dir.$a.'x'.$a.'.'.$result[2];
                                    $this->image_lib->initialize($config);
                                    if (!$this->image_lib->resize()) {
                                        $data = array(
                                            'msg' => $this->image_lib->display_errors(),
                                            'code' => 0
                                        );
                                        break;
                                    }
                                }
                                list($width, $height, $type, $attr) = getimagesize($dir.'45x45.'.$result[2]);
                                if (!$type) {
                                    $data = array(
                                        'msg' => '错误的文件格式，请传输图片的字符',
                                        'code' => 0
                                    );
                                }
                            }
                        } else {
                            $data = array(
                                'msg' => '图片字符串不规范，请使用base64格式',
                                'code' => 0
                            );
                        }

                        // 更新头像
                        if (!isset($data['code'])){
                            $data = array(
                                'code' => 1,
                                'msg' => '更新成功'
                            );
                            $this->db->where('uid', $uid)->update('member', array('avatar' => $uid));
                        }
                    } elseif ($param == 'function') {
                        // 执行函数
                        $name = $this->input->get('name', true);
                        if (function_exists($name)) {
                            $_param = array();
                            $_getall = $this->input->get(null, true);
                            if ($_getall) {
                                for ($i=1; $i<=10; $i++) {
                                    if (isset($_getall['p'.$i])) {
                                        $_param[] = $_getall['p'.$i];
                                    } else {
                                        break;
                                    }
                                }
                            }
                            $data = array('msg' => '', 'code' => 1, 'result' => call_user_func_array($name, $_param));
                        } else {
                            $data = array('msg' => '函数 （'.$name.'）不存在', 'code' => 0);
                        }
                    } elseif ($param == 'get_file') {
                        // 获取文件地址
                        $info = get_attachment((int)$this->input->get('id'));
                        if (!$info) {
                            $data = array('msg' => fc_lang('附件不存在或者已经被删除'), 'code' => 0, 'url' => '');
                        } else {
                            $data = array('msg' => '', 'code' => 1, 'url' => dr_get_file($info['attachment']));
                        }
                    } else {
                        // list数据查询
                        $data = $this->template->list_tag($param);
                        $data['code'] = $data['error'] ? 0 : 1;
                        unset($data['sql'], $data['pages']);
                    }

                    // 缓存数据
                    $cache && $this->set_cache_data($cache, $data, $param['cache']);
                }
            }
        }

		// 接收参数
		$format = $this->input->get('format');
		$function = $this->input->get('function');
        if ($function) {
            if (!function_exists($function)) {
                $data = array('msg' => fc_lang('自定义函数'.$function.'不存在'), 'code' => 0);
            } else {
                $data = $function($data);
            }
        }
		// 页面输出
		if ($format == 'php') {
			print_r($data);
		} elseif ($format == 'jsonp') {
			// 自定义返回名称
			echo $this->input->get('callback', TRUE).'('.$this->callback_json($data).')';
		} else {
			// 自定义返回名称
			echo $this->callback_json($data);
		}
		exit;
	}

    /**
     * 站点间的同步登录
     */
    public function synlogin() {
        $this->api_synlogin();
    }

    /**
     * 站点间的同步退出
     */
    public function synlogout() {
        $this->api_synlogout();
    }
}
