/**
 * monitor user's paste key ,clear user input,modified from ckeditor
 * @author: yiminghe@gmail.com
 */
KISSY.Editor.add("clipboard", function(editor) {
    var S = KISSY,
        KE = S.Editor,
        Node = S.Node,
        UA = S.UA,
        KERange = KE.Range,
        KER = KE.RANGE,
        Event = S.Event;
    if (!KE.Paste) {
        (function() {

            function Paste(editor) {
                this.editor = editor;
                this._init();
            }

            S.augment(Paste, {
                _init:function() {
                    var self = this,editor = self.editor;
                    if (UA.ie) {
                        Event.on(editor.document, "keydown", self._paste, self);
                    }
                    else {
                        Event.on(editor.document, "paste", self._paste, self);
                    }

                    editor.addCommand("copy", new cutCopyCmd("copy"));
                    editor.addCommand("cut", new cutCopyCmd("cut"));
                    editor.addCommand("paste", new cutCopyCmd("paste"));

                },
                _paste:function(ev) {
                    if (ev.type === 'keydown' &&
                        !(ev.keyCode === 86 &&
                            (ev.ctrlKey || ev.metaKey)
                            )) {
                        return;
                    }


                    var self = this,editor = self.editor,doc = editor.document;
                    //防止 ie 过快报错
                    if (self._running) {
                        ev.halt();
                        return;
                    }
                    var sel = editor.getSelection(),
                        range = new KERange(doc);

                    // Create container to paste into
                    var pastebin = new Node(UA.webkit ? '<body></body>' : '<div></div>', null, doc);
                    // Safari requires a filler node inside the div to have the content pasted into it. (#4882)
                    UA.webkit && pastebin[0].appendChild(doc.createTextNode('\xa0'));
                    doc.body.appendChild(pastebin[0]);

                    pastebin.css({
                        position : 'absolute',
                        // Position the bin exactly at the position of the selected element
                        // to avoid any subsequent document scroll.
                        top : sel.getStartElement().offset().top + 'px',
                        width : '1px',
                        height : '1px',
                        overflow : 'hidden'
                    });

                    // It's definitely a better user experience if we make the paste-bin pretty unnoticed
                    // by pulling it off the screen.
                    pastebin.css('left', '-1000px');

                    var bms = sel.createBookmarks();

                    // Turn off design mode temporarily before give focus to the paste bin.
                    range.setStartAt(pastebin, KER.POSITION_AFTER_START);
                    range.setEndAt(pastebin, KER.POSITION_BEFORE_END);
                    range.select(true);
                    self._running = true;
                    // Wait a while and grab the pasted contents
                    setTimeout(function() {

                        pastebin._4e_remove();

                        // Grab the HTML contents.
                        // We need to look for a apple style wrapper on webkit it also adds
                        // a div wrapper if you copy/paste the body of the editor.
                        // Remove hidden div and restore selection.
                        var bogusSpan;

                        pastebin = ( UA.webkit
                            && ( bogusSpan = pastebin._4e_first() )
                            && (bogusSpan.hasClass('Apple-style-span') ) ?
                            bogusSpan : pastebin );
                        sel.selectBookmarks(bms);
                        var html = pastebin.html();
                        var re = editor.fire("paste", {
                            html:html,
                            holder:pastebin
                        });
                        if (re !== undefined) html = re;
                        var dataFilter = null;
                        // MS-WORD format sniffing.
                        if (/(class="?Mso|style="[^"]*\bmso\-|w:WordDocument)/.test(html)) {
                            dataFilter = editor.htmlDataProcessor.wordFilter;
                        }
                        editor.insertHtml(html, dataFilter);
                        self._running = false;
                    }, 0);
                }
            });
            KE.Paste = Paste;


            // Tries to execute any of the paste, cut or copy commands in IE. Returns a
            // boolean indicating that the operation succeeded.
            var execIECommand = function(editor, command) {
                var doc = editor.document,
                    body = new Node(doc.body);

                var enabled = false;
                var onExec = function() {
                    enabled = true;
                };

                // The following seems to be the only reliable way to detect that
                // clipboard commands are enabled in IE. It will fire the
                // onpaste/oncut/oncopy events only if the security settings allowed
                // the command to execute.
                body.on(command, onExec);

                // IE6/7: document.execCommand has problem to paste into positioned element.
                ( UA.ie > 7 ? doc : doc.selection.createRange() ) [ 'execCommand' ](command);

                body.detach(command, onExec);

                return enabled;
            };

            // Attempts to execute the Cut and Copy operations.
            var tryToCutCopy =
                UA.ie ?
                    function(editor, type) {
                        return execIECommand(editor, type);
                    }
                    : // !IE.
                    function(editor, type) {
                        try {
                            // Other browsers throw an error if the command is disabled.
                            return editor.document.execCommand(type);
                        }
                        catch(e) {
                            return false;
                        }
                    };

            var error_types = {
                "cut":"您的浏览器安全设置不允许编辑器自动执行剪切操作，请使用键盘快捷键(Ctrl/Cmd+X)来完成",
                "copy":"您的浏览器安全设置不允许编辑器自动执行复制操作，请使用键盘快捷键(Ctrl/Cmd+C)来完成",
                "paste":"您的浏览器安全设置不允许编辑器自动执行粘贴操作，请使用键盘快捷键(Ctrl/Cmd+V)来完成"
            };

            // A class that represents one of the cut or copy commands.
            var cutCopyCmd = function(type) {
                this.type = type;
                this.canUndo = ( this.type == 'cut' );		// We can't undo copy to clipboard.
            };

            cutCopyCmd.prototype =
            {
                exec : function(editor) {
                    this.type == 'cut' && fixCut(editor);

                    var success = tryToCutCopy(editor, this.type);

                    if (!success)
                        alert(error_types[this.type]);		// Show cutError or copyError.

                    return success;
                }
            };
            var KES = KE.Selection;
            // Cutting off control type element in IE standards breaks the selection entirely. (#4881)
            function fixCut(editor) {
                if (!UA.ie ||
                    editor.document.compatMode == 'BackCompat')
                    return;

                var sel = editor.getSelection();
                var control;
                if (( sel.getType() == KES.SELECTION_ELEMENT ) && ( control = sel.getSelectedElement() )) {
                    var range = sel.getRanges()[ 0 ];
                    var dummy = new Node(editor.document.createTextNode(''));
                    dummy.insertBefore(control);
                    range.setStartBefore(dummy);
                    range.setEndAfter(control);
                    sel.selectRanges([ range ]);

                    // Clear up the fix if the paste wasn't succeeded.
                    setTimeout(function() {
                        // Element still online?
                        if (control.parent()) {
                            dummy.remove();
                            sel.selectElement(control);
                        }
                    }, 0);
                }
            }

            var lang = {
                "copy":"复制",
                "paste":"粘贴",
                "cut":"剪切"
            };

            function stateFromNamedCommand(command, doc) {
                // IE Bug: queryCommandEnabled('paste') fires also 'beforepaste(copy/cut)',
                // guard to distinguish from the ordinary sources( either
                // keyboard paste or execCommand ) (#4874).
                //UA.ie && ( depressBeforeEvent = 1 );
                return doc['queryCommandEnabled'](command) ?
                    true :
                    false;
                //depressBeforeEvent = 0;
            }

            /**
             * 给所有右键都加入复制粘贴
             */
            KE.on("contextmenu", function(ev) {
                //debugger
                var contextmenu = ev.contextmenu,
                    editor = contextmenu.cfg["editor"],
                    //原始内容
                    el = contextmenu.elDom,
                    pastes = {"copy":0,"cut":0,"paste":0};
                for (var i in pastes) {

                    if (!pastes.hasOwnProperty(i))return;
                    pastes[i] = el.one(".ke-paste-" + i);
                    (function(cmd) {
                        var cmdObj = pastes[cmd];
                        if (!cmdObj) {
                            cmdObj = new Node("<a href='#'" +
                                "class='ke-paste-" + cmd + "'>"
                                + lang[cmd]
                                + "</a>").appendTo(el);
                            cmdObj.on("click", function(ev) {
                                ev.halt();
                                if (cmdObj.hasClass("ke-menuitem-disable"))
                                    return;
                                contextmenu.hide();
                                //给 ie 一点 hide() 中的事件触发 handler 运行机会，
                                // 原编辑器获得焦点后再进行下步操作
                                setTimeout(function() {
                                    editor.execCommand(cmd);
                                }, 30);
                            });
                        }
                        pastes[cmd] = cmdObj;
                    })(i);
                    var cmdObj = pastes[i];
                    if (stateFromNamedCommand(i, editor.document)) {
                        cmdObj.removeClass("ke-menuitem-disable");
                    } else {
                        cmdObj.addClass("ke-menuitem-disable");
                    }
                }
            });
        })();
    }
    new KE.Paste(editor);
}, {
    attach:false
});
