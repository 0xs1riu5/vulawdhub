<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年3月1日
 *  内容模型控制器
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\ModelModel;

class ModelController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new ModelModel();
    }

    // 内容模型列表
    public function index()
    {
        if ((! ! $id = get('id', 'int')) && $result = $this->model->getModel($id)) {
            $this->assign('more', true);
            $this->assign('model', $result);
        } else {
            $this->assign('list', true);
            if (! ! ($field = get('field', 'var')) && ! ! ($keyword = get('keyword', 'vars'))) {
                $result = $this->model->findModel($field, $keyword);
            } else {
                $result = $this->model->getList();
            }
            $this->assign('models', $result);
        }
        $this->display('content/model.html');
    }

    // 内容模型增加
    public function add()
    {
        if ($_POST) {
            // 获取数据
            $mcode = get_auto_code($this->model->getLastCode());
            $name = post('name');
            $type = post('type');
            $listtpl = basename(post('listtpl'));
            $contenttpl = basename(post('contenttpl'));
            $status = post('status');
            
            if (! $name) {
                alert_back('模型名称不能为空！');
            }
            
            // 构建数据
            $data = array(
                'mcode' => $mcode,
                'name' => $name,
                'type' => $type,
                'listtpl' => $listtpl,
                'contenttpl' => $contenttpl,
                'status' => $status,
                'issystem' => 0,
                'create_user' => session('username'),
                'update_user' => session('username')
            );
            
            // 执行添加
            if ($this->model->addModel($data)) {
                $this->log('新增内容模型成功！');
                if (! ! $backurl = get('backurl')) {
                    success('新增成功！', base64_decode($backurl));
                } else {
                    success('新增成功！', url('/admin/Model/index'));
                }
            } else {
                $this->log('新增内容模型失败！');
                error('新增失败！', - 1);
            }
        } else {
            $this->assign('add', true);
            $this->display('content/model.html');
        }
    }

    // 内容模型删除
    public function del()
    {
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->delModel($id)) {
            $this->log('删除内容模型' . $id . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除内容模型' . $id . '失败！');
            error('删除失败！', - 1);
        }
    }

    // 内容模型修改
    public function mod()
    {
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            if ($this->model->modModel($id, "$field='$value',update_user='" . session('username') . "'")) {
                location(- 1);
            } else {
                alert_back('修改失败！');
            }
        }
        
        // 修改操作
        if ($_POST) {
            
            // 获取数据
            $name = post('name');
            $type = post('type');
            $listtpl = basename(post('listtpl'));
            $contenttpl = basename(post('contenttpl'));
            $status = post('status');
            
            if (! $name) {
                alert_back('模型名称不能为空！');
            }
            
            // 构建数据
            $data = array(
                'name' => $name,
                'type' => $type,
                'listtpl' => $listtpl,
                'contenttpl' => $contenttpl,
                'status' => $status,
                'update_user' => session('username')
            );
            
            // 执行添加
            if ($this->model->modModel($id, $data)) {
                $this->log('修改内容模型' . $id . '成功！');
                if (! ! $backurl = get('backurl')) {
                    success('修改成功！', base64_decode($backurl));
                } else {
                    success('修改成功！', url('/admin/Model/index'));
                }
            } else {
                location(- 1);
            }
        } else {
            // 调取修改内容
            $this->assign('mod', true);
            if (! $result = $this->model->getModel($id)) {
                error('编辑的内容已经不存在！', - 1);
            }
            $this->assign('model', $result);
            $this->display('content/model.html');
        }
    }
}