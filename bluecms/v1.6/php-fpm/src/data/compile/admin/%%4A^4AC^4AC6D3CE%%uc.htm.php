<?php /* Smarty version 2.6.22, created on 2018-11-20 11:06:06
         compiled from uc.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<form method="post" action="uc_setting.php" name="uc_form" >
<?php if ($this->_tpl_vars['act'] == 'install'): ?>
<table>
  <tr>
    <td>服务器端地址:</td>
    <td><input type="text" name="uc_api" maxlengtd="60" size="30" value="" /></td>
	<td>该值在您安装完 UCenter后会被初始化，在您 UCenter地址或者目录改变的情况下，<br />修改此项，一般情况请不要改动
				  例如: http://www.site.com/ucenter (最后不要加'/')</td>
  </tr>
  <tr>
    <td>创始人密码:</td>
    <td><input type="text" name="uc_admin_pwd" maxlengtd="60" size="30" value="" /></td>
  </tr>
  <tr>
    <td>服务端IP地址:</td>
    <td><input type="text" name="uc_ip" size="30" value="" /></td>
	<td>如果您的服务器无法通过域名访问 UCenter，可以输入 UCenter 服务器的 IP 地址</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="提交" class="button" />
      <input type="reset" value="重置" class="button" />
      <input type="hidden" name="act" value="install" />
   </td>
  </tr>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['act'] == 'show'): ?>
<table>
<tr>
   <td colspan="2" align="left">应用ID: <?php echo $this->_tpl_vars['uc_config']['appid']; ?>
</td>
</tr>
<tr>
   <td colspan="2" align="left">服务端地址:</td>
</tr>
<tr>
   <td><input type="text" name="uc_config[ucapi]" value="<?php echo $this->_tpl_vars['uc_config']['ucapi']; ?>
" /></td>
   <td>在您 UCenter地址或者目录改变的情况下，修改此项，一般情况请不要改动<br />
				  例如: http://www.site.com/ucenter (最后不要加'/')。</td>
</tr>
<tr>
    <td colspan="2" align="left">服务端 IP:</td>
</tr>
<tr>
    <td><input type="text" name="uc_config[ucip]" value="<?php echo $this->_tpl_vars['uc_config']['ucip']; ?>
" /></td>
    <td>正常情况下留空即可。如果由于域名解析问题导致 UCenter 与该应用通信失败，请尝试设置为该应用所在服务器的 IP 地址。</td>
 </tr>
<tr>
    <td colspan="2" align="left">通信密钥:</td>
 </tr>
 <tr>
    <td><input type="text" name="uc_config[uckey]" size="30" value="<?php echo $this->_tpl_vars['uc_config']['uckey']; ?>
" /></td>
    <td>只允许使用英文字母及数字，限 64 字节。应用端的通信密钥必须与此设置保持一致，否则该应用将无法与 UCenter 正常通信</td>
</tr>
<tr>
    <td colspan="2" align="left">连接方式:</td>
</tr>
    <tr>
        <td>      
        <select name="uc_config[connect]" onChange="if(this.value==''){document.getElementById('ucmysql').style.display = 'none';}else{document.getElementById('ucmysql').style.display = '';}">
			<option value="mysql" <?php if ($this->_tpl_vars['uc_config']['connect'] == 'mysql'): ?>selected="selected"<?php endif; ?>> 数据库方式(MySQL) </option>
			<option value="" <?php if ($this->_tpl_vars['uc_config']['connect'] == ''): ?>selected="selected"<?php endif; ?>> 接口方式(fsockopen) </option>
        </select>
        </td>
        <td>请根据您的服务器网络环境选择适当的连接方式</td>
    </tr>

    <tr>
        <td colspan="2">
            <table id="ucmysql" >
                <tr>
                    <td colspan="2" align="left">数据库服务器:</td>
                </tr>
                <tr>
                    <td width="225">
                    <input type="text" class="txt" name="uc_config[dbhost]" value="<?php echo $this->_tpl_vars['uc_config']['dbhost']; ?>
" />				
                    </td>
                    <td>默认:localhost, 如果 MySQL 端口不是默认的 3306，请填写如下形式：127.0.0.1:端口号</td>
                </tr>
                
                <tr>
                    <td colspan="2" align="left">数据库用户名:</td>
                </tr>
                <tr>
                    <td>
                    <input type="text" class="txt" name="uc_config[dbuser]" value="<?php echo $this->_tpl_vars['uc_config']['dbuser']; ?>
" />	
                    </td>
                    <td>登录uc服务端的数据库用户名</td>		
                </tr>
                
                <tr>
                    <td colspan="2" align="left">数据库密码:</td>
                </tr>                
                <tr>
                    <td>
                    <input type="text" name="uc_config[dbpass]" value="********" />	
                    </td>
                    <td>登录uc服务端数据库使用的密码</td>		
                </tr>
                
                <tr>
                    <td colspan="2" align="left">数据库名:</td>
                </tr>                
                <tr>
                    <td>
                    <input type="text" class="txt" name="uc_config[dbname]" value="<?php echo $this->_tpl_vars['uc_config']['dbname']; ?>
" />	
                    </td>
                    <td>uc服务端的数据库名称。</td>		
                </tr>
                
                <tr>
                    <td colspan="2" align="left">数据库表前缀:</td>
                </tr>                
                <tr>
                    <td>
                    <input type="text" class="txt" name="uc_config[dbtablepre]" value="<?php echo $this->_tpl_vars['uc_config']['dbtablepre']; ?>
" />	
                    </td>
                    <td>uc服务端使用的数据库表前缀</td>		
                </tr>
            </table>            
        </td>
    </tr>    
    
    <tr>
        <td colspan="2" align="left">正确的配置信息:</td>
    </tr>
    <tr>
        <td>
            <textarea cols="20" rows="4" onFocus="tdis.select()">
define('UC_CONNECT', '<?php echo $this->_tpl_vars['uc_config']['connect']; ?>
');
define('UC_DBHOST', '<?php echo $this->_tpl_vars['uc_config']['dbhost']; ?>
');
define('UC_DBUSER', '<?php echo $this->_tpl_vars['uc_config']['dbuser']; ?>
');
define('UC_DBPW', '********');
define('UC_DBNAME', '<?php echo $this->_tpl_vars['uc_config']['dbname']; ?>
');
define('UC_DBCHARSET', 'gbk');
define('UC_DBTABLEPRE', '`<?php echo $this->_tpl_vars['uc_config']['dbname']; ?>
`.<?php echo $this->_tpl_vars['uc_config']['dbtablepre']; ?>
');
define('UC_DBCONNECT', '0');
define('UC_KEY', '<?php echo $this->_tpl_vars['uc_config']['uckey']; ?>
');
define('UC_API', '<?php echo $this->_tpl_vars['uc_config']['ucapi']; ?>
');
define('UC_CHARSET', 'gbk');
define('UC_IP', '<?php echo $this->_tpl_vars['uc_config']['ucip']; ?>
');
define('UC_APPID', '<?php echo $this->_tpl_vars['uc_config']['appid']; ?>
');
define('UC_PPP', '20');
            </textarea>
        </td>
        <td>当应用的 UCenter 配置信息丢失时可复制左侧的代码到应用的配置文件中</td>
    </tr>
	<tr>
    <td colspan="2" align="center">
      <input type="submit" value="提交" class="button" />
      <input type="reset" value="重置" class="button" />
      <input type="hidden" name="act" value="edit" />
   </td>
  </tr>
</table>
	<?php endif; ?>
</form>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>