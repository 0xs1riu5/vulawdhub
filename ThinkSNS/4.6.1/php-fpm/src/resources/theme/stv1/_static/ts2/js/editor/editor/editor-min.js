/*
 Constructor for kissy editor,dependency moved to independent module
 thanks to CKSource's intelligent work on CKEditor
 @author: yiminghe@gmail.com, lifesinger@gmail.com
 @version: 2.1.5
 @buildtime: 2011-04-18 21:45:33
*/
KISSY.add("editor",function(b){function d(e,c){var a=this;if(!(a instanceof d))return new d(e,c);if(b.isString(e))e=b.one(e);e=n._4e_wrap(e);c=c||{};c.pluginConfig=c.pluginConfig||{};a.cfg=c;c.pluginConfig=c.pluginConfig;a.cfg=c;b.app(a,b.EventTarget);var k=["htmldataprocessor","enterkey","clipboard"],h=o;a.use=function(f,l){f=f.split(",");if(!h)for(var i=0;i<k.length;i++){var m=k[i];b.inArray(m,f)||f.unshift(m)}a.ready(function(){b.use.call(a,f.join(","),function(){for(var g=0;g<f.length;g++)a.usePlugin(f[g]);
l&&l.call(a);if(!h){a.setData(e.val());if(c.focus)a.focus();else(g=a.getSelection())&&g.removeAllRanges();h=p}},{global:d})});return a};a.use=a.use;a.Config.base=d.Config.base;a.Config.debug=d.Config.debug;a.Config.componentJsName=j;a.init(e);return a}var n=b.DOM,p=true,o=false,j;j=parseFloat(b.version)<1.2?function(){return"plugin-min.js?t=2011-04-18 21:45:33"}:function(e,c){return e+"/plugin-min.js"+(c?c:"?t=2011-04-18 21:45:33")};b.app(d,b.EventTarget);d.Config.base=b.Config.base+"editor/plugins/";d.Config.debug=
b.Config.debug;d.Config.componentJsName=j;b.Editor=d;b.Editor=d});
