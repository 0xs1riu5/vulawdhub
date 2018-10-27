var _ua = navigator.userAgent.toLowerCase();
var _os = '';
if(_ua.indexOf('iphone')!=-1 || _ua.indexOf('ipod')!=-1 || _ua.indexOf('ipad')!=-1) _os = 'ios';
var _bs = Dbrowser;
document.write('<style type="text/css">');
if(_os == 'ios' && (_bs == 'app' || _bs == 'b2b')) {/*修复IOS APP顶部状态栏*/
document.write('.head-bar{border-top:#F7F7F7 20px solid;}');
document.write('.head-bar-fix{height:68px;background:#F7F7F7;}');
}
if(_os == 'ios' && parseInt(_ua.match(/os (\d+)_/)[1]) > 7) {/*IOS8+ 细线*/
document.write('.bd-b,.head-bar,.list-set,.list-img,.list-txt li,.list-msg li,.content,.info,.user-info {border-bottom:#A7A7AA 0.5px solid;}');
document.write('.bd-t,.foot-bar,.list-set div,.list-set,.list-txt,.user-info {border-top:#A7A7AA 0.5px solid;}');
document.write('.bd-r {border-right:#A7A7AA 0.5px solid;}');
document.write('.bd-l {border-left:#A7A7AA 0.5px solid;}');
}
document.write('</style>');
if(_os == 'ios' && _bs != 'screen' && navigator.standalone) {/*IOS 主屏打开*/
document.write('<script type="text/javascript" src="'+AJPath+'?action=screen"></sc'+'ript>');
}