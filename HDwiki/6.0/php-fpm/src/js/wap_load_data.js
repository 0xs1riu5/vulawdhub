
!function($){

    window.WapLoadData = {
        page             : 2,                             // 当前页
        bind_element     : null,                          // 绑定滚动事件的元素
        touch_element    : null,                          // 触发事件的元素
        scroll_timer     : null,                          // 定时器对象
        load_url         : null,                          // 加载数据的地址
        is_continue_load : true,                          // 是否需要继续执行数据加载
        loading_message  : '正在加载更多数据...',               // 加载数据时的提示信息
        prompt_message   : '没有更多数据...',                 // 加载不到数据时的提示
        error_message    : '加载失败...',                    // 加载失败时的提示信息
        init_data : function (obj) {
            this.bind_element = obj.bind_element;
            this.touch_element = obj.touch_element;
            this.load_url = obj.load_url;
        },
        load_data : function() {
            var _self = this;
            this.touch_element.html(this.loading_message);
            if (_self.is_continue_load) {
                _self.is_continue_load = false;
                $.ajax({
                    type: "GET",
                    url: _self.load_url + '-' + _self.page,
                    dataType: "json",
                    success: function(json){
                        if (json.length > 0) {
                            var str = return_wap_str(json);
                            if (!str) {
                                _self.touch_element.html(_self.error_message);
                                setTimeout(function(){
                                    _self.touch_element.html('');
                                }, 2000);
                                _self.is_continue_load = true;
                                return false;
                            }
                            _self.construction_html();
                            _self.touch_element.prev('section').children('ul').append(str);
                            _self.is_continue_load = true;
                        } else {
                            _self.touch_element.html(_self.prompt_message);
                        }
                    },
                });
            }
        },
        construction_html : function() {
            WapLoadData.touch_element.html('');
            WapLoadData.page++;
        },
    };

   
}(jQuery);

$(function(){
    if (WapLoadData.bind_element !== null) {
        WapLoadData.bind_element.scroll(function(){
            clearTimeout(WapLoadData.scroll_timer);
            WapLoadData.scroll_timer = setTimeout(function(){
                var touchButton = $(window).height() - WapLoadData.touch_element.offset().top;
                var footerHeight = $('#footer1').outerHeight() + $('#footer2').outerHeight() + WapLoadData.touch_element.outerHeight();
                if (WapLoadData.touch_element.offset().top < $(window).height() && (touchButton > footerHeight)) {
                    if (WapLoadData.is_continue_load) {
                        WapLoadData.load_data();
                    }
                }
            }, 50);
        });
    }
});
