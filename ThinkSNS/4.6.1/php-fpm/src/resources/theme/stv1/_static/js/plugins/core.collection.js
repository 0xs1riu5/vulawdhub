/**
 * 收藏模型Js核心插件
 * @author jason <yangjs17@yeah.net> 
 * @version TS3.0
 */
core.collection = {	
	// 初始化参数
	_init: function(attrs) {
		// 转化为数组
		attrs = $.makeArray(attrs);
		if(typeof attrs[6] == 'undefined') {
			attrs.push(0);
		}
		if(attrs.length == 7) {
			core.collection.init(attrs[1],attrs[2],attrs[3],attrs[4],attrs[5],attrs[6]);
		} else {
			return false;
		}
	},
	init: function(obj, type, sid, stable, sapp, isIco) {
		// 未登录弹出弹出层
		if(MID == 0){
			ui.quicklogin();
			return;
		}
		// 参数验证
		if('undefined'==typeof(obj) || 'undefined'==typeof(sid) || 'undefined'==typeof(stable) || 'undefined'==typeof(sapp) ) {
			ui.error(L('PUBLIC_TIPES_ERROR'));
			return false;
		}
		// 添加收藏操作
		if($(obj).attr('rel') == 'add') {
			$.post(U('widget/Collection/addColl'), {sid:sid, stable:stable, sapp:sapp}, function(msg) {
				if(msg.status == 0) {
					ui.error(msg.data);
				} else {
					// 设置对象操作属性
					$(obj).attr('rel', 'remove');
					
					if($('.count_' + stable + '_' + sid).length > 0) {
						if(isIco == 1) {
							$(obj).find('i').eq(0).addClass('current');
						} else {
							$(obj).html(L('PUBLIC_FAVORITED'));
						}
						var nums = $('.count_' + stable + '_' + sid).html();
						$('.count_' + stable + '_' + sid).html(parseInt(nums) + 1);
					} else {
						$(obj).html(L('PUBLIC_DEL_FAVORITE'));
					}
					updateUserData('favorite_count', 1);
					ui.success(L('PUBLIC_FAVORITE_SUCCESS'));
				}
			}, 'json');
			return false;
		}
		// 删除收藏操作
		if($(obj).attr('rel') == 'remove') {
			$.post(U('widget/Collection/delColl'),{sid:sid,stable:stable},function(msg){
				if(msg.status == 1){	
					updateUserData('favorite_count',-1);
					if(type !='collection'){
						$(obj).attr('rel','add');
						if(isIco == 1) {
							$(obj).find('i').eq(0).removeClass('current');
						} else {
							$(obj).html(L('PUBLIC_FAVORITE'));
						}
						if($('.count_'+stable+'_'+sid).length >0 ){
							var nums = 	$('.count_'+stable+'_'+sid).html();
							$('.count_'+stable+'_'+sid).html(parseInt(nums)-1);
						}
					}else{
						$('#feed'+sid).fadeOut('slow');
					}
				}else{
					ui.error(msg.data);
				}
			},'json');
			return false;
		}
	}
};