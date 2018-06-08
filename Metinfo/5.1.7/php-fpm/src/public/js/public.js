function ifie(){
	return document.all;
}
function SetHome(obj,vrl,info){
	if(!ifie()){
		alert(info);
	}
        try{
            obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
            }
        catch(e){
                if(window.netscape){
                        try{
							netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
                        }  
                        catch (e){ 
                            alert("Your Browser does not support");
                        }
						var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
						prefs.setCharPref('browser.startup.homepage',vrl);
                 }
        }
}

function addFavorite(info){
	if(!ifie()){
		alert(info);
	}
    var vDomainName=window.location.href;
    var description=document.title;
    try{//IE
        window.external.AddFavorite(vDomainName,description);
    }catch(e){//FF
        window.sidebar.addPanel(description,vDomainName,"");
    }
}
function metHeight(group) {
	tallest = 0;
	group.each(function() {
		thisHeight = $(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}
function metmessagesubmit(info3,info4){
	if (document.myform.pname.value.length == 0) {
		alert(info3);
		document.myform.pname.focus();
		return false;
	}
	if (document.myform.info.value.length == 0) {
		alert(info4);
		document.myform.info.focus();
		return false;
	}
}
function addlinksubmit(info2,info3){ 
	if (document.myform.webname.value.length == 0) {
		alert(info2);
		document.myform.webname.focus();
		return false;
	}
	if (document.myform.weburl.value.length == 0 || document.myform.weburl.value == 'http://'){
		alert(info3);
		document.myform.weburl.focus();
		return false;
	}
}
function textWrap(my){
	var text='',txt = my.text();
		txt = txt.split("");
		for(var i=0;i<txt.length;i++){
			text+=txt[i]+'<br/>';
		}
		my.html(text);
}
function pressCaptcha(obj){ obj.value = obj.value.toUpperCase();}
function ResumeError() { return true; } 
window.onerror = ResumeError; 