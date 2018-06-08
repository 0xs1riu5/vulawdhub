/*!
 * jQuery.dialog  2.8.0
 *
 * Copyright 2010, Hudong.com
 * Dual licensed under the MIT and GPL licenses.
 * author: panxuepeng
 * blog: http://dushii.blog.163.com
 * date: 2011-08-31
 * last: 2012-05-10
 */
// log: 2012-01-04 代码结构进行了调整，更加清晰 
// log: 2012-04-01 基本兼容dialog1.0版本
// log：2012-04-24 取消了$.dialog({...})之后必须执行show()方法
//		即$.dialog({...}).show();
//		修改后，和1.0版本一样，$.dialog({...})即显示弹窗。
// log: 2012-05-10 增加resetHeight()方法，在内容发生变化后可以重新定位窗口的高度
(function($) {
    var IE = window.VBArray, IE6 = IE && !window.XMLHttpRequest, win = $(window), dom = $(document), locked = {}, // 窗口锁
    dialogList = {}, // 窗口列表
    openList = [], // 弹窗打开列表
    config = {
        // 弹窗全局设置
        skin: "xiaobaike",
        basePath: "./",
        zIndex: 10100
    }, DIALOG_1_0 = false;
    // 是否dialog1.0版本的配置文件，如果是的话需要做一些转换
    // 参数 op 尽量兼容 $.dialog-1.0及以前的版本
    function setOption(op) {
        if (op["onOk"]) {
            op["onok"] = op["onOk"];
            DIALOG_1_0 = true;
        } else if (!op["onok"]) {
            op.style.ok = {
                display: "none"
            };
        }
        if (op["onCancel"]) {
            op["oncancel"] = op["onCancel"];
            DIALOG_1_0 = true;
        } else if (!op["oncancel"]) {
            op.style.cancel = {
                display: "none"
            };
        }
        if (op["onClose"]) {
            op["onclose"] = op["onClose"];
            DIALOG_1_0 = true;
        }
        if (op.styleContent) {
            op.style.content = op.styleContent;
            DIALOG_1_0 = true;
        }
        if (op.styleOk) {
            op.style.ok = op.styleOk;
            DIALOG_1_0 = true;
        }
        if (op.styleCancel) {
            op.style.cancel = op.styleCancel;
            DIALOG_1_0 = true;
        }
        if (op.url && !op.content) {
            op.content = op.url;
            DIALOG_1_0 = true;
        }
        if (op.overlay) {
            if (op.styleOverlay) {
                op.overlay = {
                    opacity: op.styleOverlay.opacity,
                    bgColor: op.styleOverlay.backgroundColor,
                    backgroundColor: op.styleOverlay.backgroundColor
                };
                DIALOG_1_0 = true;
            } else if (typeof op.overlay !== "object") {
                op.overlay = {
                    opacity: .2,
                    bgColor: "#000"
                };
            }
        }
        if (op.closeImg === false) {
            op.style.close = {
                display: "none"
            };
            DIALOG_1_0 = true;
        } else if (DIALOG_1_0) {
            op.style.close = {
                display: "block"
            };
        }
        if (DIALOG_1_0) {
            if (op.style.ok.display == "none" && op.style.cancel.display == "none") {
                op.style.bottom = {
                    display: "none"
                };
            }
            switch (op.position) {
              case "middle":
              case "c":
              case "m":
                op.position = "center";
                break;

              case "rb":
              case "br":
              case "rightBottom":
              case "bottomRight":
                op.position = "right-bottom";
                break;

              case "rt":
              case "tr":
              case "rightTop":
              case "topRight":
                op.position = "right-top";
                break;

              case "lt":
              case "tl":
              case "leftTop":
              case "topLeft":
                op.position = "left-top";
                break;

              case "ct":
              case "tc":
              case "centerTop":
              case "topCenter":
                op.position = "center-top";
                break;
            }
        }
        return op;
    }
    /*
1、自定义参数覆盖默认值方式合并
2、如果此id的dialog已经存在，修改窗口的内容
3、如果不存在，则创建 dialog = new Dialog();
4、创建后，绑定事件
5、创建后的窗口，并没有显示，还要执行dialog.show()方法
*/
    $.dialog = function(option) {
        var o = Dialog, dialog, isExist = false;
        //对于未定义的选项使用默认值 defaults
        //var op = $.extend({}, o.defaults, option);
        // 2012-04-01 潘雪鹏
        // extend 第一个参数必须使用true，否则 o.defaults 当中的子对象会被破坏
        var op = $.extend(true, {}, o.defaults, option);
        // 参数兼容 $.dialog-1.0 版本
        op = setOption(op);
        dialog = dialogList[op.id];
        // 当没有指定皮肤时，使用全局设置config当中设置的皮肤
        if (!option.skin) {
            op.skin = config.skin;
        }
        if (dialog) {
            // 此id的dialog存在，设置窗口的内容和样式
            // 即时此id的弹窗已经存在，弹窗的option对象每次都会被覆盖
            delete op.one;
            dialog.option = op;
            isExist = true;
            // 打开已经存在的窗口，需要再次设置zIndex属性
            config.zIndex += 1;
            dialog.panel.css({
                zIndex: config.zIndex
            });
            dialog.panel.find(".jqd-title").html(op.title);
        } else {
            // 创建dialog
            dialog = dialogList[op.id] = new o(op);
            o.bindEvent(dialog);
        }
        var panel = dialog.panel;
        // 绑定一些事件
        panel.unbind("click.jqd");
        if (typeof op["onclick"] === "function") {
            panel.bind("click.jqd", function(e) {
                return op["onclick"](e);
            });
        }
        panel.unbind("mouseover.jqd");
        if (typeof op["onmouseover"] === "function") {
            panel.bind("mouseover", function(e) {
                return op["onmouseover"](e);
            });
        }
        panel.unbind("mouseout.jqd");
        if (typeof op["onmouseout"] === "function") {
            panel.bind("mouseout.jqd", function(e) {
                return op["onmouseout"](e);
            });
        }
        // 设置皮肤和样式
        panel.removeClass().addClass("jquery-dialog " + op.skin);
        o.setCss(dialog);
        // 绑定拖动事件
        // 拖动功能依赖于 jquery-dragdrop.js
        if (op["draggable"] && op["title"]) {
            panel.addClass("draggable");
            panel.find(".jqd-title").addClass("dragger");
        } else {
            panel.removeClass("draggable");
            panel.find(".jqd-title").removeClass("dragger");
        }
        // 设置内容
        // 对于已经存在的窗口二次打开时，必须有内容才需要重写内容区域
        // 防止使用selector时，二次显示窗口导致内容丢失的问题
        if (isExist && op.content || !isExist) {
            dialog.setContent(op.content, op.type);
        }
        dialog.show();
        if (DIALOG_1_0) {
            return panel;
        } else {
            return dialog;
        }
    };
    // Dialog的全局方法，外部可以访问
    $.extend($.dialog, {
        config: function(obj) {
            config = $.extend(config, obj);
        },
        setConfig: function(obj) {
            config = $.extend(config, obj);
        },
        // 关闭最近打开的窗口
        close: function(id) {
            var dialog;
            if (id) {
                if (dialogList[id]) {
                    dialog = dialogList[id].close();
                } else {}
            } else {
                if (openList.length) {
                    dialog = openList.pop();
                    dialog.close();
                } else {}
            }
            return dialog;
        },
        box: function(id, title, content, conf) {
            var id = id||"__box", op = {
                id: id,
                width: 500,
                height: 200,
                align: 'center',
                title: title,
                content: "",
                style: {
                    bottom: {
                        display: "none"
                    }
                }
            };
            if (typeof content === "object") {
                op = $.extend(true, op, content);
            } else if (typeof content === "string") {
                op.title = title;
                op.content = content;
            } else if (conf && typeof conf === "object") {
                op = $.extend(true, op, conf);
            }
            return $.dialog.confirm(op);
        },
        // 三个快捷dialog:$.dialog.tip/alert/confirm 
        tip: function(content, title) {
            var id = "__tip", op = {
                id: id,
                title: title,
                content: "",
                style: {
                    bottom: {
                        display: "none"
                    }
                }
            };
            if (typeof content === "object") {
                op = $.extend(true, op, content);
            } else if (typeof content === "string") {
                op.title = title;
                op.content = content;
            }
            return $.dialog.confirm(op);
        },
        alert: function(content, onok, title, conf) {
            var id = "__alert", op = {
                id: id,
                title: title,
                content: "",
                style: {
                    bottom: {
                        display: "block"
                    },
                    ok: {
                        display: "inline-block"
                    },
                    cancel: {
                        display: "none"
                    }
                }
            };
            if (typeof content === "object") {
                op = $.extend(true, op, content);
            } else if (typeof content === "string") {
                op.title = title;
                op.content = content;
            }
            
            if (conf && typeof conf === "object") {
                op = $.extend(true, op, conf);
            }
            return $.dialog.confirm(op, onok, title);
        },
        confirm: function(content, onok, title) {
            title = title || "提示";
            var id = "__confirm", op = {
                id: id,
                title: title,
                content: "",
                width: 340,
                //	height:60,
                offsetY: -50,
                zIndex: 20001,
                overlay: {
                    zIndex: 10001,
                    opacity: 0
                },
                style: {
                    content: {
                        "font-size": "14px",
                        color: "#333"
                    },
                    bottom: {
                        display: "block"
                    },
                    ok: {
                        display: "inline-block"
                    },
                    cancel: {
                        display: "inline-block"
                    }
                },
                onok: function(panel, dialog) {
                    if (typeof onok === "function") {
                        onok(panel, dialog);
                    } else {
                        dialog.close();
                    }
                },
                oncancel: function() {
                    dialog.close();
                }
            };
            if (typeof content === "object") {
                op = $.extend(true, op, content);
            } else if (typeof content === "string") {
                op.title = title;
                op.content = content;
            }
            return $.dialog(op);
        },
        get: function(id, flag) {
            if (dialogList[id]) {
                if (flag) return dialogList[id];
                return DIALOG_1_0 ? dialogList[id].panel : dialogList[id];
            } else {
                return null;
            }
        },
        /*
        // 添加皮肤
        $.dialog.addStyle(style);
        */
        addStyle: function(style) {
            $("head").append("<style>" + style + "</style>");
        }
    });
    /*
窗口的构造方法
*/
    function Dialog(option) {
        var self = this, o = Dialog;
        self.closed = true;
        self.option = option;
        self.completed = 1;
        self.panel = o.build(option);
        self.panel.click(function(e) {
            if (locked[option.id]) {
                return false;
            }
            var target = $(e.target), name = target.attr("name");
            if (/^(close|ok)$/i.test(name)) {
                return self[name]();
            }
        });
    }
    // 弹窗默认配置
    Dialog.defaults = {
        id: "default",
        title: "",
        type: "html",
        // html img ajax iframe selector
        content: "",
        align: "left",
        valign: "center",
        width: 0,
        height: 0,
        //	fixedSize: false, // 窗口大小自适应内容，并自动根据内容重新定位窗口
        offsetX: 0,
        // 水平方向位置调整，负值会使窗口偏左
        offsetY: 0,
        // 垂直方向位置调整，负值会使窗口偏上
        // 中间center 中上center-top 中下center-bottom 右下right-bottom 左上left-top
        position: "center",
        fixed: false,
        // 固定，不随屏幕滚动
        draggable: false,
        // 是否可拖动，拖动功能依赖于 jquery-dragdrop.js
        // 当弹窗不指定具体的zIndex值时，使用全局的 config.zIndex
        // 使用全局的zIndex值和使用指定的zIndex的区别是
        // 全局zIndex：弹窗zIndex值会在窗口被点击时加1
        // 私有zIndex：固定值，永远不变，适合置顶窗口使用
        zIndex: 0,
        // 默认为0，除非要使用置顶窗口，否则不要使用此设置
        autoClose: 0,
        // 自动关闭
        model: "default",
        //default together alone
        // 遮罩层
        overlay: {
            opacity: .7
        },
        // 光标默认位置，在输入框的开始或末尾
        focusInput: "",
        // '', start, end
        // 事件
        one: null,
        // 仅首次显示后执行
        callback: null,
        // 弹窗每次显示完成后执行
        onclose: null,
        oncancel: null,
        onok: null,
        onclick: null,
        onmouseover: null,
        onmouseout: null,
        textOk: "确 定",
        textCancel: "取 消",
        // 正在加载的提示信息
        //loadding: 'loadding...',
        loading: "loading...",
        loadErrorInfo: "加载失败",
        // 指定窗口的某个部分是否显示
        display: {
            title: true,
            close: true,
            bottom: true,
            ok: true,
            cancel: true
        },
        // 皮肤
        skin: config.skin,
        // 自定义样式，方便在不更换皮肤的情况下进行样式微调
        // style: {title: {key:value, key2:value2, .....}}
        style: {
            "outter-wrap": "",
            "inner-wrap": "",
            title: "",
            content: "",
            bottom: "",
            ok: "",
            cancel: "",
            close: "",
            error: "",
            success: "",
            status: ""
        }
    };
    // Dialog 的私有方法
    // 注意：
    // 并不是这样的写法使其成为了私有方法，
    // 而是外部不能访问到这些方法，使用其他书写方式也可以,
    // 因为Dialog是局部变量。
    $.extend(Dialog, {
        // 初始化，此js文件加载完成后执行
        init: function() {
            Dialog.setStyle();
            var style = [];
            // 添加预定义的皮肤
            style.push(".default .jqd-inner-wrap{border:2px solid #148ea4;border-radius:6px;box-shadow:0 0 8px #148ea4;}.default .jqd-title{background-color:#148ea4;border-bottom:1px solid #E5E5E5;color:#FFFFFF;font-weight:bold;}.default .jqd-close{width:18px;height:20px;top:2px;right:5px;}.default .jqd-button button{width:80px;height:27px;cursor: pointer;font-size:12px;line-height:100%;_line-height:26px;}");
            style.push(".noborder .jqd-inner-wrap{}.noborder .jqd-title{display:none;}.noborder .jqd-content{padding:0;}.noborder .jqd-close{display:none;}.noborder .jqd-bottom-pad{height:0;}.noborder .jqd-bottom{display:none;}");
            style.push(".bluebox .jqd-bg{background:#000; filter:alpha(opacity=30);opacity:0.3; width:100%; height:100% !important; _height:1000px;position:absolute; z-index:-1; zoom:1;}.bluebox .jqd-inner-wrap{}.bluebox .jqd-title{background-color:#009DF0;color:#FFF;font-weight:bold;}.bluebox .jqd-close{top:3px;right:5px;}.bluebox .jqd-button button{width:80px;height:27px;cursor: pointer;font-size:12px;line-height:100%;_line-height:26px;}");
            style.push(".greybox .jqd-outter-wrap{}.greybox .jqd-bg{background:#000; filter:alpha(opacity=8);opacity:0.08; width:100%; height:100% !important; _height:1000px;position:absolute; z-index:-1; zoom:1;}.greybox .jqd-inner-wrap{border:1px solid #A5A5A5;border-radius:2px;box-shadow:0;}.greybox .jqd-title{border:0; height:30px;font-size:14px;line-height:30px;color:#87C7DB;background-color:#F3F3F3;}.greybox .jqd-close{width:10px;height:10px;top:10px;right:14px;background:url(http://www.huimg.cn/xiaobaike/images/baike2.0/form.png) -42px -108px;text-indent:2em;}.greybox .jqd-button button{width:80px;height:27px;cursor: pointer;font-size:12px;line-height:100%;_line-height:26px;}");
            style.push(".xiaobaike .jqd-outter-wrap{}.xiaobaike .jqd-bg{background:#000; filter:alpha(opacity=8);opacity:0.08; width:100%; height:100% !important; _height:1000px;position:absolute; z-index:-1; zoom:1;}.xiaobaike .jqd-inner-wrap{border:1px solid #A5A5A5;border-radius:2px;box-shadow:0;}.xiaobaike .jqd-title{padding:20px 0 0 30px;font-size:20px;color:#3a3e55;font-weight:700;}.xiaobaike .jqd-content{padding:0;}.xiaobaike .jqd-close{width:16px;height:16px;background:url(js/jqeditor/skins/bg/close.png) no-repeat center center;overflow:hidden;line-height:99em;top:26px;right:26px;}.xiaobaike .jqd-button button{background-color: #0068b7;height: 40px;border-radius: 40px;font-size: 16px;color: #fff;padding:0 30px;display: inline-block;margin:0 5px;border:0;cursor: pointer;}.xiaobaike .jqd-cancel.jqd-button button[name='panel-close']{background-color:#788da3;}");
            style.push(".hudong .jqd-title, .hudong .jqd-close, .hudong .jqd-button, .hudong .jqd-button button{background:url(http://www.huimg.cn/lib/dialog/bg_minbox.png) no-repeat;}.hudong .jqd-title{background-repeat:repeat-x;height:30px;border-top:1px solid #69B2FC; font-weight:bold; color:#FFF; line-height:30px;}.hudong .jqd-bg{background:#000; filter:alpha(opacity=20);opacity:0.2; width:100%; height:100% !important; _height:1000px;position:absolute; z-index:-1; zoom:1;}.hudong .jqd-content{padding:2px 20px;}.hudong .jqd-bottom{height:25px;padding:5px 0 8px; text-align:center;}.hudong .jqd-close{width:16px;height:16px;top:7px;right:7px; background-position:0 -218px;text-indent:2em;}.hudong .jqd-button{display:inline-block;  margin:0 4px 0 3px; background-position:0 -32px;font-size:12px;}.hudong .jqd-button button{margin:0; cursor:pointer;height:31px; padding:0 14px; *padding:0 4px; border:0 none; background-position:right -63px;font-weight:bold;color:#FFF;line-height:31px;}.hudong .jqd-reg{background-position:0 -94px;}.hudong .jqd-reg button{background-position:right -125px; color:#2375CB;}.hudong .jqd-cancel{background-position:0 -156px;}.hudong .jqd-cancel button{background-position:right -187px; color:#2C2C2C;}");
            $.dialog.addStyle(style.join("\n\n"));
        },
        // 构建窗口
        build: function(option) {
            var div = null, id = "dialog-" + option.id, o = $("#" + id);
            if (!o.size()) {
                div = document.createElement("div");
                div.id = id;
                div.innerHTML = Dialog.getHtml(option);
                document.body.appendChild(div);
                o = $(div);
            }
            // 将窗口的位置设置到隐藏区域
            o.css({
                left: 0,
                top: "-2000px",
                zIndex: option.zIndex || config.zIndex,
                position: option.fixed && !IE6 ? "fixed" : "absolute"
            });
            return o;
        },
        // 将窗口默认样式添加到页面
        setStyle: function() {
            var style = ".jquery-dialog{position:absolute;z-index:1000;font-size:12px;overflow:hidden;}.jquery-dialog td{padding:0px;}.jqd-outter-wrap{padding:4px;}.jqd-outter-wrap table{margin:0;}.jqd-bg{border-radius:4px;}.jqd-inner-wrap{position:relative;background-color:#FFF;}.jqd-title{padding:0px 10px;font-size:16px;color:#FFFFFF;cursor:default;height:50px;line-height:30px;}.jqd-close{position:absolute;top:5px;right:20px;font-size:24px;color:#FFFFFF;cursor:pointer;width:18px;height:18px;overflow:hidden;font-weight:bold;font-family: tahoma;line-height:20px;}.jqd-content{padding:0px 10px;}.jqd-text{padding:10px;text-align:left;line-height:20px;font-size:14px;}.jqd-bottom{text-align:center;height:40px;padding-right:20px;}.jqd-bottom-pad{height:30px;}.jqd-cancel {margin-left:10px;}.jqd-status,.jqd-success, .jqd-error{position:absolute;width:200px;bottom:9px;background-color:#F5F5F5;color:#666;line-height:22px;border:1px dashed #CCC;border-radius:5px;left:20px;padding:0 10px;display:none;}.jqd-success{color:green;}.jqd-error{color:red;}";
            $.dialog.addStyle(style);
        },
        /*
	 弹出窗口的结构需要满足以下特征：
	 1、固定大小的以提示性文本为主的内容，垂直居中
	 2、有特定结构、固定尺寸的html
	 3、未知尺寸大小的内容
	*/
        getHtml: function(option) {
            var o = option, style = "";
            // 去掉 o.fixedSize 参数 2012-03-27 潘雪鹏
            //if( o.fixedSize ){
            //	style += 'width:'+o.width+'px; height:'+o.height+'px; overflow:hidden;';
            //}
            if (o.width && o.height) {
                style += "width:" + o.width + "px; height:" + o.height + "px; overflow:hidden;";
            } else if (o.width) {
                style += "width:" + o.width + "px; overflow-x:hidden;";
            } else if (o.height) {
                style += "height:" + o.height + "px; overflow-y:hidden;";
            }
            // 浏览器兼容需要使用table标签
            return '<div class="jqd-bg"></div><div class="jqd-outter-wrap">	<table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td>	<div class="jqd-inner-wrap">	<div class="jqd-title">' + o.title + '</div><a class="jqd-close" name="close" title="关闭">×</a>	<div class="jqd-content" id="jqd-content-' + o.id + '" style="' + style + '"></div>	<div class="jqd-bottom">	<span class="jqd-ok jqd-button"><button name="ok" type="submit" >' + o.textOk + '</button></span>	<span class="jqd-cancel jqd-button"><button name="close">' + o.textCancel + '</button></span>	</div>	<div class="jqd-bottom-pad"></div>	<div class="jqd-status" id="jqd-status-' + o.id + '"></div>	</div></td></tr></tbody></table></div>';
        },
        // 设置样式窗口
        setCss: function(self) {
            var o = self.panel, op = self.option, s;
            if (op.style && typeof op.style == "object") {
                for (var name in op.style) {
                    s = op.style[name];
                    if (s) o.find(".jqd-" + name).css(s);
                }
            }
            return self;
        },
        // 绑定事件
        bindEvent: function(self) {
            var op = self.option, timeId = 0, panel = self.panel;
            win.bind("resize", function() {
                if (!self.closed) {
                    self.setPosition();
                }
            });
            // IE6不支持fixed属性，需要自己处理一下
            if (IE6 && op.fixed) {
                win.bind("scroll", function() {
                    // 连续的滚屏停止后执行一次滚动操作
                    clearTimeout(timeId);
                    timeId = setTimeout(function() {
                        if (!self.closed) Dialog.scroll(self);
                    }, 200);
                });
            }
            // 鼠标在弹窗标题栏上按下时，zIndex要加1
            // 以便在多弹窗同时使用时，保持被点击的弹窗在最上面
            panel.mousedown(function(e) {
                if ($(e.target).is("a")) {
                    // 点击链接时不触发zindex加操作
                    return true;
                }
                var op = self.option;
                // 如果没有自定义zIndex值，则需要每次显示都加1
                if (!op.zIndex && $.dialog.self != self) {
                    config.zIndex += 2;
                    panel.css({
                        zIndex: config.zIndex
                    });
                    $.dialog.self = self;
                }
            });
        },
        scroll: function(self) {
            var op = self.option, top = dom.scrollTop(), winH = win.height(), dialogH = self.panel.outerHeight();
            // 当浏览器窗口高度大于弹出层高度时才需要执行此调整操作
            if (winH > dialogH) {
                if (/^c(enter)?$/i.test(op.position)) {
                    top += (winH - dialogH) / 2;
                } else if ("right-bottom" === op.position) {
                    top += winH - dialogH;
                }
                self.panel.animate({
                    top: top
                }, 200);
            }
        },
        // 获取窗口的显示位置
        getPostion: function(self) {
            var o = self.panel, option = self.option, winW = win.width(), winH = win.height(), dialogW = o.outerWidth(), dialogH = o.outerHeight(), pos = {}, scrollTop = dom.scrollTop();
            if (/^c(enter)?/i.test(option.position)) {
                // 水平居中改为百分比方式，
                // 当窗口大小发生变化时，不需要js再次处理即可自动水平居中
                // 垂直方向需要处理
                pos.left = "50%";
                pos.marginLeft = -dialogW / 2;
                if ("center-top" === option.position) {
                    pos.top = 0;
                } else if ("center-bottom" === option.position) {
                    pos.top = winH - dialogH - 2;
                } else {
                    pos.top = (winH - dialogH) / 2;
                }
            } else {
                if ("right-bottom" === option.position) {
                    pos.left = winW - dialogW - 2;
                    pos.top = winH - dialogH - 2;
                } else if ("right-top" === option.position) {
                    pos.left = winW - dialogW - 2;
                    pos.top = 2;
                } else if ("left-top" === option.position) {
                    pos.left = 2;
                    pos.top = 2;
                }
                pos.left += option.offsetX;
                // left 最小值不能小于0
                pos.left = pos.left < 0 ? 0 : pos.left;
            }
            if (!option.fixed || IE6) {
                pos.top += scrollTop;
            }
            pos.top += option.offsetY;
            // 注意弹窗的顶部不要被浏览器窗口遮住
            if (option.fixed) {
                if (IE6) {
                    pos.top = pos.top < scrollTop ? scrollTop : pos.top;
                } else {
                    pos.top = pos.top < 0 ? 0 : pos.top;
                    pos.position = "fixed";
                }
            } else {
                pos.top = pos.top < scrollTop ? scrollTop : pos.top;
            }
            return pos;
        },
        //修正光标的位置
        resetFocus: function(self) {
            var o = self.panel, option = self.option, focusStart = option.focusInput == "start" ? true : false;
            if (option.focusInput && /(start|end)/i.test(option.focusInput)) {
                var inputTexts = o.find(":text,textarea").filter(":visible").eq(0);
                inputTexts.focus();
                //执行focus()后，如input有值，FF默认是将光标定位在值的后面，而IE是默认定位在最前面
                //统一修正为默认定位在文本框的值后面，可以通过 option.focusInput 配置
                var el = inputTexts[0];
                if (el.createTextRange) {
                    var re = el.createTextRange();
                    re.select();
                    re.collapse(focusStart);
                    re.select();
                } else if (el.setSelectionRange) {
                    //作用于 FF Chrome 等
                    if (focusStart) {
                        el.setSelectionRange(0, 0);
                    } else {
                        el.setSelectionRange(el.value.length, el.value.length);
                    }
                }
            }
        }
    });
    // Dialog 的实例方法
    Dialog.prototype = {
        setPosition: function() {
            var self = this, o = self.panel, pos = Dialog.getPostion(self);
            //o.animate( pos,  500);
            o.css(pos);
        },
        // 显示窗口
        show: function() {
            var self = this, o = self.panel, option = self.option;
            // 如果窗口已经处于打开状态，则直接返回
            // 如果需要重新定位窗口的位置，请使用 setPosition()
            // dialog.show().show()... 也就是说连续多次调用show方法和调用一次是一样的;
            if (!self.closed) {
				if (self.completed && option.type !== "iframe") {
					setTimeout(function() {
						self.execCallback();
					}, 0);
				}
                return self;
            }
            self.closed = false;
            // 定位窗口
            o.css(Dialog.getPostion(self));
            if (option.overlay) {
                // 遮罩层和弹窗使用相同的id后缀
                option.overlay.id = option.id;
                self.overlay = $.overlay(option.overlay);
            }
            // 将打开的窗口放到打开列表里面，以方便按顺序关闭
            var len = openList.length;
            for (var i = 0; i < len; i++) {
                if (self == openList[i]) {
                    delete openList[i];
                    break;
                }
            }
            openList.push(self);
            // 处理窗口共存问题
            if (option.model.indexOf("alone") > -1) {
                for (var i = 0, len = openList.length; i < len; i++) {
                    var d = openList[i];
                    if (d && self != d && d.option.model.indexOf("together") < 0) {
                        d.close();
                    }
                }
            }
            if (self.completed && option.type !== "iframe") {
                setTimeout(function() {
                    self.execCallback();
                }, 0);
            }
            return self;
        },
        // type: html(默认值) ajax img iframe selector
        setContent: function(content, type) {
            type = type || "html";
            var self = this, html = "", op = self.option, id = "jqd-content-" + op.id, url;
            self.completed = 1;
            //  content is jQuery object
            if (typeof content === "object") {
                $("#" + id).empty().append(content);
                return self;
            }
            switch (type) {
              case "ajax":
              case "img":
                url = content;
                content = op.loading;
                self.completed = 0;
                break;

              case "iframe":
                url = content;
                content = "<iframe id='" + id + "_iframe' name='" + id + "_iframe' border='0' width='" + op.width + "' height='" + op.height + "' frameborder='no' " + " marginwidth='0' marginheight='0' scrolling='no' allowtransparency='yes'></iframe>";
                break;
            }
            if (type == "selector") {
                html = $(content).html() || $(content).data("htmltpl");
                // 清除原始内容，否则可能导致id冲突
                // 解决0.8版本在使用过程中遇到的id冲突问题
                $(content).empty();
                $(content).data("htmltpl", html);
            } else {
                html = content;
            }
            // 纯文字使用table标签包裹起来
            if (!html || !/<\/(div|table|dl|ul|ol|iframe)>/i.test(html)) {
                html = '<table border="0" cellspacing="0" cellpadding="0" width="' + op.width + '" height="' + op.height + '">' + '<tbody><tr><td class="jqd-text" style="text-align:' + op.align + "; vertical-align:" + op.valign + '">' + html + "</td></tr></tbody></table>";
            }
            $("#" + id).html(html);
            // 设置内容加载成功后的回调方法
            if ("img" === type) {
                var img = new Image();
                img.onload = function() {
                    if (self.closed) {
                        return;
                    }
                    self.completed = 1;
                    var width = img.width > 950 ? 950 : img.width;
                    self.setContent('<img src="' + url + '" width="' + width + '" />');
                    self.setPosition();
                };
                img.onerror = function() {
                    self.setContent(op.loadErrorInfo);
                };
                img.src = url;
            } else if ("ajax" === type) {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "html",
                    cache: false,
                    success: function(html, status) {
                        if (self.closed) {
                            return;
                        }
                        self.completed = 1;
                        self.setContent(html);
                        self.setPosition();
                    },
                    complete: function(xhr, status) {
                        if (self.closed) {
                            return;
                        }
                        if (status != "success") {
                            self.setContent(op.loadErrorInfo);
                        }
                    }
                });
            } else if ("iframe" === type) {
                var iframe = $("#" + id + "_iframe"), _t = 0;
                iframe.load(function() {
                    clearTimeout(_t);
                    self.execCallback();
                });
                iframe.attr("src", url);
                _t = setTimeout(function() {
                    self.execCallback();
                }, 1e4);
            } else if ("html" === type) {
                // 设置内容后注意弹窗的高度不要大于浏览器窗口的高度
                if (self.completed) {
                    self.resetHeight();
                    // 当异步加载数据时，在内容加载成功后台执行callback()
                    if ("ajax" === op.type) {
                        setTimeout(function() {
                            self.execCallback(html);
                        }, 0);
                    } else if ("img" === op.type) {
                        setTimeout(function() {
                            self.execCallback();
                        }, 0);
                    }
                }
            }
            return self;
        },
        // 当窗口内容发生变化时
        // 根据内容重置弹窗的高度后，避免fixed时出现弹窗超过浏览器下边框的问题
        // 外部程序可以根据实际情况调用此方法
        resetHeight: function() {
            var self = this, op = self.option, o = self.panel;
            // 如果 position = fixed
            // 要确保dialog高度不能超出浏览器可视高度
            if (op.fixed && op.skin != "noborder") {
                var maxHeight = win.height(), contentElement = o.find(".jqd-content");
                // 先把高度清一下，避免id相同但是加载不同内容时高度不变的问题
                contentElement.css({
                    height: ""
                });
                if (o.height() > maxHeight) {
                    contentElement.css({
                        height: maxHeight - 100,
                        width: contentElement.width(),
                        //? 需要设置这个吗？
                        display: "block",
                        overflowX: "hidden",
                        overflowY: "scroll"
                    });
                } else {
                    contentElement.css({
                        display: "block",
                        overflowX: "visible",
                        overflowY: "visible"
                    });
                }
                // 重新定位
                self.setPosition();
            }
        },
        // 执行用户的回调方法
        execCallback: function(html) {
            var self = this, o = self.panel, option = self.option;
            // 当延迟加载内容时，在第一次显示loading...时不需要进行如下操作，
            // 顾使用self.completed判断当前内容是否已经加载完成了
            if (self.completed) {
                if (typeof option["one"] === "function") {
                    option["one"](o, self);
                    delete option["one"];
                }
                Dialog.resetFocus(self);
                if (typeof option["callback"] === "function") {
                    option["callback"](o, self, html);
                }
                if (option["autoClose"]) {
                    option.autoClose = parseInt(option.autoClose, 10);
                    option.autoClose = isNaN(option.autoClose) ? 3e3 : option.autoClose;
                    if (self["autoCloseTimeoutId"]) {
                        clearTimeout(self["autoCloseTimeoutId"]);
                    }
                    self["autoCloseTimeoutId"] = setTimeout(function() {
                        self.close();
                    }, option.autoClose);
                }
            }
        },
        // 隐藏弹出层，不执行任何事件
        hide: function() {
            var self = this, len = openList.length;
            self.closed = true;
            self.panel.css({
                top: "-2000px"
            });
            if (self.option.overlay) {
                self.overlay.close();
            }
            for (var i = 0; i < len; i++) {
                if (self == openList[i]) {
                    delete openList[i];
                    break;
                }
            }
            return self;
        },
        // 隐藏弹出层，执行关闭事件
        close: function() {
            var self = this, option = self.option;
            self.hide();
            self.clear();
            if (typeof option["onclose"] === "function") {
                option["onclose"](self.panel, self);
            }
            return self;
        },
        // 执行确定事件，如没有指定事件则关闭弹出窗口
        ok: function() {
            var self = this, option = self.option;
            if (typeof option["onok"] === "function") {
                option["onok"](self.panel, self);
            } else {
                self.close();
            }
            return self;
        },
        status: function(html, type) {
            var self = this, option = self.option;
            self.clear();
            type = type || "status";
            $("#jqd-status-" + option.id).removeClass().addClass("jqd-" + type).html(html).show();
            return self;
        },
        notice: function(html) {
            return this.status(html);
        },
        success: function(html) {
            return this.status(html, "success");
        },
        error: function(html) {
            return this.status(html, "error");
        },
        // 清除所有状态信息
        clear: function() {
            var self = this, id = self.option.id;
            clearTimeout(self.statusTimeId);
            $("#jqd-status-" + id).hide();
            return self;
        },
        // 延时3秒后自动清除所有状态信息
        autoClear: function(t) {
            t = parseInt(t, 10);
            if (isNaN(t)) {
                t = 3e3;
            }
            var self = this;
            clearTimeout(self.statusTimeId);
            self.statusTimeId = setTimeout(function() {
                self.clear();
            }, t);
            return self;
        },
        lock: function() {
            locked[this.option.id] = 1;
            return this;
        },
        unlock: function() {
            locked[this.option.id] = 0;
            return this;
        }
    };
    $(document).delegate("a[data-toggle=dialog]", "click.dialog", function(e) {
        var o = $(this), data = o.data(), url = o.attr("href"), config = {
            id: "__ajax",
            title: o.attr("title") || "提示",
            type: "ajax",
            width: data.width || 400,
            height: data.height || 200,
            skin: data.skin || "hudong",
            content: url,
            style: {
                bottom: {
                    display: "none"
                }
            },
            overlay: {
                opacity: .7
            }
        };
        if (data.zindex) {
            config.zIndex = data.zindex;
        }
        if (data.opacity) {
            config.overlay.opacity = data.opacity;
        }
        // ID 选择器
        // ie7/6下，使用ajax获取的内容链接
        // 当是 # 号开头时，会导致浏览器自动改为http开头
        // 需注意！
        if (url.indexOf("#") > -1) {
            config.type = "selector";
            config.content = "#" + url.split("#")[1];
        }
        $.dialog(config);
        return false;
    });
    /*
function submit(panel, dialog){
	var form = panel.find('form'), option = dialog.option;
	if(option.__timeId) clearTimeout(option.__timeId);
	
	// 拦截表单提交事件
	form.submit(function(){
		dialog.status('正在保存，请稍等...');
		dialog.lock();
		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			dataType: 'json',
			success: function(data){
				
			},
			error: function(xhr, status){
			
			},
			complete: function(){
				clearTimeout(option.__timeId);
				dialog.unlock();
			}
		});
		
		option.__timeId = setTimeout(function(){
			dialog.unlock();
		}, 30000);
		return false;
	});
}

$(".dialog-ajax-form").on('click', function(){
	var id = "__form", o = $(this),
		width = o.attr('width') || o.attr('winwidth') || 0,
		height = o.attr('height') || o.attr('winheight') || 0;
		
	$.dialog({
		id: id,
		title: o.attr('title') || o.text(),
		type:'ajax',
		width: width,
		height: height,
		skin: o.attr('skin'),
		content: o.attr('href'),
		callback: function( panel, dialog ){
			submit(panel, dialog);
		},
		overlay: {
			opacity: 0.1
		}
	});
	return false;
});
*/
    // 执行初始化方法
    // 如果此js文件被放在了head标签里面，直接执行 Dialog.init()，
    // 在ie6下，可能会导致浏览器崩溃，具体原因不详，貌似非必现。
    // 暂时改为延迟执行（2012-03-26）
    // Dialog.init();
    setTimeout(function() {
        Dialog.init();
    }, 0);
})(jQuery);

/*!
 * jQuery.overlay  1.0
 *
 * Copyright 2010, panxuepeng
 * Dual licensed under the MIT and GPL licenses.
 * author: panxuepeng
 * blog: http://dushii.blog.163.com
 * date: 2011-08-31
 * last: 2012-01-01
 */
(function($) {
    /*
 遮罩层
 因这个功能独立使用性不强，顾和dialog整合在同一个js文件
*/
    var overlayList = {}, win = $(window), dom = $(document), IE = window.VBArray, IE6 = IE && !window.XMLHttpRequest;
    $.overlay = function(option) {
        var O = Overlay, layer;
        option = $.extend({}, O.defaults, option);
        layer = overlayList[option.id];
        if (!layer) {
            layer = overlayList[option.id] = new O(option);
        }
        layer.show();
        layer.bindEvent();
        return layer;
    };
    // 遮罩层对象
    function Overlay(option) {
        var self = this, o = Overlay;
        self.option = option;
        self.panel = o.build(option);
    }
    // 遮罩层的默认设置
    Overlay.defaults = {
        id: "default",
        bgColor: "#000",
        opacity: .1,
        zIndex: 1e4
    };
    // 构建遮罩层
    Overlay.build = function(option) {
        var div = document.createElement("div"), id = "everlay-" + option.id;
        div.id = id;
        div.className = "overlay";
        if (IE6) {
            // ie6下div遮挡不了下面的select标签，看上去不爽
            // 可以通过iframe来遮挡弹窗后面的select
            div.innerHTML = '<iframe style="width:100%;height:100%;visibility:inherit;" frameborder="0"></iframe>';
        }
        var o = $(div);
        o.css({
            position: "fixed",
            display: "none",
            zIndex: option.zIndex,
            left: 0,
            top: 0,
            width: "100%",
            height: "100%"
        });
        div.style.backgroundColor = option.bgColor;
        if (typeof option["onclick"] === "function") {
            o.click(function() {
                option["onclick"]($(this));
            });
        }
        document.body.appendChild(div);
        return $("#" + id);
    };
    Overlay.prototype = {
        show: function() {
            var self = this, o = self.panel, op = self.option;
            self.resize();
            // 设置窗口大小
            if (IE6) {
                o.show().css({
                    opacity: op.opacity,
                    position: "absolute"
                });
            } else {
                o.css({
                    opacity: 0,
                    display: "block"
                });
                o.animate({
                    opacity: op.opacity
                }, 200);
            }
            return self;
        },
        close: function() {
            var self = this, o = self.panel;
            if (IE6) {
                o.hide();
            } else {
                o.animate({
                    opacity: 0
                }, 200, function() {
                    o.hide();
                });
            }
            return self;
        },
        // 调整遮罩层的大小
        resize: function() {
            var self = this, o = self.panel, width = win.width();
            // 所以浏览器下，宽度都要调整
            o.css("width", width);
            if (IE6) {
                // ie6下需要修改top和margin-left
                o.css({
                    top: dom.scrollTop(),
                    height: win.height()
                });
                // ie6下当body使用relative定位时，需要给覆层添加margin-left=-(window.width - body.width)/2
                // body没有relative或absulote时，不需要添加margin-left
                if ($("body").css("position") == "relative") {
                    var marginLeft = parseInt((width - $("body").width()) / 2, 10);
                    // 另外只有当窗口宽度大于body宽度时需要减去相应的值
                    if (marginLeft > 0) {
                        o.css("marginLeft", -marginLeft);
                    }
                }
            }
        },
        bindEvent: function() {
            var self = this, panel = self.panel;
            win.bind("resize", function() {
                self.resize();
            });
            // 在iE6下，滚屏时需要滚动覆层
            if (IE6) {
                // 当屏幕滚动时，这个事件会被频繁的触发
                // 有一个方法可以在滚动结束时再执行，但这样可能给用户不流畅的体验
                // 综合考虑还是让其频繁触发，因为这个开销并不大
                win.bind("scroll", function() {
                    panel.css({
                        top: dom.scrollTop()
                    });
                });
            }
        }
    };
})(jQuery);