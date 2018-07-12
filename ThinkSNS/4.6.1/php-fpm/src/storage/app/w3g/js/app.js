(function($){
     $.fn.extend({
        insertAtCaret: function(myValue){
         var $t=$(this)[0];
         if (document.selection){
          this.focus();
          sel = document.selection.createRange();
          sel.text = myValue;
          this.focus();
         }
         else 
          if ($t.selectionStart || $t.selectionStart == '0') {
           var startPos = $t.selectionStart;
           var endPos = $t.selectionEnd;
           var scrollTop = $t.scrollTop;
           $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
           this.focus();
           $t.selectionStart = startPos + myValue.length;
           $t.selectionEnd = startPos + myValue.length;
           $t.scrollTop = scrollTop;
          }
          else {
           this.value += myValue;
           this.focus();
          }
        },
        selectRange : function(start, end) {  
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
        }
     })
})(jQuery);


/**
 * Created by boooo on 14-1-28.
 */
if(!window.TS){var TS;}
TS.app={
    weibo:{
        'name':'weibo',
        url:function(hash){
            return U('w3g/Index/'+hash[1],[TS.app[hash[0]].page[hash[1]].param+'='+hash[2],'ajax']);
        },
        cache:{
            weiboListEnd:function(){
                var id = $($('#ts-list').find('.microblog-item')[0]).data('id');
                return id;
            },
            weiboListStart:function(){
                var index = $('#ts-list').find('.microblog-item').length - 1;
                var id = $($('#ts-list').find('.microblog-item')[index]).data('id');
                return id;
            },
            weiboListPage:1,
            //facePath:SITE_URL+'/addons/theme/stv1/_static/image/expression/miniblog/',
            facePath:SITE_URL+'/addons/theme/stv1/_static/image/expression/new/',
            faceDict:{
                //"shengli": "shengli.gif",
                //"shenma": "shenma.gif",
                //"shandian": "shandian.gif",
                //"se": "se.gif",
                //"ruo": "ruo.gif",
                //"shuai": "shuai.gif",
                //"shuijiao": "shuijiao.gif",
                //"tiaosheng": "tiaosheng.gif",
                //"tiaowu": "tiaowu.gif",
                //"tiaopi": "tiaopi.gif",
                //"tiao": "tiao.gif",
                //"taiyang": "taiyang.gif",
                //"quantou": "quantou.gif",
                //"qiu": "qiu.gif",
                //"no": "no.gif",
                //"ok": "ok.gif",
                //"nanguo": "nanguo.gif",
                //"meng": "meng.gif",
                //"ma": "ma.gif",
                //"peifu": "peifu.gif",
                //"pijiu": "pijiu.gif",
                //"qinqin": "qinqin.gif",
                //"qioudale": "qioudale.gif",
                //"qiang": "qiang.gif",
                //"pizui": "pizui.gif",
                //"pingpang": "pingpang.gif",
                //"touxiao": "touxiao.gif",
                //"tu": "tu.gif",
                //"zaijian": "zaijian.gif",
                //"zhadan": "zhadan.gif",
                //"yun": "yun.gif",
                //"yueliang": "yueliang.gif",
                //"youtaiji": "youtaiji.gif",
                //"zhemo": "zhemo.gif",
                //"zhuakuang": "zhuakuang.gif",
                //"zuotaiji": "zuotaiji.gif",
                //"zuqiu": "zuqiu.gif",
                //"zuohengheng": "zuohengheng.gif",
                //"zhutou": "zhutou.gif",
                //"zhuanquan": "zhuanquan.gif",
                //"youhengheng": "youhengheng.gif",
                //"yongbao": "yongbao.gif",
                //"weixiao": "weixiao.gif",
                //"wen": "wen.gif",
                //"weiqu": "weiqu.gif",
                //"wabi": "wabi.gif",
                //"tuzi": "tuzi.gif",
                //"woshou": "woshou.gif",
                //"xia": "xia.gif",
                //"xu": "xu.gif",
                //"yinxian": "yinxian.gif",
                //"xinsui": "xinsui.gif",
                //"xigua": "xigua.gif",
                //"xianwen": "xianwen.gif",
                //"love": "love.gif",
                //"liwu": "liwu.gif",
                //"deyi": "deyi.gif",
                //"diaoxie": "diaoxie.gif",
                //"dao": "dao.gif",
                //"danu": "danu.gif",
                //"dangao": "dangao.gif",
                //"e": "e.gif",
                //"fadai": "fadai.gif",
                //"feiwen": "feiwen.gif",
                //"fendou": "fendou.gif",
                //"fanu": "fanu.gif",
                //"fan": "fan.gif",
                //"fadou": "fadou.gif",
                //"daku": "daku.gif",
                //"dajiao": "dajiao.gif",
                //"cahan": "cahan.gif",
                //"caidao": "caidao.gif",
                //"bizui": "bizui.gif",
                //"bishi": "bishi.gif",
                //"baiyan": "baiyan.gif",
                //"chajin": "chajin.gif",
                //"cheer": "cheer.gif",
                //"dabian": "dabian.gif",
                //"dabing": "dabing.gif",
                //"da": "da.gif",
                //"ciya": "ciya.gif",
                //"chong": "chong.gif",
                //"gangga": "gangga.gif",
                //"geili": "geili.gif",
                //"kiss": "kiss.gif",
                //"ku": "ku.gif",
                //"ketou": "ketou.gif",
                //"kelian": "kelian.gif",
                //"keai": "keai.gif",
                //"kuaikule": "kuaikule.gif",
                //"kulou": "kulou.gif",
                //"liuhan": "liuhan.gif",
                //"liulei": "liulei.gif",
                //"lenghan": "lenghan.gif",
                //"lanqiu": "lanqiu.gif",
                //"kun": "kun.gif",
                //"kafei": "kafei.gif",
                //"jingya": "jingya.gif",
                //"haixiu": "haixiu.gif",
                //"haqian": "haqian.gif",
                //"haha": "haha.gif",
                //"guzhang": "guzhang.gif",
                //"gouyin": "gouyin.gif",
                //"hua": "hua.gif",
                //"huaixiao": "huaixiao.gif",
                //"jidong": "jidong.gif",
                //"jingkong": "jingkong.gif",
                //"huitou": "huitou.gif",
                //"huishou": "huishou.gif",
                //"hufen": "hufen.gif",
                //"aoman": "aoman.gif"
                "aoman":"aoman.png",
                "baiyan":"baiyan.png",
                "bishi":"bishi.png",
                "bizui":"bizui.png",
                "cahan":"cahan.png",
                "ciya":"ciya.png",
                "dabing":"dabing.png",
                "daku":"daku.png",
                "deyi":"deyi.png",
                "fadai":"fadai.png",
                "fanu":"fanu.png",
                "fendou":"fendou.png",
                "ganga":"ganga.png",
                "guzhang":"guzhang.png",
                "haha":"haha.png",
                "haixiu":"haixiu.png",
                "haqian":"haqian.png",
                "huaixiao":"huaixiao.png",
                "jingkong":"jingkong.png",
                "jingya":"jingya.png",
                "keai":"keai.png",
                "kelian":"kelian.png",
                "ku":"ku.png",
                "kuaikule":"kuaikule.png",
                "kulou":"kulou.png",
                "kun":"kun.png",
                "lenghan":"lenghan.png",
                "liuhan":"liuhan.png",
                "liulei":"liulei.png",
                "ma":"ma.png",
                "nanguo":"nanguo.png",
                "pizui":"pizui.png",
                "qiang":"qiang.png",
                "qiaoda":"qiaoda.png",
                "qinqin":"qinqin.png",
                "qioudale":"qioudale.png",
                "ruo":"ruo.png",
                "se":"se.png",
                "shuai":"shuai.png",
                "shuijiao":"shuijiao.png",
                "tiaopi":"tiaopi.png",
                "touxiao":"touxiao.png",
                "tu":"tu.png",
                "wabi":"wabi.png",
                "weiqu":"weiqu.png",
                "weixiao":"weixiao.png",
                "xia":"xia.png",
                "xu":"xu.png",
                "yinxian":"yinxian.png",
                "yiwen":"yiwen.png",
                "youhengheng":"youhengheng.png",
                "yun":"yun.png",
                "zaijian":"zaijian.png",
                "zhemo":"zhemo.png",
                "zhu":"zhu.png",
                "zuohengheng":"zuohengheng.png"


            },
            faceCount:111,
            video:{
                video:$('.m-i-b-p-video-img'),
                videoHtml:'',
                hasVideo:false,
                windowHeight:$(window).height(),
                videoElements:[]
            }
        },
        func:{
            readNewMsg:function(){
                var url = U('w3g/Index/mcount');
                var success = function(data){
					var key,tips,href,assoc = new Array();
					assoc['messageAt'] = parseInt(data.unread_atme);
					assoc['messageReplybox'] = parseInt(data.unread_comment);
					assoc['messageMsg'] = parseInt(data.unread_message);
					assoc['messageNotify'] = parseInt(data.unread_notify);
					assoc['messageLink'] = assoc['messageAt']+assoc['messageReplybox']
					                      +assoc['messageMsg']+assoc['messageNotify'];
					assoc['messageSidr'] = assoc['messageLink'];
					for(key in assoc){
						tips = $('#'+key+' .num');
						if(tips.length > 0){
							if(assoc[key] > 0){
								tips.text(assoc[key]>9?'···':assoc[key]);
								tips.attr('num', assoc[key]).show();
							}else{
								tips.attr('num', 0).text('').hide();
							}
						}
					}
					
					//alert(data.unread_atme);
                    /*var tipCache = new Array();
                    var ifHash = false;
                    for(x in data){
                        if(data[x]>0){
                            switch (x){
                                case 'unread_notify':
                                    tipCache.push(data[x]+'条系统消息');
                                    if(!ifHash){
                                        var tipText = '<a href="'+U('w3g/Index/msgbox')+'#notify"><i class="fa fa-envelope-o"></i> 您有';
                                        ifHash = true;
                                    }
                                    break;
                                case 'unread_atme':
                                    tipCache.push(data[x]+'条@');
                                    if(!ifHash){
                                        var tipText = '<a href="'+U('w3g/Index/msgbox')+'#at"><i class="fa fa-envelope-o"></i> 您有';
                                        ifHash = true;
                                    }
                                    break;
                                case 'unread_comment':
                                    tipCache.push(data[x]+'条评论');
                                    if(!ifHash){
                                        var tipText = '<a href="'+U('w3g/Index/msgbox')+'#replybox"><i class="fa fa-envelope-o"></i> 您有';
                                        ifHash = true;
                                    }
                                    break;
                                case 'unread_message':
                                    tipCache.push(data[x]+'条私信');
                                    if(!ifHash){
                                        var tipText = '<a href="'+U('w3g/Index/msgbox')+'#msg"><i class="fa fa-envelope-o"></i> 您有';
                                        ifHash = true;
                                    }
                                    break;
                                default :
                                    break;
                            }
                        }
                    }
                    if(tipCache.length>0){
                        if(tipCache.length===1){
                            tipText +=tipCache[0];
                        }else if(tipCache>1){
                            for(var i=0;i<tipCache.length;i++){
                                if(i!=tipCache.length-1){
                                    tipText += tipCache[i]+'，';
                                }else{
                                    tipText += '和'+tipCache[i];
                                }
                            }
                        }
                        tipText += '未读 </a><i id="header-tip-close" class="fa fa-times ts-listen" data-listen="weibo-readmsg-close"></i>';
                        var html = '<div id="header-tip">'+ tipText +'</div>';
                        $('#header-tip').remove();
                        $('body').append(html);
                        if($.ui.isWeChat()){
                            $('#header-tip').addClass('wechat');
                        }
                    }else{
                        $('#header-tip').remove();
                    }*/
                }
                $.ajax({
                    url:url,
                    dataType:'json',
                    success:success,
                    timeout:2000
                })
            },
            picViewInit:function(){
                if(/^\s*$/.test($('#pic-view').html())){
                    $.ui.loadDiv($.ui.cache.originalSelected);
                }else{
                    $('#pic').css({'height':($(window).height()-16*3)+'px','overflow-y':'hidden'});
                    $('#pic-footer-all').text($('.pic-img-box').length);
                    if($.ui.cache.picIndex!==undefined){
                        $('#pic-footer-now').text($.ui.cache.picIndex+1);
                        $('.pic-img-box').removeClass('pic-img-box-show');
                        $('#pic-img-box-'+$.ui.cache.picIndex).addClass('pic-img-box-show');
                    }else{
                        $('#pic-footer-now').text('1');
                        $('.pic-img-box').removeClass('pic-img-box-show');
                        $('#pic-img-box-0').addClass('pic-img-box-show');
                    }
                }
            },
            videViewInit:function(){
                if(/^\s*$/.test($('#videoview').html())){
                    $.ui.loadDiv($.ui.cache.originalSelected);
                }
            },
            picOnload:function(){
                var picItems = $('.pic-img-box');
                var idCache = '',
                    imgCache = [];
                for(var i=0;i<picItems.length;i++){
                    imgCache.push(new Image());
                    imgCache[i].src = $($(picItems[i]).find('img')).attr('src');
                    imgCache[i].id = 'pic-img-box-'+i;
                    imgCache[i].onload = function(){
                        $('#'+this.id).css('background','none');
                    }
                }
            },
            newMessage:{
                resizeBox:function(){
                    $('#new-message').height(Math.floor($(window).height()-$('#header').height()-20)+'px');
                },
                seacheUser:function(){
                    var reciverInput = $('#new-message-reciver');
                    if(!/^\t*$/.test(reciverInput.val())){
                        $.ajax({
                            url:U('widget/SearchUser/search'),
                            type:'POST',
                            dataType:'json',
                            data:{key:reciverInput.val(), follow:0, noself:1},
                            success:function(r){
                                if(r.data !== null){
                                    var data = r.data;
                                    var html='';
                                    $.each(data,function(index,item){
                                        if(item.uname === reciverInput.val()){
                                            reciverInput.data('uid',item.uid);
                                        }
                                        html += '<div class="msg-userlist-item" uid="'+ item.uid +'">'+ item.uname +'</div>';
                                    });
                                    $("#msg-userlist").html(html).show();
                                    if($('.msg-userlist-item').length===1 && $('.msg-userlist-item:first').text()===reciverInput.val()){
                                        reciverInput.data('uid',$('.msg-userlist-item:first').data('uid'));
                                    }
                                    var reciverItem = $('.msg-userlist-item');
                                    // reciverItem.unbind('click');
                                    reciverItem.bind('click',function(){
                                        $('#new-message-reciver').data('uid',$(this).attr('uid')).val($(this).text());
                                        $("#msg-userlist").html('').hide();
                                    });
                                }else{
                                    var html = '<div class="msg-userlist-item" uid="0">没有该用户</div>';
                                    $("#msg-userlist").html(html).show();
                                }
                            }
                        });
                    }else{
                        $("#msg-userlist").html('').hide();
                    }
                },
                sendMsg:function(){
                    var sendData =  {to:$('#new-message-reciver').data('uid'), content:$('#new-message-content').val(), attach_ids:''};
                    //console.log(sendData);
                    $.ajax({
                        url:U('public/Message/doPost'),
                        type:'POST',
                        dataType:'json',
                        data:sendData,
                        success:function(r){
                            if(r.status == 1 && r.data == "发送成功"){
                                $.ui.goBack();
                                TS.showMaskOK();
                            } else {
                                $.ui.showMask('发送失败，请重试。',true);
                            }
                        }
                    })
                },
                bind:function(){
                    TS.app.weibo.func.newMessage.resizeBox();
                    var reciverInput = $('#new-message-reciver');
                    reciverInput.bind({
                        'keydown':function(){
                            TS.app.weibo.func.newMessage.seacheUser();
                        },
                        'focus':function(){
                            TS.app.weibo.func.newMessage.seacheUser();
                        }
                    });
                    $('#new-message-send,#new-message-content').bind('focus',function(){
                        $("#msg-userlist").hide();
                    });
                    //$('#new-message-send').bind('tap',TS.app.weibo.func.newMessage.sendMsg);
                    $('#new-message-send').bind('click',TS.app.weibo.func.newMessage.sendMsg);
                },
                unbind:function(){
                    var reciverInput = $('#new-message-reciver'),
                        reciverItem = $('.msg-userlist-item');
                    reciverInput.unbind('keydown focus');
                    reciverItem.unbind('click');
                    $('#new-message-send,#new-message-content').unbind('focus');
                    $('#new-message-send').unbind('tap click');
                }
            },
            listVideoInit:function(){
                TS.cache.video = $('.m-i-b-p-video-img');
                TS.cache.videoHtml = '';
                TS.cache.hasVideo = false;
                TS.cache.windowHeight = $(window).height();
                TS.cache.videoElements = [];
                $.each(TS.cache.video, function(index, item) {
                    TS.cache.videoElements.push({
                        el:TS.cache.video.get(index),
                        top:$(item).offset().top,
                        bottom:$(item).offset().top+$(item).height(),
                        video:$(item).parent().data('video'),
                        height:$(item).height()
                    });
                    $($('.m-i-b-p-video-box').get(index)).css({
                        'height':$(item).height()+'px',
                        'background-image':'url('+$(item).attr('src')+')',
                        'background-size':'100%'
                    });
                    $($('.m-i-b-p-video-box').get(index)).attr('height',$(item).height());
                });
            },
            scroll4video:function(){
                TS.cache.hasVideo = false;
                for(var i=0,len=TS.cache.videoElements.length;i<len;i++){
                    if ( TS.cache.videoElements[i].top>=window.scrollY && TS.cache.videoElements[i].bottom <= (window.scrollY +TS.cache.windowHeight) && !TS.cache.hasVideo){
                        $(TS.cache.videoElements[i].el).hide();
                        $(TS.cache.videoElements[i].el).siblings('div').hide();
                        TS.cache.videoHtml = '<video src="'+TS.cache.videoElements[i].video+'" controls="controls" loop="loop" width="100%" height="'+TS.cache.videoElements[i].height+'">您的浏览器不支持 video 标签。</video>';
                        if($($('.m-i-b-p-video-box').get(i)).find('video').length===0){
                            $($('.m-i-b-p-video-box').get(i)).append(TS.cache.videoHtml);
                            $($('.m-i-b-p-video-box').get(i)).css({'css':$($('.m-i-b-p-video-box').get(i)).find('video').height()+'px'});
                            window.setTimeout(function(){
                                if($($('.m-i-b-p-video-box').get(i)).find('video').length>0){
                                    $($('.m-i-b-p-video-box').get(i)).find('video').get(0).play();
                                }
                            },1000);
                            $($('.m-i-b-p-video-box').get(i)).find('video').get(0).play();
                        }
                        TS.cache.hasVideo = true;
                    }else{
                        $(TS.cache.videoElements[i].el).show();
                        $(TS.cache.videoElements[i].el).siblings('div').show();
                        $($($('.m-i-b-p-video-box').get(i)).find('video')).remove();
                    }
                }
            },
            listWatchVideo:function(){
                TS.app.weibo.func.listVideoInit();
                //window.addEventListener('scroll',TS.app.weibo.func.scroll4video);
            },
			listLoad:function(){
				var wrapper = $('#wrapper');
				var loadUrl = wrapper.data('url');
				var nav = $('#weibo-type-nav');
				//下拉提示容器
				var pd = $('#pull-down');
				//上拉提示容器
				var pu = $('#pull-up');
				//下拉提示容器高度
				var pdHeight = pd.outerHeight(true);
				//下拉ICON图标
				var pdIcon = $('#pull-down .icon');
				//下拉文字提示
				var pdSpan = $('#pull-down span');
				var startY = undefined;
				var pdLoadStatus = 0;
				var puLoadStatus = false;

				//加载更多
				var loadMoreRequest = null;
				var loadMore = function(){
					if(loadMoreRequest){
						loadMoreRequest.abort();
					}
					var loadId = wrapper.data('load');
					if(!loadId) {
						setPuStatus(false);
						return ;//无法再次进行加载
					}

					loadMoreRequest = $.post(loadUrl, {load_id:loadId}, function(result){
						if(result.count > 1){
							wrapper.data('load', result.minId);
							pu.before(result.data);
						}else{
							wrapper.data('load', 0);
						}
						loadMoreRequest = null;
						setPuStatus(false);//加载完毕
					}, 'json');

				}

				//加载最新
				var loadNewRequest = null;
				var loadNew = function(){
					if(loadNewRequest){
						loadNewRequest.abort();
					}
					var newId = wrapper.data('new');
					if(typeof newId == 'undefined') {
						setPdStatus('end','ajax');
						return;//无法进行加载
					}
					loadNewRequest = $.post(loadUrl, {new_id:newId}, function(result){
						if(result.count > 0){
							wrapper.data('new', result.maxId);
							nav.after(result.data);
						}
						loadNewRequest = null;
						setPdStatus('end','ajax');
					}, 'json');
				}

				var setPdStatus = function(status, param){
					if(status == 'start'){
						if(pd.stop().is(':visible')){
							var mt = parseFloat(pd.css('marginTop'));
							startY = param-((pdHeight+mt)*2.5);
						}else{
							startY = param;
						}
					}else if(status == 'end'){
						if(isNaN(startY) && param!='ajax'){
							return;
						}
						startY = undefined;
						if(pdLoadStatus == 1){
							pdLoadStatus = 2;
							pd.stop().animate({marginTop:0}, 300, function(){
								loadNew(); // 开始加载
								pdIcon.addClass('load');
								pdSpan.text('正在刷新...');
							});
						}else{
							pd.stop().animate({marginTop:-pdHeight}, 300, function(){
								pd.removeAttr('style').hide();
							});
						}
					}else if(status == 'move'){
						if(!isNaN(startY) && $(document).scrollTop()==0){
							var dist = param-startY;
							var mt = dist/2.5-pdHeight;
							if(mt > -pdHeight){
								var opacity = ((mt+pdHeight) / pdHeight);
								opacity = opacity<1?opacity.toFixed(2):1;
								if(opacity == 1){
									pdLoadStatus = 1;
									pdSpan.text('释放刷新...');
								}else{
									pdLoadStatus = 0;
									pdSpan.text('下拉刷新...');
								}
								if(loadNewRequest){
									pdIcon.addClass('load');
									pdSpan.text('正在刷新...');
								}else{
									pdIcon.removeClass('load');
								}
								pd.stop().css({
									display: 'block',
									opacity: opacity,
									marginTop: mt
								});
								return false;
							}
						}
					}
				}

				var getClientY = function(e, type){
					if(e.type.indexOf('touch')>-1 && e.originalEvent.targetTouches){
						return e.originalEvent.targetTouches[0].clientY;
					}else{
						return e.clientY;
					}
				}

				var setPuStatus = function(status, param){
					if(status){
						if(puLoadStatus) return;
						var dh  = $(document).height();
						var dst = $(document).scrollTop();
						var wh  = $(window).height();
						if(dh-wh-dst <= 20){
							puLoadStatus = true;
							pu.show();//显示加载中
							loadMore();//加载数据
						}else{
							pu.hide();//隐藏加载中
						}
					}else{
						puLoadStatus = false;
						pu.hide(); //隐藏加载中
					}
				}

				//初始化，设置高和监测触摸状态
				var initWrapper = function(){
					wrapper.on('mousedown touchstart', function(e){
						return setPdStatus('start', getClientY(e));
					})
					$('body').on('mouseup touchend', function(e){
						return setPdStatus('end');
					}).on('mousemove touchmove', function(e){
						return setPdStatus('move', getClientY(e));
					});
					$(document).on('scroll', function(e){
						setPuStatus(true);
					});
					$('#topcontrol,#topcontrol a').off('click', '**' );
					$('#topcontrol a').click(function(){
						return false;
					});
				}

				$(function(){ initWrapper(); });
			}
            //func
        },
        listen:{
            detail:function(param,el,e){
                e.stopPropagation();
                window.location.href=U('w3g/Index/detail',['weibo_id='+param]);
            },
            doreply:function(param, el){
                if(el.hasClass('disable')){
                    return false;
                }
                var url = U('w3g/Index/doComment');
                var replyContent = $('#reply-textarea').val();
                var data = {
                    content:replyContent,
                    feed_id:TS.cache.detailId,
                    app_uid:TS.cache.app_uid,
                    comment_old:~~$('#comment_old_reply:checked').val(),
                    //comment_old:TS.cache.comment_old,
                    rowid:TS.cache.detailId,
                    ifShareFeed:~~$('#ifShareFeed_reply:checked').val(),
                    at:TS.cache.at,
                    uid:TS.cache.profile.uid
                };
                var success = function(r){
                    if(r.success=='1'){
                        //console.log(param);
                        if(param==='detail'){//detail page reply
                            var html = ['<div class="c_comments ts-clearboth ts-padding10 ts-borderbottom" touid="'+TS.cache.profile.uid+'" rowid="'+TS.cache.detailId+'" appuid="'+TS.cache.app_uid+'" ccid="'+r.ccid+'">',
                                '<div class="c_comments_ava ts-floatleft">',
                                '<a href="#"><img src="'+TS.cache.profile.avatar_small+'" width=40 height=40></a>',
                                '</div>',
                                '<div class="c_comments_content">',
                                '<div class="c_comments_content_info">',
                                '<div class="c_comments_content_info_name" id="cc_name_'+ r.ccid+'">'+TS.cache.profile.uname+'</div>',
                                '<div class="c_comments_content_info_time">刚刚</div>',
                                '</div>',
                                '<p class="c_comments_content_p">'+replyContent+'</p>',
                                '<div class="c_comments_reply ts-font fa-reply ts-listen" data-listen="weibo-toreplycomment-'+r.ccid+'"></div>',
                                '</div></div>'].join('\n');
                            //console.log(html);
                            $('#detail').find('#c_comment_box').prepend(html);
                        }else if(param==='list'){//list page reply
                            var replyButton =  $($('#m-i-footer-'+TS.cache.detailId).find('.ts-number').get(1));
                            replyButton.text(Number(String(replyButton.text()).trim())+1);
                        }
                        TS.showMaskOK();
                        $.ui.goBack();
                        $('#reply-textarea').val('');
                    }else{//unsuccess
                        //console.log('reply unsuccess!');
                        TS.showMask(r.des);
                    }
                };
                var error = function(xhr,type){
                    TS.showMask('发送失败，请稍后重试。');
                };
                $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    timeout:2000,
                    dataType:'json',
                    success:success,
                    error:error
                });
            },
            retweet:function(){
                TS.cache.weiboDetailid = TS.spliteHash(window.location.hash)[2];
                $.ui.loadDiv('retweet');
            },
            toreply:function(param){
//                $(this).blur();
                TS.cache.comment_old=$(document).find('#detail .m-i-b-p').html().trim();
                $('#ts-reply-submit').data('listen','weibo-doreply-detail');
                $.ui.loadDiv('reply');
                //$('#reply-textarea').data('listen','weibo-doreply-detail');
                this.checknums(true, $('#reply-textarea'), '', true);
                $('#reply-textarea').focus();
            },
            list2reply:function(param,el){
                //var originBtn = ~~el.data('origin');
                var curoriginBtn = ~~el.data('curorigin');
                if(!curoriginBtn){
                    $.ui.showMask('您没有评论权限', true);
                    //$.ui.goBack();
                    return;
                }
                if(~~param){
                    if(el.data('query') !== undefined){
                        param += el.data('query');
                    }
                    window.location.href = U('w3g/Index/reply') + '&weibo_id=' + param;
                }
                return;
                /*
                var parent = $(el.parent(".pure-g").get(0));
                var shareBtn = ~~el.data('share');
                var originBtn = ~~el.data('origin');
                var curoriginBtn = ~~el.data('curorigin');
                if((!el.data('repost') && !originBtn) || !curoriginBtn){
                    $.ui.showMask('您没有评论权限', true);
                    $.ui.goBack();
                }
                //如果是转发分享且可评论原作者
                if(el.data('repost') && originBtn){
                    $('.ckb').find('#toOrigin').show();                    
                }else{
                    $('.ckb').find('#toOrigin').hide();
                }
                TS.cache.app_uid=parent.data('uid');
                TS.cache.at=parent.data('uname');
                //TS.cache.comment_old=$('#m-i-b-p-'+param).html().trim();
                $("#towho").html(TS.cache.at);
                $('#ts-reply-submit').data('listen','weibo-doreply-list');
                $.ui.loadDiv('reply');
                var defaultHtml = el.data('default') ? el.data('default') : '';
                $('#reply-textarea').val(defaultHtml);
                this.checknums(true, $('#reply-textarea'), '', true);
                $('#reply-textarea').focus();
                */
            },
            sendreply:function(param,el){
                if(el.hasClass('disable')){
                    return false;
                }
                var url = U('widget/Comment/addcomment');
                var replyContent = $('#reply-textarea').val();
                if(replyContent === ''){
                    $.ui.showMask('评论内容不能为空', true);
                    return ;
                }
                var data = {
                    app_name : 'public',
                    table_name : el.data('approwtable'),
                    app_uid : ~~el.data('appuid'),
                    row_id : ~~el.data('rowid'),
                    to_comment_id : ~~el.data('tocommentid'),
                    to_uid : ~~el.data('touid'),
                    app_row_id : ~~el.data('approwid'),
                    app_row_table : el.data('approwtable'),
                    content:replyContent,
                    ifShareFeed : ~~$('#ifShareFeed_reply:checked').val(),
                    comment_old : ~~$('#comment_old_reply:checked').val(),
                    app_detail_url:U('w3g/Index/detail') + '&weibo_id=' + ~~el.data('rowid'),
                    talkbox : ~~el.data('istalkbox')
                };
                var success = function(r){
                    if(r.status=='1'){
                        $('#reply-textarea').val('');
                        TS.showMaskOK();
                        if(el.data('referer') && el.data('referer') != location.href){
                            window.location.href= el.data('referer');                            
                        }else{
                            window.location.href = U('w3g/Index/msgbox') + '#replybox';
                        }
                        
                        //$.ui.goBack();
                       //console.log(param);
                        /*
                        if(param==='detail'){//detail page reply
                            var html = ['<div class="c_comments ts-clearboth ts-padding10 ts-borderbottom" touid="'+TS.cache.profile.uid+'" rowid="'+TS.cache.detailId+'" appuid="'+TS.cache.app_uid+'" ccid="'+r.ccid+'">',
                                '<div class="c_comments_ava ts-floatleft">',
                                '<a href="#"><img src="'+TS.cache.profile.avatar_small+'" width=40 height=40></a>',
                                '</div>',
                                '<div class="c_comments_content">',
                                '<div class="c_comments_content_info">',
                                '<div class="c_comments_content_info_name" id="cc_name_'+ r.ccid+'">'+TS.cache.profile.uname+'</div>',
                                '<div class="c_comments_content_info_time">刚刚</div>',
                                '</div>',
                                '<p class="c_comments_content_p">'+replyContent+'</p>',
                                '<div class="c_comments_reply ts-font fa-reply ts-listen" data-listen="weibo-toreplycomment-'+r.ccid+'"></div>',
                                '</div></div>'].join('\n');
                            //console.log(html);
                            $('#detail').find('#c_comment_box').prepend(html);
                        }else if(param==='list'){//list page reply
                        */
                        //var replyButton =  $($('#m-i-footer-'+~~el.data('rowid')).find('.ts-number').get(1));
                        //replyButton.text(Number(String(replyButton.text()).trim())+1);
                        //}
                        
                    }else{//unsuccess
                        //console.log('reply unsuccess!');
                        TS.showMask(r.data);
                    }
                };
                var error = function(xhr,type){
                    TS.showMask('发送失败，请稍后重试。');
                };
                $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    timeout:2000,
                    dataType:'json',
                    success:success,
                    error:error
                });                
            },
            reply2reply:function(param,el){
                TS.cache.detailId=param;
                var parent = $(el.parent(".pure-g").get(0));
                var shareBtn = ~~el.data('share');
                var originBtn = ~~el.data('origin');
                TS.cache.to_uid=parent.data('uid');
                TS.cache.at=parent.data('uname');
                TS.cache.to_comment_id = ~~el.data('cid');
                //TS.cache.comment_old=$('#m-i-b-p-'+param).html().trim();
                $("#towho").html(TS.cache.at);
                //$('#ts-reply-submit').data('listen','weibo-doreply2reply-list');
                $.ui.loadDiv('replyD');
                var defaultHtml = el.data('default') ? el.data('default') : '';
                $('#reply-textarea').val(defaultHtml);
                if(shareBtn>0){
                    $('label#toShare').show();
                }
                if(originBtn>0){
                    $('label#toOrigin').show();
                }
                this.checknums(true, $('#reply-textarea'), '', true);
                $('#reply-textarea').focus();
            },
            doreply2reply:function(param, el){
                if(el.hasClass('disable')){
                    return false;
                }
                var url = U('w3g/Index/doComment');
                var replyContent = $('#reply-textarea').val();
                var data = {
                    content:replyContent,
                    feed_id:TS.cache.detailId,
                    to_uid:TS.cache.to_uid,
                    comment_old:~~$('#comment_old_reply:checked').val(),
                    to_comment_id : TS.cache.to_comment_id,
                    //comment_old:TS.cache.comment_old,
                    row_id:TS.cache.detailId,
                    ifShareFeed:~~$('#ifShareFeed_reply:checked').val(),
                    at:TS.cache.at,
                    app_uid:TS.cache.profile.uid
                };
                var success = function(r){
                    if(r.success=='1'){
                        //console.log(param);
                        if(param==='detail'){//detail page reply
                            var html = ['<div class="c_comments ts-clearboth ts-padding10 ts-borderbottom" touid="'+TS.cache.profile.uid+'" rowid="'+TS.cache.detailId+'" appuid="'+TS.cache.app_uid+'" ccid="'+r.ccid+'">',
                                '<div class="c_comments_ava ts-floatleft">',
                                '<a href="#"><img src="'+TS.cache.profile.avatar_small+'" width=40 height=40></a>',
                                '</div>',
                                '<div class="c_comments_content">',
                                '<div class="c_comments_content_info">',
                                '<div class="c_comments_content_info_name" id="cc_name_'+ r.ccid+'">'+TS.cache.profile.uname+'</div>',
                                '<div class="c_comments_content_info_time">刚刚</div>',
                                '</div>',
                                '<p class="c_comments_content_p">'+replyContent+'</p>',
                                '<div class="c_comments_reply ts-font fa-reply ts-listen" data-listen="weibo-toreplycomment-'+r.ccid+'"></div>',
                                '</div></div>'].join('\n');
                            //console.log(html);
                            $('#detail').find('#c_comment_box').prepend(html);
                        }else if(param==='list'){//list page reply
                            var replyButton =  $($('#m-i-footer-'+TS.cache.detailId).find('.ts-number').get(1));
                            replyButton.text(Number(String(replyButton.text()).trim())+1);
                        }
                        TS.showMaskOK();
                        $.ui.goBack();
                        $('#reply-textarea').val('');
                    }else{//unsuccess
                        //console.log('reply unsuccess!');
                        TS.showMask(r.des);
                    }
                };
                var error = function(xhr,type){
                    TS.showMask('发送失败，请稍后重试。');
                };
                $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    timeout:2000,
                    dataType:'json',
                    success:success,
                    error:error
                });
            },
            list2digg:function(param,el){
//                if($(el.parent(".pure-g").get(0)).data('uid')!=TS.cache.profile.uid){
                    if(el.data('isdig')=='0'){
                        var url = U('widget/Digg/addDigg');
                    }else{
                        var url = U('widget/Digg/delDigg');
                    }
                    var data = {feed_id:param};
                    var success = function(r){
                        if(r.status=="1"){
                            var dignumber = $(el.find('.ts-number').get(0));
                            if(el.data('isdig')=='0'){
                                dignumber.text(Number(String(dignumber.text()).trim())+1);
                                    el.find('.ts-number').show();
                                el.data('isdig','1');
                                el.addClass('pure-light');
                            }else{
                                dignumber.text(Number(String(dignumber.text()).trim())-1);
                                if(Number(String(dignumber.text()).trim())-1 < 1){
                                    el.find('.ts-number').hide();
                                }
                                el.data('isdig','0');
                                el.removeClass('pure-light');
                            }
//                            TS.showMaskOK();
                        }else{
                            TS.showMask('点赞失败，请稍后重试。');
                        }
                    };
                    var error = function(xhr,type){
                        TS.showMask('请求超时');
                    };
                    $.ajax({
                        type:'POST',
                        url:url,
                        data:data,
                        timeout:2000,
                        dataType:'json',
                        success:success,
                        error:error
                    });
//                }
            },
			list2del:function(param,el,e){
				e.stopPropagation();
				e.stopImmediatePropagation();
				var del = $('#weibo_detail-'+param);
				if(!del.length || del.data('del')){
					return; //重复删除
				}
				del.data('del', true);
				if(!confirm('确定删除')){
					setTimeout(function(){
						del.data('del', false);
					}, 1000);
					return ; // 误点击
				}
				var data = {feed_id:param};
				var success = function(result){
					if(result.status){
						del.fadeOut('slow', function(){ del.remove(); });
					}else{
						setTimeout(function(){
							del.data('del', false);
						}, 1000);
						TS.showMask('删除失败，请稍后重试');
					}
				}
				$.ajax({
					type:'POST',
					url:U('public/Feed/removeFeed'),
					data:data,
					dataType:'json',
					success:success
				});
			},
            list2delete:function(param,el){
                if(!confirm('确定删除')){
                    return ;
                }
//                if($(el.parent(".pure-g").get(0)).data('uid')!=TS.cache.profile.uid){
                    var data = {comment_id:param};
                    var success = function(r){
                        alert(JSON.stringify(r));
                        if(r==1){
                            el.fadeOut('slow', function(){
                                el.parents('#comment_item_'+ param).remove();
                            })                            
//                            TS.showMaskOK();
                        }else{
                            TS.showMask('删除失败，请稍后重试。');
                        }
                    };
                    $.ajax({
                        type:'POST',
                        url:U('widget/Comment/delcomment'),
                        data:data,
                        dataType:'json',
                        success:success
                    });
//                }
            },
            list2favorite:function(param,el){
//                if($(el.parent(".pure-g").get(0)).data('uid')!=TS.cache.profile.uid){
                    if(el.data('isstar')=='0'){
                        var url = U('w3g/Index/doFavorite');
                    }else{
                        var url = U('w3g/Index/doUnFavorite');
                    }
                    var data = {
                        "feed_id":param,
                        "type":el.data('type')
                    };
//                  var starnumber = $(el.find('.ts-number').get(0));
                    var star = $(el.find('.ts-font').get(0));
                    if(el.data('isstar')=='0'){
                        star.addClass('fa-star').removeClass('fa-star-o');
//                                starnumber.text(Number(starnumber.text().trim())+1);
                        el.data('isstar','1');
                        el.addClass('pure-light');
                    }else{
                        star.removeClass('fa-star').addClass('fa-star-o');
//                                starnumber.text(Number(starnumber.text().trim())-1);
                        el.data('isstar','0');
                        el.removeClass('pure-light');
                    }
//                    TS.showMaskOK();
                    var success = function(r){
                        if(r.success==='1'){

                        }else{
                            TS.showMask('收藏失败，请稍后重试。');
                            var star = $(el.find('.ts-font').get(0));
                            if(el.data('isstar')=='0'){
                                star.addClass('fa-star').removeClass('fa-star-o');
                                el.data('isstar','1');
                            }else{
                                star.removeClass('fa-star').addClass('fa-star-o');
                                el.data('isstar','0');
                            }
                        }
                    };
                    var error = function(xhr,type){
                        TS.showMask('请求超时');
                    };
                    $.ajax({
                        type:'POST',
                        url:url,
                        data:data,
                        timeout:2000,
                        dataType:'json',
                        success:success,
                        error:error
                    });
//                }//if own
            },
            login:function(param){
                var mail = $('#login-input-email').val();
                var password = $('#login-input-pwd').val();
                if(mail=="" || password==""){
                    TS.showMask(" 帐号和密码都不能为空");
                // }else if(!TS.r.mail(mail) && !TS.r.uname(mail)){
                    // TS.showMask("请检查您的帐号格式");
//                }else if(!TS.r.password(password)){
//                    TS.showMask("密码由字母，数字，符号组成，6-15个字符，区分大小写");
                }else{
                    $.ui.showMask("验证中...");
                    $.ajax({
                        type:'POST',
                        url:U('w3g/Public/doLogin'),
                        data:{
                            "email":mail,
                            "password":password
                        },
                        dataType:'json',
                        success:function(data,status){
                            if(data.success=='1'){
                                window.location.href=U("w3g/Public/home");
                            }else{
                                TS.showMask(data.des);
                            }
                        },
                        error:function(xhr,type){TS.showMask(Error);}
                    });
                }
            },
            list2retweet:function(param, el){
                if(!isNaN(param)){
                    window.location.href=U('w3g/Index/retweet') + '&weibo_id='+ param;
                    return ;
                }else{
                    $.ui.showMask('参数错误', true);
                    return ;
                }
                TS.cache.detailId = param;
                TS.cache.sid = el.data('repost');
                TS.cache.comment_touid = el.parent('div.pure_g').data('uid');
                var _html = '';
                var user = '';
                //当前分享作者
                user = $('#weibo-detail-'+param).find('.m-i-h-i-l-name').text();
                if($('body').find('#repost-'+param).length>0){
                    //原分享作者
                    user = $('#repost-'+param).find('span').text();
                    _html = '@' + user + " //" + $('#m-i-b-p-' + param).html().replace(/\s{2,}/,'').replace(/<img[^>]*\/(.*)\.(gif|jpg)[^>]*>/ig, "[$1]").replace(/<a[^>]*>([^<]*)<\/a>/ig, '$1').replace(/\s{2,}/, '');
                }
                //初始化多选框
                $('.ckb input[type="checkbox"]').attr('checked', false);
                //缓存
                TS.cache.user = user;
                TS.cache.cancomment = ~~el.data('origin');
                TS.cache._html = _html;
                if(TS.cache.cancomment == 0){
                    $('#comment_old_retweet').parent('label').hide();
                }else{
                    $('#comment_old_retweet').parent('label').show();
                }
                $("font#towho").html(user);
                $('#retweet-textarea').val(_html);
                $('#retweet-textarea').focus();
                $.ui.loadDiv('retweet');
                //初始化输入框字数
                //this.checknums(true, $('#retweet-textarea'), '', true);
                //$('#retweet-textarea').focus();
            },
            doretweet:function(param, el){
                if(el.hasClass('disable')){
                    return false;
                }
                //var url = U('w3g/Index/doForward');
                var url = U('public/Feed/shareFeed');
                var data = {
                    type : 'feed',
                    app_name : 'public',
                    curtable : el.data('curtable'),
                    curid : ~~el.data('curid'),
                    sid : ~~el.data('sid') ? ~~el.data('sid') : ~~el.data('curid'),
                    body:String($('#retweet-textarea').val()).trim(),
                    content:'',
                    comment:~~$('#comment_old_retweet:checked').val()
                    //comment_touid : ~~el.data('commenttouid')
                };
                /*
                var data = {
                    type:'feed',
                    app_name:'public',
                    curid:TS.cache.detailId,
                    sid:TS.cache.sid ? TS.cache.sid : TS.cache.detailId,
                    body:String($('#retweet-textarea').val()).trim(),
                    content:'',
                    comment:~~$('#comment_old_retweet:checked').val(),
//                    comment_old :~~$('#comment_old_retweet:checked').val(),
                    comment_touid: TS.cache.comment_touid,
                    curtable:'feed'
                };*/
                var success = function(r){
                    if(r.status == 1){
                        $('#retweet-textarea').val('');
                        TS.showMaskOK();
                        var referer = el.data('referer');
                        if(referer !== '' && referer != location.href){
                            location.href = referer;
                        }else{
                            location.href=U('w3g/Index/index');
                        }
                    }else{
                        $.ui.showMask(r.data, true);
                    }
                    return;
                    
                    
                    if(r!="参数错误" && r!="内容不能为空" && r!="0" && r!="只能上传图片附件" &&r!="发布失败，字数超过限制"){
                        //更新转发数量
                        var retweetButton =  $($('#m-i-footer-'+TS.cache.detailId).find('.ts-number').get(0));
                        retweetButton.text(Number(String(retweetButton.text()).trim())+1);
                        var totalWeibo = $('body').find('#i_info_counts_box_weibo .i_info_counts_num');
                        if(~~totalWeibo){
                            totalWeibo.text(Number(String(totalWeibo.text()).trim())+1);
                        }
                        //if(TS.cache.pageType!=='detail' && r.indexOf('m-i-b-p-repost') == -1){
                        //分享详情页，分享首页推荐栏不追加转发分享
                        var disrecmmend = true;
                        if($('body').find('#weibo-type-nav').length>0 && $('#weibo-type-nav').find('.weibo-type-nav-item:eq(0)').hasClass('active')){
                            disrecmmend = false;
                        }
                        if(TS.cache.pageType!=='detail' && disrecmmend){
                            if($('body').find('#weibo-type-nav').length>0){
                                $('#weibo-type-nav').after(r);
                            }else{
                                $('#ts-list').prepend(r);
                            }
                        }
                        TS.showMaskOK();
                        $.ui.goBack();
                        $('#retweet-textarea').val('');
                    }else{
                        if(r=='0'){
                            r="转发失败, 请稍后重试。";
                        }
                        TS.showMask(r);
                    }
                };
                var error = function(xhr,type){
                    TS.showMask('转发超时，请稍后重试。');
                };
                $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    timeout:2000,
                    dataType:'json',
                    success:success,
                    error:error
                });
            },
            toreplycomment:function(param,el){                
                TS.cache.detailId=param;
                var parent = $(el.parent('.c_comments_content').parent('.c_comments').get(0));
                TS.cache.app_uid=$($('.m-i-footer>.pure-g').get(0)).data('uid');
                TS.cache.comment_id=parent.attr('ccid');
                TS.cache.rowid=parent.attr('rowid');
                TS.cache.touid=parent.attr('touid');
                TS.cache.comment_old=String($(parent.find('.c_comments_content_p').get(0)).html()).trim();
                TS.cache.at=String($(parent.find('.c_comments_content_info_name').get(0)).html()).trim();
               // $('#ts-reply-submit').data('listen','weibo-docommentD-'+param);
                $.ui.loadDiv('replyD');
            },
            docommentD:function(param,el){
                var url = U('w3g/Index/doCommentD');
                var replyContent = $('#reply-textareaD').val();
                var data = {
                    rowid:TS.cache.rowid,
                    content:replyContent,
                    comment_id:TS.cache.comment_id,
                    touid:TS.cache.touid,
                    appid:TS.cache.app_uid,
                    comment_old:~~$('#comment_old_replyD:checked').val(),
                    ifShareFeed:~~$('#ifShareFeed_replyD:checked').val(),
                    at:TS.cache.at
                };
                var success = function(r){
                    if(r.success=='1'){
                        var atLink = '<a data-ignore="true" class="c_a" href="'+U('w3g/Index/weibo',['uid='+TS.cache.touid])+'">@'+TS.cache.at+'</a>：';
                        var html = ['<div class="c_comments ts-clearboth ts-padding10 ts-borderbottom" touid="'+TS.cache.profile.uid+'" rowid="'+TS.cache.detailId+'" appuid="'+TS.cache.app_uid+'" ccid="0">',
                            '<div class="c_comments_ava ts-floatleft">',
                            '<a href="#">',
                            '<img src="'+TS.cache.profile.avatar_small+'" width=40 height=40>',
                                '</a>',
                            '</div>',
                                '<div class="c_comments_content">',
                                '<div class="c_comments_content_info">',
                                    '<div class="c_comments_content_info_name">'+TS.cache.profile.uname+'</div>',
                                    '<div class="c_comments_content_info_time">刚刚</div>',
                                '</div>',
                                '<p class="c_comments_content_p">'+atLink+replyContent+'</p>',
                            '</div></div>'].join('\n');
                        $('#detail').find('#c_comment_box').prepend(html);
                        var replyButton =  $($('#m-i-footer-'+TS.cache.detailId).find('.ts-number').get(1));
                        replyButton.text(Number(String(replyButton.text()).trim())+1);
                        TS.showMaskOK();
                        $.ui.goBack();
                        $('#reply-textareaD').val('');
                    }else{//unsuccess
                        //console.log('reply unsuccess!');
                        TS.showMask(r.des);
                    }
                };
                var error = function(xhr,type){
                    TS.showMask('发送失败，请稍后重试。');
                };
                $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    timeout:2000,
                    dataType:'json',
                    success:success,
                    error:error
                });
            },
            pic:function(parem,el){
                if(!$.ui.isWeChat()){
                    $('#pic-view').html('');
                    $.ui.cache.picIndex = Number(el.data('index'));
                    var pics = $($(el.parent()).find('img'));
                    for(var i=0;i<pics.length;i++){
                        var src = $(pics[i]).data('src');
                        $('#pic-view').append('<div id="pic-img-box-'+i+'" class="pic-img-box"><img id="pic-img-'+i+'" class="pic-view-img" src="'+src+'"></div>');
                        getZoomClass({
                            el:'pic-img-'+i
                        });
                    }
                    $.ui.loadDiv('pic');
                    TS.app.weibo.func.picOnload();
                    event.stopPropagation();
                    return false;
                }else{//is kengdie wechat
//                    alert('wechat tap pic!');
//                    alert(el.data('src'));
//                    $.ui.wechat.func.e(el.data('src'));
//                    alert($.ui.wechat.data.n.join(' , '));
                    $.ui.wechat.init();
                    WeixinJSBridge.invoke("imagePreview", {
                        current: el.data('src'),
                        urls:$.ui.wechat.data.n
                    });
                }
            },
            pager:function(param,el){
                if(param==='next'){
                    var max_id = TS.app.weibo.cache.weiboListStart();
                    window.location.href = U('w3g/Index/listbyid',['max_id='+max_id]);
                }else if(param==='prev'){//prev
                    var max_id = TS.app.weibo.cache.weiboListStart()+40;
                    window.location.href = U('w3g/Index/listbyid',['max_id='+max_id]);
                }else{//select
                    var max_id = TS.app.weibo.cache.weiboListStart() + ~~el.data('index')*20 + 20;
                    window.location.href = U('w3g/Index/listbyid',['max_id='+max_id]);
                }
            },
            pagerSelect:function(param,el){
                if(param === 'change'){
                    el.change(function(){
                        window.location.href = el.val();
                    });
                }
            },
            pagerReplace:function(param,el){
                var replaceBox = $('#'+el.data('for'));
                switch(param){
                    case "click":
                        $.ui.showMask('正在加载…');
                        replaceBox.load(el.data('link'),{}, $.ui.hideMask);
                        break;
                    case "change":
                        el.change(function(){
                            $.ui.showMask('正在加载…');
                            replaceBox.load(el.val(),{}, $.ui.hideMask);                               
                        });
                        break;
                }
            },
            upload:function(param,el){
                $('.facelistbutton').data('listen', 'weibo-facelist-show');
                $('#ts-face-list').remove();
                //隐藏@和话题列表
                $('#friendchoose').hide();
                $('#topic_list').hide();
                var ajax_iframe;
                var ajax_ifram_obj = document.getElementById('ajax_iframe');
                var imgbox_id = TS.cache.imgbox_id = '#'+ el.data('for');
                var file_id = "#file";
                if(imgbox_id.indexOf("_channel") != -1){
                    var file_id = "#file_channel";
                    ajax_ifram_obj = document.getElementById('ajax_iframe_channel');
                }
                ajax_iframe = ajax_ifram_obj.contentDocument || ajax_ifram_obj.contentWindow.document;
                var load = $(ajax_iframe).find("#loaded").text();
                if(load==='OK'){
                    //console.log($('#ts-upload-img-box>div').length);
                    console.log(imgbox_id);
                    if($(imgbox_id).find('div.file_list_view').length<9){
                        $(ajax_iframe).find(file_id).trigger('click');
                    }else{
                        $.ui.showMask('最多上传9张图片:)',true);
                    }
                }else{
                    $.ui.showMask('框架加载失败,请刷新后重试:(',true);
                }
            },
            delimage:function(param,el){
                TS.cache.step = ~~(TS.cache.step) + 1;
                var img = el.parent();
                img.remove();
            },
            dopost:function(param,el){
                var url = U('w3g/Index/doPost');
                var imgBox = $('#ts-upload-img-box,#ts-upload-img-box_channel').find('div.file_list_view');
                if(el.hasClass('disable')){
                    return;
                }
                if(imgBox.length>0){
                    var attach_id = '';
                    for(var i=0;i<imgBox.length;i++){
                        attach_id = attach_id + '|' + String($(imgBox[i]).data('id'));
                    }
                    var data = {
                        content:$('#new-weibo-textarea').val(),
                        feed_attach_type:'image',
                        attach_id:attach_id
                    }
                }else{
                    var data = {
                        content:$('#new-weibo-textarea').val()
                    }
                }
                var success = function(r){
                    //el.data('doposting',false);
                    if(r.status == 1){
                        /*if(TS.cache.isMyPage){
                            //个人主页分享数+1
                            var myWeiboNum = $('#i_info_counts_box_weibo .i_info_counts_num');
                            myWeiboNum.text(Number(myWeiboNum.text())+1);
                        }*/
                        //window.history.go(-1 - TS.cache.step);
                        TS.showMaskOK();
                        setTimeout(function(){
                         // window.location.href= U('w3g/Index/index'); 
                         window.location.href = U('w3g/Index/index');
                        },200);
                        /*if($('body').find('#weibo-type-nav').length >0 ){
                            $('#weibo-type-nav').after(r);
                        }else{
                            $('#ts-list').prepend(r);
                        }*/
                        //clean
                        $('#new-weibo-textarea').val('');
                        $('#ts-upload-img-box').html('');
                        //close face-list
                        $('.facelistbutton').data('listen','weibo-facelist-show');
                        $('#ts-face-list').remove();
                    }else{
                        el.removeClass('disable').removeClass('fc4');
                        $.ui.showMask(r.info,true);
                    }
                }
                var error = function(){
                    //el.data('doposting',false);
                    el.removeClass('disable').removeClass('fc4');
                }
                //if(!el.data('doposting')){
                    //el.data('doposting',true);
                    el.addClass('disable').addClass('fc4');
                    $.ajax({
                        type:'POST',
                        url:url,
                        data:data,
                        success:success,
                        dataType:'json'
                    });
                //}
            },
            dofollow: function(param, el){
                var _url = U('w3g/People/doFollow');
                var _data = {'fid':~~param};
                var _success = function(r){
                    if(r.status === true || r.status === 1){
                        //$(el).removeClass('dofollow').addClass('unfollow');
                        //$(obj).attr('following', r.data['following']);
                        //$(obj).attr('follower', r.data['follower']);
                        $(el).find('font').addClass('followed');
                        el.data('listen', 'weibo-unfollow-'+param);
                        $.ui.showMask(r.info, true);
                        //initFollow();
                    }else{
                        $.ui.showMask(r.info, true);
                    }
                };
                $.ajax({
                        type:'post',
                        url:_url,
                        data:_data,
                        success:_success,
                        dataType:'json'
                    });
            },
            unfollow: function(param, el){
                var _url = U('w3g/People/unFollow');
                var _data = {'fid':~~param};
                var _success = function(r){
                    if(r.status === true || r.status === 1){
                        //$(el).removeClass('dofollow').addClass('unfollow');
                        //$(obj).attr('following', r.data['following']);
                        //$(obj).attr('follower', r.data['follower']);
                        $(el).find('font').removeClass('followed');
                        el.data('listen', 'weibo-dofollow-'+param);
                        $.ui.showMask(r.info, true);
                        //initFollow();
                    }else{
                        $.ui.showMask(r.info, true);
                    }
                };
                $.ajax({
                        type:'post',
                        url:_url,
                        data:_data,
                        success:_success,
                        dataType:'json'
                    });                
            },
            feedpost:function(param,el){
                var url = U('w3g/Index/feedPost');
                var imgBox = $('#ts-upload-img-box_channel').find('div.file_list_view');
                if(el.hasClass('disable')){
                    return;
                }
                if(imgBox.length>0){
                    var attach_id = '';
                    for(var i=0;i<imgBox.length;i++){
                        attach_id = attach_id + '|' + String($(imgBox[i]).data('id'));
                    }
                    var data = {
                        app_name:'public',
                        body:$('#new-channel-textarea').val(),
                        feed_attach_type:'image',
                        attach_id:attach_id,
                        type:'postimage',
                        content:'',
                        video_id:'',
                        videourl:'',
                        channel_id:param
                    }
                }else{
                    var data = {
                        app_name:'public',
                        body:$('#new-channel-textarea').val(),
                        type:'post',
                        content:'',
                        video_id:'',
                        videourl:'',
                        channel_id:param
                    }
                }
                var success = function(r){
                    if(r!="参数错误" && r!="内容不能为空" && r!="0" && r!="只能上传图片附件" && r!="发布失败，字数超过限制"){
                        //window.history.go(-1 - TS.cache.step);
//                        if($('body').find('#channel_sort').length >0){
//                            $('#channel_sort').after(r);
//                        }else{
//                            $('#ts-list').prepend(r);
//                        }
                        //clean
                        TS.showMaskOK();
                        
                        $('#new-channel-textarea').val('');
                        $('#ts-upload-img-box_channel').html('');
                        //close face-list
                        $('.facelistbutton').data('listen','weibo-facelist-show');
                        $('#ts-face-list').remove();
                        // setTimeout(function(){
                        //     window.location.href = U('w3g/Channel/index') + "&cid=" + param;
                        // });
                    }else{
                        el.removeClass('disable').removeClass('fc4');
                        $.ui.showMask(r,true);
                    }
                    
                }
                var error = function(){
                    el.removeClass('disable').removeClass('fc4');
                }
                el.addClass('disable').addClass('fc4');
                $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    success:success,
                    error:error,
                    timeout:2000,
                    dataType:'text'
                });
            },
            readmsg:function(param){
                if(param==='close'){
                    $.post(U('w3g/Message/setAllIsRead'),{},function(txt){
                        if(txt.status) {
                            $('#header-tip').remove();
                            window.clearInterval(TS.cache.readMsg);
                        }
                    },'json');
                }
            },
            facelist:function(param,el,event){
                if(param==='show'){
                    el.data('listen','weibo-facelist-close');
                    var faceHtml = '',
                        faceGroupCount = 0,
                        faceIndexTipHtml = '',
                        faceAdded = 0;
                    for(var faceItem in TS.app.weibo.cache.faceDict){
                        if(faceAdded%30 === 0){
                            if(faceAdded!==0){
                                faceHtml+='</div>';
                            }
                            faceHtml+='<div class="face-list-group">';
                            faceGroupCount++;
                        }
                        faceHtml+='<div class="ts-listen faceboxitem" data-listen="weibo-face-add" data-title="'+faceItem+'"><img src="'+TS.app.weibo.cache.facePath+TS.app.weibo.cache.faceDict[faceItem]+'"></div>';
                        if(faceAdded+1===TS.app.weibo.cache.faceCount){
                            faceHtml+='</div>';
                        }
                        faceAdded++;
                    }
                    //添加指示器
                    // for(var i= 0;i<faceGroupCount;i++){
                    //     if(i===0){
                    //         faceIndexTipHtml+='<div class="face-list-indexitem active"></div>';
                    //     }else{
                    //         faceIndexTipHtml+='<div class="face-list-indexitem"></div>';
                    //     }
                    // }
                    // faceIndexTipHtml = '<div id="face-list-index">'+faceIndexTipHtml+'</div>';
                    faceHtml='<div id="ts-face-list"><div>'+faceHtml+'</div></div>';
                    $($(el.parent()).parent()).append(faceHtml);
                    TS.app.weibo.cache.faceListScroll = new IScroll('#ts-face-list', {
                        eventPassthrough:true, //仅横向滚动
                        scrollX:true,
                        scrollY:false,
                        snap:'.face-list-group',
                        // scrollbars: 'custom'
                    });
                    // TS.app.weibo.cache.faceListScroll.on('scrollEnd',function(){
                    //     $('.face-list-indexitem').removeClass('active');
                    //     $('.face-list-indexitem:eq('+TS.app.weibo.cache.faceListScroll.currentPage.pageX+')').addClass('active');
                    // })
                    //隐藏@和话题列表
                    $('#friendchoose').hide();
                    $('#topic_list').hide();
                }else{//close
                    el.data('listen','weibo-facelist-show');
                    $('#ts-face-list').remove();
                }
            },
            face:function(param,el,e){
                if(param==='add'){
                    var textarea = el.parents('#ts-face-list').siblings('.send_box').find('textarea');//$($($(el.parent()).parent()).find('textarea'));
                    TS.cache.lastContent = textarea.val();
                    textarea.insertAtCaret('['+el.data('title')+']');
                    this.checknums(true, textarea, '', true);
                }
            },
            atwho : function(param,el,event){
                //TS.cache.step = ~~(TS.cache.step) + 1;
                TS.cache.textara_id = el.data('for');
                if(param==='show'){
                    el.data('listen','weibo-atwho-close');
                    if($($(el.parent()).parent()).find('#friendchoose').length <1){
                        $($(el.parent()).parent()).find('#friendchoose').prepend('<div class="loading"><i class="fa fa-spinner fa-spin"></i></div>');
                        $.ajax({//验证邮箱是否已注册
                            type:"GET",
                            url :U('w3g/Index/atwho'),
                            dataType:"html",
                            success:function(r){
                                if(r){
                                    $($(el.parent()).parent()).find('#friendchoose').remove('.loading');
                                    $($(el.parent()).parent()).append(r);             
                                }else{
                                    $.ui.showMask("您还没有好友", true);
                                }
                            }
                        });
                    }else{
                        $('#friendchoose').show();
                    }
                    $('#topic_list').hide();
                    $('#ts-face-list').remove();
                    //$('#friendchoose').hide();

                }else{//close
                    el.data('listen','weibo-atwho-show');
                    $('#friendchoose').hide();
                }
            },
            at : function(param,el,event){
                var textarea = $('#'+TS.cache.textara_id);
                if(param==='add'){
                    //var textarea = $('textarea#new-weibo-textarea');
                    //var textarea = $('.send_box').find('textarea.ts_textarea');
                    // textarea.val(textarea.val()+'@'+el.data('at')+' ');
                    TS.cache.lastContent = textarea.val();
                    textarea.insertAtCaret('@'+el.data('at')+' ');
                    $('.atbutton').data('listen','weibo-atwho-show');
                    this.checknums(true, textarea, '', true);
                    $('#friendchoose').hide();
                    $('html, body').animate({scrollTop:0}, 'slow');
                }
            },
            topic:function (param,el,event){
                //TS.cache.step = ~~(TS.cache.step) + 1;
                TS.cache.textara_id = el.data('for');
                if(param==='show'){
                    el.data('listen','weibo-topic-close');
                    if($($(el.parent()).parent()).find('#topic_list').length <1){
                        $.ajax({//验证邮箱是否已注册
                            type:"GET",
                            url :U('w3g/Index/rec_topic'),
                            dataType:"html",
                            timeout:2000,
                            success:function(r){
                                if(r){
                                    $($(el.parent()).parent()).append(r);             
                                }
                            },
                            error : function(r, type){
                                $.ui.showMask('连接服务器失败，请重试', true);
                            }
                        });
                    }else{
                        $('#topic_list').show();
                    }
                    //去除表情和@列表
                    $('#friendchoose').hide();
                    $('#ts-face-list').remove();
                }else{//close
                    el.data('listen','weibo-topic-show');
                    $('#topic_list').hide();
                }
            },
            insertTopic : function(param,el, event){
                //var textarea = $($($(el.parent()).parent()).find('textarea'));
                var textarea = $('#'+TS.cache.textara_id);
                //var textarea = $('.send_box').find('textarea.ts_textarea');
                TS.cache.lastContent = textarea.val();
                textarea.insertAtCaret(el.data('topic'));
                this.checknums(true, textarea, '', true);
                $('#topic_list').hide();
                $('html, body').animate({scrollTop:0}, 'slow');
            },
            remove:function(param, el){
                var box = $("#"+el.data('for'));
                  switch(param){
                      case "click":
                            box.remove();
                          break;
                      default :
                          //$('#'+param).remove();
                          break;
                  }
            },
            channel : function(param, el, event){
                if(param){
                    window.location.href = U('w3g/Channel/index')+'&cid='+param;
                }
            },
            follow2channel : function(param, el, event){
                var uid = TS.cache.profile.uid;
                var cid = el.data('cid');
                var type = param;
               // function (uid, cid, type, obj)
                // 数据验证
                if(typeof uid == 'undefined' || typeof cid == 'undefined' || typeof type == 'undefined') {
                        return false;
                }
                // 异步提交处理
                $.post(U('widget/TopMenu/upFollowStatus'), {uid:uid, cid:cid, type:type, widget_appname:'channel'}, 
                function(res) {
                        if(res.status == 1) {
                                if(type === 'del') {
                                        $.ui.showMask('取消关注成功', true);
                                        $(el).html('关注');
                                        $(el).data('listen', 'weibo-follow2channel-add');
                                } else if(type === 'add') {
                                        $.ui.showMask('关注成功', true);
                                        $(el).html('取消关注');
                                        $(el).data('listen', 'weibo-follow2channel-del');
                                }
                        } else {
                                //ui.error('关注失败');
                                $.ui.showMask('关注失败', true);
                        }
                }, 'json');
                return false;
            },
            showDiv : function (param, el, event){
                event.stopPropagation();
                var showBtn = el.data('switch');
                //切换到隐藏按钮
                $('#'+showBtn).show();
                //显示div
                $('#'+param).fadeIn(500);
                //隐藏显示按钮
                el.hide();
            },
            hideDiv : function (param, el, event){
                event.stopPropagation();
                var showBtn = el.data('switch');
                //切换到隐藏按钮
                $('#'+showBtn).show();
                //隐藏div
                $('#'+param).hide();
                //隐藏显示按钮
                el.hide();
            },
            switchChannel:function(param,el,event){
                event.stopPropagation();
                if($("#"+param).is(":hidden")){
                    $("#"+param).fadeIn(500);
                    $("#show_channel").hide();
                    $("#hide_channel").show();
                } else{
                    $("#"+param).hide();
                    $("#show_channel").show();
                    $("#hide_channel").hide();
                }
            },
            checknums : function(param, el, event, isInit){
                var textArea = $(el);
                var lastContent = TS.cache.lastContent;
                var lastLeft = 0;
                if(isInit){
                    setNums(param,el,event);
                }
                textArea.bind('input propertychange',function(){                    
                    setNums(param,el,event);
                });
                
                function setNums(param,el,event){
                    $('#header-buttons .sendBtn').removeClass('disable').removeClass('fc4');
                    var char_len = 0;
                    if(textArea.val() == ''){
                        $('#header-buttons .sendBtn').addClass('disable').addClass('fc4');
                    }else{
                        char_len = textArea.val().length;
                    }
                    var strlen = 0;
                    for (var i = 0; i < char_len; i++) {
                        var reg = /^[\u4e00-\u9fa5]+$/i;
                        if (reg.test(textArea.val().charAt(i)) == true) {
                            strlen = strlen + 2; //中文为2个字符
                        } else {
                            strlen = strlen + 1; //英文一个字符
                        }
                    }
                    totalnum = 140 - Math.round(strlen/2);
                    if(totalnum < 0){
                        totalnum = lastLeft;
                        textArea.val(lastContent);
                    }else{
                        lastLeft = totalnum;
                        lastContent = TS.cache.lastContent = textArea.val();
                    }
                    el.siblings('.num').html(totalnum);
                }
            },
            userpage:function(param,el,event){
                window.location.href = U('w3g/Index/weibo',['uid='+param]);
                event.stopPropagation();
                return false;
            },
            weibapage:function(param,el,event){
                window.location.href = U('w3g/Weiba/detail',['weiba_id='+param]);
                event.stopPropagation();
                return false;
            },
            weibadetail:function(param,el,event){
                window.location.href = U('w3g/Weiba/postDetail',['post_id='+param]);
                event.stopPropagation();
                return false;
            },
            follow:function(param,el){
                var type = el.data('type');
                var user_id = el.data('userid');
                var page = el.data('page');
                var key = el.data('key');
                /*if(param==='follow'){
                    var url = U('w3g/Index/doFollow',['from=user_followers','type=follow','key='+key,'user_id='+user_id,'page='+page]);
                }else{//unfollow
                    var url = U('w3g/Index/doFollow',['from=user_followers','type=unfollow','key='+key,'user_id='+user_id,'page='+page]);
                }*/
                var url = el.attr('linkto');
                if(param==='follow'){
                    url = el.data('follow');
                }else{
                    url = el.data('unfollow');
                }
                var success = function(){
                    if(param==='follow'){
                        /*if(el.data('isfollower')=='1'){
                            el.text('已互粉');
                        }else{
                            el.text('取消关注');
                        }*/
                        el.text('取消关注');
//                        el.removeClass('highlight');
                        el.data('listen','weibo-follow-unfollow');
                    }else{//unfollow
                        /*if(el.data('isfollower')=='1'){
                            el.text('互粉');
                        }else{
                            el.text('加关注');
                        }*/
                        el.text('加关注');
//                        el.addClass('highlight');
                        el.data('listen','weibo-follow-follow');
                    }
                };
                $.get(url,success);
            },
            register:function(param,el){
                //获取邮件表单数据
                var email=$("#email").val();
                var verify = $('#auth-code').val();

                //获取手机表单数据
                var phone = $('#phone').val();
                var regCode = $('#phone-auth-code').val();

                var uname=$("#uname").val() ? $("#uname").val() : $("#username").val();
                var pw=$("#pwd").val() ? $("#pwd").val() : $("#password").val();
                var repass=$("#pwd-repeat").val() ? $("#pwd-repeat").val() : $("#password-repeat").val();

                if((email=="" && phone=="") || pw=="" || uname==""){
                    $.ui.showMask("请完整填写注册信息:)",true);
                }else if(email && !/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,4}/ig.test(email)){
                    $.ui.showMask("请检查您的邮件地址格式:)",true);
               }else if(!TS.r.uname(uname)){
                   $.ui.showMask("昵称格式不正确:)",true);
                }else if(!/.{6,15}$/ig.test(pw)){
                    $.ui.showMask("密码格式不正确:)",true);
                }else if(pw != repass){
                    $.ui.showMask("两次密码不一致:)",true);
                }else{
                    var postData ={};
                   //如果是邮件注册则核对验证码
                    if(email){
                         postData ={
                            "email":email,
                            "uname":uname,
                            "password":pw,
                            "repassword": repass,
                            "verify" : verify
                        };
                       if(verify == ''){
                            $.ui.showMask("请填写验证码:)",true);
                            return;
                        }
                       $.ajax({//验证邮箱是否已注册
                            type:"POST",
                            url :U('public/Register/isEmailAvailable'),
                            data:{"email":email},
                            dataType:"json",
                            timeout:2000,
                            success:function(r){
                                if(r.status==false){
                                    $.ui.showMask(r.info,true);
                                }else{
                                    //检测验证码是否正确
                                    $.ajax({
                                        type:"POST",
                                        url :U('public/Register/isValidVerify'),
                                        data:{"verify":verify},
                                        dataType:"json",
                                        timeout:2000,
                                        success:function(q){
                                            if(q.status==false){
                                                $.ui.showMask(q.info,true);
                                                change_verify();
                                            }else{
                                                __submit();
                                            }
                                        },
                                        error:function(xhr,type){
                                            $.ui.showMask("连接服务器失败，请重试:)",true);
                                        }
                                    });
                                }
                            },
                            error:function(xhr,type){
                                $.ui.showMask("连接服务器失败，请重试:)",true);
                            }
                        });                  
                    }
                    //如果是邮件注册则核对验证码
                    if(phone){
                        postData ={
                            "phone":phone,
                            "uname":uname,
                            "password":pw,
                            "repassword": repass,
                            "regCode" : regCode
                        };
                        if(regCode == ''){
                            $.ui.showMask("请填写验证码:)",true);
                            return;
                        }
                         $.ajax({//手机是否可用
                            type:"POST",
                            url :U('public/Register/isPhoneAvailable'),
                            data:{"phone":phone},
                            dataType:"json",
                            timeout:2000,
                            success:function(r){
                                if(r.status==false){
                                    $.ui.showMask(r.info,true);
                                }else{
                                   //检测验证码是否正确
                                    $.ajax({
                                        type:"POST",
                                        url :U('public/Register/isRegCodeAvailable'),
                                        data:{"phone":phone,"regCode":regCode},
                                        dataType:"json",
                                        timeout:2000,
                                        success:function(q){
                                            if(q.status==false){
                                                $.ui.showMask(q.info,true);
                                            }else{
                                                __submit();
                                            }
                                        },
                                        error:function(xhr,type){
                                            $.ui.showMask("连接服务器失败，请重试:)",true);
                                        }
                                    });                                      
                                }
                            },
                            error:function(xhr,type){
                                $.ui.showMask("连接服务器失败，请重试:)",true);
                            }
                        });                    
                    }
                    function __submit(){
                        $.ui.showMask("注册中...",false);
                        $.ajax({//检测昵称是否已注册
                            type:"POST",
                            url :U('public/Register/isUnameAvailable'),
                            data:{"uname":postData.uname},
                            dataType:"json",
                            timeout:2000,
                            success:function(q){
                                if(q.status==false){
                                    $.ui.showMask(q.info,true);
                                }else{
                                    $.ajax({//提交注册
                                        type:"POST",
                                        url :U('w3g/Public/doRegister'),
                                        data:postData,
                                        timeout:2000,
                                        dataType:"json",
                                        success:function(w){
                                            if(w.flag==1){
                                                location.href=U("w3g/Index/index");
                                            }else{
                                                $.ui.showMask(w.msg,true);
                                            }
                                        },
                                        error:function(xhr,type){
                                            $.ui.showMask("连接服务器失败，请重试:)",true);
                                        }
                                    });                                    
                                }
                            },
                            error:function(xhr,type){
                                $.ui.showMask("连接服务器失败，请重试:)",true);
                            }
                        });
                    }
                }
            },
            msgShowDetail:function(param,el){
                var id = Number(param);
                window.location.href = U('w3g/Message/detail',['id='+id]);
            },
            postmessage:function(param,el){
                var content = $('#custom').find('#conversation-toolbar-input').val();
                var url = U('w3g/Message/doPost');
                var data = {
                    to:Number(param),
                    content:content
                };
                $.ajax({
                    type:'post',
                    url :url,
                    data:data,
                    dataType:"json",
                    timeout:2000,
                    beforeSend:function(){
                        el.width(el.width());
                        el.html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success:function(r){
                        el.html('发送');
                        if(r.status == 1){
                            $('#custom').find('#conversation-toolbar-input').val('');
                            var html = '<div class="conversation-item conversation-item-right">\
                                            <div class="conversation-item-head">\
                                                <img src="'+TS.cache.profile.avatar_small+'" alt="'+TS.cache.profile.uname+'">\
                                            </div>\
                                            <div class="conversation-item-content">\
                                                <div class="conversation-item-content-content">'+content+'</div>\
                                            </div>\
                                        </div>';
                            $('#msgdetaillist>div:first').append(html);
//                            window.scrollTo(0,document.body.scrollHeight);
                            setTimeout(function(){
                                $.ui.cache.msgDetailListScroll.refresh();
                                $.ui.cache.msgDetailListScroll.scrollToElement($($.ui.cache.msgDetailListScroll.wrapper).find('div:first>div:last').get(0),1000)
                            },0);
                        }else{//error
                            $.ui.showMask(r.data, true);
                        }
                    }
                })
            },
            picview:function(param,el,e){
                e.stopPropagation();
                var allCount = $('.pic-img-box').length;
                if(param==='next'){
                    if($.ui.cache.picIndex!==undefined){
                        if($.ui.cache.picIndex===allCount-1){
                            $.ui.cache.picIndex=0;
                        }else{
                            $.ui.cache.picIndex++;
                        }
                        $('#pic-footer-now').text($.ui.cache.picIndex+1);
                        $('.pic-img-box').removeClass('pic-img-box-show f-pic-img-box-show');
                        $('#pic-img-box-'+$.ui.cache.picIndex).addClass('pic-img-box-show');
                    }else{
                        $('#pic-footer-now').text('1');
                        $('.pic-img-box').removeClass('pic-img-box-show f-pic-img-box-show');
                        $('#pic-img-box-0').addClass('pic-img-box-show');
                    }
                }else{//prev
                    if($.ui.cache.picIndex!==undefined){
                        if($.ui.cache.picIndex===0){
                            $.ui.cache.picIndex=allCount-1;
                        }else{
                            $.ui.cache.picIndex--;
                        }
                        $('#pic-footer-now').text($.ui.cache.picIndex+1);
                        $('.pic-img-box').removeClass('pic-img-box-show f-pic-img-box-show');
                        $('#pic-img-box-'+$.ui.cache.picIndex).addClass('f-pic-img-box-show');
                    }else{
                        $('#pic-footer-now').text('1');
                        $('.pic-img-box').removeClass('pic-img-box-show f-pic-img-box-show');
                        $('#pic-img-box-0').addClass('f-pic-img-box-show');
                    }
                }
            },
            video:function(param,el,e){
                e.stopPropagation();
                if(param==='view'){
                    if(el.find('video').length===0){
                        if(TS.cache.videoSetIntervalId!==undefined){
                            window.clearInterval(TS.cache.videoSetIntervalId);
                        }
                        TS.cache.video = $('.m-i-b-p-video-img');
                        $.each(TS.cache.video, function(index, item) {
                            $($('.m-i-b-p-video-box').get(index)).css({
                                'height':$(item).height()+'px',
                                'background-image':'url('+$(item).attr('src')+')',
                                'background-size':'100%'
                            });
                            $($('.m-i-b-p-video-box').get(index)).attr('height',$(item).height());
                        });
                        $('.m-i-b-p-video-box video').remove();
                        $('.m-i-b-p-video-box img,.m-i-b-p-video-box div').show();
                        var videoSrc = el.data('video'),
                            videoElement;
                        TS.cache.videoHtml = '<video src="'+videoSrc+'" autoplay="autoplay" controls="controls" loop="loop" width="100%" height="'+el.height()+'">您的浏览器不支持 video 标签。</video>';
                        el.find('div,img').hide();
                        el.append(TS.cache.videoHtml);
                        //control video
                        videoElement = el.find('video').get(0);
                        //videoElement.autoplay=true;
                        TS.cache.videoSetIntervalId = window.setInterval(function(){
                            if(videoElement.readyState>3 && videoElement.play){
                                el.removeClass('video-loading');
                                el.css({
                                    'background-color': '#eeeeee',
                                    'border': '0',
                                    'background-image':'none'
                                });
                                window.clearInterval(TS.cache.videoSetIntervalId);
                                TS.cache.videoSetIntervalId = undefined;
                            }else if(videoElement.readyState>1){
                                el.addClass('video-loading');
                                el.css({
                                    'background-color': '#ffffff',
                                    'border': '1px solid #eeeeee'
                                });
                            }
                        },250);
                    }else{
                        //do nothing
                    }
//                    $('#videoview').html('<video src="'+videoSrc+'" controls="controls" autoplay="autoplay" loop="loop">您的浏览器不支持 video 标签。</video>');
//                    window.location.hash = 'video';
                }
            },
            goback:function(param,el){
                $.ui.goBack();
            },
            tosendmsg:function(param,el){
                var btn = $('#post-msg-to-uid');
                window.location.href=U('w3g/Index/sendmsg') + '&uid=' + btn.data('uid');
                //$('#new-message-reciver').val(btn.data('uname')).data('uid',btn.data('uid'));
                //$('#new-message-reciver').attr('readonly', 'readonly');
                //window.location.hash = 'new-message';
            }
            //listen funcs
        }
    },
    sys:{
        page:{
            home:{
                name:'Home',
                logged:false,
                load:function(el){

                },
                unload:function(el){

                }
            },
            login:{
                name:'Login',
                logged:false,
                load:function(el){
                    $('#login-input-submit').bind('click',function(){
                        var email=$('#login-input-email').val();
                        var pwd=$('#login-input-pwd').val();
                        $.ui.showMask();
                        $.ajax({
                            url:U('w3g/Public/doLogin'),
                            data:{
                                'email':email,
                                'password':pwd
                            },
                            type:'POST',
                            timeout:2000,
                            success:function(data){
                                $.ui.hideMask();
                                if(data.success===0){
                                    alert(data.des);
                                }else if(data.success===1){
                                    //window.location.href= U('w3g/Public/home');
                                    TS.cache.logged=true;
                                    TS.oldHash='';
                                    TS.oldHash='#weibo-weibo_list';
                                    TS.casePage(true,TS.oldHash);
                                }else{
                                    alert('系统异常,请重试.');
                                }
                            },
                            dataType:'json'
                        })
                    });
                },
                unload:function(el){
                    $('#login-input-submit').unbind('click');
                }
            },
            register:{
                name:'Register',
                logged:false,
                load:function(el){

                },
                unload:function(el){

                }
            },
            justc:{
                name:'Just See',
                logged:false
                },
                unload:function(el){

                }
            }
        },
        url:function(hash){
          return U('w3g/Public/'+hash[0]);
        }
//    }
}

var TS_APP_ONLOAD=function(el){
    var hash=TS.spliteHash(el.getAttribute('id'));
    if(hash.length!==1){
        if(TS.app[hash[0]].page[hash[1]].load){
            TS.app[hash[0]].page[hash[1]].load(el);
        }
    }else{
        TS.app.sys.page[hash[0]].load(el);
    }
}
/**
 * picView Touchmove
 * */
$(document).on('touchstart','#pic-view img',function(e){
    e.preventDefault();
    $(this).data("startx",e.originalEvent.touches[0].pageX);
});
$(document).on('touchmove','#pic-view img',function(e){
    e.preventDefault();
    e.stopPropagation();
    if(e.originalEvent.touches.length===1){
        var moveX = Number(e.originalEvent.touches[0].pageX) - Number($(this).data('startx')) + Number($(this).data("ox"));
        var transform = 'translate3d('+moveX+'px,0px,0)';
        if(Number($(this).data('scale')) <= 1){
            transform = 'translate3d(0,0,0)';
        }
        $(this).css({
            '-webkit-transform':transform,
            '-moz-transform':transform,
            '-ms-transform':transform,
            '-o-transform':transform,
            'transform':transform
        });
    }
});
$(document).on('touchend','#pic-view img',function(e){
    var _this = this;
    e.preventDefault();
    var allCount = $('.pic-img-box').length;
    var endX = e.originalEvent.changedTouches[0].pageX;
    transform = 'translate3d(0,0,0)';
    if(Number($(this).data("startx")) - endX < 30 && Number($(this).data("startx")) - endX > -30){
        come_back(_this);
    } else if(Number($(this).data("startx")) - endX > 30){
        if($(this).parent().next(".pic-img-box").length > 0) {
            if($.ui.cache.picIndex===allCount-1){
                $.ui.cache.picIndex=0;
            }else{
                $.ui.cache.picIndex++;
            }
            $('#pic-footer-now').text($.ui.cache.picIndex+1);
            $(this).parent().removeClass("pic-img-box-show f-pic-img-box-show").next("div").addClass("pic-img-box-show");
            $(this).parent().removeClass("pic-img-box-show f-pic-img-box-show").next("div").find("img").css({
                '-webkit-transform': transform,
                '-moz-transform': transform,
                '-ms-transform': transform,
                '-o-transform': transform,
                'transform': transform
            });
            come_back(_this);
        } else {
            come_back(_this);
        }
    } else if(Number($(this).data("startx")) - endX < -30){
        if($(this).parent().prev(".pic-img-box").length > 0) {
            if($.ui.cache.picIndex===0){
                $.ui.cache.picIndex=allCount-1;
            }else{
                $.ui.cache.picIndex--;
            }
            $('#pic-footer-now').text($.ui.cache.picIndex+1);
            $(this).parent().removeClass("pic-img-box-show f-pic-img-box-show").prev("div").addClass("f-pic-img-box-show");
            $(this).parent().removeClass("pic-img-box-show f-pic-img-box-show").prev("div").find("img").css({
                '-webkit-transform': transform,
                '-moz-transform': transform,
                '-ms-transform': transform,
                '-o-transform': transform,
                'transform': transform
            });
            come_back(_this);
        } else {
            come_back(_this);
        }
    }
});
var come_back = function(obj){
    transform = 'translate3d(0,0,0)';
    $(obj).css({
        '-webkit-transform':transform,
        '-moz-transform':transform,
        '-ms-transform':transform,
        '-o-transform':transform,
        'transform':transform
    });
}
var uploading = function(){
    var imgBox = $(TS.cache.imgbox_id);
    imgBox.prepend('<div class="loading"><i class="fa fa-spinner fa-spin"></i></div>');
}
var uploaded = function(){
    TS.cache.step = ~~(TS.cache.step) + 1;
    var imgBox = $(TS.cache.imgbox_id);
    imgBox.find('.loading').remove();
}



