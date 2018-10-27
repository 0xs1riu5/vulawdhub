(function()
{
	var d = document.domain ;

	while ( true )
	{
		try
		{
			var test = window.parent.document.domain ;
			/* DT ADD 2011-06-22// Fix Firefox 4+*/
			var uaL = navigator.userAgent.toLowerCase();
			if(uaL.indexOf('firefox') != -1 && parseInt(uaL.match(/firefox\/(\d+)/)[1], 10) >= 4) {
				if(test != document.domain) document.domain = test;
			}
			/* DT ADD 2011-06-22\\*/
			break ;
		}
		catch( e ) {}
		d = d.replace( /.*?(?:\.|$)/, '' ) ;

		if ( d.length == 0 )
			break ;

		try
		{
			document.domain = d ;
		}
		catch (e)
		{
			break ;
		}
	}
})() ;
function GetCommonDialogCss( prefix )
{
	return FCKConfig.BasePath + 'dialog/common/' + '|.ImagePreviewArea{border:#000 1px solid;overflow:auto;width:100%;height:170px;background-color:#fff}.FlashPreviewArea{border:#000 1px solid;padding:5px;overflow:auto;width:100%;height:170px;background-color:#fff}.BtnReset{float:left;background-position:center center;background-image:url(images/reset.gif);width:16px;height:16px;background-repeat:no-repeat;border:1px none;font-size:1px}.BtnLocked,.BtnUnlocked{float:left;background-position:center center;background-image:url(images/locked.gif);width:16px;height:16px;background-repeat:no-repeat;border:none 1px;font-size:1px}.BtnUnlocked{background-image:url(images/unlocked.gif)}.BtnOver{border:outset 1px;cursor:pointer;cursor:hand}' ;
}
function GetE( elementId )
{
	return document.getElementById( elementId )  ;
}

function ShowE( element, isVisible )
{
	if ( typeof( element ) == 'string' )
		element = GetE( element ) ;
	element.style.display = isVisible ? '' : 'none' ;
}

function SetAttribute( element, attName, attValue )
{
	if ( attValue == null || attValue.length == 0 )
		element.removeAttribute( attName, 0 ) ;	
	else
		element.setAttribute( attName, attValue, 0 ) ;
}

function GetAttribute( element, attName, valueIfNull )
{
	var oAtt = element.attributes[attName] ;

	if ( oAtt == null || !oAtt.specified )
		return valueIfNull ? valueIfNull : '' ;

	var oValue = element.getAttribute( attName, 2 ) ;

	if ( oValue == null )
		oValue = oAtt.nodeValue ;

	return ( oValue == null ? valueIfNull : oValue ) ;
}

function SelectField( elementId )
{
	var element = GetE( elementId ) ;
	element.focus() ;
	if ( element.select )
		element.select() ;
}
var IsDigit = ( function()
	{
		var KeyIdentifierMap =
		{
			End			: 35,
			Home		: 36,
			Left		: 37,
			Right		: 39,
			'U+00007F'	: 46
		} ;

		return function ( e )
			{
				if ( !e )
					e = event ;

				var iCode = ( e.keyCode || e.charCode ) ;

				if ( !iCode && e.keyIdentifier && ( e.keyIdentifier in KeyIdentifierMap ) )
						iCode = KeyIdentifierMap[ e.keyIdentifier ] ;

				return (
						( iCode >= 48 && iCode <= 57 )		// Numbers
						|| (iCode >= 35 && iCode <= 40)		// Arrows, Home, End
						|| iCode == 8						// Backspace
						|| iCode == 46						// Delete
						|| iCode == 9						// Tab
				) ;
			}
	} )() ;

String.prototype.Trim = function()
{
	return this.replace( /(^\s*)|(\s*$)/g, '' ) ;
}

String.prototype.StartsWith = function( value )
{
	return ( this.substr( 0, value.length ) == value ) ;
}

String.prototype.Remove = function( start, length )
{
	var s = '' ;

	if ( start > 0 )
		s = this.substring( 0, start ) ;

	if ( start + length < this.length )
		s += this.substring( start + length , this.length ) ;

	return s ;
}

String.prototype.ReplaceAll = function( searchArray, replaceArray )
{
	var replaced = this ;

	for ( var i = 0 ; i < searchArray.length ; i++ )
	{
		replaced = replaced.replace( searchArray[i], replaceArray[i] ) ;
	}

	return replaced ;
}

function OpenFileBrowser( url, width, height )
{
	// oEditor must be defined.

	var iLeft = ( oEditor.FCKConfig.ScreenWidth  - width ) / 2 ;
	var iTop  = ( oEditor.FCKConfig.ScreenHeight - height ) / 2 ;

	var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes,scrollbars=yes" ;
	sOptions += ",width=" + width ;
	sOptions += ",height=" + height ;
	sOptions += ",left=" + iLeft ;
	sOptions += ",top=" + iTop ;
	if ( oEditor.FCKConfig.PreserveSessionOnFileBrowser && oEditor.FCKBrowserInfo.IsIE )
	{
		var oWindow = oEditor.window.open( url, 'FCKBrowseWindow', sOptions ) ;

		if ( oWindow )
		{
			try
			{
				var sTest = oWindow.name ;
				oWindow.opener = window ;
			}
			catch(e)
			{
				alert( oEditor.FCKLang.BrowseServerBlocked ) ;
			}
		}
		else
			alert( oEditor.FCKLang.BrowseServerBlocked ) ;
    }
    else
		window.open( url, 'FCKBrowseWindow', sOptions ) ;
}
function CreateNamedElement( oEditor, oOriginal, nodeName, oAttributes )
{
	var oNewNode ;
	var oldNode = null ;
	if ( oOriginal && oEditor.FCKBrowserInfo.IsIE )
	{
		var bChanged = false;
		for( var attName in oAttributes )
			bChanged |= ( oOriginal.getAttribute( attName, 2) != oAttributes[attName] ) ;

		if ( bChanged )
		{
			oldNode = oOriginal ;
			oOriginal = null ;
		}
	}

	if ( oOriginal )
	{
		oNewNode = oOriginal ;
	}
	else
	{
		if ( oEditor.FCKBrowserInfo.IsIE )
		{
			var sbHTML = [] ;
			sbHTML.push( '<' + nodeName ) ;
			for( var prop in oAttributes )
			{
				sbHTML.push( ' ' + prop + '="' + oAttributes[prop] + '"' ) ;
			}
			sbHTML.push( '>' ) ;
			if ( !oEditor.FCKListsLib.EmptyElements[nodeName.toLowerCase()] )
				sbHTML.push( '</' + nodeName + '>' ) ;

			oNewNode = oEditor.FCK.EditorDocument.createElement( sbHTML.join('') ) ;
			if ( oldNode )
			{
				CopyAttributes( oldNode, oNewNode, oAttributes ) ;
				oEditor.FCKDomTools.MoveChildren( oldNode, oNewNode ) ;
				oldNode.parentNode.removeChild( oldNode ) ;
				oldNode = null ;

				if ( oEditor.FCK.Selection.SelectionData )
				{
					var oSel = oEditor.FCK.EditorDocument.selection ;
					oEditor.FCK.Selection.SelectionData = oSel.createRange() ; 
				}
			}
			oNewNode = oEditor.FCK.InsertElement( oNewNode ) ;
			if ( oEditor.FCK.Selection.SelectionData )
			{
				var range = oEditor.FCK.EditorDocument.body.createControlRange() ;
				range.add( oNewNode ) ;
				oEditor.FCK.Selection.SelectionData = range ;
			}
		}
		else
		{
			oNewNode = oEditor.FCK.InsertElement( nodeName ) ;
		}
	}

	// Set the basic attributes
	for( var attName in oAttributes )
		oNewNode.setAttribute( attName, oAttributes[attName], 0 ) ;	// 0 : Case Insensitive

	return oNewNode ;
}
function CopyAttributes( oSource, oDest, oSkipAttributes )
{
	var aAttributes = oSource.attributes ;

	for ( var n = 0 ; n < aAttributes.length ; n++ )
	{
		var oAttribute = aAttributes[n] ;

		if ( oAttribute.specified )
		{
			var sAttName = oAttribute.nodeName ;
			if ( sAttName in oSkipAttributes )
				continue ;

			var sAttValue = oSource.getAttribute( sAttName, 2 ) ;
			if ( sAttValue == null )
				sAttValue = oAttribute.nodeValue ;

			oDest.setAttribute( sAttName, sAttValue, 0 ) ;	// 0 : Case Insensitive
		}
	}
	oDest.style.cssText = oSource.style.cssText ;
}