group_info = function(){};
group_info.prototype = {
	$input_tags:'',
	init:function()
	{
		this.$input_tags = $('input[name="tags"]');
	},
	text_length:function(o, length)
	{
		$o = $(o);
		if (getLength($o.val()) > length) {
			wap_error('不能超过' + length + '个字');
		} 
	},
	add_tag:function(e)
	{
		var tag = $(e).html().replace(/\s/g, '');
		var tags = this.$input_tags.val();
		if (tags.indexOf(tag) == -1) {
			this.$input_tags.val((tags?(tags.replace(/,$/g, '') + ','):'') + tag);
			this.tag_num();
		}
	},
	tag_num:function()
	{
		var tags	= this.$input_tags.val().split(',');
		var tag_num = tags.length;
		var $tag_change = $('#tags_change');
		var i;
		var _tag_num;
		for (i = 0, _tag_num = 0; i < tag_num; i++) {
			if (tags[i] != '') {
				_tag_num++;
			}
		}
		if (_tag_num > 5) {
			wap_error('添加标签最多可设置5个');
			this.$input_tags.focus();
		} else {
			$tag_change.html('');
		}
		return _tag_num;
	},
	change_verify:function()
	{
	    var date = new Date();
	    var ttime = date.getTime();
	    //var url = U('home/Public/verify');
	    var url = SITE_URL+'/public/captcha.php';
		$('#verifyimg').attr('src',url+'?'+ttime);
	},
	check_form:function()
	{
		var name = $("#groupAdd_name").val();
		var intro = $("#groupAdd_intro").val();
		var verify = $("#verCode").val();
		
		if (getLength(name) == 0) {
			wap_error("微吧名称不能为空");
			v_form.name.focus();
			return false;
		} else if (getLength(name) > 30) {
			wap_error("微吧名称不能超过30个字");
			v_form.name.focus();
			return false;
		} else if (getLength(intro) > 200) {
			wap_error("微吧简介不能超过200个字");
			v_form.intro.focus();
			return false;
		} else if (verify == '') {
			wap_error('请输入验证码');
			return false;
		}
		$.post(U('group/Index/code'),{verify:verify},function(data){	 	
	    	if(data == 0){
				wap_error("验证码错误，请重新输入");
				return false;	
            }
		});
		return true;	
	}
};
group_info = new group_info();