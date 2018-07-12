/**
 * 注册流程使用
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
(function() {
// 获取关联用户信息
var oRelatedUser = {
	nNumPerPage: 21,
	nTotalItems: 0,
	nTotalPages: 0,
	nCurrentPage: 0,
	aUserData: [],
	oCheckedUids: {},
	dChangeBtn: null,
	dUserList: null,
	fInit: function(dChangeBtn, dUserList) {
		this.dChangeBtn = dChangeBtn;
		this.dUserList = dUserList;
		this._fGetUserData(this.dChangeBtn.href);
	},
	fChange: function() {
		this.nCurrentPage = this.nCurrentPage >= this.nTotalPages ? 1 : (this.nCurrentPage + 1);

		var aHtml = '';
		var nStart = (this.nCurrentPage - 1) * this.nNumPerPage;
		var nEnd = nStart + 24;
		if(nEnd > this.aUserData.length) {
			nEnd = this.aUserData.length;
		}

		for(var i = nStart; i < nEnd; i++) {
			var icoCls = "ico-ok-mark";
			if(typeof(this.oCheckedUids[this.aUserData[i].uid] === "undefined")) {
				icoCls = "ico-empty";
			} else {
				icoCls = "ico-ok-mark";
			}
			aHtml += '<li><div style="position:relative;width:80px;height:80px"><div class="selected"><i class="'+icoCls+'"></i></div>\
					  <a event-node="bulkDoFollowData" value="'+this.aUserData[i].uid+'" class="face_part" href="javascript:void(0);">\
					  <img src="'+this.aUserData[i].avatar_big+'" /></a></div><span class="name">'+this.aUserData[i].uname+'</span></li>';
		}

		this.dUserList.innerHTML = aHtml;
		M(this.dUserList);
		$('#select_all_follow').click();
		$('.selected').find('i').attr('class', 'ico-ok-mark');
		$('.face_part').each(function() {
			var v = $(this).attr('value');
			oRelatedUser.oCheckedUids[v] = v;
		});
	},
	_fGetUserData: function(url) {
		var oThis = this;
		$.get(url, {}, function(txt) {
			oThis._fDealUserData(txt.data);
		}, 'json');
	},
	_fDealUserData: function(oData) {
		var nL = oData.length;
		this.nTotalItems += nL;
		this.nTotalPages = Math.floor(this.nTotalItems / this.nNumPerPage);
		for(var i = 0; i < nL; i ++) {
			this.aUserData.push(oData[i]);
		}
		this.fChange();
	},
	saveFollow: function(node) {
		var _this = this; 
		var fids = [];
		var l;
		var url = node.getAttribute("href") || U('public/Register/bulkDoFollow');

		for(l in oRelatedUser.oCheckedUids) {
			if(oRelatedUser.oCheckedUids[l] != false) {
				fids.push(l);
			}
		}

		if(0 === fids.length) {
			ui.error('请选择要关注的对象');
			return false;
		}
		// 添加关注操作
		$.post(url, {fids:fids.join(",")}, function(txt) {
			var i;
			var num = 0;
			if(1 == txt.status) {
				for(i in txt.data) {
					(1 == txt.data[i]['following']) && num ++;
				}
				location.href = U('public/Register/doStep4');
			} else {
				ui.error(txt.info);
			}
		}, 'json');
	}
};
/*** 事件监听 ***/
// 块监听
M.addModelFns({
	// 获取相关用户列表
	related_user_list: {
		load: function() {
			$(this).find('li').each(function() {
				var dLi = this;
				$(this).click(function(){
					var dA = dLi.getElementsByTagName( "a" )[0];
					var dI = dLi.getElementsByTagName("i")[0];
					var sValue = dA.value || dA.getAttribute( "value" );

					if(dI.className == "ico-empty") {
						dA.value = sValue;
						dA.checked = true;
						dI.className = "ico-ok-mark";
						oRelatedUser.oCheckedUids[sValue] = sValue;
					} else {
						dA.checked = false;
						dI.className = "ico-empty";
						oRelatedUser.oCheckedUids[sValue] = false;
					}
				});
			});
		}
	}
}).addEventFns({
	// 重新发送激活邮件
	resend_activation_email: {
		click: function() {
			var url = this.href;
			$.get(url, {}, function(txt) {
				txt = eval("(" + txt + ")");
				if(txt.status) {
					ui.success( txt.info );
				} else {
					ui.error( txt.info );
				}
			});
			return false;
		}
	},
	// 更改激活邮件地址
	change_activation_email: {
		click: function() {
			if(inviteEmail.getIsValid()) {
				var url = $.trim($(this).attr('checkurl'));
				var email = inviteEmail.getValue();
				$.post(url, {email:email}, function(txt) {
					if(txt.status) {
						ui.success(txt.info);
						setTimeout("location.reload();", 1500);
					} else {
						ui.error(txt.info);
					}
					return false;
				}, 'json');	
			}
			return false;
		}
	},
	// 改变关联用户选择
	register_change_related_user: {
		click: function() {
			oRelatedUser.fChange();
			$(M.getEvents('selectAllFollow')[0]).attr('checked',false);
			$('#select_all_follow').attr('checked',true);
			return false;
		},
		load: function() {
			oRelatedUser.fInit(this, M.getModels("related_recommend_list")[0]);
		}
	},
	// 保存关注用户操作
	saveFollow: {
		click: function() {
			var _this = this;
			oRelatedUser.saveFollow(this);
			return false;
		}
	},
	// 全选关组用户操作
	selectAllFollow: {
		click: function() {
			var keyword = $(this).attr('keyword');
			if($(this).attr('checked')) {
				// 全选
				$('#'+keyword).find('i').attr('class', 'ico-ok-mark');
				$('.face_part_'+keyword).each(function() {
					var v = $(this).attr('value');
					oRelatedUser.oCheckedUids[v] = v;
				});
			} else {
				// 取消
				$('#'+keyword).find('i').attr('class', 'ico-empty');
				$('.face_part_'+keyword).each(function() {
					var v = $(this).attr('value');
					oRelatedUser.oCheckedUids[v] = false;
				});
			}
		}	
	},
	//换一换
	change_related_user: {
		click: function() {
			var keywords = $(this).attr('keywords');
			$.post(this.href,{type:this.type},function(data){
				$('#'+keywords).html(data);
				$('#'+keywords).find('li').each(function() {
					var dLi = this;
					$(this).click(function(){
						var dA = dLi.getElementsByTagName( "a" )[0];
						var dI = dLi.getElementsByTagName("i")[0];
						var sValue = dA.value || dA.getAttribute( "value" );

						if(dI.className == "ico-empty") {
							dA.value = sValue;
							dA.checked = true;
							dI.className = "ico-ok-mark";
							oRelatedUser.oCheckedUids[sValue] = sValue;
						} else {
							dA.checked = false;
							dI.className = "ico-empty";
							oRelatedUser.oCheckedUids[sValue] = false;
						}
					});
				});
				$('#select_'+keywords+'_follow').attr('checked', true);
				oRelatedUser.oCheckedUids = {};
				$('.face_part').each(function() {
					var v = $(this).attr('value');
					//alert($(this).prev().find('i').attr('class'));
					if($(this).prev().find('i').attr('class') == 'ico-ok-mark'){
						oRelatedUser.oCheckedUids[v] = v;
					}
				});
			});
			return false;
		},
		load: function() {
			$('.selected').find('i').attr('class', 'ico-ok-mark');
			$('.s-ck').attr('checked', true);
			$('.face_part').each(function() {
				var type = $(this.parentModel).attr('id');
				var v = $(this).attr('value');
				oRelatedUser.oCheckedUids[v] = v;
			});
		}
	}
});
	//全选的实现
	$(".checkAll").click(function(){
    	$(this).parents(".rect").find(".checkbox").prop("checked", this.checked);
	});
	$(".checkbox").click(function(){
	  var option = $(".checkbox");
	  option.each(function(i){
		  if(!this.checked){
			  $(".check-all").prop("checked", false);
			  return false;
		  }else{
			  $(".check-all").prop("checked", true);
		  }
	  });
	});
})();