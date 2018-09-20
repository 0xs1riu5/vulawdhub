/**
 * 绑定用户小名片
 */
function ucard() {
    $('[ucard]').qtip({ // Grab some elements to apply the tooltip to
        suppress: true,
        content: {
            text: function (event, api) {
                var uid = $(this).attr('ucard');
                $.get(U('Ucenter/Public/getProfile'), {uid: uid}, function (userProfile) {
                    var follow = '';
                    if ((MID != uid) && (MID != 0)) {
                        follow = '<button type="button" class="btn btn-default" onclick="talker.start_talk(' + userProfile.uid + ')" style="float: right;margin: 5px 0;padding: 2px 12px;margin-left: 8px;">聊天</button>';
                        if (userProfile.followed == 1) {
                            follow += '<button type="button" class="btn btn-default" data-role="unfollow" data-follow-who="'+userProfile.uid+'"  style="float: right;margin: 5px 0;padding: 2px 12px;"><font title="取消关注">已关注</font></button>';
                        } else {
                            follow += '<button type="button" class="btn btn-primary" data-role="follow" data-follow-who="'+userProfile.uid+'"  style="float: right;margin: 5px 0;padding: 2px 12px;">关&nbsp;注</button>';
                        }
                    }
                    var html = '<div class="row" style="width: 350px;width: 350px;font-size: 13px;line-height: 23px;">' +
                        '<div class="col-xs-12" style="padding: 2px;">' +
                        '<img class="img-responsive" src="' + window.Think.ROOT + '/Public/images/qtip_bg.png">' +
                        '</div>' +
                        '<div class="col-xs-12" style="padding: 2px;margin-top: -25px;">' +
                        '<div class="col-xs-3">' +
                        '<img src="{$userProfile.avatar64}" class="avatar-img img-responsive" style=""/>' +
                        '</div>' +
                        '<div class="col-xs-9" style="padding-top: 25px;padding-right:0px;font-size: 12px;">' +
                        '<div style="font-size: 16px;font-weight: bold;"><a href="{$userProfile.space_url}" title="">{$userProfile.nickname}</a>{$userProfile.rank_link}' +
                        '</div>' +
                        '<div>' +
                        '<a href="{$userProfile.following_url}" title="我的关注" target="_black">关注：{$userProfile.following}</a>&nbsp;&nbsp;&nbsp;&nbsp;' +
                        '<a href="{$userProfile.fans_url}" title="我的关注" target="_black">粉丝：{$userProfile.fans}</a>&nbsp;&nbsp;&nbsp;&nbsp;' +
                        '</div>' +
                        '<div style="margin-bottom: 15px;color: #848484">' +
                        '个性签名：' +
                        '<span>' +
                        '{$userProfile.signature}' +
                        '</span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-xs-12" style="background: #f1f1f1;">' +
                        follow +
                        '</div>' +
                        '</div>';

                    userProfile.signature = userProfile.signature === '' ? '还没想好O(∩_∩)O' : userProfile.signature;
                    for (var key in userProfile) {
                        html = html.replace('{$userProfile.' + key + '}', userProfile[key]);
                    }
                    //alert(html);
                    var tpl = $(html);
                    api.set('content.text', tpl.html());
                    follower.bind_follow();

                }, 'json');
                return '获取数据中...'
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