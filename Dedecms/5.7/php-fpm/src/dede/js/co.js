/**
 * 
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 

function nav(){
    if(window.navigator.userAgent.indexOf("MSIE")>=1) return 'IE';
    else if(window.navigator.userAgent.indexOf("Firefox")>=1) return 'FF';
    else return "OT";
}

function myObj(oid){
    return document.getElementById(oid);
}

function showHide(objname){
    var obj = myObj(objname);
    if(obj.style.display==null || obj.style.display=='none'){
        if(nav()=='IE') obj.style.display = "block";
        else obj.style.display = "table-row";
    } else {
        obj.style.display = "none";
	 }
}

function showTestWin(surl){
	window.open(surl, "testWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=450,left=100, top=100");
}

function showItem(objname){
 	var obj = myObj(objname);
 	if(nav()=='IE') obj.style.display = "block";
 	else  obj.style.display = "table";
}

function showItemM(objname){
 	var obj = myObj(objname);
 	if(nav()=='IE') obj.style.display = "block";
 	else  obj.style.display = "table";
}

function showItem1(){ 
    showItem('needset'); 
    showItem('head1'); 
    myObj('adset').style.display = "none";
    myObj('head2').style.display = "none";
}
function showItem2(){ 
    showItemM('adset'); 
    showItemM('head2'); 
    myObj('needset').style.display = "none";
    myObj('head1').style.display = "none";
}

function testMore(){
    if(myObj('usemore').checked) {
        if(nav()=='IE') myObj('usemoretr').style.display = 'block';
		else myObj('usemoretr').style.display = 'table-row';
		myObj('handset').style.display = 'none';	
	} else {
		myObj('usemoretr').style.display = 'none';
		if(nav()=='IE')  myObj('handset').style.display = 'block';
		else myObj('handset').style.display = 'table-row';
	}
}

function selSourceSet(){
    if(myObj('source3').checked){
        if(nav()=='IE') myObj('rssset').style.display = 'block';
		else myObj('rssset').style.display = 'table-row';
		myObj('batchset').style.display = 'none';
		myObj('handset').style.display = 'none';
		myObj('arturl').style.display = 'none';
    } else if(myObj('source2').checked){
		myObj('rssset').style.display = 'none';
		myObj('batchset').style.display = 'none';
		if(nav()=='IE') myObj('handset').style.display = 'block';
		else myObj('handset').style.display = 'table-row';
		if(nav()=='IE') myObj('arturl').style.display = 'block';
		else myObj('arturl').style.display = 'table-row';
    } else {
        myObj('rssset').style.display = 'none';
		if(nav()=='IE') myObj('batchset').style.display = 'block';
		else myObj('batchset').style.display = 'table-row';
		if(nav()=='IE') myObj('handset').style.display = 'block';
		else myObj('handset').style.display = 'table-row';
		if(nav()=='IE') myObj('arturl').style.display = 'block';
		else myObj('arturl').style.display = 'table-row';
	 }
	 testMore();
}

function selListenSet(){
    if(myObj('islisten1').checked) {
        myObj('listentr').style.display = 'none';
	} else {
		if(nav()=='IE') myObj('listentr').style.display = 'block';
		else  myObj('listentr').style.display = 'table-row';
	}
}

function selUrlRuleSet(){
    if(myObj('urlrule2').checked) {
        myObj('arearuletr').style.display = 'none';
		if(nav()=='IE') myObj('regxruletr').style.display = 'block';
		else myObj('regxruletr').style.display = 'table-row'; 
	}
	else
	{
		if(nav()=='IE') myObj('arearuletr').style.display = 'block';
		else myObj('arearuletr').style.display = 'table-row';
		myObj('regxruletr').style.display = 'none';
	}
}

function testRss(){
	var surl = '';
	surl = escape(myObj('rssurl').value);
	showTestWin("co_do.php?dopost=testrss&rssurl="+surl);
}

function testRegx(){
	var surl = escape(myObj('regxurl').value);
	var sstart = myObj('startid').value;
	var send = myObj('endid').value;
	var saddv = myObj('addv').value;
	showTestWin("co_do.php?dopost=testregx&regxurl="+surl+"&startid="+sstart+"&endid="+send+"&addv="+saddv);
}

function toHex( n ){
	var digitArray = new Array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
	var result = ''
	var start = true;

	for ( var i=32; i>0; ) {
        i -= 4;
		var digit = ( n >> i ) & 0xf;
		if (!start || digit != 0) {
            start = false;
			result += digitArray[digit];
        }
	}
	return ( result == '' ? '0' : result );
}

function selTrim(selfield){
	var tagobj = myObj(selfield);
	if(nav()=='IE'){ var posLeft = window.event.clientX-200; var posTop = window.event.clientY; }
    else{ var posLeft = 100;var posTop = 100; }
	window.open("templets/co_trimrule.html?"+selfield, "coRule", "scrollbars=no,resizable=yes,statebar=no,width=320,height=180,left="+posLeft+", top="+posTop);
}