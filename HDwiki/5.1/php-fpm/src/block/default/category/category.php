<?php
class category{
	var $db;
	function category(&$base) {
	  $this->base = $base;
	}
	function viewcategory($setting){
		$this->base->load('category');
		$allcategory=$_ENV['category']->get_category_cache();
		$subcategory=$_ENV['category']->get_site_category(0,$allcategory);
		return array('config'=>$setting, 'subcategory'=>$subcategory);
	}
}
?>