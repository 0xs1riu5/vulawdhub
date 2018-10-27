FCKConfig.CustomConfigurationsPath = '';
FCKConfig.EditorAreaCSS = FCKConfig.BasePath + 'css/fck_editorarea.css';
FCKConfig.EditorAreaStyles = '';
FCKConfig.ToolbarComboPreviewCSS = '';
var IsAdmin = window.parent.DTAdmin ? true : false;
FCKConfig.IsAdmin = IsAdmin;
FCKConfig.ModuleID = window.parent.ModuleID;
FCKConfig.DTPath = window.parent.DTPath;
FCKConfig.UPPath = window.parent.UPPath;
FCKConfig.AJPath = window.parent.AJPath;
FCKConfig.EDPath = window.parent.EDPath;
FCKConfig.ABPath = window.parent.ABPath;
FCKConfig.DocType = '';
FCKConfig.BaseHref = '';
FCKConfig.FullPage = false;
FCKConfig.StartupShowBlocks = false;
FCKConfig.Debug = false;
FCKConfig.AllowQueryStringDebug = true;
FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/default/';
FCKConfig.SkinEditorCSS = '';
FCKConfig.SkinDialogCSS = '';
FCKConfig.PreloadImages = [ FCKConfig.SkinPath + 'images/toolbar.start.gif', FCKConfig.SkinPath + 'images/toolbar.buttonarrow.gif' ];
FCKConfig.PluginsPath = FCKConfig.BasePath + 'extends/';
FCKConfig.AutoGrowMax = 400;
FCKConfig.AutoDetectLanguage	= true;
FCKConfig.DefaultLanguage		= 'en';
FCKConfig.ContentLangDirection	= 'ltr';
FCKConfig.ProcessHTMLEntities	= true;
FCKConfig.IncludeLatinEntities	= true;
FCKConfig.IncludeGreekEntities	= true;
FCKConfig.ProcessNumericEntities = false;
FCKConfig.AdditionalNumericEntities = '' ;
FCKConfig.FillEmptyBlocks	= true;
FCKConfig.FormatSource		= true;
FCKConfig.FormatOutput		= true;
FCKConfig.FormatIndentator	= '    ';
FCKConfig.StartupFocus	= false;
FCKConfig.ForcePasteAsPlainText	= false;
FCKConfig.AutoDetectPasteFromWord = true;
FCKConfig.ShowDropDialog = true;
FCKConfig.ForceSimpleAmpersand	= false;
FCKConfig.TabSpaces		= 0;
FCKConfig.ShowBorders	= true;
FCKConfig.SourcePopup	= false;
FCKConfig.ToolbarStartExpanded	= true;
FCKConfig.ToolbarCanCollapse	= true;
FCKConfig.IgnoreEmptyParagraphValue = true;
FCKConfig.PreserveSessionOnFileBrowser = false;
FCKConfig.FloatingPanelsZIndex = 10000;
FCKConfig.HtmlEncodeOutput = false;
FCKConfig.TemplateReplaceAll = true;
FCKConfig.TemplateReplaceCheckbox = true;
FCKConfig.ToolbarLocation = 'In';
FCKConfig.ToolbarSets["Default"] = [
	['Source','-','PasteWord','PasteText','Preview','Print','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord'],
	['Undo','Redo','-','Find','Replace','-','RemoveFormat'],
	'/',
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar'],
	['OrderedList','UnorderedList','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	'/',
	['Bold','Italic','Underline','StrikeThrough'],
	['TextColor','BGColor','-','FontName','FontSize','Attach','FitWindow']
];
if(!IsAdmin) FCKConfig.ToolbarSets["Default"][0].splice(0,2);

FCKConfig.ToolbarSets["Destoon"] = [
	['Source','-','PasteWord','PasteText','-','Bold','TextColor','FontSize','Link','Unlink','Image','Flash','Rule','SpecialChar','JustifyLeft','JustifyCenter','JustifyRight','RemoveFormat','Attach','FitWindow']
];
if(!IsAdmin) FCKConfig.ToolbarSets["Destoon"][0].splice(0,2);
if(window.parent.ModuleID == 18) FCKConfig.ToolbarSets["Destoon"][0].splice(-1,0,'Smiley');


FCKConfig.ToolbarSets["Simple"] = [
	['Source','-','Bold','Italic','Underline','TextColor','-','FontSize','Link','Unlink','-','Image','JustifyLeft','JustifyCenter','JustifyRight','-','RemoveFormat','Attach','FitWindow']
];
if(!IsAdmin) FCKConfig.ToolbarSets["Simple"][0].splice(0,2);
if(window.parent.ModuleID == 18) FCKConfig.ToolbarSets["Simple"][0].splice(-1,0,'Smiley');


FCKConfig.ToolbarSets["Basic"] = [
	['Source','-','Bold','TextColor','-','Link','Unlink','-','Image','JustifyLeft','JustifyCenter','JustifyRight','RemoveFormat','FitWindow']
];
if(!IsAdmin) FCKConfig.ToolbarSets["Basic"][0].splice(0,2);
if(window.parent.ModuleID == 18) FCKConfig.ToolbarSets["Basic"][0].splice(-1,0,'Smiley');

FCKConfig.ToolbarSets["Message"] = [
	['Bold','Italic','Underline','TextColor','-','FontSize','Link','Unlink','-','Image','Smiley','JustifyLeft','JustifyCenter','JustifyRight','-','RemoveFormat','Attach','FitWindow']
];

FCKConfig.EnterMode = 'br';
FCKConfig.ShiftEnterMode = 'p';
FCKConfig.Keystrokes = [
	[ CTRL + 65 /*A*/, true ],
	[ CTRL + 67 /*C*/, true ],
	[ CTRL + 70 /*F*/, true ],
	[ CTRL + 83 /*S*/, true ],
	[ CTRL + 84 /*T*/, true ],
	[ CTRL + 88 /*X*/, true ],
	[ CTRL + 86 /*V*/, 'Paste' ],
	[ SHIFT + 45 /*INS*/, 'Paste' ],
	[ CTRL + 88 /*X*/, 'Cut' ],
	[ SHIFT + 46 /*DEL*/, 'Cut' ],
	[ CTRL + 90 /*Z*/, 'Undo' ],
	[ CTRL + 89 /*Y*/, 'Redo' ],
	[ CTRL + SHIFT + 90 /*Z*/, 'Redo' ],
	[ CTRL + 76 /*L*/, 'Link' ],
	[ CTRL + 66 /*B*/, 'Bold' ],
	[ CTRL + 73 /*I*/, 'Italic' ],
	[ CTRL + 85 /*U*/, 'Underline' ],
	[ CTRL + SHIFT + 83 /*S*/, 'Save' ],
	[ CTRL + ALT + 13 /*ENTER*/, 'FitWindow' ]
];
FCKConfig.ContextMenu = ['Generic','Link','Anchor','Image','Flash','Select','Textarea','Checkbox','Radio','TextField','HiddenField','ImageButton','Button','BulletedList','NumberedList','Table','Form'];
FCKConfig.BrowserContextMenuOnCtrl = false;
FCKConfig.EnableMoreFontColors = true;
FCKConfig.FontColors = '000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,808080,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF';
FCKConfig.FontFormats	= 'p;h1;h2;h3;h4;h5;h6;pre;address;div';
FCKConfig.FontNames		= '宋体;黑体;微软雅黑;楷体;Arial;Tahoma;Verdana;';
FCKConfig.FontSizes		= '10px;11px;12px;13px;14px;16px;18px;20px;22px;24px;26px;28px;36px;48px;72px';
FCKConfig.StylesXmlPath		= FCKConfig.EditorPath + 'fckstyles.xml';
FCKConfig.TemplatesXmlPath	= FCKConfig.EditorPath + 'fcktemplates.xml';
FCKConfig.SpellChecker			= 'ieSpell';
FCKConfig.IeSpellDownloadUrl	= 'http://www.iespell.com/download.php';
FCKConfig.SpellerPagesServerScript = 'server-scripts/spellchecker.php';
FCKConfig.FirefoxSpellChecker	= false;
FCKConfig.MaxUndoLevels = 15;
FCKConfig.DisableObjectResizing = false;
FCKConfig.DisableFFTableHandles = true;
FCKConfig.LinkDlgHideTarget		= false;
FCKConfig.LinkDlgHideAdvanced	= true;
FCKConfig.ImageDlgHideLink		= false;
FCKConfig.ImageDlgHideAdvanced	= true;
FCKConfig.FlashDlgHideAdvanced	= true;
FCKConfig.ProtectedTags = '';
FCKConfig.BodyId = '';
FCKConfig.BodyClass = '';
FCKConfig.DefaultStyleLabel = '';
FCKConfig.DefaultFontFormatLabel = '';
FCKConfig.DefaultFontLabel = '';
FCKConfig.DefaultFontSizeLabel = '';
FCKConfig.DefaultLinkTarget = '';
FCKConfig.CleanWordKeepsStructure = false;
FCKConfig.RemoveFormatTags = 'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var';
FCKConfig.RemoveAttributes = 'class,style,lang,width,height,align,hspace,valign';
FCKConfig.CustomStyles = {'Red Title'	: { Element : 'h3', Styles : { 'color' : 'Red' } }};
FCKConfig.CoreStyles = {
	'Bold'			: { Element : 'strong', Overrides : 'b' },
	'Italic'		: { Element : 'em', Overrides : 'i' },
	'Underline'		: { Element : 'u' },
	'StrikeThrough'	: { Element : 'strike' },
	'Subscript'		: { Element : 'sub' },
	'Superscript'	: { Element : 'sup' },
	'p'				: { Element : 'p' },
	'div'			: { Element : 'div' },
	'pre'			: { Element : 'pre' },
	'address'		: { Element : 'address' },
	'h1'			: { Element : 'h1' },
	'h2'			: { Element : 'h2' },
	'h3'			: { Element : 'h3' },
	'h4'			: { Element : 'h4' },
	'h5'			: { Element : 'h5' },
	'h6'			: { Element : 'h6' },
	'FontFace' : {
		Element		: 'span',
		Styles		: { 'font-family' : '#("Font")' },
		Overrides	: [ { Element : 'font', Attributes : { 'face' : null } } ]
	},
	'Size' : {
		Element		: 'span',
		Styles		: { 'font-size' : '#("Size","fontSize")' },
		Overrides	: [ { Element : 'font', Attributes : { 'size' : null } } ]
	},
	'Color' : {
		Element		: 'span',
		Styles		: { 'color' : '#("Color","color")' },
		Overrides	: [ { Element : 'font', Attributes : { 'color' : null } } ]
	},
	'BackColor'		: { Element : 'span', Styles : { 'background-color' : '#("Color","color")' } },
	'SelectionHighlight' : { Element : 'span', Styles : { 'background-color' : 'navy', 'color' : 'white' } }
};
FCKConfig.IndentLength = 30;
FCKConfig.IndentUnit = 'px';
FCKConfig.IndentClasses = [];
FCKConfig.JustifyClasses = [];
var _FileBrowserLanguage	= 'php';
var _QuickUploadLanguage	= 'php';
var _FileBrowserExtension = _FileBrowserLanguage == 'perl' ? 'cgi' : _FileBrowserLanguage;
var _QuickUploadExtension = _QuickUploadLanguage == 'perl' ? 'cgi' : _QuickUploadLanguage;
FCKConfig.LinkBrowser = false;
FCKConfig.LinkBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Connector=' + encodeURIComponent( FCKConfig.BasePath + 'filemanager/connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension );
FCKConfig.LinkBrowserWindowWidth	= FCKConfig.ScreenWidth * 0.7;
FCKConfig.LinkBrowserWindowHeight	= FCKConfig.ScreenHeight * 0.7;
FCKConfig.ImageBrowser = false;
FCKConfig.ImageBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Image&Connector=' + encodeURIComponent( FCKConfig.BasePath + 'filemanager/connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension );
FCKConfig.ImageBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7;
FCKConfig.ImageBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7;
FCKConfig.FlashBrowser = false;
FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=' + encodeURIComponent( FCKConfig.BasePath + 'filemanager/connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension );
FCKConfig.FlashBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7;
FCKConfig.FlashBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7;
FCKConfig.LinkUpload = false;
FCKConfig.LinkUploadURL = FCKConfig.BasePath + 'filemanager/connectors/' + _QuickUploadLanguage + '/upload.' + _QuickUploadExtension;
FCKConfig.LinkUploadAllowedExtensions	= ".(asf|avi|bmp|csv|doc|fla|flv|gif|gz|gzip|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|pdf|png|ppt|ram|rar|rm|rmi|rmvb|rtf|swf|tar|tgz|tif|tiff|txt|wav|wma|wmv|xls|xml|zip)$";			// empty for all
FCKConfig.LinkUploadDeniedExtensions	= ".(php|phtml|php3|php4|jsp|exe|dll|cer|asa|shtml|shtm|asp|aspx|asax|cgi|fcgi|pl)$";	// empty for no one
FCKConfig.ImageUpload = true;
FCKConfig.ImageUploadURL = FCKConfig.BasePath + 'filemanager/connectors/' + _QuickUploadLanguage + '/upload.' + _QuickUploadExtension + '?Type=Image';
FCKConfig.ImageUploadAllowedExtensions	= ".(jpg|gif|jpeg|png|bmp)$";
FCKConfig.ImageUploadDeniedExtensions	= "";
FCKConfig.FlashUpload = IsAdmin;
FCKConfig.FlashUploadURL = FCKConfig.BasePath + 'filemanager/connectors/' + _QuickUploadLanguage + '/upload.' + _QuickUploadExtension + '?Type=Flash';
FCKConfig.FlashUploadAllowedExtensions	= ".(swf|flv)$";
FCKConfig.FlashUploadDeniedExtensions	= "";
FCKConfig.SmileyPath	= FCKConfig.ABPath + 'editor/images/smiley/msn/';
FCKConfig.SmileyImages	= ['001.gif','002.gif','003.gif','004.gif','005.gif','006.gif','007.gif','008.gif','009.gif','010.gif','011.gif','012.gif','013.gif','014.gif','015.gif','016.gif','017.gif','018.gif','019.gif','020.gif','021.gif','022.gif','023.gif','024.gif','025.gif','026.gif','027.gif','028.gif','029.gif','030.gif','031.gif','032.gif','033.gif','034.gif','035.gif','036.gif','037.gif','038.gif','039.gif','040.gif'];
FCKConfig.SmileyColumns = 8;
FCKConfig.SmileyWindowWidth		= 320;
FCKConfig.SmileyWindowHeight	= 210;
FCKConfig.BackgroundBlockerColor = '#ffffff';
FCKConfig.BackgroundBlockerOpacity = 0.50;