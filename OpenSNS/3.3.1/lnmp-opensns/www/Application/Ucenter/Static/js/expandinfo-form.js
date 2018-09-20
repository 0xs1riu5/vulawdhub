/**
 * Created by Administrator on 14-6-14.
 * @author 郑钟良<zzl@ourstu.com>
 */
/**
 * 用户扩展信息验证
 * @param args
 * @param id
 * @returns {boolean}
 */
function check(args,id){
    var value=$('#expand_'+id).val();
    var text_type=args.getAttribute("text_type");
    var required=args.getAttribute("require");
    if(value.length==0){
        if(required=="1"){
            check_error(id,"该项不能为空");
            return false;
        }
    }else{
        switch(text_type){
            case "number":
                var reg = new RegExp("^[0-9]*$");
                var min_length=args.getAttribute("min_length");
                var max_length=args.getAttribute("max_length");
                if(reg.test(value)){
                    if((min_length!=0&&value.length<min_length)||(max_length!=0&&value.length>max_length)){
                        check_error(id,"数字长度必须在"+min_length+"-"+max_length+"之间");
                        return false;
                    }
                }else{
                    check_error(id,"输入内容必须为数字");
                    return false;
                }
                check_success(id,args.parent('form'));
                return true;
            case "phone":
                var reg =/^(1[3|4|5|8])[0-9]{9}$/;
                if(!reg.test(value)){
                    check_error(id,"请输入正确的手机号码");
                    return false;
                }
                check_success(id,args.parent('form'));
                return true;
            case "email":
                var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                if(!reg.test(value)){
                    check_error(id,"请输入正确的邮箱地址");
                    return false;
                }
                check_success(id,args.parent('form'));
                return true;
            case "string":
                var min_length=args.getAttribute("min_length");
                var max_length=args.getAttribute("max_length");
                if((min_length!=0&&value.length<min_length)||(max_length!=0&&value.length>max_length)){
                    if(max_length==0){
                        max_length='';
                    }
                    check_error(id,"长度必须在"+min_length+"-"+max_length+"之间");
                    return false;
                }
                check_success(id,args.parent('form'));

                return true;
        }
    }
}

function check_error(id,info){
    $('#alert_'+id).show();
    $('#label_'+id).html(info)
    $('#canSubmit_'+id).val(0);
    $('#submit_btn').removeClass("btn-primary");
    $('#submit_btn').addClass("btn-default");
}
function check_success(id,that){
    $('#alert_'+id).hide();
    $('#label_'+id).html('')
    $('#canSubmit_'+id).val(1);
    checkCanSubmit(that);
}


function check_textarea(args,id){
    var value=document.getElementById("expand_"+id).value;
    var required=args.getAttribute("require");
    if(value.length==0){
        if(required=="1"){
            check_error(id,"该项不能为空");
            return false;
        }
    }else{
        var min_length=args.getAttribute("min_length");
        var max_length=args.getAttribute("max_length");
        if((min_length!=0&&value.length<min_length)||(max_length!=0&&value.length>max_length)){
            if(max_length==0){
                max_length='';
            }
            check_error(id,"文本长度必须在"+min_length+"-"+max_length+"之间");
            return false;
        }

    }
    check_success(id,$(this).parent('form'));
    return true;
}

function checkCanSubmit(that){
    var canSubmit=true;
    that.find('.canSubmit').each(function(){
        if($(this).val()==0){
            canSubmit=false;
        }
    });
    if(!canSubmit){
        that.find('#submit_btn').removeClass("btn-primary");
        that.find('#submit_btn').addClass("btn-default");
        return false;
    }
    that.find('#submit_btn').removeClass("btn-default");
    that.find('#submit_btn').addClass("btn-primary");
    return true;
}

$(document).ready(function(){
    $('#submit_btn').click(function(check){
        if(!checkCanSubmit($(this).parent('form'))){
            check.preventDefault();
            return false;
        }
    });
});