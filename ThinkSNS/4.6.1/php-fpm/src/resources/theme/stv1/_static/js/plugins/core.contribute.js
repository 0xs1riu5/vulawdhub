/**
 * 投稿操作Js插件
 * @example
 * 工厂模式调用：core.plugInit('contribute', $(this))；其中$(this)为可编辑域对象
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
core.contribute = {
	/**
	 * 给工厂调用的借口
	 * @param object attrs 配置数据对象
	 * @return void
	 * @private
	 */
	_init: function(attrs)
	{
		if(attrs.length == 1) {
			return false;
		}
		if(attrs.length == 3) {
			core.contribute.init(attrs[1], attrs[2]);
		} else {
			core.contribute.init(attrs[1]);
		}
	},
	/**
	 * 插件初始化
	 * @param object textarea 可编辑域对象
	 * @param object clickObj 点击对象
	 * @return void
	 */
	init: function(textarea, clickObj)
	{
		this.textarea = textarea;
		this.clickObj = clickObj;
		this.cData = [];
		if($('#contribute').val() != '') {
			this.cData = $('#contribute').val().split(',');
		}
		// 插入浮动框
		this.insertTemplates();
		// 设置值
		var _this = this;
		$('.check_category').live('click', function() {
			_this.cData = [];
			$('input:checked').filter('.check_category').each(function() {
				_this.cData.push(this.value);
			});
		});
		// 点击窗口消失
		$('body').bind('click', function(event) {
			var obj = "undefined" != typeof(event.srcElement) ? event.srcElement : event.target;
			if($(obj).parents('div[id="contribute_templates"]').get(0) == undefined){
				_this.closeBox();
			}
		});
	},
	/**
	 * 投稿浮动窗口模板
	 * @return void
	 */
	insertTemplates: function()
	{
		var _this = this;
		if(!this.isExistBox()) {
			$.post(U('channel/Index/getCategoryData'), {}, function(msg) {
				if(msg.status == 1) {
					var html = '<div id="contribute_templates" class="talkPop alL" style="*padding-top:20px;">\
							 <div class="wrap-layer">\
							 <div class="arrow arrow-t"></div>\
							 <div class="talkPop_box">\
							 <div class="close hd"><a title="关闭" href="javascript:;" onclick="core.contribute.closeBox()" class="ico-close">&nbsp;</a><span>选择频道</span></div>\
							 <div class="contribute_box"><ul class="clearfix">';
					// 获取选中值
					var cData = $('#contribute').val().split(',');
					for(var i = 0; i < msg.data.length; i++) {
						var checkVal = '';
						for(var j = 0; j < cData.length; j++) {
							if(cData[j] == msg.data[i].channel_category_id) {
								checkVal = 'checked="checked"';
							}
						}
						html += '<li><input class="check_category" id="ck'+msg.data[i].channel_category_id+'" type="checkbox" '+checkVal+' value="'+msg.data[i].channel_category_id+'" /><label for="ck'+msg.data[i].channel_category_id+'">'+msg.data[i].title+'</label></li>';
					}
					html +=	'</ul><a class="btn-green-small right" onclick="core.contribute.clickBtn()"><span>确定</span></a></div></div></div></div>';
					$('body').append(html);

					var pos = $(_this.clickObj).offset();
					$('#contribute_templates').css({top:(pos.top+5)+"px",left:(pos.left-5)+"px","z-index":1001});
				} else {
					ui.error('获取数据失败');
					return false;
				}
			}, 'json');
		}
	},
	/**
	 * 点击保存按钮后操作
	 * @return void
	 */
	clickBtn: function()
	{
		$('#contribute').val('');
		if(this.cData.length != 0) {
			$('#contribute').val(this.cData.join(','));
			var contributeObj = M.nodes.events['insert_contribute'];
			for(var i in contributeObj) {
				$(contributeObj[i]).html('<i class="contribute ico-contribute-h"></i>已投稿');
			}
		} else {
			var contributeObj = M.nodes.events['insert_contribute'];
			for(var i in contributeObj) {
				$(contributeObj[i]).html('<i class="contribute"></i>投稿');
			}
		}
		this.closeBox();
		return false;
	},
	/**
	 * 关闭窗口
	 * @return void
	 */
	closeBox: function()
	{
		if(this.isExistBox()) {
			$('#contribute_templates').remove();
		}
		return false;
	},
	/**
	 * 窗口是否存在
	 * @return boolean 是否存在窗口
	 */
	isExistBox: function()
	{
		var result = document.getElementById('contribute_templates') === null ? false : true;
		return result;
	},
	/**
	 * 重置投稿信息
	 * @return void
	 */
	resetBtn: function()
	{
		$('#contribute').val('');
		var contributeObj = M.nodes.events['insert_contribute'];
		for(var i in contributeObj) {
			$(contributeObj[i]).html('<i class="contribute"></i>投稿');
		}
		return false;
	}
};