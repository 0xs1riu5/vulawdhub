<?php
//侧栏商品列表
function product_hotlist($num=6) {
	global $pe,$db;
	return $db->pe_selectall('product', array('order by'=>'`product_clicknum` desc'), '*', array($num));
}
//侧栏商品列表
function product_selllist($num) {
	global $pe,$db;
	return $db->pe_selectall('product', array('order by'=>'`product_sellnum` desc'), '*', array($num));
}
//商品统计更新
function product_num($type, $id) {
	global $db;
	$id = intval($id);
	switch ($type) {
		case 'addnum':
		case 'delnum':
			$orderdata_list = $db->pe_selectall('orderdata', array('order_id'=>$id));
			if ($type == 'addnum') {
				foreach ($orderdata_list as $v) {
					$db->pe_update('product', array('product_id'=>$v['product_id']), "`product_num`=`product_num`+{$v['product_num']}");
				}
			}
			else {
				foreach ($orderdata_list as $v) {
					$db->pe_update('product', array('product_id'=>$v['product_id']), "`product_num`=`product_num`-{$v['product_num']}");
				}
			}
		break;
		case 'sellnum':
			$orderdata_list = $db->pe_selectall('orderdata', array('order_id'=>$id));
			foreach ($orderdata_list as $v) {
				$db->pe_update('product', array('product_id' => $v['product_id']), "`product_sellnum` = `product_sellnum` + {$v['product_num']}");
			}
		break;
		case 'clicknum':
			$db->pe_update('product', array('product_id' => $id), "`product_clicknum` = `product_clicknum` + 1");
		break;
		default:
			if (in_array($type, array('collectnum', 'asknum', 'commentnum'))) {
				$num = $db->pe_num(substr($type, 0, -3), array('product_id' => $id));
				return $db->pe_update('product', array('product_id' => $id), array("product_{$type}" => $num));
			}
		break;
	}
}
?>