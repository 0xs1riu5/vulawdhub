var Common = new Object();
Common.trim = function(str){
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

Common.strlen = function (str){
	var Charset = jQuery.browser.msie ?document.charset : document.characterSet
	if(Charset.toLowerCase() == 'utf-8'){
		return str.replace(/[\u4e00-\u9fa5]/g, "***").length;
	} else {
		return str.replace(/[^\x00-\xff]/g, "**").length;
	}
}


validator={
	errinput : 'errinput',
	errmsg : 'errmsg',
	errcls : 'no',
	yescls : 'yes',
	errorTip : 'errorTip',
	errorInput : 'errorInput',
	validTip   : 'validTip',
	require : /[^(^\s*)|(\s*$)]/,	
	email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
	phone : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
	mobile : /^((\(\d{3}\))|(\d{3}\-))?13[0-9]\d{8}?$|15[89]\d{8}?$/,
	url : /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
	idCard : "this.isIdCard(value)",
	currency : /^\d+(\.\d+)?$/,
	number : /^\d+$/,
	zip : /^[1-9]\d{5}$/,
	ip  : /^[\d\.]{7,15}$/,
	qq : /^[1-9]\d{4,8}$/,
	integer : /^[-\+]?\d+$/,
	double : /^[-\+]?\d+(\.\d+)?$/,
	english : /^[A-Za-z]+$/,
	chinese : /^[\u0391-\uFFE5]+$/,
	userName : /^[A-Za-z0-9_]{3,}$/i,
	//unSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/,
	unSafe : /[<>\?\#\$\*\&;\\\/\[\]\{\}=\(\)\.\^%,]/,
	//safeStr : /[^#\'\"~\.\*\$&;\\\/\|]/,
	isSafe : function(str){return !this.unSafe.test(str);},
	safeString : "this.isSafe(value)",
	filter : "this.doFilter(value)",
	limit : "this.checkLimit(Common.strlen(value))",
	limitB : "this.checkLimit(this.LenB(value))",
	date : "this.isDate(value)",
	repeat : "this.checkRepeat(value)",
	range : "this.checkRange(value)",
	compare : "this.checkCompare(value)",
	custom : "this.Exec(value)",
	group : "this.mustChecked()",
	ajax: "this.doajax(errindex)",

	isIdCard : function(number){
		var date, Ai;
		var verify = "10x98765432";
		var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
		var area = ['','','','','','','','','','','','北京','天津','河北','山西','内蒙古','','','','','','辽宁','吉林','黑龙江','','','','','','','','上海','江苏','浙江','安微','福建','江西','山东','','','','河南','湖北','湖南','广东','广西','海南','','','','重庆','四川','贵州','云南','西藏','','','','','','','陕西','甘肃','青海','宁夏','新疆','','','','','','台湾','','','','','','','','','','香港','澳门','','','','','','','','','国外'];

		var re = number.match(/^(\d{2})\d{4}(((\d{2})(\d{2})(\d{2})(\d{3}))|((\d{4})(\d{2})(\d{2})(\d{3}[x\d])))$/i);
		if(re == null) return false;
		if(re[1] >= area.length || area[re[1]] == "") return false;
		if(re[2].length == 12){
			Ai = number.substr(0, 17);
			date = [re[9], re[10], re[11]].join("-");
		} else {
			Ai = number.substr(0, 6) + "19" + number.substr(6);
			date = ["19" + re[4], re[5], re[6]].join("-");
		}
		if(!this.isDate(date, "ymd")) return false;
		var sum = 0;
		for(var i = 0;i<=16;i++){
			sum += Ai.charAt(i) * Wi[i];
		}
		Ai += verify.charAt(sum%11);

		return (number.length ==15 || number.length == 18 && number == Ai);
	},

	isDate : function(op){
		var formatString = this['element'].attr('format');
		formatString = formatString || "ymd";
		var m, year, month, day;
		switch(formatString){
		case "ymd" :
			m = op.match(new RegExp("^((\\d{4})|(\\d{2}))([-./])(\\d{1,2})\\4(\\d{1,2})$"));
			if(m == null ) return false;
			day = m[6];
			month = m[5]*1;
			year = (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
		break;
		case "dmy" :
			m = op.match(new RegExp("^(\\d{1,2})([-./])(\\d{1,2})\\2((\\d{4})|(\\d{2}))$"));
			if(m == null ) return false;
			day = m[1];
			month = m[3]*1;
			year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10));
		break;
		default :
			break;
		}
		if(!parseInt(month)) return false;
		month = month==0 ?12:month;
		var date = new Date(year, month-1, day);
		return (typeof(date) == "object" && year == date.getFullYear() && month == (date.getMonth()+1) && day == date.getDate());
		function GetFullYear(y){
			return ((y<30 ? "20" : "19") + y)|0;
		}
	}, //end isDate
	doFilter : function(value){
		var filter =this['element'].attr('accept');
		return new RegExp("^.+\.(?=EXT)(EXT)$".replace(/EXT/g,filter.split(/\s*,\s*/).join("|")),"gi").test(value);
	},

	checkLimit:function(len){
		var minval=this['element'].attr('min') ||Number.MIN_VALUE;
		var maxval=this['element'].attr('max') ||Number.MAX_VALUE;
		return (minval<= len && len<=maxval);

	},

	LenB : function(str){
		return str.replace(/[^\x00-\xff]/g,"**").length;
	},

	checkRepeat:function(value){
		var to = this['element'].attr('to');
		var xxx = jQuery('input[name="'+to+'"]').eq(0).val();
		if(!xxx) xxx = jQuery('#'+to).val();
		return value==xxx;
	},

	checkRange : function(value){
		value = value|0;
		var minval=this['element'].attr('min') || Number.MIN_VALUE;
		var maxval=this['element'].attr('max') || Number.MAX_VALUE;
		return (minval<=value && value<=maxval);
	},

	checkCompare : function(value){
		var compare=this['element'].attr('compare');
		
		if(isNaN(value)) return false;
		value = parseInt(value);
		return eval(value+compare);
	},

	Exec : function(value){
		var reg = this['element'].attr('regexp');
		return new RegExp(reg,"gi").test(value);
	},

	mustChecked : function(){
		var tagName=this['element'].attr('name');
		var f=this['element'].parents('form');
		var n=f.find('input[name="'+tagName+'"][checked]').length;
		var count = f.find('input[name="'+tagName+'"]').length;
		var minval=this['element'].attr('min') || 1;
		var maxval=this['element'].attr('max') || count;
		return (minval<=n && n<=maxval);
	},

	doajax : function(value) {	
		var element = this['element'];
		var errindex = this['errindex'];
		var url=this['element'].attr('url');
		var msgid = jQuery('#'+element.attr('msgid'));
		var val = this['element'].val();
		var str_errmsg=this['element'].attr('msg');
		var arr_errmsg ;
		var errmsg ;
		if(str_errmsg.indexOf('|')>-1) {
      		arr_errmsg= str_errmsg.split('|') ;
      		errmsg = arr_errmsg[errindex] ;
		} else {
      		errmsg='';
		}
		var type=this['element'].attr('type');
		var Charset = jQuery.browser.msie ? document.charset : document.characterSet;
		//var methodtype = (Charset.toLowerCase() == 'utf-8') ? 'post' : 'get';
		var methodtype	=	'post';
		var method=this['element'].attr('method') || methodtype;
		var name = this['element'].attr('name');
		if(url=="" || url==undefined) {
			alert('Please specify url');
      			return false ;
			}
		//if(url.indexOf('?')>-1){
	     // url = url+"&"+name+"="+val;
		//	} else {
	     // url = url+'?'+name+"="+val;
		//}
		check_data	=	name+"="+val;
		validator.removeErr(this['element']);
		this['element'].parent('*').find('.'+validator.errorTip+',.'+validator.validTip).remove();
		var e_name = element.attr('name');
		var s = $.ajax({
			type: method,
			url: url,
			data: check_data,
			cache: false,
			async: false,
			success: function(data){
				data = data.replace(/(^\s*)|(\s*$)/g, "");
				   if(data != 'success'){
					   element.parent('*').find('.js-error').remove();
					  errmsg = errmsg=="" ? data : errmsg;
					  (type!='checkbox' && type!='radio' && element.addClass(validator.errorInput));
						if(msgid.length>0){
							msgid.html('<div class="btn-error js-error"><span><b class="ico-error"></b>'+errmsg+'</span></div>');
						} else{
							jQuery('<div class="btn-error js-error"><span><b class="ico-error"></b>'+errmsg+'</span></div>').insertAfter(element);
						}
						return false;
					}
				 
					if(data=='success') {
						element.parent('*').find('.js-error').remove();
						if(msgid.length>0){
							msgid.html('<span class="ok js-error"></span>');
					   } else {
							jQuery('<span class="ok js-error"></span>').insertAfter(element);
						}
						return true;
					}
				}
			}).responseText;
			s = s.replace(/(^\s*)|(\s*$)/g, "");
			return s == 'success' ? true : false;
	}
};

// element 
validator.showErr=function (element, errindex){
	var str_errmsg=element.attr('msg') ||'unkonwn';
	var arr_errmsg = str_errmsg.split('|');
	var errmsg = arr_errmsg[errindex] ? arr_errmsg[errindex]: arr_errmsg[0];
	var msgid= jQuery('#'+element.attr('msgid'));
	var type=element.attr('type');
	(type!='checkbox' && type!='radio' && element.addClass(this['errorinput']));
	element.parent('*').find('.js-error').remove();
	if(msgid.length>0){
		msgid.html('<div class="btn-error js-error"><span><b class="ico-error"></b>'+errmsg+'</span></div>');
	} else{
		jQuery('<div class="btn-error js-error"><span><b class="ico-error"></b>'+errmsg+'</span></div>').insertAfter(element);
	}
	return false ;
}

validator.removeErr =  function(element){
	element.removeClass(this['errorInput']);
	element.parent('*').find('.js-error').remove();
	jQuery('<span class="ok js-error"></span>').insertAfter(element);
}

validator.checkajax = function(element, datatype, errindex) {
	var value=jQuery.trim(element.val());
	this['element'] = element;
	this['errindex'] = errindex;
	validator.removeErr(element);
	return eval(this[datatype]);
}

validator.checkDatatype = function(element,datatype){
	var value=jQuery.trim(element.val());
	this['element'] = element;
	validator.removeErr(element);
	switch(datatype){
		case "idCard" :
		case "date" :
		case "repeat" :
		case "range" :
		case "compare" :
		case "custom" :
		case "group" :
		case "limit" :
		case "limitB" :
		case "safeString" :
		case "filter" :	
		return eval(this[datatype]);
		break;
	default:
		return this[datatype].test(value);
		break;
	}
}

validator.check=function(obj){
	var datatype = obj.attr('datatype');
	var value = jQuery.trim(obj.val());
	
	if(typeof(datatype) == "undefined") return true;

	if(obj.attr('require')!="true" && value=="") return true;
	var datatypes = datatype.split('|');
	var isValid = true;

	jQuery.each(datatypes,function(index,type){
		if(typeof(validator[type]) == "undefined") {
			isValid = false;
			return  false;
		}
		
		//ajax validate 
		if(type=='ajax')   return isValid = validator.checkajax(obj, type, index);
		
		if(validator.checkDatatype(obj,type)==false){  //the form element validate failed
			obj.addClass(validator.errorInput);
			validator.showErr(obj, index);
			return isValid=false;
		} else { // validate success
			var e_name = obj.attr('name');
			//jQuery(".error_"+e_name).hide();
			//jQuery("#success_"+e_name).show();
			jQuery("#message_"+e_name).show();
			validator.showErr(obj, index);
			obj.removeClass(validator.errorInput);
			var msgid = jQuery('#'+obj.attr('msgid'));
			if(msgid.length>0) {
				msgid.removeClass(validator.errorTip).addClass(validator.validTip).html('&nbsp;&nbsp;');
			} else {				
				obj.parent('*').find('.'+validator.errorTip+',.'+validator.validTip).remove();
				jQuery('<span class="'+validator.validTip+'">&nbsp;&nbsp;&nbsp;&nbsp;</span>').insertAfter(obj);
			}				
		}
	});
	return isValid;
}
  
jQuery.fn.checkForm = function(){
	var form=jQuery(this);
	var elements = form.find(':input[require]');
	elements.blur(function(index){
		return validator.check(jQuery(this));
	});
	
	form.submit(function(){
		var isValid = true;
		var errIndex= new Array();
		var n=0;
		elements.each(function(i){
			if(validator.check(jQuery(this))==false){
				isValid  = false;
				errIndex[n++]=i;
			};
		});

		if(isValid==false){
			elements.eq(errIndex[0]).focus().select();
			return false;
		}

		return true;
	});
}