/**
 * Created by zzl on 2016/8/25.
 */
var OS_Loading={
    htmls:{
        loading1:'<div class="os-loading1 os-loading spinner"> \
                    <div class="rect1 change-color"></div>\
                    <div class="rect2 change-color"></div>\
                    <div class="rect3 change-color"></div>\
                    <div class="rect4 change-color"></div>\
                    <div class="rect5 change-color"></div>\
                  </div>',
        loading2:'<div class="os-loading"><div class="os-loading2 spinner change-color"></div></div>',
        loading3:'<div class="os-loading3 os-loading spinner">\
                    <div class="double-bounce1 change-color"></div>\
                    <div class="double-bounce2 change-color"></div>\
                  </div>',
        loading4:'<div class="os-loading4 os-loading spinner">\
                    <div class="cube1 change-color"></div>\
                    <div class="cube2 change-color"></div>\
                  </div>',
        loading5:'<div class="os-loading5 os-loading spinner">\
                    <div class="dot1 change-color"></div>\
                    <div class="dot2 change-color"></div>\
                  </div>',
        loading6:'<div class="os-loading6 os-loading spinner">\
                    <div class="bounce1 change-color"></div>\
                    <div class="bounce2 change-color"></div>\
                    <div class="bounce3 change-color"></div>\
                  </div>',
        loading7:'<div class="os-loading"><div class="os-loading7 spinner change-color"></div></div>',
        loading8:'<div class="os-loading8 os-loading spinner">\
                    <div class="spinner-container container1" style="display: none;">\
                        <div class="circle1 change-color"></div>\
                        <div class="circle2 change-color"></div>\
                        <div class="circle3 change-color"></div>\
                        <div class="circle4 change-color"></div>\
                    </div>\
                    <div class="spinner-container container2">\
                        <div class="circle1 change-color"></div>\
                        <div class="circle2 change-color"></div>\
                        <div class="circle3 change-color"></div>\
                        <div class="circle4 change-color"></div>\
                    </div>\
                    <div class="spinner-container container3">\
                        <div class="circle1 change-color"></div>\
                        <div class="circle2 change-color"></div>\
                        <div class="circle3 change-color"></div>\
                        <div class="circle4 change-color"></div>\
                    </div>\
                  </div>',
    },
    loading:function($tag,type,color){
        var html=OS_Loading.htmls[type];
        $tag.append(html);
        if(color!=undefined){
            $tag.find('.os-loading').children('.change-color').css("background-color",color);
        }
        return true;
    },
    remove:function($tag){
        $tag.find('.os-loading').remove();
        return true;
    }
}
