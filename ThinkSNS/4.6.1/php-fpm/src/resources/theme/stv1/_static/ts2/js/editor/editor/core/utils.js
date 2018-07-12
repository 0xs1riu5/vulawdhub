/**
 * common utils for kissy editor
 * @author: <yiminghe@gmail.com>
 */
KISSY.Editor.add("utils", function(KE) {

    var
        TRUE = true,
        FALSE = false,
        NULL = null,
        S = KISSY,
        Node = S.Node,
        DOM = S.DOM,
        UA = S.UA,
        Event = S.Event,
        Utils = {
            debugUrl:function(url) {
                url = url.replace(/-min\.(js|css)/i, ".$1");
                if (!KE["Config"]['debug']) {
                    url = url.replace(/\.(js|css)/i, "-min.$1");
                }
                if (url.indexOf("?t") == -1) {
                    if (url.indexOf("?") != -1) {
                        url += "&";
                    } else {
                        url += "?";
                    }
                    url += "t="+encodeURIComponent("2011-02-14 14:32:25");
                }
                return KE["Config"].base + url;
            },
            /**
             * 懒惰一下
             * @param obj {Object} 包含方法的对象
             * @param before {string} 准备方法
             * @param after {string} 真正方法
             */
            lazyRun:function(obj, before, after) {
                var b = obj[before],a = obj[after];
                obj[before] = function() {
                    b.apply(this, arguments);
                    obj[before] = obj[after];
                    return a.apply(this, arguments);
                };
            }
            ,

            /**
             * srcDoc 中的位置在 destDoc 的对应位置
             * @param x {number}
             * @param y {number}
             * @param srcDoc {Document}
             * @param destDoc {Document}
             * @return 在最终文档中的位置
             */
            getXY:function(x, y, srcDoc, destDoc) {
                var currentWindow = srcDoc.defaultView || srcDoc.parentWindow;

                //x,y相对于当前iframe文档,防止当前iframe有滚动条
                x -= DOM.scrollLeft(currentWindow);
                y -= DOM.scrollTop(currentWindow);
                if (destDoc) {
                    var refWindow = destDoc.defaultView || destDoc.parentWindow;
                    if (currentWindow != refWindow && currentWindow['frameElement']) {
                        //note:when iframe is static ,still some mistake
                        var iframePosition = DOM._4e_getOffset(currentWindow['frameElement'],
                            destDoc);
                        x += iframePosition.left;
                        y += iframePosition.top;
                    }
                }
                return {left:x,top:y};
            }
            ,
            /**
             * 执行一系列函数
             * @param var_args {...function()}
             * @return {*} 得到成功的返回
             */
            tryThese : function(var_args) {
                var returnValue;
                for (var i = 0, length = arguments.length; i < length; i++) {
                    var lambda = arguments[i];
                    try {
                        returnValue = lambda();
                        break;
                    }
                    catch (e) {
                    }
                }
                return returnValue;
            },

            /**
             * 是否两个数组完全相同
             * @param arrayA {Array}
             * @param arrayB {Array}
             * @return {boolean}
             */
            arrayCompare: function(arrayA, arrayB) {
                if (!arrayA && !arrayB)
                    return TRUE;

                if (!arrayA || !arrayB || arrayA.length != arrayB.length)
                    return FALSE;

                for (var i = 0; i < arrayA.length; i++) {
                    if (arrayA[ i ] !== arrayB[ i ])
                        return FALSE;
                }

                return TRUE;
            }
            ,

            /**
             * 根据dom路径得到某个节点
             * @param doc {Document}
             * @param address {Array.<number>}
             * @param normalized {boolean}
             * @return {KISSY.Node}
             */
            getByAddress : function(doc, address, normalized) {
                var $ = doc.documentElement;

                for (var i = 0; $ && i < address.length; i++) {
                    var target = address[ i ];

                    if (!normalized) {
                        $ = $.childNodes[ target ];
                        continue;
                    }

                    var currentIndex = -1;

                    for (var j = 0; j < $.childNodes.length; j++) {
                        var candidate = $.childNodes[ j ];

                        if (normalized === TRUE &&
                            candidate.nodeType == 3 &&
                            candidate.previousSibling &&
                            candidate.previousSibling.nodeType == 3) {
                            continue;
                        }

                        currentIndex++;

                        if (currentIndex == target) {
                            $ = candidate;
                            break;
                        }
                    }
                }

                return $ ? new Node($) : NULL;
            }
            ,
            /**
             * @param database {Object}
             */
            clearAllMarkers:function(database) {
                for (var i in database)
                    database[i]._4e_clearMarkers(database, TRUE);
            }
            ,
            /**
             *
             * @param text {string}
             * @return {string}
             */
            htmlEncodeAttr : function(text) {
                return text.replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/, '&gt;');
            }
            ,
            /**
             *
             * @param str {string}
             * @return {string}
             */
            ltrim:function(str) {
                return str.replace(/^\s+/, "");
            }
            ,
            /**
             *
             * @param str {string}
             * @return {string}
             */
            rtrim:function(str) {
                return str.replace(/\s+$/, "");
            }
            ,
            /**
             *
             * @param str {string}
             * @return {string}
             */
            trim:function(str) {
                return this.ltrim(this.rtrim(str));
            }
            ,
            /**
             *
             * @param var_args {...Object}
             * @return {Object}
             */
            mix:function(var_args) {
                var r = {};
                for (var i = 0; i < arguments.length; i++) {
                    var ob = arguments[i];
                    r = S.mix(r, ob);
                }
                return r;
            }
            ,
            isCustomDomain : function() {
                if (!UA.ie)
                    return FALSE;

                var domain = document.domain,
                    hostname = window.location.hostname;

                return domain != hostname &&
                    domain != ( '[' + hostname + ']' );	// IPv6 IP support (#5434)
            },
            /**
             *
             * @param delim {string} 分隔符
             * @param loop {number}
             * @return {string}
             */
            duplicateStr:function(delim, loop) {
                return new Array(loop + 1).join(delim);
            },
            /**
             * Throttles a call to a method based on the time between calls.
             * Based on work by Simon Willison: http://gist.github.com/292562
             * @param fn {function()} The function call to throttle.
             * @param ms {number} The number of milliseconds to throttle the method call. Defaults to 150
             * @return {function()} Returns a wrapped function that calls fn throttled.
             */
            throttle : function(fn, scope, ms) {
                ms = ms || 150;

                if (ms === -1) {
                    return (function() {
                        fn.apply(scope, arguments);
                    });
                }

                var last = (new Date()).getTime();

                return function() {
                    var now = (new Date()).getTime();
                    if (now - last > ms) {
                        last = now;
                        fn.apply(scope, arguments);
                    }
                };
            },
            /**
             *
             * @param fn {function()}
             * @param scope {Object}
             * @param ms {number}
             * @return {function()}
             */
            buffer : function(fn, scope, ms) {
                ms = ms || 0;
                var timer = NULL;
                return (function() {
                    timer && clearTimeout(timer);
                    var args = arguments;
                    timer = setTimeout(function() {
                        return fn.apply(scope, args);
                    }, ms);
                });
            },

            isNumber:function(n) {
                return /^\d+(.\d+)?$/.test(S.trim(n));
            },

            /**
             *
             * @param inputs {Array.<Node>}
             * @param warn {string}
             * @return {boolean} 是否验证成功
             */
            verifyInputs:function(inputs, warn) {
                for (var i = 0; i < inputs.length; i++) {
                    var input = DOM._4e_wrap(inputs[i]),
                        v = S.trim(input.val()),
                        verify = input.attr("data-verify"),
                        warning = input.attr("data-warning");
                    if (verify &&
                        !new RegExp(verify).test(v)) {
                        alert(warning);
                        return FALSE;
                    }
                }
                return TRUE;
            },
            /**
             *
             * @param editor {KISSY.Editor}
             * @param plugin {Object}
             */
            sourceDisable:function(editor, plugin) {
                editor.on("sourcemode", plugin.disable, plugin);
                editor.on("wysiwygmode", plugin.enable, plugin);
            },

            /**
             *
             * @param inp {Node}
             */
            resetInput:function(inp) {
                var placeholder = inp.attr("placeholder");
                if (placeholder && !UA.webkit) {
                    inp.addClass("ke-input-tip");
                    inp.val(placeholder);
                } else if (UA.webkit) {
                    inp.val("");
                }
            },

            valInput:function(inp, val) {
                inp.removeClass("ke-input-tip");
                inp.val(val);
            },

            /**
             *
             * @param inp {Node}
             * @param tip {string}
             */
            placeholder:function(inp, tip) {
                inp.attr("placeholder", tip);
                if (UA.webkit) {
                    return;
                }
                inp.on("blur", function() {
                    if (!S.trim(inp.val())) {
                        inp.addClass("ke-input-tip");
                        inp.val(tip);
                    }
                });
                inp.on("focus", function() {
                    inp.removeClass("ke-input-tip");
                    if (S.trim(inp.val()) == tip) {
                        inp.val("");
                    }
                });
            },

            /**
             *
             * @param node {(Node)}
             */
            clean:function(node) {
                node = node[0] || node;
                var cs = S.makeArray(node.childNodes);
                for (var i = 0; i < cs.length; i++) {
                    var c = cs[i];
                    if (c.nodeType == KE.NODE.NODE_TEXT && !S.trim(c.nodeValue)) {
                        node.removeChild(c);
                    }
                }
            },
            /**
             * Convert certain characters (&, <, >, and ') to their HTML character equivalents
             *  for literal display in web pages.
             * @param {string} value The string to encode
             * @return {string} The encoded text
             */
            htmlEncode : function(value) {
                return !value ? value : String(value).replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
            },

            /**
             * Convert certain characters (&, <, >, and ') from their HTML character equivalents.
             * @param {string} value The string to decode
             * @return {string} The decoded text
             */
            htmlDecode : function(value) {
                return !value ? value : String(value).replace(/&gt;/g, ">").replace(/&lt;/g, "<").replace(/&quot;/g, '"').replace(/&amp;/g, "&");
            },


            equalsIgnoreCase:function(str1, str2) {
                return str1.toLowerCase() == str2.toLowerCase();
            },

            /**
             *
             * @param params {Object}
             * @return {Object}
             */
            normParams:function (params) {
                params = S.clone(params);
                for (var p in params) {
                    if (params.hasOwnProperty(p)) {
                        var v = params[p];
                        if (S.isFunction(v)) {
                            params[p] = v();
                        }
                    }
                }
                return params;
            },

            /**
             *
             * @param o {Object} 提交 form 配置
             * @param ps {Object} 动态参数
             * @param url {string} 目的地 url
             */
            doFormUpload : function(o, ps, url) {
                var id = S.guid("form-upload-");
                var frame = document.createElement('iframe');
                frame.id = id;
                frame.name = id;
                frame.className = 'ke-hidden';

                var srcScript = 'document.open();' +
                    // The document domain must be set any time we
                    // call document.open().
                    ( Utils.isCustomDomain() ? ( 'document.domain="' + document.domain + '";' ) : '' ) +
                    'document.close();';
                if (UA.ie) {
                    frame.src = UA.ie ? 'javascript:void(function(){' + encodeURIComponent(srcScript) + '}())' : '';
                }
                S.log("doFormUpload : " + frame.src);
                document.body.appendChild(frame);

                if (UA.ie) {
                    document['frames'][id].name = id;
                }

                var form = DOM._4e_unwrap(o.form),
                    buf = {
                        target: form.target,
                        method: form.method,
                        encoding: form.encoding,
                        enctype: form.enctype,
                        action: form.action
                    };
                form.target = id;
                form.method = 'POST';
                form.enctype = form.encoding = 'multipart/form-data';
                if (url) {
                    form.action = url;
                }

                var hiddens, hd;
                if (ps) { // add dynamic params
                    hiddens = [];
                    ps = KE.Utils.normParams(ps);
                    for (var k in ps) {
                        if (ps.hasOwnProperty(k)) {
                            hd = document.createElement('input');
                            hd.type = 'hidden';
                            hd.name = k;
                            hd.value = ps[k];
                            form.appendChild(hd);
                            hiddens.push(hd);
                        }
                    }
                }

                function cb() {
                    var r = {  // bogus response object
                        responseText : '',
                        responseXML : NULL
                    };

                    r.argument = o ? o.argument : NULL;

                    try { //
                        var doc;
                        if (UA.ie) {
                            doc = frame.contentWindow.document;
                        } else {
                            doc = (frame.contentDocument || window.frames[id].document);
                        }

                        if (doc && doc.body) {
                            r.responseText = doc.body.innerHTML;
                        }
                        if (doc && doc['XMLDocument']) {
                            r.responseXML = doc['XMLDocument'];
                        } else {
                            r.responseXML = doc;
                        }

                    }
                    catch(e) {
                        // ignore
                        //2010-11-15 由于外边设置了document.domain导致读不到数据抛异常
                        S.log(e);
                    }

                    Event.remove(frame, 'load', cb);
                    o.callback && o.callback(r);

                    setTimeout(function() {
                        DOM._4e_remove(frame);
                    }, 100);

                }

                Event.on(frame, 'load', cb);

                form.submit();

                form.target = buf.target;
                form.method = buf.method;
                form.enctype = buf.enctype;
                form.encoding = buf.encoding;
                form.action = buf.action;

                if (hiddens) { // remove dynamic params
                    for (var i = 0, len = hiddens.length; i < len; i++) {
                        DOM._4e_remove(hiddens[i]);
                    }
                }
                return frame;
            },
            /**
             * extern for closure compiler
             */
            extern:function(obj, cfg) {
                for (var i in cfg) {
                    obj[i] = cfg[i];
                }
            },
            map:function(arr, callback) {
                for (var i = 0; i < arr.length; i++) {
                    arr[i] = callback(arr[i]);
                }
                return arr;
            },
            //直接判断引擎，防止兼容性模式影响
            ieEngine:(function() {
                if (!UA.ie) return;
                return document['documentMode'] || UA.ie;
            })(),

            /**
             * 点击 el 或者 el 内的元素，不会使得焦点转移
             * @param el
             */
            preventFocus:function(el) {
                if (UA.ie) {
                    //ie 点击按钮不丢失焦点
                    el._4e_unselectable();
                } else {
                    el.attr("onmousedown", "return false;");
                }
            },

            isFlashEmbed:function(element) {
                var attributes = element.attributes;
                return (
                    attributes.type == 'application/x-shockwave-flash'
                        ||
                        /\.swf(?:$|\?)/i.test(attributes.src || '')
                    );
            },

            addRes:function() {
                this.__res = this.__res || [];
                var res = this.__res;
                res.push.apply(res, S.makeArray(arguments));
            },

            destroyRes:function() {
                var res = this.__res || [];
                for (var i = 0; i < res.length; i++) {
                    var r = res[i];
                    if (S.isFunction(r)) {
                        r();
                    } else {
                        if (r.detach)
                            r.detach();
                        if (r.destroy) {
                            r.destroy();
                        }
                        if (r.nodeType && r.remove) {
                            r.remove();
                        }
                    }
                }
                this.__res = [];
            }
        };

    KE.Utils = Utils;

    /**
     * export for closure compiler
     */
    KE["Utils"] = Utils;
    UA.ieEngine = Utils.ieEngine;
    Utils.extern(Utils, {
        "debugUrl": Utils.debugUrl,
        "lazyRun": Utils.lazyRun,
        "getXY": Utils.getXY,
        "tryThese": Utils.tryThese,
        "arrayCompare": Utils.arrayCompare,
        "getByAddress": Utils.getByAddress,
        "clearAllMarkers": Utils.clearAllMarkers,
        "htmlEncodeAttr": Utils.htmlEncodeAttr,
        "ltrim": Utils.ltrim,
        "rtrim": Utils.rtrim,
        "trim": Utils.trim,
        "mix": Utils.mix,
        "isCustomDomain": Utils.isCustomDomain,
        "duplicateStr": Utils.duplicateStr,
        "buffer": Utils.buffer,
        "isNumber": Utils.isNumber,
        "verifyInputs": Utils.verifyInputs,
        "sourceDisable": Utils.sourceDisable,
        "resetInput": Utils.resetInput,
        "placeholder": Utils.placeholder,
        "clean": Utils.clean,
        "htmlEncode": Utils.htmlEncode,
        "htmlDecode": Utils.htmlDecode,
        "equalsIgnoreCase": Utils.equalsIgnoreCase,
        "normParams": Utils.normParams,
        "throttle": Utils.throttle,
        "doFormUpload": Utils.doFormUpload,
        "map": Utils.map
    });
});
