/**
 * 验证类
 * @param {string} formid 表单id
 * @param {bool} isalert 是否要用alert方式显示错误
 * @param {string} errorcss 错误信息样式名
 */
var Validator = function(formid, isalert, errorcss){
    var base = this;
    var form = document.getElementById(formid);
    var validArr = new Array();
    var validObjTag = "v_";
    var issubmit = false; /*是否通过*/
    /*
    //绑定验证控件的Class属性
    if (!isalert) {
        var spans = document.getElementsByTagName("span");
        for (var i = 0; i < spans.length; i++) {
            if (spans[i].id.indexOf(validObjTag) != -1) {
                if (errorcss == null || errorcss == '') 
                    spans[i].style.color = 'red';
                else 
                    spans[i].className = errorcss;
            }
        }
    }
    */
	
    /**
     * 绑定提交事件
     */
    form.onsubmit = function(){
        return base.valid();
    }
    
    /**
     * 显示验证消息对应的容器ID
     * @param {Object} id
     */
    function validObjID(id){
        return validObjTag + id;
    }
    
    /**
     * 绑定验证事件
     * @param {string} id 验证控件ID
     * @param {Array} 验证规则数组
     */
    this.bind = function(id, eventArr){
        validArr.push(new Array(id, eventArr));
        
        if (!isalert) {
            if (!document.getElementById(validObjID(id))) {
				alert("验证控件（id=" + validObjID(id) + "）不存在！");
				return;
			}
            
            if (window.document.all) {
                document.getElementById(id).attachEvent("onblur", function(){
                    var result = true;
                    for (var i = 0; i < eventArr.length; i++) {
                        result = base.doValid(id, eventArr[i]);
                        if (!result) 
                            return;
                    }
                });
            }
            else {
                document.getElementById(id).addEventListener("blur", function(){
                    var result = true;
                    for (var i = 0; i < eventArr.length; i++) {
                        result = base.doValid(id, eventArr[i]);
                        if (!result) 
                            return;
                    }
                }, false);
            }
        }//end if(!isalert)
    }
    
    /**
     * 击发所有对象的验证事件
     */
    this.valid = function(){
        this.issubmit = true;
        var focusid = null;
        for (var i = 0; i < validArr.length; i++) {
            if (isalert && !this.issubmit) 
                break;
            for (var j = 0; j < validArr[i][1].length; j++) {
                if (!base.doValid(validArr[i][0], validArr[i][1][j])) {
                    this.issubmit = false;
                    if (focusid == null) 
                        focusid = validArr[i][0];
                    break;
                }
            }
        }
        if (focusid != null){
			try{document.getElementById(focusid).focus();} catch(e){}
		}
        return this.issubmit;
    }
    
    /**
     * 一个对象的验证事件
     * @param {string} id
     * @param {Array} 验证规则数组
     */
    this.doValid = function(id, arr){
        var val = document.getElementById(id).value;
        var result = true;
        switch (arr.length) {
            case 2:
                var type = arr[0];
                var msg = arr[1];
                
                switch (type) {
                    case "empty":
                        result = writeMsg(id, msg, (trim(val) == ''));
                        break;
                    case "number":
                        /* result = writeMsg(id, msg, (isNaN(val))); 是否是数字*/
                        var patrn = /^[0-9]+$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "double":
                        var patrn = /^[0-9.]+$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "date":
                        var patrn = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/; /*YYYY-MM-DD*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "time":
                        var patrn = /^((20|21|22|23|[0-1]\d)\:[0-5][0-9])(\:[0-5][0-9])?$/; /*hh:mm:ss*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "datetime":
                        var patrn = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/; /*YYYY-MM-DD hh:mm:ss*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "url":
                        var patrn = /^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/; /*网址*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "email":
                        var patrn = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; /*邮件*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "identity":
                        var patrn = /^\d{17}[\d|X]|\d{15}$/; /*身份证号*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "ip":
                        var patrn = /^(((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))\.){3}((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "zip":
                        var patrn = /^\d{6}$/; /*邮编*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "qq":
                        var patrn = /^[1-9][0-9]{4,}$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "phone":
                        var patrn = /^\d{3}-\d{8}|\d{4}-\d{7,8}|\d{11}|\d{12}$/; /*电话*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "mobile":
                        var patrn = /^(13|15|18)\d{9}$/; /*手机*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "string":
                        var patrn = /^[a-zA-Z0-9_]+$/; /*a-z,A-Z,0-9 */
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "image":
                        var patrn = /(.jpg|.gif|.bmp|.png|.img|.swf)$/i; /*图片扩展名*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "html":
                        var patrn = /(.htm|.html|.shtml)$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "chinese":
                        var patrn = /^[\u0391-\uFFE5]+$/; /*中文*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "userorpwd":
                        var patrn = /^[A-Za-z0-9]{6,20}$/; /*6-20位;只限数字(0-9)和英文(a-z),不区分大小写*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                }
                break;
            case 3:
                var type = arr[0];
                var element = arr[1];
                var msg = arr[2];
                
                switch (type) {
                    case "compare_eq": /*相等*/
                        result = writeMsg(id, msg, !(val == document.getElementById(element).value));
                        break;
                    case "compare_neq": /*不相等*/
                        result = writeMsg(id, msg, !(val != document.getElementById(element).value));
                        break;
                    case "compare_gt": /*大于*/
                        result = writeMsg(id, msg, !(val > document.getElementById(element).value));
                        break;
                    case "compare_gte": /*大于等于*/
                        result = writeMsg(id, msg, !(val >= document.getElementById(element).value));
                        break;
                    case "compare_lt": /*小于*/
                        result = writeMsg(id, msg, !(val < document.getElementById(element).value));
                        break;
                    case "compare_lte": /*小于等于*/
                        result = writeMsg(id, msg, !(val <= document.getElementById(element).value));
                        break;
                    case "regular": /*正则*/
                        result = writeMsg(id, msg, (regular(val, element)));
                        break;
                    case "custom": /*自定义*/
                        result = writeMsg(id, msg, !(eval(element)));
                        break;
                }
                break;
        }
        return result;
    }
	
	/**
	 *去掉空格
	 */
	function trim(str){  
		return str.replace(/(^\s*)|(\s*$)/g, "");   
	}
    /**
     * 正则匹配
     * @param {string} val 要匹配的字符
     * @param {RegExp} patrn 正则对象
     */
    function regular(val, patrn){
        var result = false;
        if (val != '') 
            result = !patrn.test(val);
        return result;
    }
    
    /**
     * 写出错误信息
     * @param {string} id 显示错误信息容器ID
     * @param {string} msg 错误信息
     * @param {bool} result 验证或匹配结果
     * @return {bool} 是否通验证
     */
    function writeMsg(id, msg, result){
        if (!isalert) {
            if (result) {
				var obj=document.getElementById(validObjID(id));
                obj.innerHTML = msg;
                if (errorcss == null || errorcss == '') 
                    obj.style.color = 'red';
                else 
                    obj.className = errorcss;
                return false;
            }
            else {
                document.getElementById(validObjID(id)).innerHTML = "";
                return true;
            }
        }
        else {
            if (result) {
                alert(msg);
                return false;
            }
            else {
                return true;
            }
        }
    }
}
