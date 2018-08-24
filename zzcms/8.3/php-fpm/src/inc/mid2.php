<?php
if ($n<=3){
	$mids2=str_replace("{#xuhao}", "<font class=xuhao1>".addzero($xuhao,2)."</font>",$mids2);
	}else{
	$mids2=str_replace("{#xuhao}", "<font class=xuhao2>".addzero($xuhao,2)."</font>",$mids2);
	}
	if ( $column <> "" && $column > 0) {//所有模板中以<ul>为布局的默认值都设为了1,最好还是设为0 ,即默认0时不分列，这里改为>0,布局table时1就能生效了。
		if ( $n % $column == 0) {$mids2 = $mids2 . "</tr>";}
	}
	$mids2 = $mids2 . "\r\n";
?>