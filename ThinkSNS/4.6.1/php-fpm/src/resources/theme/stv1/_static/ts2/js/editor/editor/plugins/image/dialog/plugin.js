KISSY.Editor.add("image/dialog", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        DOM = S.DOM,
        UA = S.UA,
        JSON = S['JSON'],
        Node = S.Node,
        Event = S.Event,
        TIP = "http://",
        DTIP = "自动",
        MARGIN_DEFAULT = 0;

    var bodyHtml = "<div class='ke-image-wrap'>" +
        "<ul class='ke-tabs ks-clear'>" +
        "<li " +
        "rel='remote'>" +
        "网络图片" +
        "</li>" +
        "<li " +
        "rel='local'>" +
        "本地上传" +
        "</li>" +
        "</ul>" +
        "<div style='" +
        "padding:12px 20px 5px 20px;'>" +
        "<div class='ke-image-tabs-content-wrap' " +
        ">" +
        "<div>" +
        "<label>" +
        "<span " +
        "class='ke-image-title'" +
        ">" +
        "图片地址： " +
        "</span>" +
        "<input " +
        " data-verify='^(https?:/)?/[^\\s]+$' " +
        " data-warning='网址格式为：http:// 或 /' " +
        "class='ke-img-url ke-input' " +
        "style='width:390px;' " +
        "/>" +
        "</label>" +
        "</div>" +
        "<div style='position:relative;'>" +
        "<form class='ke-img-upload-form'>" +
        "<p style='zoom:1;'>" +
        "<input class='ke-input ke-img-local-url' " +
        "readonly='readonly' " +
        "style='margin-right: 15px; " +
        "vertical-align: middle; " +
        "width: 368px;" +
        "color:#969696;'/>" +
        "<a " +
        "style='padding:3px 11px;" +
        "position:absolute;" +
        "left:390px;" +
        "top:0px;" +
        "z-index:1;' " +
        "class='ke-image-up ke-button'>浏览...</a>" +
        "</p>" +
        "<div class='ke-img-up-extraHtml'>" +
        "</div>" +
        "</form>" +
        "</div>" +
        "</div>" +
        "<table " +
        "style='width:100%;margin-top:8px;' " +
        "class='ke-img-setting'>" +
        "<tr>" +
        "<td>" +
        "<label>" +
        "宽度： " +
        "<input " +
        " data-verify='^(" + DTIP + "|((?!0$)\\d+))?$' " +
        " data-warning='宽度请输入正整数' " +
        "class='ke-img-width ke-input' " +
        "style='vertical-align:middle;width:60px' " +
        "/> 像素 </label>" +
        "</td>" +
        "<td>" +
        "<label>" +
        "高度： " +
        "<input " +
        " data-verify='^(" + DTIP + "|((?!0$)\\d+))?$' " +
        " data-warning='高度请输入正整数' " +
        "class='ke-img-height ke-input' " +
        "style='vertical-align:middle;width:60px' " +
        "/> 像素 </label>" +
        "<label>" +
        "<input " +
        "type='checkbox' " +
        "class='ke-img-ratio' " +
        "style='vertical-align:middle;" +
        "margin-left:5px;" +
        "' " +
        "checked='checked'/>" +
        " 锁定高宽比" +
        "</label>" +
        "</td>" +

        "</tr>" +

        "<tr>" +
        "<td>" +
        "<label>" +
        "对齐：" +
        "<select class='ke-img-align'>" +
        "<option value='none'>无</option>" +
        "<option value='left'>左对齐</option>" +
        "<option value='right'>右对齐</option>" +
        "</select>" +
        "</label>" +
        "</td>" +
        "<td><label>" +
        "间距： " +
        "<input " +
        "" +
        " data-verify='^\\d+$' " +
        " data-warning='间距请输入非负整数' " +
        "class='ke-img-margin ke-input' style='width:60px' value='"
        + MARGIN_DEFAULT + "'/> 像素" +
        "</label>" +
        "</td>" +
        "</tr>" +
        "</table>" +
        "</div>" +
        "</div>",
        footHtml = "<div style='padding:5px 20px 20px;'><a class='ke-img-insert ke-button' " +
            "style='margin-right:30px;'>确定</a> " +
            "<a  class='ke-img-cancel ke-button'>取消</a></div>";


    var d,
        tab,
        imgUrl,
        imgHeight,
        imgWidth,
        imgAlign,
        imgRatio,
        imgMargin,
        imgRatioValue,
        imgLocalUrl,
        fileInput,
        uploadForm,
        warning = "请点击浏览上传图片",
        selectedEl,
        cfg = (editor.cfg["pluginConfig"]["image"] || {})["upload"] ||
            null,
        suffix = cfg && cfg["suffix"] || "png,jpg,jpeg,gif";

    var //不要加g：http://yiminghe.javaeye.com/blog/581347
        suffix_reg = new RegExp(suffix.split(/,/).join("|") + "$", "i"),
        suffix_warning = "只允许后缀名为" + suffix + "的图片";

    var controls = {},
        addRes = KE.Utils.addRes,
        destroyRes = KE.Utils.destroyRes;

    function prepare() {

        d = new KE.Dialog({
            autoRender:true,
            width:500,
            headerContent:"图片",//属性",
            bodyContent:bodyHtml,
            footerContent:footHtml,
            mask:true
        });
        addRes.call(controls, d);
        var content = d.get("el"),
            cancel = content.one(".ke-img-cancel"),
            ok = content.one(".ke-img-insert"),
            verifyInputs = KE.Utils.verifyInputs,
            commonSettingTable = content.one(".ke-img-setting");
        uploadForm = content.one(".ke-img-upload-form");
        imgLocalUrl = content.one(".ke-img-local-url");
        tab = new KE.Tabs({
            tabs:content.one("ul.ke-tabs"),
            contents:content.one("div.ke-image-tabs-content-wrap")
        });
        addRes.call(controls, tab);
        imgLocalUrl.val(warning);
        imgUrl = content.one(".ke-img-url");
        imgHeight = content.one(".ke-img-height");
        imgWidth = content.one(".ke-img-width");
        imgRatio = content.one(".ke-img-ratio");
        imgAlign = KE.Select.decorate(content.one(".ke-img-align"));
        imgMargin = content.one(".ke-img-margin");
        var placeholder = KE.Utils.placeholder;
        placeholder(imgUrl, TIP);
        placeholder(imgHeight, DTIP);
        placeholder(imgWidth, DTIP);
        imgHeight.on("keyup", function() {
            var v = parseInt(imgHeight.val());
            if (!v ||
                !imgRatio[0].checked ||
                imgRatio[0].disabled ||
                !imgRatioValue) {
                return;
            }
            imgWidth.val(Math.floor(v * imgRatioValue));
        });
        addRes.call(controls, imgHeight, imgUrl, imgWidth);

        imgWidth.on("keyup", function() {
            var v = parseInt(imgWidth.val());
            if (!v ||
                !imgRatio[0].checked ||
                imgRatio[0].disabled ||
                !imgRatioValue) {
                return;
            }
            imgHeight.val(Math.floor(v / imgRatioValue));
        });
        addRes.call(controls, imgWidth);
        cancel.on("click", function(ev) {
            d.hide();
            ev.halt();
        });
        addRes.call(controls, cancel);
        var loadingCancel = new Node("<a class='ke-button' style='position:absolute;" +
            "z-index:" +
            KE.baseZIndex(KE.zIndexManager.LOADING_CANCEL) + ";" +
            "left:-9999px;" +
            "top:-9999px;" +
            "'>取消上传</a>").appendTo(document.body);

        /**
         * 取消当前iframe的上传
         */
        var uploadIframe = null;
        loadingCancel.on("click", function(ev) {
            ev && ev.halt();
            d.unloading();
            if (uploadIframe) {
                Event.remove(uploadIframe, "load");
                DOM.remove(uploadIframe);
            }
            loadingCancel.css({
                left:-9999,
                top:-9999
            });
            uploadIframe = null;
        });
        addRes.call(controls, loadingCancel);
        function getFileSize(file) {
            if (file['files']) {
                return file['files'][0].size;
            } else if (1 > 2) {
                //ie 会安全警告
                try {
                    var fso = new ActiveXObject("Scripting.FileSystemObject");
                    var file2 = fso['GetFile'](file.value);
                    return file2.size;
                } catch(e) {
                    S.log(e.message);
                }
            }
            return 0;
        }

        ok.on("click", function(ev) {
            ev && ev.halt();
            if (tab.activate() == "local" && cfg) {

                if (!verifyInputs(commonSettingTable.all("input")))
                    return;
                if (imgLocalUrl.val() == warning) {
                    alert("请先选择文件!");
                    return;
                }

                if (!suffix_reg.test(imgLocalUrl.val())) {
                    alert(suffix_warning);
                    //清除已选文件， ie 不能使用 val("")
                    uploadForm[0].reset();
                    imgLocalUrl.val(warning);
                    return;
                }
                var size = (getFileSize(fileInput[0]));
                if (sizeLimit && sizeLimit < (size / 1000)) {
                    alert("上传图片最大：" + sizeLimit / 1000 + "M");
                    return;
                }
                d.loading();
                uploadIframe = KE.Utils.doFormUpload({
                    form:uploadForm,
                    callback:function(r) {
                        uploadIframe = null;
                        loadingCancel.css({
                            left:-9999,
                            top:-9999
                        });
                        var data = S.trim(r.responseText)
                            .replace(/\r|\n/g, "");
                        d.unloading();
                        try {
                            //ie parse error,不抛异常
                            data = JSON.parse(data);
                        } catch(e) {
                            S.log(data);
                            data = null;
                        }
                        if (!data) data = {error:"服务器出错，请重试"};
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        imgUrl.val(data['imgUrl']);
                        insert();
                    }
                }, cfg['serverParams'], cfg['serverUrl']);

                var loadingMaskEl = d.get("el"),
                    offset = loadingMaskEl.offset(),
                    width = loadingMaskEl[0].offsetWidth,
                    height = loadingMaskEl[0].offsetHeight;
                loadingCancel.css({
                    left:(offset.left + width / 2.5),
                    top:(offset.top + height / 1.5)
                });
            } else {
                if (! verifyInputs(content.all("input")))
                    return;
                insert();
            }
        });

        addRes.call(controls, ok);

        if (cfg) {
            if (cfg['extraHtml']) {
                content.one(".ke-img-up-extraHtml")
                    .html(cfg['extraHtml']);
            }
            var ke_image_up = content.one(".ke-image-up"),
                sizeLimit = cfg && cfg['sizeLimit'];

            fileInput = new Node("<input " +
                "type='file' " +
                "style='position:absolute;" +
                "cursor:pointer;" +
                "left:" +
                (UA.ie ? "360" : "369") +
                "px;" +
                "z-index:2;" +
                "top:0px;" +
                "height:26px;' " +
                "size='1' " +
                "name='" + (cfg['fileInput'] || "Filedata") + "'/>")
                .insertAfter(imgLocalUrl);
            if (sizeLimit)
                warning = "单张图片容量不超过 " + (sizeLimit / 1000) + " M";
            imgLocalUrl.val(warning);
            fileInput.css({
                opacity:0
            });

            fileInput.on("mouseenter", function() {
                ke_image_up.addClass("ke-button-hover");
            });
            fileInput.on("mouseleave", function() {
                ke_image_up.removeClass("ke-button-hover");
            });
            fileInput.on("change", function() {
                var file = fileInput.val();
                //去除路径
                imgLocalUrl.val(file.replace(/.+[\/\\]/, ""));
            });
            addRes.call(controls, fileInput);
        }
        else {
            tab.remove("local");
        }
    }


    function insert() {
        var url = imgUrl.val(),
            height = parseInt(imgHeight.val()),
            width = parseInt(imgWidth.val()),
            align = imgAlign.val(),
            margin = parseInt(imgMargin.val()),
            style = '';
        if (height) {
            style += "height:" + height + "px;";
        }
        if (width) {
            style += "width:" + width + "px;";
        }
        if (align) {
            style += "float:" + align + ";";
        }
        if (!isNaN(margin)) {
            style += "margin:" + margin + "px;";
        }

        d.hide();

        /**
         * 2011-01-05
         * <a><img></a> 这种结构，a不要设成 position:absolute
         * 否则img select 不到？!!: editor.getSelection().selectElement(img) 选择不到
         */
        if (selectedEl) {
            editor.fire("save");
            selectedEl.attr({
                "src":url,
                //注意设置，取的话要从 _ke_saved_src 里取
                "_ke_saved_src":url,
                "style":style
            });
            editor.fire("save");
        } else {
            var img = new Node("<img " +
                (style ? ("style='" +
                    style +
                    "'") : "") +
                " src='" +
                url +
                "' " +
                "_ke_saved_src='" +
                url +
                "' alt='' />", null, editor.document);
            editor.insertElement(img, function(el) {
                el.on("abort error", function() {
                    el.detach();
                    //ie6 手动设置，才会出现红叉
                    el[0].src = url;
                });
            });
        }

    }

    function update(_selectedEl) {
        var active = "remote",
            resetInput = KE.Utils.resetInput,
            valInput = KE.Utils.valInput;
        selectedEl = _selectedEl;
        if (selectedEl) {
            valInput(imgUrl, selectedEl.attr("src"));
            var w = selectedEl.width(),
                h = selectedEl.height();
            valInput(imgHeight, h);
            valInput(imgWidth, w);
            imgAlign.val(selectedEl.css("float") || "none");
            var margin = parseInt(selectedEl._4e_style("margin"))
                || 0;
            imgMargin.val(margin);
            imgRatio[0].disabled = false;
            imgRatioValue = w / h;
        } else {
            if (tab.getTab("local"))
                active = "local";
            resetInput(imgUrl);
            resetInput(imgHeight);
            resetInput(imgWidth);
            imgAlign.val("none");
            imgMargin.val(MARGIN_DEFAULT);
            imgRatio[0].disabled = true;
            imgRatioValue = null;
        }
        uploadForm[0].reset();
        imgLocalUrl.val(warning);
        tab.activate(active);
    }

    KE.use("overlay,tabs,select", function() {
        editor.addDialog("image/dialog", {
            show:function(_selectedEl) {
                update(_selectedEl);
                d.show();
            },
            hide:function() {
                d.hide();
            },
            destroy:function() {
                destroyRes.call(controls);
            }
        });
        prepare();
    });
}, {
    attach:false
});