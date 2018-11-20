<?php /* Smarty version 2.6.22, created on 2018-11-20 09:49:30
         compiled from footer.htm */ ?>
<div class="footer">
	<!--<hr style="color:#CCE6F5;">
	--><div id="bottom_nav">
			<a href="./" target="_blank" rel='nofollow'>网站首页</a>&nbsp;<?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['bot_nav']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
?>&nbsp;|&nbsp;<a href="<?php echo $this->_tpl_vars['bot_nav'][$this->_sections['n']['index']]['navlink']; ?>
" target="<?php if ($this->_tpl_vars['bot_nav'][$this->_sections['n']['index']]['opennew'] == 1): ?>_blank<?php else: ?>_self<?php endif; ?>" rel='nofollow'><?php echo $this->_tpl_vars['bot_nav'][$this->_sections['n']['index']]['navname']; ?>
</a><?php endfor; endif; ?>
	</div>
	<div id="foot"><?php echo $this->_tpl_vars['right']; ?>
<br/>
	<!-- 如未得到官方允许，请务必保留此版权信息！ -->
		Powered by <span class="bluecms"><a href="http://www.bluecms.net" target="_blank" >BlueCMS</a></span> <span class="version"><?php echo $this->_tpl_vars['version']; ?>
</span> &nbsp;&nbsp;<?php echo $this->_tpl_vars['icp']; ?>
&nbsp;&nbsp;<?php echo $this->_tpl_vars['count']; ?>

	</div>
</div>