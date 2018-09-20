$(function () {
    var $menu = $(".menu"), $menuLi = $menu.find("li"), $current = $menu.find('.current'), $li_3 = $menu.find('li.li_3'), $li_3_content = $li_3.find('.li_3_content');
    $menuLi.hover(function () {
        var $this = $(this), num = $menuLi.index($this), current = $menuLi.index($(".first")), len = current - num;
        $menu.css("background-position", (101 * current) + "px" + " bottom");
        $current.removeClass("lihover");
        $menuLi.removeClass("first");
        $this.addClass("first");
        if (len <= 0) { len = -len; };
        if (num != 4) {
            $menu.stop().animate({ backgroundPosition: (101 * num) + "px" + " bottom" }, 100 * len);
        }
        else {
            $menu.stop().animate({ backgroundPosition: (101 * num + 30) + "px" + " bottom" }, 100 * len);
        }
    });
    $li_3.hover(function () {
        $li_3_content.stop(true, true).fadeIn(0);
    }, function () {
        $li_3_content.fadeOut(500, function () {
            $li_3_content.css("display", "none");
        });
    });
    $menu.mouseleave(function () {
        var $this = $(this), num = $menuLi.index($this), current = $menuLi.index($current), len = current - num;
        $menuLi.removeClass("first");
        $current.addClass("first");
        if (len <= 0) { len = -len; };
        $menu.stop().animate({ backgroundPosition: (100 * current + 1) + "px" + " bottom" }, 100 * len);
    });
    $("a.noclick").click(function (event) {
        event.preventDefault();
    });
});