/**
 * 2016-10-21 16:11:53
 * 词条浏览页 - 导航目录
 * 
 * 目前主要是在词条浏览页调用
 */
!function($){
    var timer = null;
    var toolWidth = $('#tool').width();                              // tool元素的宽度
    var toolRight = parseInt($('#tool').css('right'));               // 获取到当前#doc-aside的元素的right值
    var blockLeftOuterWidth = $('.doc-show').outerWidth();           // 左侧部分的宽度
    // 判断可视状态
    $(window).scroll( function() {
        clearTimeout(timer);            // 加clearTimeout和setTimeout是为了优化scroll触发的次数,减小浏览器压力
        timer = setTimeout(function(){
            viewdoc_cate.toolSwitch();  // 执行右侧的小目录的显示或隐藏操作
            viewdoc_cate.sync();        // 右侧的小目录与左侧目录的同步操作
        },50);
        viewdoc_cate.moveScroll();  // 当滚动条左右移动的时候，改变tool的位置
    });

    // 点击li时,将页面拉到对应的标题的位置，并且高亮显示
    $(document).on('click', '.full-list ul li', function(){
        var thisIndex = $(this).index() ? $(this).index() : 0;
        $(document).scrollTop($(viewdoc_cate.hnlist[thisIndex]).offset().top);
    });

    // 点击向上箭头时返回顶部
    $(document).on('click', '.topcontrol', function(){
        $(document).scrollTop(0);
    });

    // 当前窗口大小发生改变时
    $(window).resize(function(event){
    	$('#tool').css('left', $('#content-body').outerWidth()+$('#content-body').offset().left + $('#doc-aside').offset().left - ($('#content-body').outerWidth()+$('#content-body').offset().left));
    });

    /**
     * @brief 实现词条目录与右下角同步的功能
     */
    var viewdoc_cate = {
        hnlist : null,   // 存储分析到的h2和h3目录
        scroll : null,   // 初始化滚动条高度
        oNodes : {},                          // 初始化一个对象,存放当前左侧当前可视区第一个目录对象及他的索引
        /**
         * @brief  init()初始化操作
         * 1. 获取到所有的h2和h3元素,确定他们的父子级关系
         * 2. 拼接li
         * 3. 将其写入#tool这个容器的ul里面
         */
       init : function() {
            this.scroll = $(document).scrollTop();                 // 初始化滚动条高度
            this.hnlist = $('#content-body h2, #content-body h3'); // 获取到所有的h2和h3元素
            var nh2 = 1, nh3 = 1, nodes = '';
            // 遍历所有的h2和h3元素组装内容字符串,填充到右侧小目录内
            $.each(this.hnlist, function(i, n){
                var num, sClassName = '',_html = '',sTagName = $(n).get(0).tagName.toUpperCase();
                if ('H3' == sTagName) {
                    sClassName = 'dot';
                    num = (nh2-1) + '.' + (nh3++);
                    _html = $(n).text();
                } else if ('H2' == sTagName) {
                    nh3 = 1;
                    num = nh2++;
                    _html = $(n).find('span').text();
                }
                nodes += '<li class="'+ sClassName +'" data-tag="'+ sTagName +'"><em>'+ num +'</em><a href="javascript:void(0);" catalogkey="1" class="bold">'+ _html +'</a></li>';
            });
            $('.full-list').children('ul').html(nodes);
            this.initTool();                        // tool定位
        },

        /**
         * @brief 显示或隐藏tool
         */
        toolSwitch : function() {
            // 如果有目录元素,再执行里面的显示右侧框或者隐藏右侧框的操作
            if(this.hnlist.length > 0) {
                var nowScroll = $(document).scrollTop();                                     // 存储当前的滚动条高度
                var toolOffsetTop = $('#tool').offset().top;                                 // 获取到右侧目录栏距顶部的距离
                var blockRightOffsetTop = $('#doc-aside').offset().top;                    // 获取右侧栏目距离顶部的高度
                var blockRightOuterHeight = parseInt($('#doc-aside').outerHeight(true));   // 获取右侧栏目的总高度

                // 判断是往上拉还是往下拉, 如果上次拉的滚动条高度大于当前的滚动条高度,就是往下拉，反之就是往上拉
                if(this.scroll > nowScroll) {
	                if ( (blockRightOffsetTop + blockRightOuterHeight) >= toolOffsetTop ) {
	                     $('#tool').hide();
	                 }
	            } else {
	                if (blockRightOuterHeight < (toolOffsetTop + blockRightOffsetTop)) {
	                    $('#tool').show();
	                }
	            }
	            this.scroll = nowScroll;                    // 修改上次滚动条停止的高度
            }
        },

        /**
         * @brief 同步操作
         *  1. 左边的词条目录与右下角的词条目录同步操作,
         *     当滚动条滚动到左边某个目录上的时候,右边的小目录高亮显示
         *  2. 当右边高亮显示的目录不在可视区范围之内的时候,改变#tool的滚动条位置，使其可见
         */
        sync : function() {
            var oLis = $('.full-list').find('li');    // 获取右侧所有的目录元素
            var wHeight = $(window).height();         // 窗口的大小
            var sHeight = $(document).scrollTop();    // 当前滚动条距顶部的高度
            // 遍历所有的目录元素
            $.each( viewdoc_cate.hnlist, function(i, n){
                // 如果当前的目录元素距离顶部的距离+自己的高度小于当前滚动条距顶部的高度,
                // 并且当前的目录元素距离顶部的距离 小于窗口的大小+当前滚动条距离顶部的距离
                // 将当前元素及索引写入到oNodes对象,结束掉循环
                var nOffsetTop = $(n).offset().top;  // 当前元素的距离顶部的距离
                if ((nOffsetTop + parseInt($(n).css('height'))) > sHeight && nOffsetTop < (wHeight + sHeight)) {
                    viewdoc_cate.oNodes.li = viewdoc_cate.hnlist[i];
                    viewdoc_cate.oNodes.index = i;
                    // 给当前选中的元素给一个css状态
                    oLis.removeClass('current').eq(viewdoc_cate.oNodes.index).addClass('current');
                    return false;
                }
            });

            // 如果当前选中的元素是h3，给它的上一级h2元素给个选中状态
            if ($(oLis[this.oNodes.index]).data('tag') == 'H3') {
                this.searchPrevH2(oLis, this.oNodes.index);
            }

            // 检测当前toll里面的li是否处于显示状态，如果不显示，修改scrollTop的值，使其显示
            var oFullList = $('#tool .full-list');  // 获取到右侧显示内容的元素
            var oFullListScrollTop = oFullList.scrollTop(); // 获取当前div可视区域的高度
            var sumTotal = this.sumTotal(oLis, this.oNodes.index) ? this.sumTotal(oLis, this.oNodes.index) : 0;  // 计算当前元素及其前面的兄弟元素的总高度
            // 如果当前元素及其前面的兄弟元素的总高度小于滚动条的高度，或者大于窗口的高度+滚动条的高度，就让滚动条的高度等于当前元素的高度
            if (sumTotal < oFullListScrollTop || sumTotal > oFullListScrollTop + $(oFullList).height()) {
                oFullList.scrollTop(sumTotal);
            }
        },

        // 计算当前元素及其子元素的高度之和
        sumTotal : function(oLis, i) {
           var num = 0;
           for(var j = 0; j < i; j++) {
               num += $(oLis[j]).height();
           }
           return num;
        },

        // 如果当前选中的标签是h3，那就获取到它的父级元素，并且高亮显示
        searchPrevH2 : function(oLis, i) {
            for (var j = i; j >= 0; j--) {
                if ('H2' == $(oLis[j]).prev('li').data('tag')) {
                    $(oLis[j-1]).addClass('current');
                    return true;
                }
            }
        },
        
        // 当视窗大小发生改变tool元素的位置
        positionTool : function() {
            // 如果#doc-aside元素的宽度大于等于初始化的值时,
            // 将#doc-aside + 100的宽度赋值给tool的left
            // 否则就将tool的left清空
            // 如果小于等于就还原id为tool的元素的right跟left值
        	if ($('.doc-show').outerWidth() >= blockLeftOuterWidth) {
        		$('#tool').css('left', $('.doc-show').outerWidth() + 100);
        	} else {
        		$('#tool').css('left', '');
        	}

            blockLeftOuterWidth = $('.doc-show').outerWidth();
            $('#tool').width(toolWidth);
        },
        
        // 滚动条左右移动的时候，改变tool的位置
        moveScroll : function(){
            var scrollLeft = $(document).scrollLeft();
            if (scrollLeft > 0) {
            	$('#tool').css('left',$('#content-body').outerWidth()+$('#content-body').offset().left + $('#doc-aside').offset().left - ($('#content-body').outerWidth()+$('#content-body').offset().left) - scrollLeft );
            }
        },
        
        // 刷新界面时定位tool元素
        initTool : function() {
        	$('#tool').css('left', $('#content-body').outerWidth()+$('#content-body').offset().left + $('#doc-aside').offset().left - ($('#content-body').outerWidth()+$('#content-body').offset().left));
            $('#tool').width(toolWidth);
        }
    };

    viewdoc_cate.init();
}(jQuery);
