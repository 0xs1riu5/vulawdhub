$(function(){

	//搜索用户
	$('#people_searchinput').unbind().bind('blur', function(){
		if($(this).val().replace(/\s*/, '') == ''){
			return;
		}
		location.href = U('w3g/People/search') + '&k=' + $(this).val();
	});

	//初始化关注事件
	//initFollow();
    //初始化微吧加关注事件
    initFollowWeiba();

    //分类标签切换
    initSortSelect();

    //初始化微吧事件监听
    initWeibaEvents();
});
function initSortSelect(){
    $('body').find('.sort_select').each(function(){
        $(this).click(function(){
            $(this).find('.sort_layer').toggle();
        });
    });
    $('.sort_select').find('li').click(function(){
		if($(this).attr('data-url')){
			window.location.href = $(this).attr('data-url');
			return ;
		}
		var dataType = $(this).attr('data-type');
        if(dataType == undefined){
            return ;
        }

        var listBox = $("#" + $(this).parents('.sort_select').attr("for"));

        $(this).addClass('current');
        $(this).siblings('li').removeClass('current');
        var img = $('.sort_select > a img');
        $('.sort_select > a').html('');
        img.appendTo($('.sort_select > a'));
        $('.sort_select > a').append($(this).text());

        //设置页面列表数据类型
        $("#listType").val(dataType);
        $.ajax({
            type:"GET",
            url : location.href,
            data:{"type":dataType,"ajaxReturn":1},
            dataType:"html",
            timeout:10000,
            success:function(r){
                if(r){
                    $(listBox).html(r);
                }else{
                    $.ui.showMask("连接服务器失败，请重试:)",true);
                }
            },
            error : function(x){
                $.ui.showMask("连接服务器失败，请重试:)",true);
            }
        });
    });
}
var lock_follow = 0;
//加关注
function dofollow(obj, fid){
    if(lock_follow == 1){
        return false;
    }
    lock_follow = 1;
    $.post(U('public/Follow/doFollow'),{'fid':fid},function(r){
            if(r.status === true || r.status === 1){
                    $(obj).removeClass('dofollow').addClass('unfollow');
                    //$(obj).attr('following', r.data['following']);
                    //$(obj).attr('follower', r.data['follower']);
                $(obj).find('font').addClass('followed');
                    initFollow();
            }else{
                    $.ui.showMask(r.info, true);
            }
            lock_follow = 0;
        }, 'json');
    return;
    $.ajax({
        type : "post",
        url : U('public/Follow/doFollow'),
        data : {'fid':fid},
        dataType:"json",
        timeout:2000,
        success:function(r){
            if(r.status === true || r.status === 1){
                    $(obj).removeClass('dofollow').addClass('unfollow');
                    //$(obj).attr('following', r.data['following']);
                    //$(obj).attr('follower', r.data['follower']);
                $(obj).find('font').addClass('followed');
                    initFollow();
            }else{
                    $.ui.showMask(r.info, true);
            }
            lock_follow = 0;
        },
        error : function(xhr, type){
            lock_follow = 0;
            $.ui.showMask("连接服务器失败，请重试", true);
        }
    });
}
//取消关注
function unfollow(obj, fid){
    if(lock_unfollow == 1){
        return false;
    }
    lock_unfollow = 1;
    $.ajax({//验证邮箱是否已注册
        type:"post",
        url :U('public/Follow/unFollow'),
        data:{"fid":fid},
        dataType:"json",
        timeout:2000,
        success:function(r){
            if(r.status === true || r.status === 1){
                    $(obj).removeClass('unfollow').addClass('dofollow');
                    //$(obj).attr('following', r.data['following']);
                    //$(obj).attr('follower', r.data['follower']);
                    $(obj).find('a,font').removeClass('followed');
                    initFollow();
            }else{
                    $.ui.showMask(r.info, true);
            }
            lock_unfollow = 0;
        },error : function(xhr, type){
            lock_unfollow = 0;
            $.ui.showMask("连接服务器失败，请重试", true);
        }
    });
}

//初始化关注事件
function initFollow(){
	//$('#people').find('.dofollow').each(function(){
		//$(this).find('font').html('关注');
	//});
	//关注事件
	$('#people').find('.dofollow').unbind();
	$('#people').find('.dofollow').on('click',function(){
		dofollow($(this), $(this).attr('data'));
	});
        $('#people').find('.dofollow').on('tap',function(){
		dofollow($(this), $(this).attr('data'));
	});
	//$('#people').find('.unfollow').each(function(){
		// if(~~$(this).attr('follower') == 1){
		// 	$(this).find('font').html('互相关注');
		// }else{
			//$(this).find('font').html('取消');		
		//}
	//});
	//取消关注事件
	$('#people').find('.unfollow').unbind();
	$('#people').find('.unfollow').on('tap',function(){
		unfollow($(this), $(this).attr('data'));
	});
        $('#people').find('.unfollow').on('tap',function(){
		unfollow($(this), $(this).attr('data'));
	});
}

//微吧加关注
function dofollowWeiba(obj, fid){
    $.ajax({//验证邮箱是否已注册
        type:"GET",
        url :U('w3g/Weiba/doFollowWeiba'),
        data:{"weiba_id":fid},
        dataType:"json",
        timeout:2000,
        success:function(r){
            if(r.status === true){
                $(obj).removeClass('dofollowWeiba').addClass('unfollowWeiba');
                //$(obj).attr('following', r.data['following']);
                $(obj).find('span').addClass('followed');
                initFollowWeiba();
            }else{
                $.ui.showMask(r.info, true);
            }
        },
        error : function(x){
             $.ui.showMask("连接服务器失败，请重试:)",true);
        }
    });
}
//微吧取消关注
function unfollowWeiba(obj, fid){
    $.ajax({//验证邮箱是否已注册
        type:"GET",
        url :U('w3g/Weiba/unFollowWeiba'),
        data:{"weiba_id":fid},
        dataType:"json",
        timeout:2000,
        success:function(r){
            if(r.status === true){
                $(obj).removeClass('unfollowWeiba').addClass('dofollowWeiba');
                //$(obj).attr('following', r.data['following']);
                $(obj).find('span').removeClass('followed');
                initFollowWeiba();
            }else{
                $.ui.showMask(r.info, true);
            }
        },
        error : function(x){
             $.ui.showMask("连接服务器失败，请重试:)",true);
        }
    });
}

//初始化微吧关注事件
function initFollowWeiba(){
    $('body').find('.dofollowWeiba').each(function(){
        $(this).find('span').html('+关注');
    });
    //关注事件
    $('.dofollowWeiba').unbind();
    $('.dofollowWeiba').click(function(){
        dofollowWeiba($(this), $(this).attr('data'));
    });
    $('body').find('.unfollowWeiba').each(function(){
        // if(~~$(this).attr('follower') == 1){
        //  $(this).find('font').html('互相关注');
        // }else{
            $(this).find('span').html('已关注');        
        //}
    });
    //取消关注事件
    $('.unfollowWeiba').unbind();
    $('.unfollowWeiba').click(function(){
        unfollowWeiba($(this), $(this).attr('data'));
    });
}

function doHighlight(a,b){
    highlightStartTag="<span style='color:red'>";
    highlightEndTag="</span>";
    var c="";
    var i=-1;
    var d=b.toLowerCase();
    var e=a.toLowerCase();
    while(a.length>0){
        i=e.indexOf(d,i+1);
        if(i<0){
            c+=a;
            a="";
        }else{
            if(a.lastIndexOf(">",i)>=a.lastIndexOf("<",i)){
                if(e.lastIndexOf("/script>",i)>=e.lastIndexOf("<script",i)){
                    c+=a.substring(0,i)+highlightStartTag+a.substr(i,b.length)+highlightEndTag;
                    a=a.substr(i+b.length);e=a.toLowerCase();
                    i=-1;
                }
            }
        }
    }
    return c;
};

$.fn.highlight=function(z){
    $(this).each(
        function(){
            if (z !== null) {
                $(this).html(doHighlight($(this).html(),z))
            }
        });
    return this;
}

ui = {};
ui.success = function(msg, autohide, time){
    $.ui.shaowMask(msg, autohide);
};
ui.error = function(msg, autohide, time){
    $.ui.showMask(msg, autohide);
};
ui.confirm = function(obj, msg, callback){
    callback = callback == undefined ? $(obj).prop('callback') : callback;
    if(confirm(msg) && callback != undefined){
        callback();
    }
    return false;
};


function start_highlight(key){
       // if(curType == 3){
       //      $('.u_tag').highlight(key3);
       //  }else{
            // for(one in key3){
                $('.people-find-recommenditem-info').highlight(key);
                //$('.ask_title').highlight(key3[one]);
           // }
      //  }

}


//初始化微吧事件监听
function initWeibaEvents(){
    var replyBox = $('.input,.re_box');
    //绑定表情事件
    // replyBox.find('.acts .face-block').click(function(event){
    //     var e = event || window.event;
    //     var btn = e.srcElement ? e.srcElement : e.target;

    //     if(replyBox.find('#faceList').length == 0){
    //         replyBox.append('<div id="faceList"></div>');
    //         $.post(U('public/Feed/getSmile'),{}, function(d){
    //             for(var i in d){
    //                 $("#faceList").append('<img width="24" onclick="textarea_append(this)" height="24" src="'+THEME_URL+'/image/expression/'+d[i]['type']+'/'+d[i]['filename']+'" title="'+d[i]['title']+'" emotion="'+d[i]['emotion']+'"/>');
    //             }
    //         }, 'json');
    //         replyBox.find('#faceList').css({"width":"60%","position":"absolute","left": "60px"});
    //         replyBox.find('#faceList img')
    //         .css("margin", '5px');
    //     }else{
    //         replyBox.find('#faceList').toggle();
    //     }
    // });
}
//追加表情
function textarea_append(obj){
    var replyBox = $('.input');
    cur_val = replyBox.find('textarea').val();
    replyBox.find('textarea').val(cur_val + $(obj).attr('emotion'));
}

//收藏微吧帖子
function favorite_weiba(_ts, id, uid, wid){
    if(!id || !uid || !wid){
        return false;
    }
    $.post(U('w3g/Weiba/favorite'), {'post_id': id, 'post_uid':uid, 'weiba_id':wid}, function(d){
        if(d==1){
             $(_ts).hide();
             $(_ts).siblings().show();
        }else{
             $.ui.showMask('收藏失败', true);
        }
    }, 'json');
}
//收藏微吧帖子
function unfavorite_weiba(_ts, id, uid, wid){
    if(!id || !uid || !wid){
        return false;
    }
   $.post(U('w3g/Weiba/unfavorite'), {'post_id': id, 'post_uid':uid, 'weiba_id':wid}, function(d){
        if(d==1){
             $(_ts).hide();
             $(_ts).siblings().show();
        }else{
             $.ui.showMask('解除收藏失败', true);
        }
    }, 'json');
}

//删除微吧回复操作
function del_weiba_reply(_ts, rid){
    if(!rid){
        return false;
    }
    $.post(U('widget/WeibaReply/delReply'), {'widget_appname': 'weiba','reply_id': rid}, function(d){
        if(d==1){
             $(_ts).parents('dl').remove();
        }else{
            $.ui.showMask('删除失败', true);
        }
    }, 'json');
}