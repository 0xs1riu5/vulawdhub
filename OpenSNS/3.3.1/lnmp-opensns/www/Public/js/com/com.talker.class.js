/**
 * 聊天对象
 * 主要用于处理前台聊天事件
 */
var talker = {

    'container': function () {
        return $('#talker');//talker DIV容器
    },
    show: function () {
        var container = talker.container();
        if (container.text().trim() == '') {
            toast.success('聊天发起成功。');
            toast.showLoading();
            $.get(U('Ucenter/Session/panel'), {},
                function (html) {
                    container.html(html);
                    talker.bind_ctrl_enter();
                    toast.hideLoading()
                }
            );
        } else {
            container.toggle();
        }
    },
    /**
     * 发起聊天请求
     * @param uid
     */
    start_talk: function (id,has) {
        if(has == 1){
            show_chat_frame(function(){
                create_conv(id);
            });
        }

        else{
            $.post(U('Ucenter/Session/createTalk'), {uids: id}, function (msg) {
                if (msg.status) {
                    talker.show();
                    talker.open(msg.info.id);
                    /*在面板中加入一个项目*/
                    talker.prepend_session(msg.info);
                } else {
                    //TODO 创建失败
                }
            }, 'json');
        }


/*
        $.post(U('Ucenter/Session/createTalk'), {uids: uid}, function (msg) {
            if (msg.status) {
                talker.show();
                talker.open(msg.info.id);
                *//*在面板中加入一个项目*//*
                talker.prepend_session(msg.info);
            } else {
                //TODO 创建失败
            }
        }, 'json');*/
    },
    /**
     * 向聊天窗添加一条消息
     * @param html 消息内容
     */
    append_message: function (html) {
        $('#scrollContainer_chat').append(html);
        $('#scrollArea_chat').slimScroll({scrollTo: $('#scrollContainer_chat').height()});
        ucard();
    },
    /**
     * 渲染消息模板
     * @param message 消息体
     * @param mid 当前用户ID
     * @returns {string}
     */
    fetch_message_tpl: function (message, mid) {
        var tpl_right = '<div class="row talk_right">' +
            '<div class="time"><span class="timespan">{ctime}</span></div>' +
            '<div class="row">' +
            '<div class="col-md-9 bubble_outter">' +
            '<h3>&nbsp;</h3><i class="bubble_sharp"></i>' +
            '<div class="talk_bubble">{content}' +
            '</div>' +
            '</div>' +
            ' <div class="col-md-3 "><img ucard="{uid}" class="avatar-img talk-avatar"' +
            'src="{avatar64}"/>' +
            '</div> </div> </div>';

        var tpl_left = '<div class="row">' +
            '<div class="time"><span class="timespan">{ctime}</span></div>' +
            '<div class="row">' +
            '<div class="col-md-3 "><img ucard="{uid}" class="avatar-img talk-avatar"' +
            'src="{avatar64}"/>' +
            '</div><div class="col-md-9 bubble_outter chat_bubble">' +
            '<h3>&nbsp;</h3><i class="bubble_sharp"></i>' +
            '<div class="talk_bubble">{content}' +
            '</div></div></div></div>';
        var tpl = message.uid == mid ? tpl_right : tpl_left;
        $.each(message, function (index, value) {
            tpl = tpl.replace('{' + index + '}', value);
        });
        return tpl;
    },
    /**
     * 清空聊天框内的内容
     */
    clear_box: function () {
        $('#scrollContainer_chat').html('');
    },
    /**
     * 退出一个聊天框
     * @param id
     */
    exit: function (id) {
            if (typeof (id) == 'undefined') {
                id = $('#chat_id').val();
            } else {
            }
            $.post(U('Ucenter/Message/doDeleteTalk'), {talk_id: id}, function (msg) {
                if (msg.status) {
                    $('#chat_li_' + id).remove();
                    toast.success('成功退出聊天。', '聊天助手');
                }

            }, 'json');
    },
    /**
     * 绑定快速回复，ctrl+enter组合键
     */
    bind_ctrl_enter: function () {
        $('#chat_content').keypress(function (e) {
            if (e.ctrlKey && e.which == 13 || e.which == 10) {
                talker.post_message();
            }
        });
    },

    /**
     * 聊天框发送消息
     */
    post_message: function () {
        var myDate = new Date();
        $.post(U('Ucenter/Message/postMessage'), {
            talk_id: $('#chat_id').val(),
            content: $('#chat_content').val()
        }, function (msg) {
            if (!msg.status) {
                toast.error(msg.info);
            } else {
                talker.append_message(op_fetchMessageTpl({
                    uid: MID, content: msg.content,
                    avatar128: myhead,
                    ctime: myDate.toLocaleTimeString()
                }, MID));
                $('#chat_content').val('');
                $('#chat_content').focus();
                $('.XT_face').remove();
            }

        }, 'json');
    },
    /**
     * 打开一个聊天框
     * @param id
     */
    open: function (id) {
        $.get(U('Ucenter/Session/getSession'), {id: id}, function (data) {
            talker.clear_box();
            $('li', '#chat-list').removeClass();
            $('.badge_new', '#chat_li_' + id).remove();

            if (typeof ($('.friend_list').find('.badge_new').html()) == 'undefined') {
                $('#friend_has_new').hide();
            }

            $('#chat_li_' + id).addClass('active');
            $('#chat_box').show();
            talker.set_current(data);
        }, 'json');
    },
    /**
     * 添加一个session到当前会话面板中
     * @param data
     */
    prepend_session: function (data) {
        var tpl=' <li id="chat_li_'+data.id+'">\
            <a target="_blank" onclick="talker.open('+data.id+')"\
        title="'+data.title+'">\
        <div class="row">\
            <div class="col-md-4">\
                <img src="'+data.icon+'"\
                class="avatar-img"\
                style="width: 40px;max-width: 200%">\
                </div>\
                <div class="col-md-8" style="padding-left: 0">\
                    <div class="text-more talk-name" style="width: 90%">\
                                               '+data.title+'\
                    </div><span class="btn-close" onclick="talker.exit('+data.id+')"><i\
                title="退出聊天"\
                class="icon-remove"></i></span>\
                </div>\
            </div>\
        </a>\
        </li>';


        $('#chat-list #chat_li_'+data.id).remove();
        $('#chat-list').prepend(tpl);
        $('#friend_has_new').css('display', 'inline-block');
    },

    /**
     * 设置某个消息为未读
     * @param talk_id
     */
    set_session_unread: function (talk_id) {
        function chatpanel_has_loaded() {
            return typeof ($('#chat_li_' + talk_id).html()) != 'undefined';
        }


        if (chatpanel_has_loaded()) {//当聊天面板已经载入了
            if (typeof ($('#chat_li_' + talk_id).find('.badge_new').html()) != 'undefined') {//检测是否已经存在新标记
                //如果已经存在新标记
                return true;
            } else {
                $('#chat_li_' + talk_id).find('.session_ico').append('<span class="badge_new">&nbsp;</span>');
            }

        }

        $('#friend_has_new').attr('style', 'display:inline-block');
        //TODO tox设置某个session未读
    },
    /**
     * 设置当前聊天框
     * @param chat
     */
    set_current: function (chat) {
        $('#chat_ico').attr('src', chat.ico);
        $('#chat_title').text(chat.title);
        $('#chat_id').val(chat.id);
        $.each(chat.messages, function (i, item) {
            talker.append_message(talker.fetch_message_tpl(item, MID));
        });
        talker.append_message('<hr/>' +
        '<div style="text-align: center;color: #666">以上为历史聊天记录</div>', MID);
    }


}

$(function(){
    talker.bind_ctrl_enter();//绑定
})