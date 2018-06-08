HD.util.showTextarea = function(id, cmd, height, tip,text){
	var title = '插入'+HD.lang[cmd];
	//默认内连分隔符
	var innerlink_default_fig = {"空格":"&nbsp;&nbsp;","逗号":"&nbsp;,&nbsp;","分号":"&nbsp;;&nbsp;","顿号":"&nbsp;、&nbsp;","回车":"<br/>","单竖线":"&nbsp;&nbsp;|&nbsp;&nbsp;"};
	if(typeof innerlink_fig=="object"){
		innerlink_default_fig = innerlink_fig;
	}
	if (title.indexOf(' '>0)){
		title = title.split(' ')[0];
	}
	var html ='';
	if (tip) html += '<li><div style="width:300px">'+tip+'</div></li>';
	html += '<li><textarea id="hd_input_'+cmd+'" style="width:98%;border: 1px solid #AAAAAA;padding:3px;">'+(typeof text!="undefined"?text:"")+'</textarea></li>';
	html += '<li>选择分隔符：<select name="innerlink_fig">';
	for(var key in innerlink_default_fig){
		html+= '<option value="'+innerlink_default_fig[key]+'">'+key+'</option>';
	}
	html += '</select></li>';
	html += '<li style="min-height:30px;"><div id="error_'+cmd+'" style="color:red;text-align:left;"></div></li>';
	var dialog = new HD.dialog2({
		id : id,
		cmd:cmd,
		content : html,
		width : 350,
		height : height>0?height:200,
		title : title,
		yesButton : HD.lang['yes'],
		noButton : HD.lang['close']
	});
	dialog.show();
	HD.iframeClickTag = '';
	return true;
};

HD.plugin['innerlink'] = {
	click : function(id,text) {
		var obj = HD.g[id].toolbarIcon['innerlink'];
		if (!obj ||obj.className.indexOf('disabled') > -1){
			return false;
		}
		var html = HD.util.getSelectedText(id,'html');
		if (html && /<\/table>|hdwiki_tmml/i.test(html)){
			this.showBox(id,html);
			return this.showError('当前的选择范围当中包含表格或标题，不能转为内部链接！');
		}
		var text=text || HD.util.getSelectedText(id);
		var el = HD.util.getParentElement(id);
		this.checkClick(id,text,el);
		HD.iframeClickTag = '';
	},
	exec : function(id) {
		var text = HD.$('hd_input_innerlink').value;
		text = text.replace(/<.*?>/g, '');
		//HD.util.focus(id);
		if (text != ''){
			this.checkClick(id,text);
		}else{
			this.showError('内链接内容不能为空！');
		}
		return false;
    },
	/*
	id：模块id
	text：内连接内容字符串
	el：区分是否是在页面上设置的，为空是代表弹出框设置
	*/
	checkClick : function(id,text,el){
		var url, tag;
		if(el){
			tag =el.nodeName;
		}
		if('' != text){
			var text = HD.util.trim(text);
			if (/[\*#%~><\/\\]/i.test(text)){
				this.showBox(id,text);
				return this.showError('内连接内容不能包含空格或特殊符号"% * ～ < > # \ / +［］【】"等！');
			}else if(text.replace(/[\;\s]+/g,"").length==0){
				this.showBox(id,text);
				return this.showError('内链接内容不能为空！');
			}else{
				if(el && HD.util.inArray(el.className,['hdwiki_tmml','hdwiki_tmmll','img'])){
					this.showBox(id,text);
					return this.showError('当前选中的内容不能添加为内部链接！');
				}
				text = text.replace(/\s+/g, '');
				HD.util.focus(id);
				HD.util.insertHtml(id, this.getLinkHtml(text));
				HD.layout.hide(id);
				HD.toolbar.disable(id, ['save','preview','cut','copy','paste','source','bold','fontstyle','innerlink']);
			}
		}else if(el){
			pel = el.parentNode;
			if ((tag == 'STRONG' || tag == 'B') && pel.nodeName == 'A'){
				tag = 'A';
			}
			if('A' == tag){
				HD.shortcutMenu.a_unlink();
			}else{
				this.showBox(text);
			}
		}
	},
	/*
	获取html字符串
	text：以分号分隔的字符串
	*/
	getLinkHtml : function(text){
		var str = "";
		var url = 'index.php?doc-innerlink-';
		//默认分隔符为空格
		var default_fig = " ";
		if($("select[name='innerlink_fig']").length>0){
			default_fig = $("select[name='innerlink_fig']").val();
		}else if(typeof innerlink_fig=="object"){
			for(var key in innerlink_fig){
				default_fig = innerlink_fig[key];
				break;
			}
		}
		var items = text.split(";");
		for(var i= 0 ; i< items.length ; i++){
			var item = items[i];
			if(item&&item!=' '){
				str +='<a class="innerlink" title="'+item+'" href="'+url + encodeURI(item)+'">'+item+'</a>';
				if(items[i+1]){
					str +=default_fig;
				}
			}
		}
		return str;
	},
	/*
	弹出对话框，如果对话框已经存在则不弹出
	id:该插件的id
	text:选中的内容，如果没有则为空
	*/
	showBox : function(id,text){
		if(!$("#hd-dialog").length){
			HD.util.showTextarea(id,'innerlink', 450, '一个外链最多40个字符，一个汉字算两个字符，一次可输入多个！[<span style="color:red;font-size:11px;">多个用英文分号(;)分割</span>]',text);
			$("#hd_input_innerlink").click(function(){
				$("#error_innerlink").hide();
			});
		}
	},
	/*
	显示错误信息
	*/
	showError : function(message){
		$("#error_innerlink").html(message).show();
	}
};