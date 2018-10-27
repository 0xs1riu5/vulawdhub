var FCKeditor = function(instanceName, width, height, toolbarSet, value) {
	this.InstanceName	= instanceName;
	this.Width			= width			|| '100%';
	this.Height			= height		|| '200';
	this.ToolbarSet		= toolbarSet	|| 'Default';
	this.Value			= value			|| '';
	this.BasePath		= FCKeditor.BasePath;
	this.CheckBrowser	= true;
	this.DisplayErrors	= true;
	this.Config			= new Object();
	this.OnError		= null;
}
FCKeditor.BasePath = '/fckeditor/';
FCKeditor.MinHeight = 200;
FCKeditor.MinWidth = 750;
FCKeditor.prototype.Version			= '2.6';
FCKeditor.prototype.VersionBuild	= '18638';
FCKeditor.prototype.Create = function() {document.write(this.CreateHtml());}
FCKeditor.prototype.CreateHtml = function() {
	if(!this.InstanceName || this.InstanceName.length == 0) { this._ThrowError(701, 'You must specify an instance name.');return '';}
	var sHtml = '';
	if(!this.CheckBrowser || this._IsCompatibleBrowser()) {
		sHtml += '<input type="hidden" id="' + this.InstanceName + '" name="' + this.InstanceName + '" value="' + this._HTMLEncode(this.Value) + '" style="display:none" />';
		sHtml += this._GetConfigHtml();
		sHtml += this._GetIFrameHtml();
	} else {
		var sWidth  = this.Width.toString().indexOf('%')  > 0 ? this.Width  : this.Width  + 'px';
		var sHeight = this.Height.toString().indexOf('%') > 0 ? this.Height : this.Height + 'px';
		sHtml += '<textarea name="' + this.InstanceName + '" rows="4" cols="40" style="width:' + sWidth + ';height:' + sHeight + '">' + this._HTMLEncode(this.Value) + '<\/textarea>';
	}
	return sHtml;
}
FCKeditor.prototype.ReplaceTextarea = function() {
	if(!this.CheckBrowser || this._IsCompatibleBrowser()) {
		var oTextarea = document.getElementById(this.InstanceName);
		var colElementsByName = document.getElementsByName(this.InstanceName);
		var i = 0;
		while (oTextarea || i == 0)	{
			if(oTextarea && oTextarea.tagName.toLowerCase() == 'textarea') break;
			oTextarea = colElementsByName[i++];
		}
		if(!oTextarea) {alert('Error: The TEXTAREA with id or name set to "' + this.InstanceName + '" was not found');return;}
		oTextarea.style.display = 'none';
		this._InsertHtmlBefore(this._GetConfigHtml(), oTextarea);
		this._InsertHtmlBefore(this._GetIFrameHtml(), oTextarea);
	}
}
FCKeditor.prototype._InsertHtmlBefore = function(html, element) {
	if(element.insertAdjacentHTML) {
		element.insertAdjacentHTML('beforeBegin', html);
	} else {
		var oRange = document.createRange();
		oRange.setStartBefore(element);
		var oFragment = oRange.createContextualFragment(html);
		element.parentNode.insertBefore(oFragment, element);
	}
}
FCKeditor.prototype._GetConfigHtml = function() {
	var sConfig = '';
	for (var o in this.Config) {
		if(sConfig.length > 0) sConfig += '&amp;';
		sConfig += encodeURIComponent(o) + '=' + encodeURIComponent(this.Config[o]);
	}
	return '<input type="hidden" id="' + this.InstanceName + '___Config" value="' + sConfig + '" style="display:none" />';
}
FCKeditor.prototype._GetIFrameHtml = function() {
	var sFile = 'fckeditor.html';
	try {if((/fcksource=true/i).test(window.top.location.search))sFile = 'fckeditor.original.html';}
	catch (e) {}
	var sLink = this.BasePath + 'editor/' + sFile + '?InstanceName=' + encodeURIComponent(this.InstanceName);
	if(this.ToolbarSet) sLink += '&amp;Toolbar=' + this.ToolbarSet;
	return '<iframe id="' + this.InstanceName + '___Frame" src="' + sLink + '" width="' + this.Width + '" height="' + this.Height + '" frameborder="0" scrolling="no"></iframe>';
}
FCKeditor.prototype._IsCompatibleBrowser = function() {return FCKeditor_IsCompatibleBrowser();}
FCKeditor.prototype._ThrowError = function(errorNumber, errorDescription) {
	this.ErrorNumber		= errorNumber;
	this.ErrorDescription	= errorDescription;
	if(this.DisplayErrors) {
		document.write('<div style="COLOR: #ff0000">');
		document.write('[ FCKeditor Error ' + this.ErrorNumber + ': ' + this.ErrorDescription + ' ]');
		document.write('</div>');
	}
	if(typeof(this.OnError) == 'function') this.OnError(this, errorNumber, errorDescription);
}
FCKeditor.prototype._HTMLEncode = function(text) {
	if(typeof(text) != "string") text = text.toString();
	text = text.replace(
		/&/g, "&amp;").replace(
		/"/g, "&quot;").replace(
		/</g, "&lt;").replace(
		/>/g, "&gt;");
	return text;
};(function() {
	var textareaToEditor = function(textarea) {
		var editor = new FCKeditor(textarea.name);
		editor.Width = Math.max(textarea.offsetWidth, FCKeditor.MinWidth);
		editor.Height = Math.max(textarea.offsetHeight, FCKeditor.MinHeight);
		return editor;
	}
	FCKeditor.ReplaceAllTextareas = function() {
		var textareas = document.getElementsByTagName('textarea');
		for (var i = 0; i < textareas.length; i++) {
			var editor = null;
			var textarea = textareas[i];
			var name = textarea.name;
			if(!name || name.length == 0) continue;
			if(typeof arguments[0] == 'string') {
				var classRegex = new RegExp('(?:^|)' + arguments[0] + '(?:$|)');
				if(!classRegex.test(textarea.className)) continue;
			} else if(typeof arguments[0] == 'function') {
				editor = textareaToEditor(textarea);
				if(arguments[0](textarea, editor) === false) continue;
			}
			if(!editor) editor = textareaToEditor(textarea);
			editor.ReplaceTextarea();
		}
	}
})();
function FCKeditor_IsCompatibleBrowser() {
	var sAgent = navigator.userAgent.toLowerCase();
	if(sAgent.indexOf("msie 1") != -1) return true;//DT 2012/11/9 IE10+
	if(/*@cc_on!@*/false && sAgent.indexOf("mac") == -1) {
		var sBrowserVersion = navigator.appVersion.match(/MSIE (.\..)/)[1];
		return (sBrowserVersion >= 5.5);
	}
	if(navigator.product == "Gecko" && navigator.productSub >= 20030210 && !(typeof(opera) == 'object' && opera.postError)) return true;
	if(window.opera && window.opera.version && parseFloat(window.opera.version()) >= 9.5) return true;
	if(sAgent.indexOf(' adobeair/') != -1) return (sAgent.match(/ adobeair\/(\d+)/)[1] >= 1);
	if(sAgent.indexOf(' applewebkit/') != -1) return (sAgent.match(/ applewebkit\/(\d+)/)[1] >= 522);
	return false;
}