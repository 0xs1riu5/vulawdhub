/**
 * 绑定用户小名片
 */
var card = {
    'set_alias': function (uid) {
        var input = prompt('请输入备注名称');
        if(input==''|| input==null){
            return false;
        }else{
            $.post(U('Ucenter/Public/setAlias'),{uid:uid,alias:input},function(data){
                if(data.status){
                    toast.success('设置成功');
                }else{
                    toast.error('设置失败，'+data.info);
                }
            },'json');
        }
    }
};
function ucard() {
    $('[ucard]').qtip({ // Grab some elements to apply the tooltip to
        suppress: true,
        content: {
            text: function (event, api) {
                var uid = $(this).attr('ucard');
                if (uid != 0) {
                    $.get(U('Ucenter/Public/card'), {uid: uid}, function (html) {
                        api.set('content.text', html);
                        follower.bind_follow();
                    });

                    return '获取数据中...'
                } else {
                    return '获取信息失败';
                }

            }

        }, position: {
            viewport: $(window)
        }, show: {
            solo: true,
            delay: 500
        }, style: {
            classes: 'qtip-bootstrap'

        }, hide: {
            delay: 500, fixed: true
        }
    })
}