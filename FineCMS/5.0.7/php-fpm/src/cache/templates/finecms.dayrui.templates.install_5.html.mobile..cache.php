<?php if ($fn_include = $this->_include("install_header.html", "admin")) include($fn_include); ?>
<section class="section">
	<div class="">
		<div class="success_tip cc">
			<span class="f16 b">安装完成</span>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="admin.php" class="f16 b">进入后台</a>
            </p>
		</div>
		<div class=""> </div>
	</div>
</section>
<?php if ($fn_include = $this->_include("install_footer.html", "admin")) include($fn_include); ?>