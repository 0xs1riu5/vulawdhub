/**
 * Created by Administrator on 14-6-30.
 */
/**
 * 表单验证
 */
var obj;
var checkCan = new Array();
var patterns = new Object();
//匹配ip地址
patterns.Ip = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/;
//匹配邮件地址
patterns.Email = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
//匹配日期格式2008-01-31，但不匹配2008-13-00
patterns.Date = /^\d{4}-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2]\d|3[0-1])$/;
/*匹配时间格式00:15:39，但不匹配24:60:00，下面使用RegExp对象的构造方法
 来创建RegExp对象实例，注意正则表达式模式文本中的“\”要写成“\\”*/
patterns.Time = new RegExp("^([0-1]\\d|2[0-3]):[0-5]\\d:[0-5]\\d$");
//匹配整形数字
patterns.Num = /^[0-9]*$/;
//匹配浮点数字
patterns.FloatNum = /^\d+(\.\d+)?$/;
//匹配日期加时间格式
patterns.DateAndTime = /^(?:19|20)[0-9][0-9]-(?:(?:0[1-9])|(?:1[0-2]))-(?:(?:[0-2][1-9])|(?:[1-3][0-1])) (?:(?:[0-2][0-3])|(?:[0-1][0-9])):[0-5][0-9](:[0-5][0-9])?$/;
//匹配手机号码
patterns.Phone = /^(1[3|4|5|8])[0-9]{9}$/;
//匹配姓名
patterns.Chinese=/^[\u4e00-\u9fa5]{2,8}$/;
//qq号码
patterns.QQ=new RegExp("^[1-9]\\d{4,10}$");
patterns.Telephone=/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/;
//身份证正则表达式(15位)
patterns.isIDCard1=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;
//身份证正则表达式(18位)
patterns.isIDCard2=/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;


$(function (e) {
    bind_form_check();
})

var bind_form_check=function(){
    $('.form_check').after('<div class=" show_info" ></div>');
    $('.form_check').click(function () {
    })
    $('.form_check').focus(function () {
    })
    $('.form_check').blur(function () {
        obj = $(this);
        var type = obj.attr('check-type');
        //验证内容的长度是否正确
        var checkLength = checkInputLength();
        //正则匹配
        if(type!==undefined){
            eval('check = check' + type + '()');
        }
        if(checkLength == 1 &&typeof(check)=='undefined'){
            //只做长度检测
            check_form.success('');
            checkCan[obj.attr('name')] = 1;
        }else if (checkLength == 1 && check.status) {
            //匹配正确
            check_form.success(check.info);
            checkCan[obj.attr('name')] = 1;
        } else if (checkLength == 0) {
            //匹配内容为空
            //是否可以为空
            var canEmpty= obj.attr('can-empty')||0;
            if(canEmpty!=0){//如果可以为空
                check_form.remove();
                checkCan[obj.attr('name')] = 1;
            }else{
                check_form.error('不能为空！')
                checkCan[obj.attr('name')] = 0;
            }
        } else if (checkLength == -1) {
            //匹配内容长度不正确
            check_form.error('长度在' + obj.attr('check-length') + '内！')
            checkCan[obj.attr('name')] = 0;
        }else if (checkLength==-2) {
            var strs = new Array(); //定义一数组
            strs = obj.attr('check-length').split(","); //字符分割
            //匹配内容长度不正确
            check_form.error('长度不能少于' + strs[0] + '个字符！')
            checkCan[obj.attr('name')] = 0;
        }else if (!check.status) {
            //匹配不成功
            check_form.error(check.info)
            checkCan[obj.attr('name')] = 0;
        }
    })
    $('.form_check').change(function () {
        $(this).blur();
    })
    /**
     * 提交时检测数据
     */
    $(":submit").click(function (e) {
        var canDubmit = true;
        for (var key in checkCan) {
            canDubmit = canDubmit & checkCan[key];
        }
        if (!canDubmit) {
            toast.error('请填写完整且正确的信息后再提交！')
            e.preventDefault();
            return false;
        }
    })
}
/**
 * 检查是否为空
 * @returns {boolean}
 */
var checkEmpty = function () {
    if (obj.val().length == 0) {
        return false;
    } else {
        return true;
    }
}
/**
 * 检查长度是否符合要求
 * @returns {number}
 */
var checkInputLength = function () {
    str = obj.val().replace(/\s+/g, "");
    if (str.length == 0) {
        return 0;
    } else {
        if (typeof (obj.attr('check-length')) != 'undefined') {
            var strs = new Array(); //定义一数组
            strs = obj.attr('check-length').split(","); //字符分割
            if (strs[1]) {
                if(strs[1]=='*'){
                    if (str.length < strs[0]) {//1,*（设置最小值）
                        return -2;
                    }
                }else if (strs[1] < str.length || str.length < strs[0]) {
                    return -1;
                }
            }else {
                if (strs[0] < str.length) {//单个值（最大值）
                    return -1;
                }
            }
        }
        return 1;
    }
}
/**
 * 验证文本框
 * @returns {{status: number, info: string}}
 */
var checkText = function () {
    var res = {status: 1, info: ''}
    return res;
}

/**zzl
 * 验证姓名输入框
 * @returns {{status: number, info: string}}
 */
var checkChinese = function () {
    var str=obj.val();
    if(patterns['Chinese'].test(str)){
        var res = {status: 1, info: ''}
    }
    else{
        var res = {status: 0, info: '请输入2-8个汉字的姓名'}
    }
    return res;
}

/**zzl
 * 验证QQ号
 * @returns {{status: number, info: string}}
 */
var checkQQ = function () {
    var str=obj.val();
    if(patterns['QQ'].test(str)){
        var res = {status: 1, info: ''}
    }
    else{
        var res = {status: 0, info: '请输入正确qq号！'}
    }
    return res;
}

/**zzl
 * 验证邮箱
 * @returns {{status: number, info: string}}
 */
var checkEmail = function () {
    var str=obj.val();
    if(patterns['Email'].test(str)){
        var res = {status: 1, info: ''}
    }
    else{
        var res = {status: 0, info: '请输入正确qq号！'}
    }
    return res;
}

/**zzl
 * 验证身份证号
 * @returns {{status: number, info: string}}
 */
var checkIDCard = function () {
    var str=obj.val();
    if(patterns['isIDCard1'].test(str)||patterns['isIDCard2'].test(str)){
        var res = {status: 1, info: ''}
    }
    else{
        var res = {status: 0, info: '请输入正确身份证号！'}
    }
    return res;
}

/**
 * 验证日期
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkDate = function () {
    var str = obj.val();
    if (patterns['Date'].test(str)) {
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写正确的格式！'}
    }
    return res;
}
/**
 * 验证日期加时间
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkDateAndTime = function () {
    var str = obj.val();
    if (patterns['DateAndTime'].test(str)) {
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写正确的格式！'}
    }
    return res;
}
/**
 * 验证数字
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkNum = function () {
    var str = obj.val();
    if (patterns['Num'].test(parseInt(str))) {
        if (typeof (obj.attr('check-value')) != 'undefined') {
            var strs = new Array(); //定义一数组
            strs = obj.attr('check-value').split(","); //字符分割
            str = parseInt(obj.val());
            if (strs[1]) {
                if(strs[1] == '*'){
                    if ( str < parseInt(strs[0])) {
                        var res = {status: 0, info: '数字至少为' + parseInt(strs[0]) }
                        return res;
                    }
                }
                if (parseInt(strs[1]) < str || str < parseInt(strs[0])) {
                    var res = {status: 0, info: '数字范围在' + strs + '内'}
                    return res;
                }
            }
            else {
                if (parseInt(strs[0]) < str) {
                    var res = {status: 0, info: '数字范围在' + strs + '内'}
                    return res;
                }
            }
        }
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写数字！'}
    }
    return res;
}

/**zzl
 * 验证整形数字
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkIntNum = function () {
    var str = obj.val();
    if(parseInt(str)!=str){
        var res = {status: 0, info: '请填写整数数字！'}
        return res;
    }
    if (patterns['Num'].test(parseInt(str))) {
        if (typeof (obj.attr('check-value')) != 'undefined') {
            var strs = new Array(); //定义一数组
            strs = obj.attr('check-value').split(","); //字符分割
            str = parseInt(obj.val());
            if (strs[1]) {
                if(strs[1] == '*'){
                    if ( str < parseInt(strs[0])) {
                        var res = {status: 0, info: '数字至少为' + parseInt(strs[0]) }
                        return res;
                    }
                }
                if (parseInt(strs[1]) < str || str < parseInt(strs[0])) {
                    var res = {status: 0, info: '数字范围在' + strs + '内'}
                    return res;
                }
            }
            else {
                if (parseInt(strs[0]) < str) {
                    var res = {status: 0, info: '数字范围在' + strs + '内'}
                    return res;
                }
            }
        }
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写整数数字！'}
    }
    return res;
}

/**zzl
 * 验证浮点型数字
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkFloatNum = function () {
    var str = obj.val();
    if(parseFloat(str)!=str){
        var res = {status: 0, info: '请填写浮点型数字！'}
        return res;
    }
    if (patterns['FloatNum'].test(parseFloat(str))) {
        if (typeof (obj.attr('check-value')) != 'undefined') {
            var strs = new Array(); //定义一数组
            strs = obj.attr('check-value').split(","); //字符分割
            str = parseFloat(obj.val());
            if (strs[1]) {
                if(strs[1] == '*'){
                    if ( str < parseFloat(strs[0])) {
                        var res = {status: 0, info: '数字至少为' + parseFloat(strs[0]) }
                        return res;
                    }
                }
                if (parseFloat(strs[1]) < str || str < parseFloat(strs[0])) {
                    var res = {status: 0, info: '数字范围在' + strs + '内'}
                    return res;
                }
            }
            else {
                if (parseFloat(strs[0]) < str) {
                    var res = {status: 0, info: '数字范围在' + strs + '内'}
                    return res;
                }
            }
        }
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写整数数字！'}
    }
    return res;
}

/**zzl
 * 验证手机号码或固定电话
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkPhoneOrTelephone = function () {
    var str = obj.val();
    if (patterns['Phone'].test(parseInt(str))||patterns['Telephone'].test(str)) {
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写手机号码或固定电话！'}
    }
    return res;
}

/**
 * 验证手机
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkPhone = function () {
    var str = obj.val();
    if (patterns['Phone'].test(parseInt(str))) {
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写手机号码！'}
    }
    return res;
}
/**
 * 验证固定电话
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkTelephone = function () {
    var str = obj.val();
    if (patterns['Telephone'].test(str)) {
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请填写固定电话号码！'}
    }
    return res;
}


/**
 * 验证下拉菜单
 * @returns {{status: number, info: string}|{status: number, info: string}}
 */
var checkSelect = function () {
    var str = obj.val();
    if(str==0){
        var can_empty=obj.attr('can-empty');
        if(can_empty==1){
            check_form.remove();
            checkCan[obj.attr('name')] = 1;
            return;
        }
    }
    if (str != 0 && str != '' && typeof (str) != 'undefined') {
        var res = {status: 1, info: ''}
    }
    else {
        var res = {status: 0, info: '请选择！'}
    }
    return res;
}

/**
 * 验证传入的正则表达式
 * 例：reg-exp="^[0-9]*\/[0-9]*\/[0-9]*$"
 */
var checkRegExps=function(){
    var regExps=obj.attr('reg-exp');
    if(typeof(regExps)=="undefined"){
        checkCan[obj.attr('name')] = 1;
        return;
    }
    var str=obj.val();
    regExps=new RegExp(regExps);
    if(regExps.test(str)){
        var res={status:1,info:''}
    }
    else{
        var errorInfo=obj.attr('error-info')||"请按正确格式填写！";
        var res = {status: 0, info:errorInfo}
    }
    return res;
}

var checkUsername = function(){
    var str = obj.val();
    var type = 'username';
    var url = obj.attr('check-url');
    $.post(url,{account:str,type:type},function(res){
        ajaxRerurn(res);
    },'json')

}
var checkUserEmail = function(){
    var str = obj.val();
    var type = 'email';
    var url = obj.attr('check-url');
    $.post(url,{account:str,type:type},function(res){
        ajaxRerurn(res);
    },'json')

}

var checkUserMobile = function(){
    var str = obj.val();
    var type = 'mobile';
    var url = obj.attr('check-url');
    $.post(url,{account:str,type:type},function(res){
        ajaxRerurn(res);
    },'json')

}

var checkNickname = function(){
    var str = obj.val();
    var url = obj.attr('check-url');
    $.post(url,{nickname:str},function(res){
        ajaxRerurn(res);
    },'json')

}

var ajaxRerurn = function(res){
    if(res.status){
        checkCan[obj.attr('name')] = 1;
        check_form.success(res.info);

    }else{
        checkCan[obj.attr('name')] = 0;
        check_form.error(res.info);
    }
}


/**
 * 显示提示信息
 * @param str
 */
var showInfo = function (str,status) {
    if(str != ''){
        var color;
        if(status == 1 ||  status == true){
            color = 'green';
        }
        else{
            color = 'red';
        }

        var html = '<div class="send '+color+'"><div class="arrow"></div>' + str + '</div>';
        obj.parent().find('.show_info').html(html);
    }else{
        removeInfo();
    }


}
/**zzl
 * 移除提示信息
 *
 */
var removeInfo = function () {
    var html = '';
    obj.parent().find('.show_info').html(html);

}

/**
 * 显示信息
 * @type {{error: Function, success: Function}}
 */
var check_form = {
    /**zzl
     * 移除非必填输入框提示消息
     */
    remove:function(){
       // var html = ' <span class="glyphicon form-control-feedback"></span>';
       // obj.parent().find('.glyphicon').replaceWith(html);
        removeInfo();
    },
    error: function (str) {
        //var html = ' <span class="glyphicon glyphicon-remove form-control-feedback"></span>';
        //obj.parent().find('.glyphicon').replaceWith(html);
        showInfo(str);
       // obj.next().css('margin-left', obj.width() + 22 + 'px');
    },
    success: function (str) {
       // var html = ' <span class="glyphicon glyphicon-ok form-control-feedback"></span>';
        //obj.parent().find('.glyphicon').replaceWith(html);
        showInfo(str,1);
        //obj.next().css('margin-left', obj.width() + 22 + 'px');
    }
}

