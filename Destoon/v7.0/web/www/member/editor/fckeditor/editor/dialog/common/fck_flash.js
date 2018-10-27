var dialog		= window.parent ;
var oEditor		= dialog.InnerDialogLoaded() ;
var FCK			= oEditor.FCK ;
var FCKLang		= oEditor.FCKLang ;
var FCKConfig	= oEditor.FCKConfig ;
var FCKTools	= oEditor.FCKTools ;
dialog.AddTab( 'Info', oEditor.FCKLang.DlgInfoTab ) ;
if ( FCKConfig.FlashUpload )
	dialog.AddTab( 'Upload', FCKLang.DlgLnkUpload ) ;
if ( !FCKConfig.FlashDlgHideAdvanced )
	dialog.AddTab( 'Advanced', oEditor.FCKLang.DlgAdvancedTag ) ;
function OnDialogTabChange( tabCode )
{
	ShowE('divInfo'		, ( tabCode == 'Info' ) ) ;
	ShowE('divUpload'	, ( tabCode == 'Upload' ) ) ;
	ShowE('divAdvanced'	, ( tabCode == 'Advanced' ) ) ;
}
var oFakeImage = dialog.Selection.GetSelectedElement() ;
var oEmbed ;

if ( oFakeImage )
{
	if ( oFakeImage.tagName == 'IMG' && oFakeImage.getAttribute('_fckflash') )
		oEmbed = FCK.GetRealElement( oFakeImage ) ;
	else
		oFakeImage = null ;
}

window.onload = function()
{
	oEditor.FCKLanguageManager.TranslatePage(document) ;
	LoadSelection() ;
	GetE('tdBrowse').style.display = FCKConfig.FlashBrowser	? '' : 'none' ;
	if ( FCKConfig.FlashUpload )
		GetE('frmUpload').action = FCKConfig.FlashUploadURL ;
	dialog.SetAutoSize( true ) ;
	dialog.SetOkButton( true ) ;

	SelectField( 'txtUrl' ) ;
}

function LoadSelection()
{
	if ( ! oEmbed ) return ;

	GetE('txtUrl').value    = GetAttribute( oEmbed, 'src', '' ) ;
	if(GetE('txtUrl').value.indexOf('vcastr3.swf') > 0) {
		var tmp = GetAttribute( oEmbed, 'flashvars', '' ) ;
		var t1 = tmp.split('<source>');
		var t2 = t1[1].split('</source>');
		if(t2[0]) GetE('txtUrl').value = t2[0];
	}
	GetE('txtWidth').value  = GetAttribute( oEmbed, 'width', '' ) ;
	GetE('txtHeight').value = GetAttribute( oEmbed, 'height', '' ) ;
	if(GetAttribute( oEmbed, 'autostart', 'true' ) == 'true') {
		GetE('autostart').checked = true;
	} else {
		GetE('autostart0').checked = true;
	}

	GetE('txtAttId').value		= oEmbed.id ;
	//GetE('chkAutoPlay').checked	= GetAttribute( oEmbed, 'play', 'true' ) == 'true' ;
	GetE('chkLoop').checked		= GetAttribute( oEmbed, 'loop', 'true' ) == 'true' ;
	GetE('chkMenu').checked		= GetAttribute( oEmbed, 'menu', 'true' ) == 'true' ;
	GetE('cmbScale').value		= GetAttribute( oEmbed, 'scale', '' ).toLowerCase() ;

	GetE('txtAttTitle').value		= oEmbed.title ;

	if ( oEditor.FCKBrowserInfo.IsIE )
	{
		GetE('txtAttClasses').value = oEmbed.getAttribute('className') || '' ;
		GetE('txtAttStyle').value = oEmbed.style.cssText ;
	}
	else
	{
		GetE('txtAttClasses').value = oEmbed.getAttribute('class',2) || '' ;
		GetE('txtAttStyle').value = oEmbed.getAttribute('style',2) || '' ;
	}

	UpdatePreview() ;
}
function Ok()
{
	var v = GetVHTML();
	if (v) {

		//Fix FireFox
		if(navigator.userAgent.toLowerCase().indexOf('firefox') != -1) {
			v = v.replace(/\:\/\//g, '://fix-firefox-auto.');
			FCK.InsertHtml(v);
			v = FCK.GetXHTML(true);
			v = v.replace(/\:\/\/fix\-firefox\-auto\./g, '://');
			FCK.SetData(v);
			return true;
		}

		FCK.InsertHtml(v);
		return true ;
	} else {
		alert(oEditor.FCKLang.DlgAlertUrl) ;
		return false ;
	}
}

function UpdatePreview()
{
	var v = GetVHTML();
	if(v) {
		var ow = GetE('txtWidth').value;
		var oh = GetE('txtHeight').value;
		var nw = 200;
		var nh = parseInt(nw*oh/ow);
		v = v.replace('width="'+ow+'"', 'width="'+nw+'"');
		v = v.replace('height="'+oh+'"', 'height="'+nh+'"');
		GetE('vPreview').innerHTML = v;
	}
}

function SetUrl( url, width, height )
{
	GetE('txtUrl').value = url ;

	if ( width )
		GetE('txtWidth').value = width ;

	if ( height )
		GetE('txtHeight').value = height ;

	UpdatePreview() ;

	dialog.SetSelectedTab( 'Info' ) ;
}