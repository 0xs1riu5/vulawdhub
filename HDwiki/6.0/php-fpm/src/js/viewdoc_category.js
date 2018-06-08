/**
 * 2016-10-25 16:54:22
 * 词条浏览页 词条目录
 * 
 * 目前主要是在词条浏览页调用
 */
!function($){
    // 提取目录标题
    var titles = $('#content-body h2, #content-body h3')
    var did = 1
    
    // <li><em>1</em><a href="index.php?doc-view-38#1"></a></li>
    var n=0 // 一级目录序号
    var n2 = 0 // 二级目录序号
    var html = [[], [], []]
    var len = Math.ceil(titles.length / 3)
    
    // 每列至少几个目录，避免目录比较少的情况下将其分为多列
    if (len < 6) {
        len = 6
    }
    
    titles.each(function(i, item){
        var o = $(item)
        
        // j代表第几列（从0开始），目录显示为三列
        var j = 0
        
        if (i+1 > len * 2) {
            j = 2
        } else if (i+1 > len) {
            j = 1
        }
        
        if ($.nodeName(item, 'h2')) {
            // 一级目录
            n += 1
            n2 = 0
            html[j].push('<li><em>'+ n +'</em><a href="#'+ (n*2-1) +'">'+ o.find('span').text() +'</a></li>')
        } else {
            // 二级目录
            n2 += 1
            $(item).before('<a name="'+ (n*2-1) +'.'+ n2 +'"></a>')
            html[j].push('<li class="dot"><a href="#'+ (n*2-1) +'.'+ n2 +'">'+ o.text() +'</a></li>')
        }
    })
    
    $('#full-all ul').eq(0).html(html[0].join(''))
    $('#full-all ul').eq(1).html(html[1].join(''))
    $('#full-all ul').eq(2).html(html[2].join(''))

}(jQuery)