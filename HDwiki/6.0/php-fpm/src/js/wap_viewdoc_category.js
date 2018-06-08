!function(){
    // 这里是将节目的title抓取出来根据给定格式写入到右侧的ul中
    var titles = $('.wrap h2, .wrap h3');
    var str = '';
    titles.each(function(i, item){
         var el = $(item);
         str += '<li catalogkey="'+ i + '"';
         str += ( el.get(0).tagName.toUpperCase() == 'H2' ? ' class=""' : ' class="lv2"');
         str += '><a href="javascript:void(0)">'+ el.html() +'</a>';
         str += '</li>';
    });
    $('#list-catalogue ul').append(str);

    $('.iclose').click(function(){
        $('#fbg,#catalogue').hide();
    });

    // 点击显示小目录的时候，将当前处于显示状态的h2、h3标签选中
    $('.btn-pagenavi').click(function(){
        $('#fbg,#catalogue').show();
        if (titles.length == 0) {
            $('#list-catalogue').after('<p style="width:100%;height:100%;text-align:center;margin-top:100%;color:#666;">无目录信息</p>');
        } else {
            $('#list-catalogue ul li').removeClass('current');
            titles.each(function(i, item){
                var elOffsetTop = $(item).offset().top - $('header').outerHeight(); // 当前元素距离顶部的距离  > 0
               if (elOffsetTop > 0) {
                   $('#list-catalogue ul li').eq(i).addClass('current');
                   return false;
               }
            });
        }
    });

    // 点击小目录的时候，词条内容跳到该目录的位置
    $(document).delegate('#list-catalogue ul li', 'click', function() {
        var _this = $(this);
        if (_this.attr('class') != 'current' && _this.attr('class') != 'lv2 current') {
            $('#list-catalogue ul li').removeClass('current');
            _this.addClass('current');
            $('.wrap').scrollTop($('.wrap h2, .wrap h3').eq(_this.index()).offset().top - $('header').outerHeight());
        }
    });

    // 获取图片的个数
    $('#albumLength').html($('section').find('img').size() + '张图');
}(jQuery);