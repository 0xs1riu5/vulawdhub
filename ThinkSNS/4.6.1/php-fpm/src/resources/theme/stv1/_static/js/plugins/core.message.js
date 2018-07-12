core.message = new function(){

    var self    = this;
    self.params = {
        /*任务栏配置*/
        taskbar : {
            clickLi : function(li, e){},
            clickLiClearMsgnum : true,
            removeLi : function(li, e){},
            lis : {
                "pl" : {
                    id : 'pl',
                    title : "评论",
                    src : THEME_URL+'/image/message/pl.png'
                },
                "zan": {
                    id : 'zan',
                    title : "赞",
                    src : THEME_URL+'/image/message/zan.png'
                },
                "tz" : {
                    id : 'tz',
                    title : "通知",
                    src : THEME_URL+'/image/message/tz.png'
                },
                "at": {
                    id: 'at',
                    title: 'At我的',
                    src: THEME_URL + '/image/message/at.png'
                },
                "lxr": {
                    id : 'lxr',
                    title : "联系人",
                    src : THEME_URL+'/image/message/lxr.png'
                }
            }
        }//任务栏配置结束
    };

        /* 任务栏 开始 */
    var taskbar = {
        
        el : '<div id="message-taskbar">\
               <div class="wrap">\
                 <ul id="message-fixed" class="message-list"></ul>\
                 <i class="sys-user"></i>\
                 <ul id="message-users" class="message-list"></ul>\
               </div>\
               <div class="smartButton" show="1">\
                    <i class="smartButton-num"></i>\
                    <i class="smartButton-iconfont">&#xf0200;</i>\
                    <span class="smartButton-text">联系人</span>\
               </div>\
             </div>',

        li : '<li class="tooltip tip-left"><a href="javascript:;"><img /><i></i></a></li>',

        isBindEvents : false,

        /* # 储存消息的对象 */
        messageNumList: {},

        /* # 储存消息总数 */
        messageCount: 0,

        /* #  */
        limitHeight: 47,

        /**
         * 在页面上构建任务栏
         * @return void
         */
        build : function(){
            taskbar.remove();

            $('body').append(taskbar.el);
            taskbar.initEvents();

            try{
                var lis = self.params.taskbar.lis;
                $.each(lis, function(i, params){
                    taskbar.addLi(params);
                });
                /* # 添加智能按钮初始事件 */
                taskbar.initSmartButton();
                /* # 窗口变化事件 */
                // $(window).on('resize', function() {
                //     taskbar.initSmartButton();
                // });
                /* # 开启定时事件 */
                taskbar.setTimeoutSmartButtonMessageNumber();
            }catch(e){}
        },

        /* # 初始化SmartButton */
        initSmartButton: function() {
            /* # 针对非chrome浏览器的兼容处理 */
            //if (/*navigator.userAgent.toLowerCase().match(/edge/) == null && */window.chrome) {
                taskbar.jq('.wrap').addClass('chrome');
                taskbar.limitHeight = 40;
            //};
            /* # 前置判断，判断分辨率问题 */
            taskbar.messageStatus = $(document).width() < 1140;
            if (!taskbar.messageStatus) {
                // taskbar.jq().animate({
                //     right: '0',
                //     top: 0
                // }, 1500);
                // taskbar.jq('.smartButton').show().animate({
                //     left: 0,
                //     opacity: 0
                // }, 450);
                // taskbar.jq('.wrap').animate({
                //         marginLeft: 0
                // }, 100);
                taskbar.jq().css({
                    right: 0,
                    top:0
                });
                taskbar.jq('.smartButton').show().css({
                    left:0,
                    opacity: 0
                });
                taskbar.jq('.wrap').css('margin-left', 0);
                /* # 中断，不执行初始化 */
                return false;
            };

            /* # 初始化 */
            taskbar.jq().css({
                top: '59px',
                right: '-45px'
            });
            taskbar.messageStatus && taskbar.jq('.smartButton').show().animate({
                left: '-30px',
                opacity: 1
            }, 900, function() {
                taskbar.jq().animate({
                    right: '-32px',
                    top: '59px'
                }, 450);
                taskbar.jq('.wrap').animate({
                    marginLeft: '5px'
                }, 450);
            });
            /* # 绑定额外区域变比smartButton */
            taskbar.initSmartBar || $(document).on('click', 'div#body-bg', function() {
                taskbar.messageStatus && taskbar.changeSmartBarStatus(false);
            });
            /* # 绑定smartButton点击事件 */
            taskbar.initSmartBar || taskbar.jq('.smartButton').on('click', function(event) {
                event.preventDefault();
                taskbar.messageStatus && taskbar.changeSmartBarStatus(true);
            });
            taskbar.initSmartBar = true;
        },

        /* # title新增闪动效果 */
        $head: $('head').find('title'),
        isShowTitle: false,
        showTitleText: $('head').find('title').text(),
        showTitle: function(status) {
            taskbar.isShowTitle = status ? true : false;
            taskbar.showTitleToOne = true;
            if (taskbar.isShowTitle) {
                if (taskbar.showTitlwSetp == 1) {
                    taskbar.showTitlwSetp = 2;
                    taskbar.$head.text('【新消息】' + taskbar.showTitleText);
                } else {
                    taskbar.showTitlwSetp = 1;
                    taskbar.$head.text('【　　　】' + taskbar.showTitleText);
                };
                return false;
            };
            taskbar.$head.text(taskbar.showTitleText);
            taskbar.isShowTitle = false;
        },

        /* # 定时执行消息提示方法 */
        setTimeoutSmartButtonMessageNumber: function() {
            taskbar.messageCount = 0;
            for (i in taskbar.messageNumList) {
                taskbar.messageCount += parseInt(taskbar.messageNumList[i]);
            }
            if (taskbar.messageCount > 0) {
                taskbar.jq('.smartButton-num').text(taskbar.messageCount > 9 ? '···' : taskbar.messageCount).fadeIn('show');
                taskbar.jq('.smartButton-text').text('新消息');
                taskbar.showTitle(true);
            } else {
                taskbar.jq('.smartButton-text').text('联系人');
                taskbar.jq('.smartButton-num').text(0).fadeOut('show');
                taskbar.showTitle(false);
            };
            setTimeout(function() {
                taskbar.setTimeoutSmartButtonMessageNumber();
            }, 500);
        },

        /* # smartButton和taskBar状态切换 */
        changeSmartBarStatus: function(status) {
            status = status ? true : false;
            if (status) {
                taskbar.jq('.smartButton').animate({
                    left: '5px',
                    opacity: 0
                }, 450, function() {
                    taskbar.jq('.smartButton').hide();
                });
                taskbar.jq().animate({
                    right: 0
                }, 900);
                taskbar.jq('.wrap').animate({
                        marginLeft: 0
                }, 900);
                /* # 用于中断执行 */
                return true;
            };
            taskbar.jq('.smartButton').show().animate({
                left: '-30px',
                opacity: 1
            }, 900);
            taskbar.jq().animate({
                right: '-32px'
            }, 450);
            taskbar.jq('.wrap').animate({
                marginLeft: '5px'
            }, 100);
        },

        /**
         * 从页面上移除任务栏
         * @return void
         */
        remove : function(){
            if(taskbar.exist()){
                taskbar.jq().remove();
            }
        },

        /**
         * 检查任务栏是否已经在页面上量
         * @return boolean 存在返回true，否则为false
         */
        exist : function(){
            return taskbar.jq().length>0;
        },

        /**
         * 设置消息数量，展现在前端提示用户
         * @param string id 列表项的ID
         * @param integer number 设置的消息数量
         * @return void
         */
        setMessageNumber : function(id, number){
            if(!taskbar.exist()) return;
            /* # 设置消息数量到数组 */
            taskbar.messageNumList[id] = number;
            var i = taskbar.jq('#message-'+id+' i');
            i.data('num', number).text(number>9?'···':number);
            if(number <= 0){
                i.addClass('hide');
            }else{
                i.removeClass('hide');
            }
        },

        /**
         * 添加一个li标签
         * @param array|object params li的参数
         * @param string|function insert 插入的方式
         * @param string toId 插入参照的ID
         * @return void
         */
        addLi : function(params, insert, toId){
            //任务栏不存在则自动建立
            if(!taskbar.exist()) {
                taskbar.build();
            }

            var li  = $(taskbar.li);  //li标签
            var img = li.find('img'); //img标签
            var i   = li.find('i');   //i标签
            var number = params.num || 0; //消息数
            var type,isReplace,method;

            /* # 设置消息数量到数组 */
            taskbar.messageNumList[params.id] = number;

            // li参数设置
            li.attr('id', 'message-'+params.id);
            li.data('id', params.id);
            li.data('realId', 'message-'+params.id);
            // li.attr('title', params.title || '');
            li.attr('data-tooltip',params.title || '');
            img.attr('src', params.src);
            i.data('num', number).text(
                number>9?'···':number
            );
            if(number <= 0){
                i.addClass('hide');
            }else{
                i.removeClass('hide');
            }
            if(params.roomid){
                type = 'users';
                li.data('roomid', params.roomid);
            }else{
                type = 'fixed';
            }
            type = params.type || type;
            li.data('type', type);
            // li参数设置结束

            //是否需要替换
            isReplace = taskbar.hasId(params.id);
            //支持的插入方式
            method = ['append','prepend','after','before'];
            // 如果传入一个function，则直接执行
            if(typeof insert=='function'){
                insert(li, type, isReplace);
                return;
            }else if($.inArray(insert, method)<0){
                insert = 'append';
            }

            toId = toId ? toId : type;
            if(isReplace){
                li.addClass('noanimat');
                li.replaceAll(taskbar.getId(params.id));
            }else{
                eval('taskbar.getId(toId).'+insert+'(li);');
            }
        },
        //移除某个ID
        removeId : function(id){
            /* # 设置消息数量到数组 */
            taskbar.messageNumList[id] = 0;
            taskbar.getId(id).remove();
        },
        //清空某个类型里面的全部
        clear : function(type){
            taskbar.getId(type).html('');
        },
        //取得指定表达式的jquery对象
        jq : function(expr){
            if(expr){
                return $('#message-taskbar').find(expr);
            }else{
                return $('#message-taskbar');
            }
        },
        //取得指定ID的jquery对象
        getId : function(id){
            return taskbar.jq('#message-'+id);
        },
        //检查是否存在某个ID
        hasId : function(id){
            return taskbar.getId(id).length>0;
        },
        //初始化事件
        initEvents : function(){
            if(taskbar.isBindEvents) return;
            taskbar.isBindEvents = true;

            var noActiveMove = null;
            var isMousedown = false;
            var mousedownLi = null;
            var mousedownX  = 0;
            var mousedownY  = 0;
            var lis = taskbar.jq('.message-list li');

            lis.live('mousedown', function(e) { //开始移动
                var li = $(this);
                if(li.data('type')=='fixed'||li.hasClass('move')){
                    return false;
                }
                //按住500毫秒后激活移动
                noActiveMove = setTimeout(function(){
                    noActiveMove = null;
                    mousedownLi = li;
                    isMousedown = true;
                    mousedownX  = e.pageX;
                    mousedownY  = e.pageY;
                }, 500);
                return false;
            }).live('click', function(e){ //单击事件
                //还未激活移动则取消
                if(noActiveMove){
                    clearTimeout(noActiveMove);
                }
                var li = $(this);
                if(li.hasClass('move')){
                    return false; //正在移动中的
                }
                try{
                    if(self.params.taskbar.clickLiClearMsgnum){
                        taskbar.setMessageNumber(li.data('id'), 0);
                    }
                    if(typeof self.params.taskbar.clickLi=='function'){
                        self.params.taskbar.clickLi(li, e);
                    }
                    taskbar.setSmartShow(true);
                }catch(e){}
                return false;
            }).live('mouseup', function(){
                //还未激活移动则取消
                if(noActiveMove){
                    clearTimeout(noActiveMove);
                }
            });
            //移动出去
            $(document).mousemove(function(e) {
                if(!isMousedown) return;
                mousedownLi.css({
                    right:mousedownX-e.pageX,
                    bottom:mousedownY-e.pageY,
                }).addClass('move');
            }).mouseup(function(e){ //停止移动
                if(noActiveMove) clearTimeout(noActiveMove);
                if(!isMousedown) return;
                isMousedown = false;
                //删除
                if(parseInt(mousedownLi.css('right')) > 50){
                    mousedownLi.addClass('scale');
                    setTimeout(function(){
                        try{
                            if(typeof self.params.taskbar.removeLi=='function'){
                                self.params.taskbar.removeLi(mousedownLi, e);
                            }
                        }catch(e){}
                        mousedownLi.remove();
                        mousedownLi = null;
                    }, 800);
                }else{ // 回去
                    mousedownLi.animate({
                        right:0,bottom:0
                    }, 300, function(){
                        mousedownLi.removeAttr('style')
                          .removeClass('move');
                        mousedownLi = null;
                    });
                }
            });
        }
        
    }; /* 任务栏 结束 */

    var msgbox  = {
        el : '<div id="msgbox-shield"></div>\
              <div id="msgbox-main">\
                <div class="msgbox-title-wrap">\
                 <div class="msgbox-title">\
                  <h3></h3>\
                  <div class="rt">\
                    <div class="btn"></div>\
                    <div class="close"><a href="javascript:;">×</a></div>\
                  </div>\
                 </div>\
                </div>\
                <div class="msgbox-body"></div>\
                <div class="msgbox-footer-wrap">\
                 <div class="msgbox-footer">\
                 </div>\
                </div>\
              </div>',
        open : function(data){
            if(!msgbox.exist()){
                $('body').append(msgbox.el);
                $('#msgbox-shield').data('st', $(window).scrollTop());
                $('#msgbox-shield,#msgbox-main .close a').click(function(){
                    msgbox.close();
                });
            }else{
                msgbox.onclose();
            }
            msgbox.setData(data || {});
        },
        close : function(){
            msgbox.onclose();
            $('#msgbox-shield').remove();
            $('#msgbox-main').attr('id','msgbox-remove');
            setTimeout(function(){
                $('#msgbox-remove').remove();
            }, 1000);
        },
        exist: function(){
            return $('#msgbox-main').length>0;
        },
        setData : function(data){
            if(!msgbox.exist()) return;
            var title = $('#msgbox-main .msgbox-title');
            /*标题 系统消息[<span>ThinkSNS是智士软件旗下开源社交软件</span>] */
            data.title = data.title || '消息盒子';
            title.find('h3').children().remove();
            title.find('h3').empty().append(data.title);
            /*导航链接 <a href="">全部</a><a href="">微吧</a><a href="">分享</a> */
            data.navs = data.navs || '';
            if(data.navs){
                title.find('h3').append('<span class="navs"></span>');
                title.find('h3 .navs:last').append(data.navs);
            }
            /*按钮 <a href="javascript:;">按钮名称</a> */
            data.btn   = data.btn || '';
            title.find('.btn').children().remove();
            if(data.btn){
                title.find('.btn').empty().append(data.btn);
            }
            /*内容*/
            var msgbody = $('#msgbox-main .msgbox-body');
            if(data.loading){
                msgbody.addClass('msgbox-loading');
            }else{
                msgbody.removeClass('msgbox-loading');
            }
            msgbody.children().remove(); msgbody.empty();
            msgbody.append(data.content);
            /*底部*/
            var footer = $('#msgbox-main .msgbox-footer');
            footer.children().remove(); footer.empty();
            if(data.footer){
                footer.parent().show();
                footer.append(data.footer);
            }else{
                footer.parent().hide();
            }
            msgbox.scrollY(data.scrollY||0, data.scrollYTime);
        },
        scrollY : function(y, time){
            if(y == 'same') return;
            var msgbody = $('#msgbox-main .msgbox-body');
            if(y == 'top'){
                y = 0;
            }else if(y == 'bottom'){
                y = 0;
                msgbody.children().each(function(i, c){
                    y += $(c).outerHeight(true);
                });
                y = y-msgbody.height()+100;
            }
            time = time===undefined?200:time;
            if(time <= 0){
                msgbody.stop().scrollTop(y);
            }else{
                msgbody.stop().animate({
                    scrollTop : parseInt(y)
                }, time);
            }
        },
        openUrl : function(url, loading){
            if(loading !== false){
                msgbox.open({
                    title:'loading...',
                    loading : true,
                });
            }
            $.get(url, function(html){
                if(!msgbox.exist()) return;
                var content = $(html).filter('#set-data');
                var data = {
                    content:html,
                    loading:false,
                    scrollY:0
                };
                if(content.length > 0){
                    data.title = content.data('title');
                    data.navs  = content.data('navs');
                    data.btn   = content.data('btn');
                    data.footer = content.data('footer');
                    if(content.data('scrolly')){
                        data.scrollY = content.data('scrolly');
                    }
                    var time = parseInt(content.data('scrollytime'));
                    if(!isNaN(time)){
                        data.scrollYTime = time;
                    }
                }
                msgbox.open(data);
            });
        },
        openRoom: function(query){
            var url = U('public/WebMessage/room')+'&'+query;
            msgbox.openUrl(url, !msgbox.exist());
        },
        oncloseCallback: null,
        onclose: function(callback){
            if(callback){
                msgbox.oncloseCallback = callback;
            }else if(msgbox.oncloseCallback){
                msgbox.oncloseCallback();
                msgbox.oncloseCallback = null;
            }
        }
    };

    


    
    self._init  = function(args){
        self.init(args);
    };

    self.init   = function(args){
        if(MID <= 0) return;
        $(function(){
            taskbar.build();
            self.params.taskbar.clickLi = function(li){
                if(li.data('type') == 'fixed' && !li.data('roomid')){
                    msgbox.openUrl(U('public/WebMessage/'+li.data('id')));
                }else{
                    msgbox.openRoom('roomid='+li.data('roomid'));
                }
            }

            $(window).scroll(function(e) {
                var shield = $('#msgbox-shield');
                if(shield.length > 0){
                    $(window).scrollTop(shield.data('st'));
                }
            });
            
            var setTaskRoom = function(pos){
                var limit = $(window).height() / taskbar.limitHeight - 5;
                $.get(U('public/WebMessage/latelyRoomList'), {limit:limit}, function(res){
                    /* # 评论和赞以及通知 */
                    res.info.comment = parseInt(res.info.comment);
                    res.info.digg    = parseInt(res.info.digg);
                    res.info.notice  = parseInt(res.info.notice);
                    res.info.at      = parseInt(res.info.at);
                    res.info.comment >= 1 && taskbar.setMessageNumber('pl' , res.info.comment);
                    res.info.digg    >= 1 && taskbar.setMessageNumber('zan', res.info.digg);
                    res.info.notice  >= 1 && taskbar.setMessageNumber('tz' , res.info.notice);
                    res.info.at      >= 1 && taskbar.setMessageNumber('at' , res.info.at);

                    if(!res.data) return;
                    var i;
                    for(i in res.data){
                        taskbar.addLi({
                            id : 'room'+res.data[i].room_id,
                            title : res.data[i].title,
                            src : res.data[i].src,
                            num: res.data[i].msg_new,
                            roomid : res.data[i].room_id
                        }, pos?pos:'append');
                    }
                }, 'json');
            }
            
            setTaskRoom('append');
            
            setInterval(function(){ setTaskRoom('prepend'); }, 30000);
            
            self.params.taskbar.removeLi = function(li){
                var data = {roomid: li.data('roomid')};
                $.get(U('public/WebMessage/clearMessage'), data, function(res){}, 'json');
                /* # 清理本地缓存的消息数量 */
                taskbar.messageNumList['room' + data.roomid] = 0;
            }

        });
        
        
    };

    self.taskbar = taskbar;
    self.msgbox  = msgbox;
    self.openUrl = msgbox.openUrl;
    self.openRoom = msgbox.openRoom;
    self.close   = msgbox.close;
    self.setMessageNumber = taskbar.setMessageNumber;
    self.scrollY = msgbox.scrollY;
    self.onclose = msgbox.onclose;
    return undefined;
};