
var weibo = {
    page: 1,
    lastId: 0,
    loadCount: 1,
    url: '',
    type:'all',
    select:$('#smallnav').val(),
    noMoreNextPage: false,
    isLoadingWeibo: false,
    isLoadMoreVisible: function () {
        var visibleHeight = $(window.top).height();
        var loadMoreOffset = $('#load_more').offset();

        return visibleHeight + $(window).scrollTop() >= loadMoreOffset.top-500;
    },
    loadNextPage: function () {
        if (this.loadCount == 3) {
            $('#index_weibo_page').show();
        }

        if (this.page == 1 && this.loadCount < 3) {
            this.loadCount++;
            this.loadWeiboList();
        }

        if (this.page > 1) {
            this.loadWeiboList();
        }
    },
    reloadWeiboList: function () {
        this.loadCount = 1;
        this.loadWeiboList(1, function () {
            this.clearWeiboList();
            this.page = 1;
        });
    },
    loadWeiboList: function () {
        //默认载入第1页
        if (this.page == undefined) {
            this.page = 1;
        }
        //通过服务器载入微博列表
        this.isLoadingWeibo = true;
        $('#load_more_text').html('<div class="sk-cube-grid"> <div class="sk-cube sk-cube1"></div> <div class="sk-cube sk-cube2"></div> <div class="sk-cube sk-cube3"></div> <div class="sk-cube sk-cube4"></div> <div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div> <div class="sk-cube sk-cube7"></div> <div class="sk-cube sk-cube8"></div> <div class="sk-cube sk-cube9"></div> </div>');
       
        $.get(this.url, {page: this.page, lastId: this.lastId,type:this.type,loadCount:this.loadCount,select:this.select}, function (a) {
            if (a.status == 0) {
                weibo.noMoreNextPage = true;
                $('#load_more_text').text('没有了');
            }
            $('#weibo_list').append(a);
            $('#load_more_text').text('');
            weibo.isLoadingWeibo = false;
            weibo_bind();
            bind_atwho();

        });
    },
    clearWeiboList: function () {
        this.page = 0;
        $('#weibo_list').html('');
    }

}


var bind_weibo_popup = function () {
    $('.popup-gallery').each(function () {
        $(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: '正在载入 #%curr%...',
            mainClass: 'mfp-img-mobile',

            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {

                tError: '<a href="%url%">图片 #%curr%</a> 无法被载入.',
                titleSrc: function (item) {
                    return '';
                },
                verticalFit: false
            }
        });
    });
}


$(function () {
    $('#weibo_content').keypress(function (e) {
        if (e.ctrlKey && e.which == 13 || e.which == 10) {
            $(this).parents('.weibo_post_box').find("[data-role='send_weibo']").click();
        }
    });
    send_weibo();
})

var send_weibo = function(){
    $('[data-role="send_weibo"]').unbind('click');
    $('[data-role="send_weibo"]').click(function () {
        var $this = $(this);
        var $hook_show = $this.parents('.weibo_post_box').find('#hook_show');
        var extra = $hook_show.find('.extra').serialize();
        var feedType = $hook_show.find('[name="feed_type"]').val();
        //获取参数
        var url = $(this).attr('data-url');
        var content = $(this).parents('.weibo_post_box').find('#weibo_content').val();
        var button = $(this);
        var originalButtonText = button.val();
        var attach_ids = '';
        var $attach_ids = $(this).parents('.weibo_post_box').find('[name="attach_ids"]');
        if (typeof($attach_ids) != 'undefined' && $attach_ids.val() != '') {
            attach_ids = $attach_ids.val();
        }
        //发送到服务器
        if(typeof feedType == 'undefined'){
            feedType = 'feed';
        }
        $.post(url, {content: content, type: feedType, attach_ids: attach_ids,extra:extra}, function (a) {
            handleAjax(a);
            if (a.status) {
                if (MODULE_NAME == 'Weibo' && ACTION_NAME == 'index') {
                    $('#weibo_list').prepend(a.html);
                    weibo_bind();
                    bind_atwho();
                }
                clear_weibo();
                var html = "还可以输入" + initNum + "个字";
                $('.show_num_quick').html(html);
                $('.show_num').html(html);
                $('.XT_face').remove();
                insert_image.close();
                $('.mfp-close').click();
                $('#hook_show').html('');
            }
        });
    });
}
var WEIBO_CONTENT_CLASS = '.weibo_post_box';


insert_topic = {
    find: function (obj) {
        return $(this.obj).parents(WEIBO_CONTENT_CLASS).find(obj);
    },
    obj: 0,
    InsertTopic: function (obj) {
        this.obj = obj;
        var textbox = this.find("#weibo_content");
        var text = '请在这里输入自定义话题';
        textbox.val(textbox.val()+"#"+text+"#");
        var len = textbox.val().length;
        textbox.selectRange(len-text.length-1,len-1);
    }
}



$(function () {
    $.fn.selectRange = function(start, end) {
        return this.each(function() {
            if (this.setSelectionRange) {
                this.focus();
                this.setSelectionRange(start, end);
            } else if (this.createTextRange) {
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', start);
                range.select();
            }
        });
    };

})

insert_image = {
    find: function (obj) {
        return $(this.obj).parents(WEIBO_CONTENT_CLASS).find(obj);
    },
    obj: 0,
    insertImage: function (obj) {
        if (insert_image.obj != 0)
            insert_image.close();
        insert_image.obj = obj;
        this.find('#insert_image').attr('onclick', 'insert_image.showBox()');
        var box_url = this.find('#box_url').val();
        $.post(U('weibo/type/imagebox'), {}, function (res) {
            var html = '<div class="XT_image XT_insert">' +
                '<div class="XT_face_main XT_insert_image" style="margin-left: 0;"><div class="XT_face_title"><span class="XT_face_bt" style="float: left">图片上传(支持拖拽和粘贴)</span>' +
                '<a onclick="insert_image.close()" class="XT_face_close">X</a></div><div class="XT-num"><span>共<span id="upload_num_' + res.unid + '">0</span>张，还能上传<span id="total_num_' + res.unid + '">' + res.total + '</span>张</span></div><div id="face">' + res.html + '</div></div></div>';
            insert_image.find('#hook_show').html(html);
        }, 'json');
    },

    close: function () {
        this.find('.XT_image').remove();
        this.find('.attach_ids').remove();
        this.find('#insert_image').attr('onclick', 'insert_image.insertImage(this)');
        this.obj = 0;
    },
    showBox: function () {
        $('.XT_image').css('z-index', '1005');
    }


}





var weibo_comment = function () {
    $('[data-role="weibo_comment_btn"]').unbind('click');
    $('[data-role="weibo_comment_btn"]').click(function (e) {
        var weibo_id = $(this).attr('data-weibo-id');
        var weiboCommentList = $('#weibo_' + weibo_id + ' .weibo-comment-list');
        if (weiboCommentList.is(':visible')) {
            hide_weibo_comment_list(weiboCommentList);
        } else {
            show_weibo_comment_list(weiboCommentList);
            $('#weibo_'+weibo_id).find('[data-role="show-comment-input"]').click();
        }
        //取消默认动作
        e.preventDefault();
        return false;
    })
}

var show_weibo_comment_list = function (weiboCommentList) {
    var trueList=weiboCommentList.find('.comment-list-block');
    if(trueList.text().trim() == ''){
        var weibo_id = weiboCommentList.attr('data-weibo-id');
        $.post(U('Weibo/Index/loadComment'), {weibo_id: weibo_id}, function (res) {
            var html = '<div class="col-xs-12"><div class=" weibo-comment-block" style=""><div class="weibo-comment-container"></div></div></div>';
            trueList.html(html);
            trueList.find('.weibo-comment-container').html(res.html);
            $('#text_'+weibo_id).focus();
            weibo_bind();
            bind_atwho();
        }, 'json');
    }

    weiboCommentList.show();
    show_comment_textarea(weiboCommentList)
}

var hide_weibo_comment_list = function (weiboCommentList) {
    weiboCommentList.hide();
}

var show_all_comment = function (weiboId) {
    $.post(U('Weibo/Index/commentlist'), {weibo_id: weiboId, show_more: 1}, function (res) {
        $('#show_comment_' + weiboId).append(res);
        $('#show_all_comment_' + weiboId).hide()
    }, 'json');
}

var show_comment_textarea = function (obj) {
    obj.find('input').focus();
}


var weibo_reply = function () {
    $('[data-role="weibo_reply"]').unbind('click');
    $('[data-role="weibo_reply"]').click(function () {

        var weibo_comment = $(this).closest('.weibo_comment');
        var weibo_id = weibo_comment.attr('data-weibo-id');
        var comment_id = weibo_comment.attr('data-comment-id');
        var nickname = $(this).attr('data-user-nickname');
        var weibo = $('#weibo_' + weibo_id);
        var textarea = $('.weibo-comment-content', weibo);
        var content = textarea.val();
        var weiboToCommentId = $('[name="reply_id"]', weibo);

        show_comment_textarea($('.single_line', weibo));

        weiboToCommentId.val(comment_id);
        textarea.focus();
        textarea.val('回复 @' + nickname + ' ：');
        $('.bottom-top').show();
        $(this).parents('.weibo-bottom').find('.bottom-top').hide();
    })
}


var do_comment = function () {
    $('[data-role="do_comment"]').unbind('click');
    $('[data-role="do_comment"]').click(function () {

        var weiboId = $(this).attr('data-weibo-id');
        var position=$(this).attr('data-type');


        var weibo = $('#weibo_' + weiboId);
        var content = $('.weibo-comment-content', weibo).val();
        var url = U('Weibo/Index/doComment');
        var weiboCommentList = $('.weibo-comment-list', weibo);


        var weiboToCommentId = $('[name="reply_id"]', weibo);
        var comment_id = weiboToCommentId.val();
        $.post(url, {weibo_id: weiboId, content: content, comment_id: comment_id,position:position}, function (a) {
            handleAjax(a);
            if (a.status) {

                if (weibo_comment_order == 1) {
                    var comment_list = $('#show_comment_' + weiboId)
                    comment_list.attr('data-comment-count', parseInt(comment_list.attr('data-comment-count')) + 1)
                    var count = comment_list.attr('data-comment-count');
                    weibo_page(weiboId, Math.ceil(count / 10));
                } else {
                    $('#show_comment_' + weiboId).prepend(a.html);
                }


                $('.weibo-comment-content', weibo).val('');
                $('.XT_face').remove();
                weibo_bind();
            } else {

            }
        });

    })
}

var weibo_page = function (weibo_id, position, page) {
    $.post(U('Weibo/Index/commentlist'), {weibo_id: weibo_id, page: page,position:position}, function (res) {
        $('#show_comment_' + weibo_id).html(res);
        weibo_bind();
        if (page == 1) {
            $('#show_all_comment_' + weibo_id).show()
        } else {
            $('#show_all_comment_' + weibo_id).hide()
        }
    }, 'json');
}




/**
 * 评论微博
 * @param obj
 * @param comment_id 评论ID
 */
var comment_del = function (obj, comment_id) {


    $('[data-role="comment_del"]').unbind('click');
    $('[data-role="comment_del"]').click(function () {

        var weibo_comment = $(this).closest('.weibo_comment');
        var comment_id = weibo_comment.attr('data-comment-id');
        var url = U('Weibo/Index/doDelComment');
        $.post(url, {comment_id: comment_id}, function (msg) {
            if (msg.status) {
                weibo_bind();
                weibo_comment.prev().fadeOut()
                weibo_comment.fadeOut()
                toast.success(msg.info, '温馨提示');
            } else {
                toast.error(msg.info, '温馨提示');
            }
        }, 'json');

    })


}



var del_weibo = function(){
    $('[data-role="del_weibo"]').unbind('click');
    $('[data-role="del_weibo"]').click(function () {
        if (confirm("确定要删除此动态吗？")){
        var $this = $(this);
        var weibo_id = $this.attr('data-weibo-id');
        $.post(U('Weibo/Index/doDelWeibo'), {weibo_id: weibo_id}, function (msg) {
            if (msg.status) {
                toast.success('删除动态成功。', '温馨提示');
                setTimeout(function () {
                    location.href=U('Weibo/Index/index');
                }, 500);
            }
        }, 'json');}
    })

}

var weibo_set_top = function(){
    $('[data-role="weibo_set_top"]').unbind('click');
    $('[data-role="weibo_set_top"]').click(function () {
        var weiboId = $(this).attr('data-weibo-id');
        $.post(U('weibo/index/setTop'), {weibo_id: weiboId}, function (msg) {
            if (msg.status) {
                toast.success(msg.info);
                setTimeout('location.reload()', 500);
            } else {
                toast.error(msg.info);
            }
        });
    })
}




var bind_repost =  function () {
    $('[data-role="send_repost"]').magnificPopup({
        type: 'ajax',
        overflowY: 'scroll',
        modal: true,
        callbacks: {
            ajaxContentAdded: function () {
                // Ajax content is loaded and appended to DOM
                $('#repost_content').focus();
                console.log(this.content);
            }, open: function () {
                $('.mfp-bg').css('opacity', 0.1)
            }
        }
    });
}

$(function(){
    weibo_bind();
    chose_topic();
    $.post(U('Core/Public/atWhoJson'),{},function(res){
        atwho_config = {
            at: "@",
            data: res,
            tpl: "<li data-value='[at:${id}]' ><img class='avatar-img' style='width:2em;margin-right: 0.6em' src='${avatar32}'/>${nickname}</li>",
            show_the_at: true,
            search_key: 'search_key',
            start_with_space: false
        };
        bind_atwho();
        $('#weibo_content').atwho(atwho_config);
    },'json')





})

var bind_lazy_load = function(){
    $("img.lazy").lazyload({effect: "fadeIn",threshold:200,failure_limit : 100});
}

//zzl显示隐藏置顶微博
var unshow_top_weibo_ids=function(unshow_ids, id,type) {
    var newArr = [];
    if(unshow_ids!=undefined){
        var attachArr = unshow_ids.split(',');
        for (var i in attachArr) {
            if (attachArr[i] !== '' && attachArr[i] !== id.toString()) {
                newArr.push(attachArr[i]);
            }
        }
    }
    switch (type){
        case 'asc':
            newArr.push(id);
            break;
        case 'desc':
            break;
    }
    unshow_ids=newArr.join(',');
    return unshow_ids;
}

var show_all_top_weibo=function(){
    $('[data-role="show_all_top_weibo"]').unbind('click');
    $('[data-role="show_all_top_weibo"]').click(function () {
        $('#top_list').children('.top_can_hide').show();
        //location.reload();
        $(this).hide();
        toast.success('操作成功！');
        //清空cookie
        $.cookie('Weibo_index_top_hide_ids',null);
    });
}

function follow() {
    $('[data-role="follow"]').unbind('click')
    $('[data-role="follow"]').click(function () {
        var $this = $(this);
        var uid = $this.attr('data-follow-who');
        $.post(U('Core/Public/follow'), {uid: uid}, function (msg) {
            if (msg.status) {

                $this.attr('class', $this.attr('data-before'));
                $this.attr('data-role', 'unfollow');
                $this.html('已关注');
                follower.bind_follow();
                toast.success(msg.info, L('_KINDLY_REMINDER_'));
            } else {
                toast.error(msg.info, L('_KINDLY_REMINDER_'));
            }
        }, 'json');
    })

    $('[data-role="unfollow"]').unbind('click')
    $('[data-role="unfollow"]').click(function () {
        var $this = $(this);
        var uid = $this.attr('data-follow-who');
        $.post(U('Core/Public/unfollow'), {uid: uid}, function (msg) {
            if (msg.status) {
                $this.attr('class', $this.attr('data-after'));
                $this.attr('data-role', 'follow');
                $this.html('关注');
                follower.bind_follow();
                toast.success(msg.info, L('_KINDLY_REMINDER_'));
            } else {
                toast.error(msg.info, L('_KINDLY_REMINDER_'));
            }
        }, 'json');
    })
}

function mouse_follow() {
    $('[data-role="id_weibo"]').mouseover(function(){
        var id = $(this).attr('id');
        $(this).find(".follow-btn").css("display", "inline-block");
    })
    $('[data-role="id_weibo"]').mouseout(function(){
        $(".follow-btn").css("display", "none");
    })
}

function bind_comment_support() {
    $('[data-role="support_btn"]').unbind('click');
    $('[data-role="support_btn"]').click(function () {
        // event.stopPropagation();
        var me = $(this);
        if (MID == 0) {
            toast.error('请在登陆后再点赞。', L('_KINDLY_REMINDER_'));
            return;
        } else {
            var row = $(this).attr('row');
            var table = $(this).attr('table');
            var uid = $(this).attr('uid');
            var jump = $(this).attr('jump');
            var weibo_id = $(this).attr('weibo_id');
            if (typeof(THIS_MODEL_NAME) != 'undefined') {
                MODULE_NAME = THIS_MODEL_NAME;
            }
            $.post(U('Weibo/Index/doSupport'), {appname: MODULE_NAME, row: row, table: table, uid: uid, jump: jump, weibo_id: weibo_id}, function (msg) {
                if (msg.status) {
                    var num_tag = $('#support_' + MODULE_NAME + '_' + table + '_' + row);
                    var pos = $('#support_' + MODULE_NAME + '_' + table + '_' + row + '_pos');
                    if (pos.text() == '') {
                        var html = '<span id="' + '#support_' + MODULE_NAME + '_' + table + '_' + row + '">1</span>';
                        pos.html('&nbsp;( ' + html + '&nbsp;)');

                    } else {
                        var num = num_tag.text();
                        num++;
                        num_tag.text(num);
                    }
                    var ico = $('#support_' + MODULE_NAME + '_' + table + '_' + row+'_icon');;
                    ico.removeClass();
                    ico.addClass('iconfont icon-dianzan-already');
                    $('#comment_support_show_'+row).addClass('show-always');
                    toast.success(msg.info, L('_KINDLY_REMINDER_'));

                } else {
                    toast.error(msg.info, L('_KINDLY_REMINDER_'));
                }

            }, 'json');
        }

    });
}

//zzl显示隐藏置顶微博 end
var weibo_bind = function(){
    follow();
    ucard();
    weibo_reply();
    weibo_comment();
    do_comment();
    bind_support();
    bind_comment_support();
    comment_del();
    del_weibo();
    weibo_set_top();
    bind_repost();
    bind_weibo_popup();
    do_send_repost();
    bind_lazy_load();
    bind_single_line();
    show_all_top_weibo();
    bind_show_video();
    bind_rotate();
    mouse_follow();
    bind_show_comment_input();
    bind_comment_hover();
    bind_weibo_support();
    hide_top_weibo();
}
var hide_top_weibo = function(){
    $('[data-role="hide_top_weibo_list"]').unbind('click');
    $('[data-role="hide_top_weibo_list"]').click(function () {
        var weiboId = $(this).attr('data-weibo-id');
        $(this).parents('.top_can_hide').hide();
        if(($('[data-role="show_all_top_weibo"]').length!==0)){
            if(!$('[data-role="show_all_top_weibo"]').is(':visited')){
                $('[data-role="show_all_top_weibo"]').show();
            }
        }
        toast.success('隐藏成功！');
        //写入cookie
        var unshow_top_weibo=$.cookie('Weibo_index_top_hide_ids');
        unshow_top_weibo=unshow_top_weibo_ids(unshow_top_weibo,weiboId,'asc');
        $.cookie('Weibo_index_top_hide_ids',unshow_top_weibo,{expires:365});
    });
}

var bind_weibo_support=function() {
    $('[data-role="support-weibo"]').unbind('click');
    $('[data-role="support-weibo"]').click(function () {
        // event.stopPropagation();
        var me = $(this);
        if (MID == 0) {
            toast.error('请在登陆后再点赞。', L('_KINDLY_REMINDER_'));
            return;
        } else {
            var row = $(this).attr('row');
            var table = $(this).attr('table');
            var uid = $(this).attr('uid');
            var jump = $(this).attr('jump');
            if (typeof(THIS_MODEL_NAME) != 'undefined') {
                MODULE_NAME = THIS_MODEL_NAME;
            }
            $.post(U('Weibo/Index/doSupport'), {appname: MODULE_NAME, row: row, table: table, uid: uid, jump: jump}, function (msg) {
                if (msg.status) {
                    var num_tag = $('#support_' + MODULE_NAME + '_' + table + '_' + row);
                    var pos = $('#support_' + MODULE_NAME + '_' + table + '_' + row + '_pos');
                    if (pos.text() == '') {
                        var html = '<span id="' + '#support_' + MODULE_NAME + '_' + table + '_' + row + '">1</span>';
                        pos.html('&nbsp;( ' + html + '&nbsp;)');

                    } else {
                        var num = num_tag.text();
                        num++;
                        num_tag.text(num);
                    }
                    var supporter_tag=$('#supporter_Weibo_weibo_'+row);
                    var user_html='<a ucard="'+msg.user.uid+'" href="'+msg.user.space_url+'" class="text-color">'+msg.user.nickname+'</a>';
                    var ico = me.parents('[data-position="one-weibo"]').find('[data-role="support-weibo"]');
                    if(num==1){
                        ico.find('.support-text').remove();
                        supporter_tag.html(user_html);
                    }else{
                        supporter_tag.prepend(user_html+'，');
                    }
                    ucard();
                    var ico_i=ico.find('i.weibo_like');
                    ico_i.removeClass();
                    ico_i.addClass('weibo_like icon-heart');
                    toast.success(msg.info, L('_KINDLY_REMINDER_'));

                } else {
                    toast.error(msg.info, L('_KINDLY_REMINDER_'));
                }

            }, 'json');
        }

    });
}

var bind_comment_hover=function(){
    $('[data-role="comment_content_hover"]').unbind();
    $('[data-role="comment_content_hover"]').hover(function () {
        var comment_id=$(this).attr('data-comment-id');
        $('#comment_time_show_'+comment_id).show();
        $('#comment_action_show_'+comment_id).show();
        $('#comment_support_show_'+comment_id).show();
    })
    $('[data-role="comment_content_hover"]').on('mouseleave',function () {
        var comment_id=$(this).attr('data-comment-id');
        $('#comment_time_show_'+comment_id).hide();
        $('#comment_action_show_'+comment_id).hide();
        $('#comment_support_show_'+comment_id).hide();
    })
}
var bind_show_comment_input=function(){
    $('[data-role="show-comment-input"]').unbind('click');
    $('[data-role="show-comment-input"]').click(function () {
        $('.bottom-top').show();
        var weibo_id=$(this).attr('data-id');
        $(this).parents('.bottom-top').hide();
        $('#text_'+weibo_id).focus();
        return true;
    });
}

function bind_rotate() {
    var angle = 0;
    $('[data-role="pswp__rotate--right"]').click(function() {
        angle +=90;
        $('.pswp__img').css('transform', 'rotate('+angle+'deg)');
    });
    $('[data-role="pswp__rotate--left"]').click(function() {
        angle -=90;
        $('.pswp__img').css('transform', 'rotate('+angle+'deg)');
    });
    $('.pswp__button--arrow--left').click(function() {
        angle = 0;
        $('.pswp__img').css('transform', 'rotate(0deg)');
    });
    $('.pswp__button--arrow--right').click(function() {
        angle = 0;
        $('.pswp__img').css('transform', 'rotate(0deg)');
    });
    $('.pswp__button--close').click(function() {
        angle = 0;
        $('.pswp__img').css('transform', 'rotate(0deg)');
    });
    $('body').click(function() {
        obj = event.srcElement ? event.srcElement : event.target;
        var source = obj.tagName;
        if(source == 'DIV') {
            angle = 0;
            $('.pswp__img').css('transform', 'rotate(0deg)');
        }
    });
    $('body').keydown(function() {
        if(event.keyCode == '27') {
            angle = 0;
            $('.pswp__img').css('transform', 'rotate(0deg)');
        }
    });
}

var initPhotoSwipeFromDOM = function(gallerySelector) {

    // parse slide data (url, title, size ...) from DOM elements
    // (children of gallerySelector)
    var parseThumbnailElements = function(el) {
        var thumbElements = el.childNodes,
            numNodes = thumbElements.length,
            items = [],
            figureEl,
            childElements,
            linkEl,
            size,
            item;

        for(var i = 0; i < numNodes; i++) {


            figureEl = thumbElements[i]; // <figure> element

            // include only element nodes
            if(figureEl.nodeType !== 1) {
                continue;
            }

            linkEl = figureEl.children[0]; // <a> element

            size = linkEl.getAttribute('data-size').split('x');

            // create slide object
            item = {
                src: linkEl.getAttribute('href'),
                w: parseInt(size[0], 10),
                h: parseInt(size[1], 10)
            };



            if(figureEl.children.length > 1) {
                // <figcaption> content
                item.title = figureEl.children[1].innerHTML;
            }

            if(linkEl.children.length > 0) {
                // <img> thumbnail element, retrieving thumbnail url
                item.msrc = linkEl.children[0].getAttribute('src');
            }

            item.el = figureEl; // save link to element for getThumbBoundsFn
            items.push(item);
        }

        return items;
    };

    // find nearest parent element
    var closest = function closest(el, fn) {
        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
    };

    // triggers when user clicks on thumbnail
    var onThumbnailsClick = function(e) {
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;

        var eTarget = e.target || e.srcElement;

        var clickedListItem = closest(eTarget, function(el) {
            return el.tagName === 'FIGURE';
        });

        if(!clickedListItem) {
            return;
        }


        // find index of clicked item
        var clickedGallery = clickedListItem.parentNode,
            childNodes = clickedListItem.parentNode.childNodes,
            numChildNodes = childNodes.length,
            nodeIndex = 0,
            index;

        for (var i = 0; i < numChildNodes; i++) {
            if(childNodes[i].nodeType !== 1) {
                continue;
            }

            if(childNodes[i] === clickedListItem) {
                index = nodeIndex;
                break;
            }
            nodeIndex++;
        }



        if(index >= 0) {
            openPhotoSwipe( index, clickedGallery );
        }
        return false;
    };

    // parse picture index and gallery index from URL (#&pid=1&gid=2)
    var photoswipeParseHash = function() {
        var hash = window.location.hash.substring(1),
            params = {};

        if(hash.length < 5) {
            return params;
        }

        var vars = hash.split('&');
        for (var i = 0; i < vars.length; i++) {
            if(!vars[i]) {
                continue;
            }
            var pair = vars[i].split('=');
            if(pair.length < 2) {
                continue;
            }
            params[pair[0]] = pair[1];
        }

        if(params.gid) {
            params.gid = parseInt(params.gid, 10);
        }

        if(!params.hasOwnProperty('pid')) {
            return params;
        }
        params.pid = parseInt(params.pid, 10);
        return params;
    };

    var openPhotoSwipe = function(index, galleryElement, disableAnimation) {
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;

        items = parseThumbnailElements(galleryElement);

        // define options (if needed)
        options = {
            index: index,

            // define gallery index (for URL)
            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

            getThumbBoundsFn: function(index) {
                // See Options -> getThumbBoundsFn section of docs for more info
                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect();

                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            },

            // history & focus options are disabled on CodePen
            // remove these lines in real life:
            historyEnabled: false,
            focus: false

        };

        if(disableAnimation) {
            options.showAnimationDuration = 0;
        }

        // Pass data to PhotoSwipe and initialize it
        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    };

    // loop through all gallery elements and bind events
    var galleryElements = document.querySelectorAll( gallerySelector );

    for(var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i+1);
        galleryElements[i].onclick = onThumbnailsClick;
    }

    // Parse URL and open gallery if it contains #&pid=3&gid=1
    var hashData = photoswipeParseHash();
    if(hashData.pid > 0 && hashData.gid > 0) {
        openPhotoSwipe( hashData.pid - 1 ,  galleryElements[ hashData.gid - 1 ], true );
    }
};



var bind_atwho = function(){
    $('.weibo-comment-content').atwho(atwho_config);
}



var clear_weibo = function () {
    $('.weibo_post_box #weibo_content').val('');
}



var do_send_repost = function(){
    $('[data-role="do_send_repost"]').unbind('click')
    $('[data-role="do_send_repost"]').click(function () {
        //获取参数
        var url = $(this).attr('data-url');
        var content = $('#repost_content').val();
        var button = $(this);
        var originalButtonText = button.val();
        var feedType = 'repost';
        var sourceId = button.attr('data-source-id');
        var weiboId = button.attr('data-weibo-id');
        var becomment=   document.getElementsByName("becomment")
        //发送到服务器
        $.post(url, {content: content,type:feedType,sourceId:sourceId,weiboId:weiboId,becomment:becomment[0].checked}, function (a) {
            handleAjax(a);
            if (a.status) {
                $('.mfp-close').click();
                button.attr('class', 'btn btn-primary');
                button.val(originalButtonText);
                if (MODULE_NAME == 'Weibo' && ACTION_NAME == 'index' && CONTROLLER_NAME =='Index' ) {
                    setTimeout(function(){
                        $('#weibo_list').prepend(a.html)
                        weibo_bind();
                        bind_atwho();
                    },1000)
                }

                $('.XT_face').remove();
                insert_image.close();

            }
        });
    });
}


var to_be_number_one = function (tid) {
    $.post(U('weibo/topic/beAdmin'),{tid:tid},function(msg){
        handleAjax(msg);
    })
}


var show_comment = function (weiboId) {
    var obj = $('#show_comment_' + weiboId + ' > div');
    obj.show();
    $('#show_comment_' + weiboId).next().hide()
}


var bind_single_line = function(){
    $('.single_line').unbind('focus');
    $('.single_line').focus(function () {
        show_comment_textarea($(this));
    })
}



var chose_topic = function(){
    $('[data-role="chose_topic"]').click(function(){
        var $textarea = $(this).parents('.weibo_post_box').find('#weibo_content');
        $textarea.val($textarea.val()+$(this).text());
    })
}


var bind_show_video = function(){
    $('[data-role="show_video"]').click(function () {
        var html = '<embed src="'+$(this).attr('data-src')+'" wmode="transparent" allowfullscreen="true" loop="false" type="application/x-shockwave-flash" style="width: 100%;height:350px;" autostart="false"></embed>';
        $(this).html(html).removeAttr('style');
    });
}

function openImg(obj) {
    var src= $(obj).parent().parent().parent().children().children().attr('src');
    window.open(src);

}
$('[data-role="search-btn"]').click(function () {
    $('#search-form').show();
    $('#search-text').focus();
    $(this).hide();
});
$('[data-role="do-search"]').click(function () {
    $('#search-form').submit();
});
$('[ data-role="select-li"]').click(function () {
    var url=$(this).children().attr('href');
    window.location.href=url;
})



if(MODULE_NAME =='Weibo' && CONTROLLER_NAME=='Index' && ACTION_NAME=='index'){
    $(window).on('ws:send_weibo', function (obj, data) {
        if (parseInt(MID) == parseInt(data.content.uid)) {
            return false
        }
        if ($('.new-weibo-tip').length > 0) {
            $('.new-weibo-count').text(parseInt($('.new-weibo-count').text()) + 1);
        } else {
            $('#top_list').before('<a data-role="show-new-weibo" class="new-weibo-tip">共有<span class="new-weibo-count">1</span>条新增微博</a>');
        }

        $('[data-role="show-new-weibo"]').unbind('click')
        $('[data-role="show-new-weibo"]').click(function () {
            $('[data-role="show-new-weibo"]').unbind('click')
            $.post(U('weibo/index/getNewWeibo'), {count: parseInt($('.new-weibo-count').text())}, function (res) {
                $('#weibo_list').prepend(res.html);
                $('.new-weibo-tip').remove();
                weibo_bind();
                bind_atwho();
            })
        });
    });

}
