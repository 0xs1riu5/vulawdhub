<?php
//#####################@ 无限级分类父子类-20120314-koyshe @#####################//
class category {
	private $field;//分类字段名
	private $tree = array();//生成的树形分类数组
	private $tempvar = '';//临时变量
	public function __construct($category_id = null, $category_pid = null, $category_name = null, $category_showname = null)
	{
		$this->field['id'] = isset($category_id) ? $category_id : 'category_id';
		$this->field['pid'] = isset($category_pid) ? $category_pid : 'category_pid';
		$this->field['name'] = isset($category_name) ? $category_name : 'category_name';
		$this->field['showname'] = isset($category_showname) ? $category_showname : 'category_showname';
	}
	public function gettree($data, $pid = 0, $level = 1)
	{
		$newdata = array();
		foreach ($data as $v) {
			if ($v[$this->field['pid']] == $pid) {
				//前三级前导符号
				$level == 1 && $v[$this->field['showname']] = "{$v[$this->field['name']]}";
				$level == 2 && $v[$this->field['showname']] = "　 ┝ {$v[$this->field['name']]}";
				$level >= 3 && $v[$this->field['showname']] = '　 '.str_repeat('　　', $level - 2)."┝ {$v[$this->field['name']]}";
				$this->tree[] = $v;
				$this->gettree($data, $v[$this->field['id']], $level+1);
			}
			else {
				continue;
			}
		}
		return $this->tree;
	}
	
	public function getpid_arr($data, $id, $init = 1)
	{
		$init == 1 && $this->tempvar = array();
		!$this->tempvar && $this->tempvar = array();
		foreach ($data as $v) {
			if ($v[$this->field['id']] == $id) {
				if ($v[$this->field['pid']] == 0) {
					krsort($this->tempvar);
				}
				else {
					$this->tempvar[] = $v[$this->field['pid']];
					$this->getpid_arr($data, $v[$this->field['pid']], 0);
				}
				break;
			}
			else {
				continue;
			}
		}
		return $this->tempvar;
	}

	public function getcid_arr($data, $pid = 0, $init = 1)
	{
		$init == 1 && $this->tempvar = array();
		!$this->tempvar && $this->tempvar = array();
		foreach ($data as $v) {
			if ($v[$this->field['pid']] == $pid) {
				$this->tempvar[] = $v[$this->field['id']];
				$this->getcid_arr($data, $v[$this->field['id']], 0);
			}
			else {
				continue;
			}
		}
		return $this->tempvar;
	}

	public function gettid($data, $id)
	{
		$this->tempvar = '';
		foreach ($data as $v) {
			if ($v[$this->field['id']] == $id) {
				if ($v[$this->field['pid']] == 0) {
					$this->tempvar = $id;
				}
				else {
					$this->gettid($data, $v[$this->field['pid']]);
				}
				break;
			}
			else {
				continue;
			}
		}
		return $this->tempvar;
	}
}
?>