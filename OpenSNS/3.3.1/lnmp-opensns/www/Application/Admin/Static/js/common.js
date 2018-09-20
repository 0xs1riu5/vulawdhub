//dom加载完成后执行的js
;
$(function () {

    //全选的实现
    $(".check-all").click(function () {
        $(".ids").prop("checked", this.checked);
    });
    $(".ids").click(function () {
        var option = $(".ids");
        option.each(function (i) {
            if (!this.checked) {
                $(".check-all").prop("checked", false);
                return false;
            } else {
                $(".check-all").prop("checked", true);
            }
        });
    });

    //ajax get请求
    $('.ajax-get').click(function () {
        var target;
        var that = this;
        if ($(this).hasClass('confirm')) {
            if (!confirm('确认要执行该操作吗?')) {
                return false;
            }
        }
        if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            $.get(target).success(function (data) {
                if (data.status == 1) {
                    if (data.url) {
                        toast.success(data.info + ' 页面即将自动跳转~', 'success');
                    } else {
                        toast.success(data.info, 'success');
                    }
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else if ($(that).hasClass('no-refresh')) {
                            $('#top-alert').find('button').click();
                        } else {
                            location.reload();
                        }
                    }, 3000);
                } else {
                    toast.error(data.info);
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else {
                            $('#top-alert').find('button').click();
                        }
                    }, 15000);
                }
            });

        }
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function () {
        var target, query, form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm = false;
        if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            form = $('.' + target_form);

            if ($(this).attr('hide-data') === 'true') {//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            } else if (form.get(0) == undefined) {
                toast.error('没有可操作数据。');
                return false;
            } else if (form.get(0).nodeName == 'FORM') {
                if ($(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
                    confirm_info=confirm_info?confirm_info:"确认要执行该操作吗?";
                    if (!confirm(confirm_info)) {
                        return false;
                    }
                }
                if ($(this).attr('url') !== undefined) {
                    target = $(this).attr('url');
                } else {
                    target = form.get(0).action;
                }
                query = form.serialize();
            } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {
                form.each(function (k, v) {
                    if (v.type == 'checkbox' && v.checked == true) {
                        nead_confirm = true;
                    }
                })
                if (nead_confirm && $(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
                    confirm_info=confirm_info?confirm_info:"确认要执行该操作吗?";
                    if (!confirm(confirm_info)) {
                        return false;
                    }
                }
                query = form.serialize();
            } else {
                if ($(this).hasClass('confirm')) {
                    var confirm_info = $(that).attr('confirm-info');
                    confirm_info=confirm_info?confirm_info:"确认要执行该操作吗?";
                    if (!confirm(confirm_info)) {
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            if(query==''&&$(this).attr('hide-data') != 'true'){
                toast.error(' 请勾选操作对象。');
                return false;
            }
            $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
            $.post(target, query).success(function (data) {
                if (data.status == 1) {
                    if (data.url) {
                        toast.success(data.info + ' 页面即将自动跳转~');
                    } else {
                        toast.success(data.info );
                    }
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else if ($(that).hasClass('no-refresh')) {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false);
                        } else {
                            location.reload();
                        }
                    }, 1500);
                } else {
                    toast.error(data.info);
                    setTimeout(function () {
                        if (data.url) {
                            location.href = data.url;
                        } else {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false);
                        }
                    }, 1500);
                }
            });
        }
        return false;
    });

    /**顶部警告栏*/
    var content = $('#main');
    var top_alert = $('#top-alert');
    top_alert.find('.close').on('click', function () {
        top_alert.removeClass('block').slideUp(200);
        // content.animate({paddingTop:'-=55'},200);
    });

    window.updateAlert = function (text, c) {


        if(typeof c !='undefined')
        {
            toast.show(text, {placement: 'bottom', type:c});
        }else {
            toast.show(text, {placement: 'bottom'});
        }
    };



    // 独立域表单获取焦点样式
    $(".text").focus(function () {
        $(this).addClass("focus");
    }).blur(function () {
        $(this).removeClass('focus');
    });
    $("textarea").focus(function () {
        $(this).closest(".textarea").addClass("focus");
    }).blur(function () {
        $(this).closest(".textarea").removeClass("focus");
    });
});



//标签页切换(无下一步)
function showTab() {
    $(".tab-nav li").click(function () {
        var self = $(this), target = self.data("tab");
        self.addClass("current").siblings(".current").removeClass("current");
        window.location.hash = "#" + target.substr(3);
        $(".tab-pane.in").removeClass("in");
        $("." + target).addClass("in");
    }).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();
}

//标签页切换(有下一步)
function nextTab() {
    $(".tab-nav li").click(function () {
        var self = $(this), target = self.data("tab");
        self.addClass("current").siblings(".current").removeClass("current");
        window.location.hash = "#" + target.substr(3);
        $(".tab-pane.in").removeClass("in");
        $("." + target).addClass("in");
        showBtn();
    }).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();

    $("#submit-next").click(function () {
        $(".tab-nav li.current").next().click();
        showBtn();
    });
}

// 下一步按钮切换
function showBtn() {
    var lastTabItem = $(".tab-nav li:last");
    if (lastTabItem.hasClass("current")) {
        $("#submit").removeClass("hidden");
        $("#submit-next").addClass("hidden");
    } else {
        $("#submit").addClass("hidden");
        $("#submit-next").removeClass("hidden");
    }
}

//导航高亮
function highlight_subnav(url) {
    $('#sub_menu').find('a[href="' + url + '"]').closest('li').addClass('active');
}

moduleManager = {
    'install': function (id) {
        $.post(U('admin/module/install'),{id:id},function(msg){
            handleAjax(msg);
        })
    },
    'uninstall': function (id) {
        $.post(U('admin/module/uninstall'),{id:id},function(msg){
            handleAjax(msg);
        })

    }

}
/**
 * 处理ajax返回结果
 */
function handleAjax(msg) {
    //如果需要跳转的话，消息的末尾附上即将跳转字样
    if (msg.url) {
        msg.info += '，页面即将跳转～';
    }

    //弹出提示消息
    if (msg.status) {
        toast.success(msg.info);
    } else {
        toast.error(msg.info);
    }

    //需要跳转的话就跳转
    var interval = 1500;
    if (msg.url == "refresh") {
        setTimeout(function () {
            location.href = location.href;
        }, interval);
    } else if (msg.url) {
        setTimeout(function () {
            location.href = msg.url;
        }, interval);
    }
}

/**
 * 模拟U函数
 * @param url
 * @param params
 * @returns {string}
 * @constructor
 */
function U(url, params, rewrite) {


    if (window.Think.MODEL[0] == 2) {

        var website = window.Think.ROOT + '/';
        url = url.split('/');

        if (url[0] == '' || url[0] == '@')
            url[0] = APPNAME;
        if (!url[1])
            url[1] = 'Index';
        if (!url[2])
            url[2] = 'index';
        website = website + '' + url[0] + '/' + url[1] + '/' + url[2];

        if (params) {
            params = params.join('/');
            website = website + '/' + params;
        }
        if (!rewrite) {
            website = website + '.html';
        }

    } else {
        var website = window.Think.ROOT + '/index.php';
        url = url.split('/');
        if (url[0] == '' || url[0] == '@')
            url[0] = APPNAME;
        if (!url[1])
            url[1] = 'Index';
        if (!url[2])
            url[2] = 'index';
        website = website + '?s=/' + url[0] + '/' + url[1] + '/' + url[2];
        if (params) {
            params = params.join('/');
            website = website + '/' + params;
        }
        if (!rewrite) {
            website = website + '.html';
        }
    }

    if(typeof (window.Think.MODEL[1])!='undefined'){
        website=website.toLowerCase();
    }
    return website;
}



admin_image ={
    /**
     *
     * @param obj
     * @param attachId
     */
    removeImage: function (obj, attachId) {
        // 移除附件ID数据
        this.upAttachVal('del', attachId, obj);
        obj.parents('.each').remove();

    },
    /**
     * 更新附件表单值
     * @return void
     */
    upAttachVal: function (type, attachId,obj) {
        var $attach_ids = obj.parents('.controls').find('.attach');
        var attachVal = $attach_ids.val();
        var attachArr = attachVal.split(',');
        var newArr = [];
        for (var i in attachArr) {
            if (attachArr[i] !== '' && attachArr[i] !== attachId.toString()) {
                newArr.push(attachArr[i]);
            }
        }
        type === 'add' && newArr.push(attachId);
        $attach_ids.val(newArr.join(','));
        return newArr;
    }
}

/**
 * os ajaxModal，调用bootstrap的模态弹窗。解决了bs无法纯js调用ajax模态问题；解决了bs使用ajax的load只load一次的问题，实现每次点击重新load
 * @param param
 * @returns {modalOS}
 */
var modalOS=function(param){
    var defaults={
        backdrop:true,//指定一个静态的背景，当用户点击模态框外部时不会关闭模态框。可选值true、false、static
        keyboard:true,//当按下 escape 键时关闭模态框，设置为 false 时则按键无效。
        show:true,//当初始化时显示模态框。
        title:'模态弹窗',
        url:''//从此 URL 地址加载要展示的内容
    }
    param= $.extend({},defaults,param);
    $('#osModal')
    var $tag=$('#osModal');

    if($tag.length==0){
        $('body').append('<!--模态框-->\
            <div class="modal fade" id="osModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
            <div class="modal-dialog" role="document">\
                <div class="modal-content">\
                    <div class="modal-header">\
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>\
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>\
                    </div>\
                    <div class="modal-body">\
                    </div>\
                    <div class="modal-footer">\
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
                        <button type="button" class="btn btn-primary">Save changes</button>\
                    </div>\
                </div>\
            </div>\
        </div>\
            <!--模态框-->');
        $tag=$('#osModal');
    }
    $tag.modal({
        backdrop:param.backdrop,//指定一个静态的背景，当用户点击模态框外部时不会关闭模态框。可选值true、false、static
        keyboard:param.keyboard,//当按下 escape 键时关闭模态框，设置为 false 时则按键无效。
        show:param.show,//当初始化时显示模态框。
        remote:param.url
    });
    $tag.on("hidden.bs.modal", function() {
        $(this).removeData("bs.modal");
    });
    $tag.on('loaded.bs.modal',function(){
        if($(this).find('.modal-body').length==0){
            $(this).find('.modal-content>div').first().addClass('modal-body');
        }
        var html='';
        if($(this).find('.modal-header').length==0){
            html='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"></button><h4 class="modal-title" id="myModalLabel">'+param.title+'</h4></div>';
            $(this).find('.modal-content').prepend(html);
        }
    });
    return this;
}