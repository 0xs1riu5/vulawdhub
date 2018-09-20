(function ($) {
    var BG_POS = 'bgPos';
    var usesTween = !!$.Tween;
    if (usesTween) {
        $.Tween.propHooks['backgroundPosition'] = {
            get: function (tween) {
                return parseBackgroundPosition($(tween.elem).css(tween.prop));
            },
            set: function (tween) {
                setBackgroundPosition(tween);
            }
        };
    }
    else {
        $.fx.step['backgroundPosition'] = setBackgroundPosition;
    };
    function parseBackgroundPosition(value) {
        var bgPos = (value || '').split(/ /);
        var presets = { center: '50%', left: '0%', right: '100%', top: '0%', bottom: '100%' };
        var decodePos = function (index) {
            var pos = (presets[bgPos[index]] || bgPos[index] || '50%').
                match(/^([+-]=)?([+-]?\d+(\.\d*)?)(.*)$/);
            bgPos[index] = [pos[1], parseFloat(pos[2]), pos[4] || 'px'];
        };
        if (bgPos.length == 1 && $.inArray(bgPos[0], ['top', 'bottom']) > -1) {
            bgPos[1] = bgPos[0];
            bgPos[0] = '50%';
        }
        decodePos(0);
        decodePos(1);
        return bgPos;
    }
    function setBackgroundPosition(fx) {
        if (!fx.set) {
            initBackgroundPosition(fx);
        }
        $(fx.elem).css('background-position',
            ((fx.pos * (fx.end[0][1] - fx.start[0][1]) + fx.start[0][1]) + fx.end[0][2]) + ' ' +
            ((fx.pos * (fx.end[1][1] - fx.start[1][1]) + fx.start[1][1]) + fx.end[1][2]));
    }
    function initBackgroundPosition(fx) {
        var elem = $(fx.elem);
        var bgPos = elem.data(BG_POS);
        elem.css('backgroundPosition', bgPos);
        fx.start = parseBackgroundPosition(bgPos);
        fx.end = parseBackgroundPosition($.fn.jquery >= '1.6' ? fx.end :
            fx.options.curAnim['backgroundPosition'] || fx.options.curAnim['background-position']);
        for (var i = 0; i < fx.end.length; i++) {
            if (fx.end[i][0]) {
                fx.end[i][1] = fx.start[i][1] + (fx.end[i][0] == '-=' ? -1 : +1) * fx.end[i][1];
            }
        }
        fx.set = true;
    }
    $.fn.animate = function (origAnimate) {
        return function (prop, speed, easing, callback) {
            if (prop['backgroundPosition'] || prop['background-position']) {
                this.data(BG_POS, this.css('backgroundPosition') || 'left top');
            }
            return origAnimate.apply(this, [prop, speed, easing, callback]);
        };
    }($.fn.animate);
})(jQuery);
