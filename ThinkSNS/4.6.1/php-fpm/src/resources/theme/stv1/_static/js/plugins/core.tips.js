// 顶操作模型
core.tips = {	
			_init: function(attrs) {
				return false;
			},
			dotip:function(obj,args){
				if('undefined' == typeof(obj) || 'undefined' == typeof(args.sid) 
						|| 'undefined' == typeof(args.stable) || 'undefined' == typeof(args.type) 
						|| 'undefined' == typeof(args.text) || 'undefined' == typeof(args.count) 
						|| 'undefined' == typeof(args.uid)) 
				{
					ui.error( L('PUBLIC_TIPES_ERROR') );
					return false;
				}
				var _obj = obj;
				$.post(U('widget/Tips/doExec'), {sid:args.sid, stable:args.stable, type:args.type, uid:args.uid}, function(msg) {
					if(msg == 1) {
						var nums = args.count;
						nums = parseInt(nums) + 1;
						var html = args.text + '(' + nums + ')';
						$(_obj).replaceWith('<span style="color:#BBBBBB;cursor:default;">' + html + '</span>');
						M.removeListener(_obj);
						ui.success( L('PUBLIC_ADMIN_OPRETING_SUCCESS') );
						setTimeout(args.callback + '(' + args.sid + ')', '1500');
						return false;
					} else if(msg == 2) {
						ui.error( L('PUBLIC_ADMIN_OPRETING_TIPES') );
						return false;
					} else {
						ui.error( L('PUBLIC_ADMIN_OPRETING_ERROR') );
						return false;
					}
				});
			}
		};