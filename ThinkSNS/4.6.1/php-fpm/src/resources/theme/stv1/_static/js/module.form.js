/**
 * 异步提交表单
 * @param object form 表单DOM对象
 * @return void
 */
var ajaxSubmit = function(form) {
	var args = M.getModelArgs(form);
	M.getJS(THEME_URL + '/js/jquery.form.js', function() {
        var options = {
        	dataType: "json",
            success: function(txt) {
        		if(1 == txt.status) {
        			if("function" ===  typeof form.callback) {
        				form.callback(txt);
        			} else {
        				if("string" == typeof(args.callback)) {
        					eval(args.callback+'()');
        				} else {
        					ui.success(txt.info);
        				}
        			}
        		} else {
        			ui.error(txt.info);
        		}
            }
        };
        $(form).ajaxSubmit(options);
	});
};

(function(){
// 是否点击了发送按钮
// 块状模型监听
M.addModelFns({
	account_save:{
		callback:function(){
			ui.success(L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
			setTimeout(function() {
				location.href = location.href;
			}, 1500);
		}
	},
	verify_apply:{
		callback:function(){
			ui.success('申请成功，请等待审核');
			setTimeout(function() {
				location.href = U('public/Account/authenticate');
			}, 1500);
		}
	},
	// 普通表单发送验证
	normal_form: {
		submit: function() {
			window.tips.setSubmit(1);
			var oCollection = this.elements;
			var nL = oCollection.length;
			var bValid = true;
			var dFirstError;

			for(var i = 0; i < nL; i++) {
				var dInput = oCollection[i];
				var sName = dInput.name;
				// 如果没有事件节点，则直接略过检查
				if(!sName || !dInput.getAttribute("event-node")) {
					continue;
				}

				("function" === typeof(dInput.onblur)) && dInput.onblur();

				if(!dInput.bIsValid) {
					bValid = false;
					if(dInput.type != 'hidden') {
						dFirstError = dFirstError || dInput;
					}
				}
			}

			dFirstError && dFirstError.focus();

			return bValid;
		}
	}
});
// 事件模型监听
M.addEventFns({
	// 文本框输入文本验证
	input_text: {
		focus: function() {
			this.className='s-txt-focus';
			return false;
		},
		blur: function() {
			this.className = 's-txt';
			// 设置文本框的最大与最小输入限制
			var oArgs = M.getEventArgs( this );
			var	min = oArgs.min ? parseInt( oArgs.min ) : 0;
			var	max = oArgs.max ? parseInt( oArgs.max ) : 0;
			// 最大和最小长度均小于或等于0，则不进行长度验证
			if(min <= 0 && max <= 0) {
				return false;
			}

			var dTips = (this.parentModel.childEvents[this.getAttribute( "name" ) + "_tips"] || [])[0];
			var sValue = this.value;
			sValue = sValue.replace(/(^\s*)|(\s*$)/g, "");	
			var nL = sValue.replace(/[^\x00-\xff]/ig,'xx').length / 2;

			if(nL <= min-1 || ( max && nL > max)) {
				dTips && (dTips.style.display = "none");
				tips.error(this, oArgs.error);
				this.bIsValid = false;
			} else {
				tips.success(this);
				dTips && (dTips.style.display = "");
				this.bIsValid = true;
			}
			return false;
		},
		load: function() {
			this.className='s-txt';
		}
	},
	// 文本框输入纯数字文本验证
	input_nums: {
		focus: function() {
			this.className = 's-txt-focus';
			return false;
		},
		blur: function() {
			this.className = 's-txt';
			// 设置文本框的最大与最小输入限制
			var oArgs = M.getEventArgs(this);
			var min = oArgs.min ? parseInt( oArgs.min ) : 0;
			var max = oArgs.max ? parseInt( oArgs.max ) : 0;
			// 最大和最小长度均小于或等于0，则不进行长度验证
			if(min <= 0 && max <= 0) {
				return false;
			}

			var dTips = (this.parentModel.childEvents[this.getAttribute( "name" ) + "_tips"] || [])[0];
			var sValue = this.value;

			// 纯数字验证
			var re = /^[0-9]*$/;
			if(!re.test(sValue)) {
				dTips && (dTips.style.display = "none");
				tips.error(this, L('PUBLIC_TYPE_ISNOT'));		// 格式不正确
				this.bIsValid = false;
				return false;
			}

			sValue = sValue.replace(/(^\s*)|(\s*$)/g, "");	
			var nL = sValue.replace(/[^\x00-\xff]/ig, 'xx').length / 2;

			if(nL <= min-1 || (max && nL > max)) {
				dTips && (dTips.style.display = "none");
				tips.error(this, oArgs.error);
				this.bIsValid = false;
			} else {
				tips.success(this);
				dTips && (dTips.style.display = "");
				this.bIsValid = true;
			}

			return false;
		},
		load: function() {
			this.className = 's-txt';
		}
	},
	// 文本域验证
	textarea: {
		focus: function() {
			this.className = 's-textarea-focus';
		},
		blur: function() {
			this.className = 's-textarea';
			// 设置文本框的最大与最小输入限制
			var oArgs = M.getEventArgs(this);
			var min = oArgs.min ? parseInt( oArgs.min ) : 0;
			var max = oArgs.max ? parseInt( oArgs.max ) : 0;
			// 最大和最小长度均小于或等于0，则不进行长度验证
			if(min <= 0 && max <= 0) {
				return false;
			}

			if($.trim(this.value)) {
				tips.success(this);
				this.bIsValid = true;
			} else {
				if("undefined" != typeof(oArgs.error )) {
					tips.error(this, oArgs.error);
					this.bIsValid = false;
				}
			}
		},
		load: function() {
			this.className = 's-textarea';
		}
	},
	// 部门信息验证
	input_department: {
		blur: function() {
			var sValue = this.value;
			sValue && (sValue = parseInt(sValue));

			var dLastEmlement = this.nextSibling;
			(1 !== dLastEmlement.nodeType) && (dLastEmlement = dLastEmlement.nextSibling);
			if(sValue) {
				tips.success(dLastEmlement);
				this.bIsValid = true;
			} else {
				tips.error(dLastEmlement, L('PUBLIC_SELECT_DEPARTMENT'));
				this.bIsValid = false;
			}
		},
		load:function(){
			if("undefined" != typeof(core.department)) {
				delete core.department;
			}
			core.plugInit('department', $(this), $(this));
		}
	},
	input_verify: {
		focus: function() {
			this.className='s-txt-focus';
			return false;
		},
		blur: function() {
			this.className='s-txt';

			var dVerify = this;
			var sUrl = dVerify.getAttribute('checkurl');
			var sValue = dVerify.value;
			var oArgs = M.getEventArgs(dVerify);

			if(!sUrl || (this.dSuggest && this.dSuggest.isEnter)) return;

			$.post(sUrl, {verify:sValue}, function(oTxt) {
				if(oTxt.status) {
					'false' == oArgs.success ? tips.clear(dVerify) : tips.success(dVerify);
					dVerify.bIsValid = true;
				} else {
					'false' == oArgs.error ? tips.clear(dVerify) : tips.error(dVerify, oTxt.info);
					dVerify.bIsValid = false;
				}
				return true;
			}, 'json');
			$(this.dTips).hide();
		},
		load: function() {
			this.className='s-txt';
		}
	},
	// 地区信息验证
	input_area: {
		blur: function() {
			// 获取数据
			var sValue = $.trim(this.value);
			var sValueArr = sValue.split(",");
			sValueArr[0] == 0 && sValueArr[1] == 0 && sValueArr[2] == 0 && (sValue = '');
			// 验证数据正确性
			if(sValue == "" || sValueArr[0] == 0) {
				tips.error(this, "请选择地区");
				this.bIsValid = false;
				// this.value = '0,0,0';
			} else if(sValueArr[1] == 0 || sValueArr[2] == 0) {
				tips.error(this, "请选择完整地区信息");
				this.bIsValid = false;
			} else {
				tips.success(this);
				this.bIsValid = true;
			}
		},
		load: function() {
			// 获取参数信息
			var _this = this;
			// 验证数据正确性
			setInterval(function() {
				// 获取数据
				var sValue = $.trim(_this.value);
				var sValueArr = sValue.split(",");
				sValueArr[0] == 0 && sValueArr[1] == 0 && sValueArr[2] == 0 && (sValue = '');
				// 验证数据正确性
				if(sValue == "" || sValueArr[0] == 0) {
					tips.error(_this, "请选择地区");
					_this.bIsValid = false;
				} else if(sValueArr[1] == 0 || sValueArr[2] == 0) {
					tips.error(_this, "请选择完整地区信息");
					_this.bIsValid = false;
				} else {
					tips.success(_this);
					_this.bIsValid = true;
				}
			}, 200);
		}
	},
	input_user_tag: {
		blur: function() {
			var sValue = $.trim(this.value);
			if (sValue == '') {
				tips.error(this, '请选择标签信息');
				this.bIsValid = false;
			} else {
				tips.success(this);
				this.bIsValid = true;
			}
		},
		load: function() {
			var _this = this;
			setInterval(function() {
				var sValue = $.trim(_this.value);
				if (sValue == '') {
 					tips.error(_this, '请选择标签信息');
 					_this.bIsValid = false;
				} else {
					tips.success(_this);
					_this.bIsValid = true;
				}
			}, 200);
		}
	},
	input_face: {
		blur: function() {
			var sValue = $(this).attr('src');
			var name = sValue.split('/').pop();
			if (name == 'small.jpg') {
				tips.error(this, '用户头像必须上传');
				this.bIsValid = false;
			} else {
				tips.success(this);
				this.bIsValid = true;
			}
		},
		load: function() {
			var _this = this;
			setInterval(function() {
				var sValue = $(_this).attr('src');
				var name = sValue.split('/').pop();
				if (name == 'small.jpg') {
					tips.error(_this, '用户头像必须上传');
					_this.bIsValid = false;
				} else {
					tips.success(_this);
					_this.bIsValid = true;
				}
			}, 200);
		}
	},
	// 时间格式验证
	input_date: {
		focus: function() {
			this.className = 's-txt-focus';

			var dDate = this;
			var oArgs = M.getEventArgs(this);

			M.getJS(THEME_URL + '/js/rcalendar.js', function() {
				rcalendar(dDate, oArgs.mode);
			});
		},
		blur: function() {
			this.className = 's-txt';

			var dTips = (this.parentModel.childEvents[this.getAttribute( "name" ) + "_tips"] || [])[0];
			var oArgs = M.getEventArgs(this);
			if(oArgs.min == 0) {
				return true;
			}		
			var _this = this;	
			setTimeout(function() {
				sValue = _this.value;
				if(!sValue) {
					dTips && (dTips.style.display = "none");
					tips.error(_this, oArgs.error);
					this.bIsValid = false;
				} else {
					tips.success(_this);
					dTips && (dTips.style.display = "");
					_this.bIsValid = true;
				}
			}, 250);
		},
		load: function() {
			this.className = 's-txt';
		}
	},
	// 邮箱验证
	email: {
		focus: function() {
			this.className = 's-txt-focus';
			var x = $(this).offset();
			$(this.dTips).css({'position':'absolute','left':x.left+'px','top':x.top+$(this).height()+12+'px','width':$(this).width()+10+'px'});
		},
		blur: function() {
			this.className = 's-txt';

			var dEmail = this;
			var sUrl = dEmail.getAttribute("checkurl");
			var sValue = dEmail.value;

			if(!sUrl || (this.dSuggest && this.dSuggest.isEnter)) {
				return false;
			}

			$.post(sUrl, {email:sValue}, function(oTxt) {
				var oArgs = M.getEventArgs(dEmail);
				if(oTxt.status) {
					"false" == oArgs.success ? tips.clear( dEmail ) : tips.success( dEmail );
					dEmail.bIsValid = true;
				} else {
					"false" == oArgs.error ? tips.clear( dEmail ) : tips.error( dEmail, oTxt.info );
					dEmail.bIsValid = false;
				}
				return true;
			}, 'json');
			$(this.dTips).hide();
		},
		load: function() {
			this.className = 's-txt';

			var dEmail = this;
			var oArgs = M.getEventArgs(this);

			if(!oArgs.suffix) {
				return false;
			}

			var aSuffix = oArgs.suffix.split( "," );
			var dFrag = document.createDocumentFragment();
			var dTips = document.createElement( "div" );
			var dUl = document.createElement( "ul" );
			
			this.dTips = $(dTips);
		    $('body').append(this.dTips);

		    dTips.className = "mod-at-wrap";
			dDiv = dTips.appendChild(dTips.cloneNode(false));
			dDiv.className = "mod-at";
			dDiv = dDiv.appendChild(dTips.cloneNode(false));
			dDiv.className = "mod-at-list";
			dUl = dDiv.appendChild(dUl);
			dUl.className = "at-user-list";
			dTips.style.display = "none";
			dEmail.parentNode.appendChild(dFrag);

			M.addListener(dTips, {
				mouseenter: function() {
					this.isEnter = 1;
				},
				mouseleave: function() {
					this.isEnter = 0;
				}
			});

			// 附加到Input DOM 上
			dEmail.dSuggest = dTips;

			setInterval(function() {
				var sValue = dEmail.value;
				var sTips = dEmail.dSuggest;
				if(dEmail.sCacheValue === sValue) {
					return false;
				} else {
					// 缓存值
					dEmail.sCacheValue = sValue;
				}
				// 空值判断
				if(!sValue) {
					dTips.style.display = "none";
					return ;
				}
				var aValue = sValue.split("@");
				var dFrag = document.createDocumentFragment();
				var l = aSuffix.length;
				var sSuffix;

				sInputSuffix = ["@",aValue[1]].join(""); // 用户输入的邮箱的后缀

				for(var i = 0; i < l; i ++) {
					sSuffix = aSuffix[i];
					if(aValue[1] && ( "" != aValue[1] ) && (sSuffix.indexOf(aValue[1]) !== 1 ) || (sSuffix === sInputSuffix)) {
						continue;
					}
					var dLi = dLi ? dLi.cloneNode(false) : document.createElement("li");
					var dA = dA ? dA.cloneNode(false) : document.createElement("a");
					var dSpan = dSpan ? dSpan.cloneNode(false) : document.createElement("span");
					var dText = dText ? dText.cloneNode(false) : document.createTextNode("");

					dText.nodeValue = [aValue[0], sSuffix].join("");

					dSpan.appendChild(dText);

					dA.appendChild(dSpan);

					dLi.appendChild(dA);

					dLi.onclick = (function(dInput, sValue, sSuffix) {
						return function(e) {
							dInput.value = [ sValue, sSuffix ].join( "" );
							// 选择完毕，状态为离开选择下拉条
							dTips.isEnter = 0;
							// 自动验证
							dInput.onblur();
							return false;
						};
					})(dEmail, aValue[0], sSuffix);

					dFrag.appendChild(dLi);
				}
				if(dLi) {
					dUl.innerHTML = "";
					dUl.appendChild( dFrag );
					dTips.style.display = "";
					$(dUl).find('li').hover(function() {
						$(this).addClass('hover');
					},function() {
						$(this).removeClass('hover');
					});

				} else {
					dTips.style.display = "none";
				}
			}, 200);
		}
	},
	// 密码验证
	password: {
		focus: function() {
			this.className = 's-txt-focus';
		},
		blur: function() {
			this.className = 's-txt';
			var dWeight = this.parentModel.childModels["password_weight"][0];
			var sValue = this.value + "";
			var nL = sValue.length;
			var min = 6
			var max = 15;
			if ( nL < min ) {
				dWeight.style.display = "none";
				tips.error( this, L('PUBLIC_PASSWORD_TIPES_MIN',{'sum':min}));
				this.bIsValid = false;
			} else if ( nL > max ) {
				dWeight.style.display = "none";
				tips.error( this, L('PUBLIC_PASSWORD_TIPES_MAX',{'sum':max}) );
				this.bIsValid = false;
			} else {
				tips.clear( this );
				dWeight.style.display = "";
				this.bIsValid = true;
				var args = M.getEventArgs(this);
				if (typeof args.repeat === 'undefined') {
					args.repeat = 1;
				}
				args.repeat === 1 && this.parentModel.childEvents["repassword"][0].onblur();
			}
		},
		keyup:function(){
			this.value = this.value.replace(/^\s+|\s+$/g,""); 
		},
		load: function() {
			this.value = '';
			this.className='s-txt';

			var dPwd = this,
				dWeight = this.parentModel.childModels["password_weight"][0],
				aLevel = [ "psw-state-empty", "psw-state-poor", "psw-state-normal", "psw-state-strong" ];

			setInterval( function() {
				var sValue = dPwd.value;
				// 缓存值
				if ( dPwd.sCacheValue === sValue ) {
					return ;
				} else {
					dPwd.sCacheValue = sValue;
				}
				// 空值判断
				if ( ! sValue ) {
					dWeight.className = aLevel[0];
					dWeight.setAttribute('className',aLevel[0]);
					return ;
				}
				var nL = sValue.length;

				if ( nL < 6 ) {
					dWeight.className = aLevel[0];
					dWeight.setAttribute('className',aLevel[0]);
					return ;
				}

				var nLFactor = Math.floor( nL / 10 ) ? 1 : 0;
				var nMixFactor = 0;

				sValue.match( /[a-zA-Z]+/ ) && nMixFactor ++;
				sValue.match( /[0-9]+/ ) && nMixFactor ++;
				sValue.match( /[^a-zA-Z0-9]+/ ) && nMixFactor ++;
				nMixFactor > 1 && nMixFactor --;

				dWeight.className = aLevel[nLFactor + nMixFactor];
				dWeight.setAttribute('className',aLevel[nLFactor + nMixFactor]);

			}, 200 );
		}
	},
	repassword: {
		focus: function() {
			this.className='s-txt-focus';
		},
		keyup:function(){
			this.value = this.value.replace(/^\s+|\s+$/g,""); 
		},
		blur: function() {
			this.className='s-txt';

			var sPwd = this.parentModel.childEvents["password"][0].value,
				sRePwd = this.value;

			if ( ! sRePwd ) {
				tips.error( this, L('PUBLIC_PLEASE_PASSWORD_ON') );
				this.bIsValid = false;
			} else if ( sPwd !== sRePwd ) {
				tips.error( this, L('PUBLIC_PASSWORD_ISDUBLE_NOT') );
				this.bIsValid = false;
			} else {
				tips.success( this );
				this.bIsValid = true;
			}
		},
		load: function() {
			this.className='s-txt';
		}
	},
	// 昵称验证
	uname: {
		focus: function() {
			this.className='s-txt-focus';
			return false;
		},
		blur: function() {
			this.className='s-txt';

			var dUname = this;
			var sUrl = dUname.getAttribute('checkurl');
			var sValue = dUname.value;
			var oArgs = M.getEventArgs(dUname);
			var oValue = oArgs.old_name;

			if(!sUrl || (this.dSuggest && this.dSuggest.isEnter)) return;

			$.post(sUrl, {uname:sValue, old_name:oValue}, function(oTxt) {
				if(oTxt.status) {
					'false' == oArgs.success ? tips.clear(dUname) : tips.success(dUname);
					dUname.bIsValid = true;
				} else {
					'false' == oArgs.error ? tips.clear(dUname) : tips.error(dUname, oTxt.info);
					dUname.bIsValid = false;
				}
				return true;
			}, 'json');
			$(this.dTips).hide();
		},
		load: function() {
			this.className='s-txt';
		}
	},
	phone: {
		focus: function() {
			this.className = 's-txt-focus';
			return false;
		},
		blur: function() {
			this.className = 's-txt';

			var dPhone = this;
			var sUrl = dPhone.getAttribute('checkurl');
			var sValue = dPhone.value;
			var oArgs = M.getEventArgs(dPhone);

			if (!sUrl || (this.dSuggest && this.dSuggest.isEnter)) return;

			$.post(sUrl, {phone:sValue}, function(oTxt) {
				if (oTxt.status) {
					'false' == oArgs.success ? tips.clear(dPhone) : tips.success(dPhone);
					dPhone.bIsValid = true;
				} else {
					'false' == oArgs.error ? tips.clear(dPhone) : tips.error(dPhone, oTxt.info);
					dPhone.bIsValid = false;
				}
				return true;
			}, 'json');
			$(this.dTips).hide();
		},
		load: function() {
			this.className = 's-txt';
		}
	},
	input_reg_code: {
		load: function() {
			this.className = 's-txt';
		},
		focus: function() {
			this.className = 's-txt-focus';
			return false;
		},
		blur: function() {
			this.className = 's-txt';

			var dCode = this;
			var sUrl = dCode.getAttribute('checkurl');
			var sValue = dCode.value;
			var telValue = dCode.getAttribute('tel');
			var oArgs = M.getEventArgs(dCode);

			if (!sUrl || (this.dSuggest && this.dSuggest.isEnter)) return;

			$.post(sUrl, {regCode:sValue, phone:telValue}, function(oTxt) {
				if (oTxt.status) {
					'false' == oArgs.success ? tips.clear(dCode) : tips.success(dCode);
					dCode.bIsValid = true;
				} else {
					'false' == oArgs.error ? tips.clear(dCode) : tips.error(dCode, oTxt.info);
					dCode.bIsValid = false;
				}
				return true;
			}, 'json');
			$(this.dTips).hide();
		}
	},
	radio: {
		click: function() {
			this.onblur();
		},
		blur: function() {
			var sName  = this.name,
				oRadio = this.parentModel.elements["sex"],
				oArgs  = M.getEventArgs( oRadio[0] ),
				dRadio, nL = oRadio.length, bIsValid = false,
				dLastRadio = oRadio[nL - 1];

			for ( var i = 0; i < nL; i ++ ) {
				dRadio = oRadio[i];
				if ( dRadio.checked ) {
					bIsValid = true;
					break;
				}
			}

			if ( bIsValid ) {
				tips.clear( dLastRadio.parentNode );
			} else {
				tips.error( dLastRadio.parentNode, oArgs.error );
			}

			for ( var i = 0; i < nL; i ++ ) {
				oRadio[i].bIsValid = bIsValid;
			}
		}
	},
	checkbox: {
		click: function() {
			this.onblur();
		},
		blur: function() {
			var oArgs = M.getEventArgs( this );
			if ( this.checked ) {
				tips.clear( this.parentNode );
				this.bIsValid = true;
			} else {
				tips.error( this.parentNode, oArgs.error );
				this.bIsValid = false;
			}
		}
	},
	submit_btn: {
		click: function(){
			var args  = M.getEventArgs(this);
			if ( args.info && ! confirm( args.info )) {
				return false;
			}
			try{
				(function( node ) {
					var parent = node.parentNode;
					// 判断node 类型，防止意外循环
					if ( "FORM" === parent.nodeName ) {
						if ( "false" === args.ajax ) {
							( ( "function" !== typeof parent.onsubmit ) || ( false !== parent.onsubmit() ) ) && parent.submit();
						} else {
							ajaxSubmit( parent );
						}
					} else if ( 1 === parent.nodeType ) {
						arguments.callee( parent );
					}
				})(this);
			}catch(e){
				return true;
			}
			return false;
		}
	},
	sendBtn: {
		click: function() {
			var parent = this.parentModel;
			return false;
		}
	}
});
/**
 * 提示语Js对象
 */
var tips = {
	/**
	 * 初始化，正确与错误提示
	 * @param object D DOM对象
	 * @return void
	 */
	init: function(D) {
		this._initError(D);
		this._initSuccess(D);
		this.isSubmit || (this.isSubmit = 0);
	},
	setSubmit: function(status) {
		this.isSubmit = status;
	},
	/**
	 * 调用错误接口
	 * @param object D DOM对象
	 * @param string txt 显示内容
	 * @return void
	 */
	error: function(D, txt) {
		this.init(D);
		if($(D).val() == '' && !this.isSubmit) {
			D.dError.style.display = "none";
			D.dSuccess.style.display = "none";
		} else {
			D.dSuccess.style.display = "none";
			D.dError.style.display = "";
			D.dErrorText.nodeValue = txt;
		}
	},
	/**
	 * 调用成功接口
	 * @param object D DOM对象
	 * @return void
	 */
	success: function(D) {
		this.init(D);
		D.dError.style.display = "none";
		D.dSuccess.style.display = "";
	},
	/**
	 * 清除提示接口
	 * @param object D DOM对象
	 * @return void
	 */
	clear: function(D) {
		this.init(D);
		D.dError.style.display = "none";
		D.dSuccess.style.display = "none";
	},
	/**
	 * 初始化错误对象
	 * @param object D DOM对象
	 * @return void
	 * @private
	 */
	_initError: function(D) {
		if (!D.dError || !D.dErrorText) {
			// 创建DOM结构
			var dFrag = document.createDocumentFragment();
			var dText = document.createTextNode("");
			var dB = document.createElement("b");
			var dSpan = document.createElement("span");
			var dDiv = document.createElement("div");
			// 组装HTML结构 - DIV
			D.dError = dFrag.appendChild(dDiv);
			dDiv.className = "box-ver";
			dDiv.style.display = "none";
			// 组装HTML结构 - SPAN
			dDiv.appendChild( dSpan );
			// 组装HTML结构 - B
			dSpan.appendChild( dB );
			dB.className = "ico-error";
			D.dErrorText = dSpan.appendChild(dText);
			// 插入HTML
			var dParent = D.parentNode;
			var dNext = D.nextSibling;
			if(dNext) {
				dParent.insertBefore(dFrag, dNext);
			} else {
				dParent.appendChild(dFrag);
			}
		}
	},
	/**
	 * 初始化成功对象
	 * @param object D DOM对象
	 * @return void
	 * @private
	 */
	_initSuccess: function(D) {
		if(!D.dSuccess) {
			// 创建DOM结构
			var dFrag = document.createDocumentFragment();
			var dSpan = document.createElement("span");
			// 组装HTML结构 - SPAN
			D.dSuccess = dFrag.appendChild(dSpan);
			dSpan.className = "ico-ok";
			dSpan.style.display = "none";
			// 插入HTML
			var dParent = D.parentNode;
			var dNext = D.nextSibling;
			if(dNext) {
				dParent.insertBefore(dFrag, dNext);
			} else {
				dParent.appendChild(dFrag);
			}
		}
	}
};
// 定义Window属性
window.tips = tips;
})();