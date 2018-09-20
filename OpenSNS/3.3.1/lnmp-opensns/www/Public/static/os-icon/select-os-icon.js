/**
 * Created by Administrator on 16-7-8.
 * @author 郑钟良<zzl@ourstu.com>
 */
(function($){

    var os_icon_list=[
        "action-redo", "action-undo", "anchor", "arrow-down", "arrow-left", "arrow-right", "arrow-up", "badge", "bag", "ban", "bar-chart", "basket", "basket-loaded", "bell", "book-open", "briefcase", "bubble", "bubbles", "bulb", "calculator", "calendar", "call-end", "call-in", "call-out", "camcorder", "camera", "check", "chemistry", "clock", "close", "cloud-download", "cloud-upload", "compass", "control-end", "control-forward", "control-pause", "control-play", "control-rewind", "control-start", "credit-card", "crop", "cup", "cursor", "cursor-move", "diamond", "direction", "directions", "disc", "dislike", "doc", "docs", "drawer", "drop", "earphones", "earphones-alt", "emoticon-smile", "energy", "envelope", "envelope-letter", "envelope-open", "equalizer", "eye", "eyeglasses", "feed", "film", "fire", "flag", "folder", "folder-alt", "frame", "game-controller", "ghost", "globe", "globe-alt", "graduation", "graph", "grid", "handbag", "heart", "home", "hourglass", "info", "key", "layers", "like", "link", "list", "lock", "lock-open", "login", "logout", "loop", "magic-wand", "magnet", "magnifier", "magnifier-add", "magnifier-remove", "map", "microphone", "mouse", "moustache", "music-tone", "music-tone-alt", "note", "notebook", "paper-clip", "paper-plane", "pencil", "picture", "pie-chart", "pin", "plane", "playlist", "plus", "pointer", "power", "present", "printer", "puzzle", "question", "refresh", "reload", "rocket", "screen-desktop", "screen-smartphone", "screen-tablet", "settings", "share", "share-alt", "shield", "shuffle", "size-actual", "size-fullscreen", "social-dribbble", "social-dropbox", "social-facebook", "social-tumblr", "social-twitter", "social-youtube", "speech", "speedometer", "star", "support", "symbol-female", "symbol-male", "tag", "target", "trash", "trophy", "umbrella", "user", "user-female", "user-follow", "user-following", "user-unfollow", "users", "vector", "volume-1", "volume-2", "volume-off", "wallet", "wrench"
    ];

    var OS_ICON=function(element,options){
        this.select=element;
        this.options=$.extend({},$.fn.select_os_icon.defaults,options);
        this.init();
    }

    OS_ICON.prototype={
        init:function(){
            var $tag=this.select;
            $tag.find('option').remove();
            $tag.parent().find('.select-os-icon-block').remove();
            this._append_options_html($tag)._append_select_html($tag.parent());
            $tag.hide();
            return this;
        },
        _append_options_html:function($tag){
            var html='<option value="-">无</option>';
            for(var key in os_icon_list){
                html+='<option value="'+os_icon_list[key]+'">os-icon-'+os_icon_list[key]+'</option>';
            }
            $tag.append(html);
            return this;
        },
        _append_select_html:function($tag){
            var html='<div class="select-os-icon-block"><a class="select-single" data-role="select-single" tabindex="-1"><span title="[没有图标]">[没有图标]</span><div><b></b></div></a><div class="option-list"><ul class="select-results">';
            html+='<li class="active-result" title="" data-option-array-index="0"><em></em>[没有图标]</li>';
            for(var key in os_icon_list){
                html+='<li class="active-result icon" title="" data-option-array-index="'+(parseInt(key)+1)+'"><i class="os-icon-'+os_icon_list[key]+'" title="'+os_icon_list[key]+'"></i></li>';
            }
            html+='</ul></div></div>';
            $tag.append(html);
            $tag.each(function(){
                var icon_name=$(this).find('.select-os-icon').attr('data-value');
                if(icon_name!='-'){
                    $(this).find('.select-single span').attr('title',icon_name).html('<i class="os-icon-'+icon_name+'"></i>'+icon_name);
                }
            });
            return this;
        },
        bind_select:function(){
            $('[data-role="select-single"]').unbind();
            $('[data-role="select-single"]').click(function(){
                var $tag=$(this).parents('.select-os-icon-block');
                if($tag.hasClass('active')){
                    $tag.removeClass('active');
                }else{
                    $('.select-os-icon-block').removeClass('active');
                    $tag.addClass('active');
                }
                return true;
            });
            $('.active-result').unbind();
            $('.active-result').click(function(){
                var $tag=$(this).parents('.select-os-icon-block');
                var num=parseInt($(this).attr('data-option-array-index'));
                $tag.removeClass('active');
                if(num===0){
                    $tag.find('.select-single span').attr('title','[没有图标]').html('[没有图标]');
                    $tag.siblings('.select-os-icon').val('-').attr('data-value','-');
                }else{
                    num--;
                    $tag.find('.select-single span').attr('title',os_icon_list[num]).html('<i class="os-icon-'+os_icon_list[num]+'"></i>'+os_icon_list[num]);
                    $tag.siblings('.select-os-icon').val(os_icon_list[num]).attr('data-value',os_icon_list[num]);
                }
            });
            return this;
        }
    }


    $.fn.select_os_icon=function(opts){
        var os_icon=new OS_ICON(this,opts);
        os_icon.bind_select();
        var icon='-';
        $(this).each(function(){
            icon=$(this).attr('data-value');
            $(this).val(icon);
        })
        return this;
    }
    $.fn.select_os_icon.defaults={

    }
})(jQuery);