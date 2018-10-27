/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
*
* @author Roddy <luolonghao@gmail.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/
/*DESTOON*/
function _cutstr(str, mark1, mark2) {
	var p1 = str.indexOf(mark1);
	if(p1 == -1) return '';
	str = str.substr(p1 + mark1.length);
	var p2 = str.indexOf(mark2);
	if(p2 == -1) return str;
	return str.substr(0, p2);
}
function url2video(u) {
	var p,p1,p2;
	var d = _cutstr(u, '://', '/');
	switch(d) {
		case 'v.youku.com':
			p = _cutstr(u, 'id_', '.html');
			if(p) return 'http://player.youku.com/embed/'+p;
		break;
		case 'player.youku.com':
			p = _cutstr(u, 'sid/', '/');
			if(p) return 'http://player.youku.com/embed/'+p;
			p = _cutstr(u, 'embed/', u.indexOf("'") != -1 ? "'" : '"');
			if(p) return 'http://player.youku.com/embed/'+p;
		break;
		case 'imgcache.qq.com':
		case 'static.v.qq.com':
		case 'v.qq.com':
			p = _cutstr(u, 'vid=', '&');
			if(p) return 'https://v.qq.com/iframe/player.html?vid='+p+'&tiny=0&auto=0';
			p = _cutstr(u, 'cover/', '.html');
			if(p) p = _cutstr(p, '/', '/');
			if(p) return 'https://v.qq.com/iframe/player.html?vid='+p+'&tiny=0&auto=0';
		break;
		case 'open.iqiyi.com':
			p1 = _cutstr(u, 'vid=', '&');
			p2 = _cutstr(u, 'tvId=', '&');
			if(p1 && p2) return 'http://m.iqiyi.com/shareplay.html?vid='+p1+'&tvid='+p2;
		break;
		case 'player.video.qiyi.com':
			p1 = _cutstr(u, 'player.video.qiyi.com/', '/');
			p2 = _cutstr(u, 'tvId=', '-');
			if(p1 && p2) return 'http://m.iqiyi.com/shareplay.html?vid='+p1+'&tvid='+p2;
		break;
		case 'www.huya.com':
			p = _cutstr(u, 'www.huya.com/', '/');
			if(p) return 'http://liveshare.huya.com/iframe/'+p;
		break;
		case 'www.douyu.com':
			p = _cutstr(u, 'www.douyu.com/', '/');
			if(p) return 'https://staticlive.douyucdn.cn/common/share/play.swf?room_id='+p;
		break;
		case 'www.youtube.com':
			p = _cutstr(u, 'v=', '&');
			if(p) return 'http://www.youtube.com/v/'+p;
		break;
		default:
		break;
	}
	return u;
}

KindEditor.plugin('media', function(K) {
	var self = this, name = 'media', lang = self.lang(name + '.'),
		allowMediaUpload = K.undef(self.allowMediaUpload, true),
		allowFileManager = K.undef(self.allowFileManager, false),
		formatUploadUrl = K.undef(self.formatUploadUrl, true),
		extraParams = K.undef(self.extraFileUploadParams, {}),
		filePostName = K.undef(self.filePostName, 'imgFile'),
		uploadJson = K.undef(self.uploadJson, self.basePath + 'php/upload_json.php');
	self.plugin.media = {
		edit : function() {
			var html = [
				'<div style="padding:20px;">',
				//url
				'<div class="ke-dialog-row">',
				'<label for="keUrl" style="width:60px;">' + lang.url + '</label>',
				'<input class="ke-input-text" type="text" id="keUrl" name="url" value="" style="width:160px;" /> &nbsp;',
				'<input type="button" class="ke-upload-button" value="' + lang.upload + '" /> &nbsp;',
				'<span class="ke-button-common ke-button-outer">',
				'<input type="button" class="ke-button-common ke-button" name="viewServer" value="' + lang.viewServer + '" />',
				'</span>',
				'</div>',
				//width
				'<div class="ke-dialog-row">',
				'<label for="keWidth" style="width:60px;">' + lang.width + '</label>',
				'<input type="text" id="keWidth" class="ke-input-text ke-input-number" name="width" value="550" maxlength="4" />',
				'</div>',
				//height
				'<div class="ke-dialog-row">',
				'<label for="keHeight" style="width:60px;">' + lang.height + '</label>',
				'<input type="text" id="keHeight" class="ke-input-text ke-input-number" name="height" value="400" maxlength="4" />',
				'</div>',
				//autostart
				'<div class="ke-dialog-row">',
				'<label for="keAutostart">' + lang.autostart + '</label>',
				'<input type="checkbox" id="keAutostart" name="autostart" value="" /> ',
				'</div>',
				'</div>'
			].join('');
			var dialog = self.createDialog({
				name : name,
				width : 450,
				height : 230,
				title : self.lang(name),
				body : html,
				yesBtn : {
					name : self.lang('yes'),
					click : function(e) {
						var url = K.trim(urlBox.val()),
							width = widthBox.val(),
							height = heightBox.val();
						if (url == 'http://' || K.invalidUrl(url)) {
							alert(self.lang('invalidUrl'));
							urlBox[0].focus();
							return;
						}

						/*DESTOON*/
						var vurl = url2video(url);
						if(vurl && vurl != url) url = vurl;

						if (!/^\d*$/.test(width)) {
							alert(self.lang('invalidWidth'));
							widthBox[0].focus();
							return;
						}
						if (!/^\d*$/.test(height)) {
							alert(self.lang('invalidHeight'));
							heightBox[0].focus();
							return;
						}
						var html = K.mediaImg(self.themesPath + 'common/blank.gif', {
								src : url,
								type : K.mediaType(url),
								width : width,
								height : height,
								autostart : autostartBox[0].checked ? 'true' : 'false',
								loop : 'true'
							});
						self.insertHtml(html).hideDialog().focus();
					}
				}
			}),
			div = dialog.div,
			urlBox = K('[name="url"]', div),
			viewServerBtn = K('[name="viewServer"]', div),
			widthBox = K('[name="width"]', div),
			heightBox = K('[name="height"]', div),
			autostartBox = K('[name="autostart"]', div);
			urlBox.val('http://');

			if (allowMediaUpload) {
				var uploadbutton = K.uploadbutton({
					button : K('.ke-upload-button', div)[0],
					fieldName : filePostName,
					extraParams : extraParams,
					url : K.addParam(uploadJson, 'dir=media'),
					afterUpload : function(data) {
						dialog.hideLoading();
						if (data.error === 0) {
							var url = data.url;
							if (formatUploadUrl) {
								url = K.formatUrl(url, 'absolute');
							}
							urlBox.val(url);
							if (self.afterUpload) {
								self.afterUpload.call(self, url, data, name);
							}
							alert(self.lang('uploadSuccess'));
						} else {
							alert(data.message);
						}
					},
					afterError : function(html) {
						dialog.hideLoading();
						self.errorDialog(html);
					}
				});
				uploadbutton.fileBox.change(function(e) {
					dialog.showLoading(self.lang('uploadLoading'));
					uploadbutton.submit();
				});
			} else {
				K('.ke-upload-button', div).hide();
			}

			if (allowFileManager) {
				viewServerBtn.click(function(e) {
					self.loadPlugin('filemanager', function() {
						self.plugin.filemanagerDialog({
							viewType : 'LIST',
							dirName : 'media',
							clickFn : function(url, title) {
								if (self.dialogs.length > 1) {
									K('[name="url"]', div).val(url);
									if (self.afterSelectFile) {
										self.afterSelectFile.call(self, url);
									}
									self.hideDialog();
								}
							}
						});
					});
				});
			} else {
				viewServerBtn.hide();
			}

			var img = self.plugin.getSelectedMedia();
			if (img) {
				var attrs = K.mediaAttrs(img.attr('data-ke-tag'));
				urlBox.val(attrs.src);
				widthBox.val(K.removeUnit(img.css('width')) || attrs.width || 0);
				heightBox.val(K.removeUnit(img.css('height')) || attrs.height || 0);
				autostartBox[0].checked = (attrs.autostart === 'true');
			}
			urlBox[0].focus();
			urlBox[0].select();
		},
		'delete' : function() {
			self.plugin.getSelectedMedia().remove();
			// [IE] 删除图片后立即点击图片按钮出错
			self.addBookmark();
		}
	};
	self.clickToolbar(name, self.plugin.media.edit);
});
