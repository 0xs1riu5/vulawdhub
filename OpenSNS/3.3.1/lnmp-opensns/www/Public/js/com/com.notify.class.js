var Notify = {
    'readMessage': function (obj, message_id) {
        var url = $(obj).attr('data-url');
        if( url !=''){
            toast.showLoading();
            $.post(U('Ucenter/Public/readMessage'), {message_id: message_id}, function (msg) {
                toast.hideLoading();
                location.href = url;
            }, 'json');
        }

    },
    /**
     * 将所有的消息设为已读
     */
    'setAllReaded': function () {
        $.post(U('Ucenter/Public/setAllMessageReaded'), function () {
            $hint_count.text(0);
            $('#nav_message').html('<div style="font-size: 18px;color: #ccc;font-weight: normal;text-align: center;line-height: 150px">暂无任何消息!</div>');
            $nav_bandage_count.hide();
            $nav_bandage_count.text(0);

        });
    }
};