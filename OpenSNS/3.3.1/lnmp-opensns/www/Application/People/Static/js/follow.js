/**
 * Created by Administrator on 2016/9/7 0007.
 */
    $('[data-role="dofollow"]' ).click(function () {

        var follow_who=$(this).attr('data-follow-who');
        var who_follow=is_login();
        var type=$(this).attr('data-value');
        var url=U('People/Index/follow');
        if(type=='follow'){
            $(this).removeClass("icon-expand-alt").addClass("icon-collapse-alt");
            $(this).attr('data-value','unfollow');
        }
        if(type=='unfollow'){
            $(this).removeClass("icon-collapse-alt").addClass("icon-expand-alt");
            $(this).attr('data-value','follow');
        }
        $.post(url,{who_follow:who_follow,follow_who:follow_who},function (msg) {
            if(msg.status){
                toast.success(msg.info);
            }else{
                toast.error(msg.info);
            }
        });


    });

    $('.zhanzhang,.dropmenu').mouseover(function(){
        $('.dropmenu').css('display','block').css('z-index','999');
    });
    $('.zhanzhang,.dropmenu').mouseleave(function(){
        $('.dropmenu').css('display','none');
    });

    $('[data-role="select_tag"]').click(function () {
        var id = $(this).attr('data-id');
        $('[data-role="tag-id"]').val(id);
        $('[data-role="role-id"]').val('');
        $('#tag-select').submit();
    });
    $('[data-role="select_role"]').click(function () {
        var id = $(this).attr('data-id');
        $('[data-role="role-id"]').val(id);
        $('[data-role="tag-id"]').val('');
        $('#tag-select').submit();
    });
    $('[data-role="go-login"]').click(
        function () {
            toast.error('请先登录~');
        }
    )
