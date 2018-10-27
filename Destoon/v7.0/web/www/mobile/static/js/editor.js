/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
$.fn.extend({
	config: {
		safetags: ["<br/>"],
		breaks: false
	},
    DEditor: function (config) {
        var _this = this,
        styles = {"overflow-y":"auto","text-break":"brak-all","outline":"none","cursor":"text","-webkit-user-select":"text"};
        $(this).css(styles).attr("contenteditable", true);
        _this.config = $.extend(_this.config, config);
        _this.upload();
        _this.action();
        if(_this.config.textareaid) {
			$('#'+_this.config.textareaid).val(_this.getval());
            $(_this).on('input', function () {
                $('#'+_this.config.textareaid).val(_this.getval());
            });
        }
        $(this).on('input click', function() {
            setTimeout(function() {
                var selection = window.getSelection ? window.getSelection() : document.selection;
                _this.range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);
            },10);
            return false;
        });
        $(this).on('paste', function (e) {
            var content = $(this).html();
            valiHTML = _this.config.safetags;
            content = content.replace(/_moz_dirty=""/gi, "").replace(/\[/g, "[[-").replace(/\]/g, "-]]").replace(/<\/ ?tr[^>]*>/gi, "[br]").replace(/<\/ ?td[^>]*>/gi, "&nbsp;&nbsp;").replace(/<(ul|dl|ol)[^>]*>/gi, "[br]").replace(/<(li|dd)[^>]*>/gi, "[br]").replace(/<p [^>]*>/gi, "[br]").replace(new RegExp("<(/?(?:" + valiHTML.join("|") + ")[^>]*)>", "gi"), "[$1]").replace(new RegExp('<span([^>]*class="?at"?[^>]*)>', "gi"), "[span$1]").replace(/<[^>]*>/g, "").replace(/\[\[\-/g, "[").replace(/\-\]\]/g, "]").replace(new RegExp("\\[(/?(?:" + valiHTML.join("|") + "|img|span)[^\\]]*)\\]", "gi"), "<$1>");
            if (!/firefox/.test(navigator.userAgent.toLowerCase())) {
                content = content.replace(/\r?\n/gi, "<br>");
            }
            $(this).html(content);
        });
        if(!/firefox/.test(navigator.userAgent.toLowerCase()) && this.config.breaks) {
            $(this).keydown(function(e) {
                if(e.keyCode === 13) {
                    document.execCommand('insertHTML', false, '<br/><br/>');
                    return false;
                }
            });
        }        
    },
	action: function() {
        var _this = this;
        $('.ui-editor-toolbar li:gt(0)').on('click', function(e) {
			//e.preventDefault();
			document.execCommand($(this).attr('class').replace('ui-editor-', ''), false, null);
            //italic underline justifyleft justifycenter justifyright
        });
	},
	upload: function() {
		var _this = this;
		var editoru = WebUploader.create({
			auto: true,
			server: _this.config.server,
			pick: '#'+_this.config.editorid+'-picker',
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,bmp,png',
				mimeTypes: 'image/*'
			},
			resize: false
		});
		editoru.on('fileQueued', function(file) {
			Dtoast('上传中..', '', 30);
		});
		editoru.on('uploadProgress', function(file, percentage) {
			var p = parseInt(percentage * 100);
			$('.ui-toast').html(p > 99 ? '处理中...' : '上传中...'+p+'%');
		});
		editoru.on( 'uploadSuccess', function(file, data) {
			if(data.error) {
				Dtoast(data.message, '', 5);
			} else {
				_this.insert('<img src="'+data.url+'"/>');
			}
		});
		editoru.on( 'uploadError', function(file, data) {
			Dtoast(data.message, '', 5);
		});
		editoru.on('uploadComplete', function(file) {
			$('.ui-toast').hide();
		});
	},
	insert: function (htm) {
        $(this).focus();
		var selection = window.getSelection ? window.getSelection() : document.selection;
        var range;
        if(this.range) {
            range = this.range;
            this.range = null;
        } else {
            range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);
        }
        if(!window.getSelection) {
            range.pasteHTML(htm);
            range.collapse(false);
            range.select();
        } else {
            range.collapse(false);
            var hasR = range.createContextualFragment(htm);
            var hasLastChild = hasR.lastChild;
            while(hasLastChild && hasLastChild.nodeName.toLowerCase() == "br" && hasLastChild.previousSibling && hasLastChild.previousSibling.nodeName.toLowerCase() == "br") {
                var e = hasLastChild;
                hasLastChild = hasLastChild.previousSibling;
                hasR.removeChild(e);
            }
            range.insertNode(range.createContextualFragment("<br/>"));
            range.insertNode(hasR);
            if (hasLastChild) {
                range.setEndAfter(hasLastChild);
                range.setStartAfter(hasLastChild);
            }
            selection.removeAllRanges();
            selection.addRange(range);
        }
        if(this.config.textareaid) {
            $('#'+this.config.textareaid).val(this.getval());
        }
    },
    getval: function () {
        return $(this).html();
    },
    setval: function (htm) {
        $(this).html(htm);
    }
});
