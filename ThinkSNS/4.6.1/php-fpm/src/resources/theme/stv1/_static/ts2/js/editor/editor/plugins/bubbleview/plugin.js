/**
 * bubble or tip view for kissy editor
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("bubbleview", function() {
    var S = KISSY,
        KE = S.Editor,
        Event = S.Event,
        DOM = S.DOM;

    if (KE.BubbleView) {
        S.log("attach bubbleview more", "warn");
        return;
    }


    var BubbleView = S['UIBase'].create(KE.Overlay,
        [], {
        renderUI:function() {

            var el = this.get("el");
            el.addClass("ke-bubbleview-bubble");
        },
        show:function() {

            var self = this,
                a = self._selectedEl,
                xy = a._4e_getOffset(document);
            xy.top += a.height() + 5;
            self.set("xy", [xy.left,xy.top]);

            var archor = getTopPosition(self);
            if (!archor) {
            } else {
                xy.top = archor.get("y") + archor.get("el")[0].offsetHeight;
            }

            BubbleView['superclass'].show.call(self);
            self.set("xy", [xy.left,xy.top]);
        },
        destructor:function() {
            KE.Utils.destroyRes.call(this);
        }
    }, {
        ATTRS:{
            focus4e:{
                value:false
            },
            "zIndex":{
                value:KE.baseZIndex(KE.zIndexManager.BUBBLE_VIEW)
            }
        }
    });


    var holder = {};


    /**
     * 是否两个bubble上下重叠？
     * @param b1
     * @param b2
     */
    function overlap(b1, b2) {
        var b1_y = b1.get("y"),b1_y2 = b1_y + b1.get("el")[0].offsetHeight;

        var b2_y = b2.get("y"),b2_y2 = b2_y + b2.get("el")[0].offsetHeight;

        return !(b1_y2 < b2_y || b2_y2 < b1_y);

    }

    /**
     * 得到依附在同一个节点上的所有bubbleview中的最下面一个
     * @param self
     */
    function getTopPosition(self) {
        var archor;
        for (var p in holder) {
            var h = holder[p];
            if (h.bubble) {
                if (self != h.bubble
                    && h.bubble.get("visible")
                    && overlap(self, h.bubble)
                    ) {
                    if (!archor) {
                        archor = h.bubble;
                    } else if (archor.get("y") < h.bubble.get("y")) {
                        archor = h.bubble;
                    }
                }
            }
        }
        return archor;
    }

    function getInstance(pluginName) {
        var h = holder[pluginName];
        if (!h.bubble) {
            h.bubble = new BubbleView({
                autoRender:true
            });
            h.cfg.init && h.cfg.init.call(h.bubble);
        }
        return h.bubble;
    }


    BubbleView.destroy = function(pluginName) {
        var h = holder[pluginName];
        if (h && h.bubble) {
            h.bubble.destroy();
            h.bubble = null;
        }
    };

    BubbleView.attach = function(cfg) {
        var pluginName = cfg.pluginName;
        var cfgDef = holder[pluginName];
        S.mix(cfg, cfgDef.cfg, false);
        var pluginContext = cfg.pluginContext,
            func = cfg.func,
            editor = cfg.editor,
            bubble = cfg.bubble;
        //借鉴google doc tip提示显示
        editor.on("selectionChange", function(ev) {
            var elementPath = ev.path,
                elements = elementPath.elements,
                a,
                lastElement;
            if (elementPath && elements) {
                lastElement = elementPath.lastElement;
                if (!lastElement) return;
                a = func(lastElement);
                if (a) {
                    bubble = getInstance(pluginName);
                    bubble._selectedEl = a;
                    bubble._plugin = pluginContext;
                    bubble.hide();
                    //等所有bubble hide 再show
                    setTimeout(function() {
                        bubble.show();
                    }, 10);
                } else if (bubble) {
                    bubble._selectedEl = bubble._plugin = null;
                    bubble.hide();
                }
            }
        });
        //代码模式下就消失
        //!TODO 耦合---
        function hide() {
            bubble && bubble.hide();
        }

        editor.on("sourcemode blur", hide);
        Event.on(DOM._4e_getWin(editor.document), "scroll", hide);
    };
    BubbleView.register = function(cfg) {
        var pluginName = cfg.pluginName;
        holder[pluginName] = holder[pluginName] || {
            cfg:cfg
        };
        Event.on(document, "click", function() {
            cfg.bubble && cfg.bubble.hide();
        });
        if (cfg.editor) {
            BubbleView.attach(cfg);
        }
    };

    KE.BubbleView = BubbleView;
}, {
    attach:false,
    requires:["overlay"]
});