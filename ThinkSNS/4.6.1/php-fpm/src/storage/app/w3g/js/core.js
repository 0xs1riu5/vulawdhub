/**
 * ThinkSNS核心Js对象
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
var _core = function() {
	// 核心通用的加载源函数
	var obj = this;
	// 加载文件方法
	this._coreLoadFile = function() {
		var temp = new Array();
		var tempMethod = function(url, callback) {
			// 第二次调用的时候就不=0了
			var flag = 0;
			for(i in temp) {
				if(temp[i] == url) {
					flag = 1;
				}
			}
			if(flag == 0) {
				// 未载入过
				temp[temp.length] = url;	
				// JQuery的ajax载入文件方式，如果有样式文件，同理在此引入相关样式文件
				$.getScript(url, function() {	
					if("undefined" != typeof(callback)) {
						if("function" == typeof(callback)) {
							callback();
						} else {
							eval(callback);
						}
					}
				});
			} else {
				if("undefined" != typeof(callback)) {
					// 利用setTimeout 避免未定义错误
					setTimeout(callback, 200);	
				}
			}
		};
		// 返回内部包函数，供外部调用并可以更改temp的值
		return tempMethod;
	};
	// 加载CSS文件
	this._loadCss = function() {
		var temp = new Array();
		var tempMethod = function(url) {
			// 第二次调用的时候就不=0了
			var flag = 0;
			for(i in temp) {
				if(temp[i] == url) {
					flag = 1;
				}
			}
			if(flag == 0) {
				// 未载入过
				temp[temp.length] = url;	
				var css = '<link href="'+THEME_URL+'/js/tbox/box.css" rel="stylesheet" type="text/css">';
				$('head').append(css);
			}
		};
		// 返回内部包函数,供外部调用并可以更改temp的值
		return tempMethod;
	};
	/**
	 * 时间插件源函数
	 * 利用必包原理只载入一次js文件,其他类似功能都可以参照此方法
	 * 需要提前引入jquery.js文件
	 * @author yangjs
	 */
	this._rcalendar = function(text, mode, refunc) {
		// 标记值 
		var temp = 0;	
		var tempMethod = function(t, m, r) {
			// 第二次调用的时候就不=0了
			if(temp == 0) {	
				// JQuery的ajax载入文件方式，如果有样式文件，同理在此引入相关样式文件
				$.getScript(THEME_URL+'/js/rcalendar.js', function() {	
					rcalendar(t, m, r);
				});
			} else {
				rcalendar(t, m, r);
			}
			temp++;
		};
		// 返回内部包函数，供外部调用并可以更改temp的值
		return tempMethod;	
	};
	/**
	 * 生成IMG的html块
	 */
	this._createImageHtml = function() {
		var _imgHtml = '';
		var _c = function() {
			if(_imgHtml == '') {
				$.post(U('public/Feed/getSmile'), {}, function(data) {
					// for(var k in data) {
					// 	_imgHtml += '<a href="javascript:void(0)" title="'+data[k].title+'" onclick="core.face.face_chose(this)";><img src="'+ THEME_URL +'/image/expression/'+data[k].type+'/'+ data[k].filename +'" width="24" height="24" /></a>';	
					// }
					// _imgHtml += '<div class="c"></div>';
					_imgHtml += '<a ;="" onclick="core.face.face_chose(this)" title="aoman" href="javascript:void(0)"><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/aoman.gif"></a><a ;="" onclick="core.face.face_chose(this)" title="baiyan" href="javascript:void(0)"><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/baiyan.gif"></a><a href="javascript:void(0)" title="bishi" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/bishi.gif"></a><a href="javascript:void(0)" title="bizui" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/bizui.gif"></a><a href="javascript:void(0)" title="cahan" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/cahan.gif"></a><a href="javascript:void(0)" title="ciya" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/ciya.gif"></a><a href="javascript:void(0)" title="da" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/da.gif"></a><a href="javascript:void(0)" title="dabing" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/dabing.gif"></a><a href="javascript:void(0)" title="daku" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/daku.gif"></a><a href="javascript:void(0)" title="danu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/danu.gif"></a><a href="javascript:void(0)" title="deyi" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/deyi.gif"></a><a href="javascript:void(0)" title="e" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/e.gif"></a><a href="javascript:void(0)" title="fadai" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/fadai.gif"></a><a href="javascript:void(0)" title="ma" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/ma.gif"></a><a href="javascript:void(0)" title="zhemo" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zhemo.gif"></a><a href="javascript:void(0)" title="zhuakuang" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zhuakuang.gif"></a><a href="javascript:void(0)" title="fendou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/fendou.gif"></a><a href="javascript:void(0)" title="fanu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/fanu.gif"></a><a href="javascript:void(0)" title="gangga" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/gangga.gif"></a><a href="javascript:void(0)" title="guzhang" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/guzhang.gif"></a><a href="javascript:void(0)" title="haha" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/haha.gif"></a><a href="javascript:void(0)" title="haixiu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/haixiu.gif"></a><a href="javascript:void(0)" title="haqian" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/haqian.gif"></a><a href="javascript:void(0)" title="tu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/tu.gif"></a><a href="javascript:void(0)" title="qinqin" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/qinqin.gif"></a><a href="javascript:void(0)" title="qioudale" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/qioudale.gif"></a><a href="javascript:void(0)" title="lenghan" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/lenghan.gif"></a><a href="javascript:void(0)" title="shuai" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/shuai.gif"></a><a href="javascript:void(0)" title="liuhan" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/liuhan.gif"></a><a href="javascript:void(0)" title="xu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/xu.gif"></a><a href="javascript:void(0)" title="yinxian" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/yinxian.gif"></a><a href="javascript:void(0)" title="yongbao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/yongbao.gif"></a><a href="javascript:void(0)" title="zuohengheng" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zuohengheng.gif"></a><a href="javascript:void(0)" title="youhengheng" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/youhengheng.gif"></a><a href="javascript:void(0)" title="wabi" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/wabi.gif"></a><a href="javascript:void(0)" title="yun" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/yun.gif"></a><a href="javascript:void(0)" title="zaijian" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zaijian.gif"></a><a href="javascript:void(0)" title="weiqu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/weiqu.gif"></a><a href="javascript:void(0)" title="weixiao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/weixiao.gif"></a><a href="javascript:void(0)" title="wen" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/wen.gif"></a><a href="javascript:void(0)" title="tiaopi" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/tiaopi.gif"></a><a href="javascript:void(0)" title="xia" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/xia.gif"></a><a href="javascript:void(0)" title="touxiao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/touxiao.gif"></a><a href="javascript:void(0)" title="shuijiao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/shuijiao.gif"></a><a href="javascript:void(0)" title="liulei" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/liulei.gif"></a><a href="javascript:void(0)" title="se" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/se.gif"></a><a href="javascript:void(0)" title="jingkong" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/jingkong.gif"></a><a href="javascript:void(0)" title="jingya" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/jingya.gif"></a><a href="javascript:void(0)" title="pizui" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/pizui.gif"></a><a href="javascript:void(0)" title="ku" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/ku.gif"></a><a href="javascript:void(0)" title="nanguo" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/nanguo.gif"></a><a href="javascript:void(0)" title="kuaikule" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/kuaikule.gif"></a><a href="javascript:void(0)" title="huaixiao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/huaixiao.gif"></a><a href="javascript:void(0)" title="keai" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/keai.gif"></a><a href="javascript:void(0)" title="kun" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/kun.gif"></a><a href="javascript:void(0)" title="kelian" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/kelian.gif"></a><a href="javascript:void(0)" title="caidao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/caidao.gif"></a><a href="javascript:void(0)" title="xigua" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/xigua.gif"></a><a href="javascript:void(0)" title="qiu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/qiu.gif"></a><a href="javascript:void(0)" title="lanqiu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/lanqiu.gif"></a><a href="javascript:void(0)" title="zuqiu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zuqiu.gif"></a><a href="javascript:void(0)" title="pingpang" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/pingpang.gif"></a><a href="javascript:void(0)" title="pijiu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/pijiu.gif"></a><a href="javascript:void(0)" title="kafei" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/kafei.gif"></a><a href="javascript:void(0)" title="fan" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/fan.gif"></a><a href="javascript:void(0)" title="zhutou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zhutou.gif"></a><a href="javascript:void(0)" title="cheer" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/cheer.gif"></a><a href="javascript:void(0)" title="dangao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/dangao.gif"></a><a href="javascript:void(0)" title="liwu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/liwu.gif"></a><a href="javascript:void(0)" title="hua" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/hua.gif"></a><a href="javascript:void(0)" title="diaoxie" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/diaoxie.gif"></a><a href="javascript:void(0)" title="kiss" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/kiss.gif"></a><a href="javascript:void(0)" title="love" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/love.gif"></a><a href="javascript:void(0)" title="xinsui" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/xinsui.gif"></a><a href="javascript:void(0)" title="taiyang" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/taiyang.gif"></a><a href="javascript:void(0)" title="yueliang" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/yueliang.gif"></a><a href="javascript:void(0)" title="shandian" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/shandian.gif"></a><a href="javascript:void(0)" title="zhadan" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zhadan.gif"></a><a href="javascript:void(0)" title="dao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/dao.gif"></a><a href="javascript:void(0)" title="kulou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/kulou.gif"></a><a href="javascript:void(0)" title="chong" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/chong.gif"></a><a href="javascript:void(0)" title="dabian" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/dabian.gif"></a><a href="javascript:void(0)" title="qiang" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/qiang.gif"></a><a href="javascript:void(0)" title="ruo" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/ruo.gif"></a><a href="javascript:void(0)" title="shengli" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/shengli.gif"></a><a href="javascript:void(0)" title="quantou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/quantou.gif"></a><a href="javascript:void(0)" title="no" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/no.gif"></a><a href="javascript:void(0)" title="ok" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/ok.gif"></a><a href="javascript:void(0)" title="peifu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/peifu.gif"></a><a href="javascript:void(0)" title="woshou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/woshou.gif"></a><a href="javascript:void(0)" title="chajin" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/chajin.gif"></a><a href="javascript:void(0)" title="gouyin" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/gouyin.gif"></a><a href="javascript:void(0)" title="tiaosheng" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/tiaosheng.gif"></a><a href="javascript:void(0)" title="tiaowu" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/tiaowu.gif"></a><a href="javascript:void(0)" title="tiao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/tiao.gif"></a><a href="javascript:void(0)" title="youtaiji" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/youtaiji.gif"></a><a href="javascript:void(0)" title="zuotaiji" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zuotaiji.gif"></a><a href="javascript:void(0)" title="zhuanquan" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/zhuanquan.gif"></a><a href="javascript:void(0)" title="xianwen" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/xianwen.gif"></a><a href="javascript:void(0)" title="feiwen" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/feiwen.gif"></a><a href="javascript:void(0)" title="fadou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/fadou.gif"></a><a href="javascript:void(0)" title="dajiao" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/dajiao.gif"></a><a href="javascript:void(0)" title="ketou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/ketou.gif"></a><a href="javascript:void(0)" title="jidong" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/jidong.gif"></a><a href="javascript:void(0)" title="huitou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/huitou.gif"></a><a href="javascript:void(0)" title="huishou" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/huishou.gif"></a><a href="javascript:void(0)" title="meng" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/meng.gif"></a><a href="javascript:void(0)" title="shenma" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/shenma.gif"></a><a href="javascript:void(0)" title="tuzi" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/tuzi.gif"></a><a href="javascript:void(0)" title="geili" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/geili.gif"></a><a href="javascript:void(0)" title="hufen" onclick="core.face.face_chose(this)" ;=""><img width="24" height="24" src="'+ THEME_URL +'/image/expression/miniblog/hufen.gif"></a><div class="c"></div>';
					$('#emot_content').html(_imgHtml);
				}, 'json');
			} else {
				$('#emot_content').html(_imgHtml);
			}
		};
		return _c;
	};
}

// 核心对象
var core = new _core();

/**
 * 核心的插件列表
 */

//分享加载文件，支持回调函数 调用方式core.loadFile(url,callback)
core.loadFile = core._coreLoadFile();
core.loadCss = core._loadCss();

/**
 * 核心插件自动生成的工厂函数
 * 这里用到了js的工厂模式等设计模式
 * 
 * 使用方法：将ｊｓ插件写到plugins/下的对应文件下，文件名必须与插件对象同名，如core.at.js
 * JS 插件里面需要有一个_init 函数，根据传入参数真正调用 init函数 
 * 
 * 如：core.plugInit('searchUser',$(this))；
 * 其中searchUser表示插件的名称是core.searchUser.js
 * $(this) 为 init的第一个参数
 * 
 * @author yangjs
 */
core.plugInit = function() {
	if(arguments.length > 0) {
		var arg = arguments;
		var back = function() {
			eval("var func = core." + arg[0] + ";");
			if("undefined" != typeof(func)) {
				func._init(arg);	
			}
		};
		var file = THEME_URL + '/js/plugins/core.' + arguments[0] + '.js';
		core.loadFile(file, back);
	}
};
//与上面方法类似 只不过可以自己写回调函数（不主动执行init）
core.plugFunc = function(plugName,callback){
	var file = THEME_URL+'/js/plugins/core.'+plugName+'.js';
	core.loadFile(file,callback);
};

/**
 * 优化setTimeout函数
 * @param func
 * @param time
 */
core.setTimeout = function(func,time){
//	var type = typeof(func);
//	if("undefined" == type){
		setTimeout(func, time);
//	}else{
//		if("string" == type){
//			eval(func);
//		}else{
//			func();
//		}
//	}	

};
// 获取对象编辑框内的可输入数字
core.getLeftNums = function(obj) {
	var str = obj.innerHTML;
	// 替换br标签
	var imgNums = $(obj).find('img').size();
	// 判断是否为空
	var _str = str.replace(new RegExp("<br>","gm"),"");	
	_str = _str.replace(/[ ]|(&nbsp;)*/g, "");
	// 判断字数是否超过，一个空格算一个字
	_str = str.replace(/<[^>]*>/g, "");		// 去掉所有HTML标签
	_str = trim(_str,' ');
	
	if(imgNums <1 ) {
		var emptyStr = _str.replace(/&nbsp;/g,"").replace(/\s+/g,"");
		if(emptyStr.length == 0) {
			return initNums;
		}
	}
	_str = _str.replace(/&nbsp;/g,"a"); 	// 由于可编辑DIV的空格都是nbsp 所以这么算

	return initNums - getLength(_str) - imgNums;
};
core.getLength = function(str, shortUrl) {
	str = str + '';
	if (true == shortUrl) {
		// 一个URL当作十个字长度计算
		return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
							.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	} else {
		return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	}
};
// 一些自定义的方法
// 生成表情图片
core.createImageHtml = core._createImageHtml();
//日期控件,调用方式 core.rcalendar(this,'full')
//this 也可以替换为具体ID,full表示时间显示模式,也可以参考rcalendar.js内的其他模式
core.rcalendar = core._rcalendar();	


//临时存储机制 适用于分割开存储的内容

core.stringDb = function(obj,inputname,tags){
    this.inputname = inputname;
    this.obj  = obj;
    if(tags != ''){
    	this.tags = tags.split(',');	
    }else{
    	this.tags = new Array();
    }
    this.taglist = $(obj).find('ul');
    this.inputhide = $(obj).find("input[name='"+inputname+"']");
};

core.stringDb.prototype = {
	init:function(){
		if(this.tags.length > 0){
			var html = '';
			for(var i in this.tags){
				if(this.tags[i] != ''){
					html +='<li><label>'+this.tags[i]+'</label><em>X</em></li>';
				}
			}
			this.taglist.html(html);
			this.bindUl();
		}
	},
	bindUl:function(){
		var _this = this;
		this.taglist.find('li').click(function(){
			_this.remove($(this).find('label').html());
		});
	},
    add:function(tag){
    	var _tag = tag.split(',');
    	var _this = this;
    	var add = function(t){
    		for(var i in _this.tags){
    			if(_this.tags[i] == t){
    				return false;
    			}
    		}
    		var html = '<li><label>'+t+'</label><em>X</em></li>';
    		_this.tags[_this.tags.length] = t;
    		_this.taglist.append(html);		
    	};	

    	for(var ii in _tag){ 
    		if(_tag[ii] != ''){
    			add(_tag[ii]);
    		}
    	}
    	
    	this.inputhide.val(this.tags.join(','));
    	this.bindUl();
    },
    remove:function(tag){
    	var del = function(arr,n){
    		if(n<0){
    			return arr;
    		}else{
    			return arr.slice(0,n).concat(arr.slice(n+1,arr.length))
    		}
    	}

    	for(var i in this.tags){
    		if(this.tags[i] == tag){
    			this.tags = del(this.tags,parseInt(i));
    			this.taglist.find('li').eq(i).remove();
    			this.inputhide.val(this.tags.join(','));
    		}
    	}
    }	
};

/*** 核心Js函数库 ***/
/**
 * 模拟TS的U函数，需要预先定义JS全局变量SITE_URL和APPNAME
 * @param string url 链接地址
 * @param array params 链接参数
 * @return string 组装后的链接地址
 */
var U = function(url, params) {
	var website = SITE_URL+'/index.php';
	url = url.split('/');
	if(url[0]=='' || url[0]=='@')
		url[0] = APPNAME;
	if (!url[1])
		url[1] = 'Index';
	if (!url[2])
		url[2] = 'index';
	website = website+'?app='+url[0]+'&mod='+url[1]+'&act='+url[2];
	if(params) {
		params = params.join('&');
		website = website + '&' + params;
	}
	return website;
};
/**
 * 窗体对象，全站使用，统一窗体接口
 */
var ui = {
	/**
	 * 浮屏显示消息，提示信息框
	 * @param string message 信息内容
	 * @param integer error 是否是错误样式，1表示错误样式、0表示成功样式
	 * @param integer lazytime 提示时间
	 * @return void
	 */
	showMessage: function(message, error, lazytime) {
		var style = (error=="1") ? "html_clew_box clew_error" : "html_clew_box";
		var ico = (error == "1") ? 'ico-error' : 'ico-ok';
		var html = '<div class="'+style+'" id="ui_messageBox" style="display:none">\
					<div class="html_clew_box_con" id="ui_messageContent">\
					<i class="'+ico+'"></i>'+message+'</div></div>';		
		var _u = function() {
			for (var i = 0; i < arguments.length; i++) {
		        if (typeof arguments[i] != 'undefined') return false;
			}
		    return true;
		};
		// 显示提示弹窗
		ui.showblackout();
		// 将弹窗加载到body后
		$(html).appendTo(document.body);
		// 获取高宽
		var _h = $('#ui_messageBox').height();
		var _w = $('#ui_messageBox').width();
		// 获取定位值
		var left = ($('body').width() - _w)/2 ;
		var top  = $(window).scrollTop() + ($(window).height()-_h)/2;
		// 添加弹窗样式与动画效果（出现）
		$('#ui_messageBox').css({
			left:left + "px",
			top:top + "px"
		}).fadeIn("slow",function(){
			$('#ui_messageBox').prepend('<iframe style="z-index:;position: absolute;visibility:inherit;width:'+_w+'px;height:'+_h+'px;top:0;left:0;filter=\'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)\'"'+
		 	'src="about:blank"  border="0" frameborder="0"></iframe>');
		});
		// 添加弹窗动画效果（消失）
		setTimeout(function() { 
			$('#ui_messageBox').find('iframe').remove();
			$('#ui_messageBox').fadeOut("fast", function() {
			  ui.removeblackout();
			  $('#ui_messageBox').remove();
			});
		} , lazytime*1000);
	},
	/**
	 * 添加弹窗
	 * @return void
	 */
	showblackout: function() {
		if($('.boxy-modal-blackout').length > 0) {
			// TODO:???
    	} else {
    		var height = $('body').height() > $(window).height() ? $('body').height() : $(window).height();
    		$('<div class ="boxy-modal-blackout" ><iframe style="z-index:-1;position: absolute;visibility:inherit;width:'+$('body').width()+'px;height:'+height+'px;top:0;left:0;filter=\'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)\'"'+
		 'src="about:blank"  border="0" frameborder="0"></iframe></div>')
        	.css({
        	    height:height+'px',width:$('body').width()+'px',zIndex: 991, opacity: 0.5
        	}).appendTo(document.body);
    	}
	},
	/**
	 * 移除弹窗
	 * @return void
	 */
	removeblackout: function() {
		if($('#tsbox').length > 0) {
		 	if(document.getElementById('tsbox').style.display == 'none'){
		 		$('.boxy-modal-blackout').remove();
		 	}	
		 } else {
		 	$('.boxy-modal-blackout').remove(); 	
		 }
	},
	/**
	 *  操作成功显示API
	 * @param string message 信息内容
	 * @param integer time 展示时间
	 * @return void
	 */
	success: function(message, time) {
		var t = "undefined" == typeof(time) ? 1 : time;
		ui.showMessage(message, 0, t);
	},
	/**
	 * 操作出错显示API
	 * @param string message 信息内容
	 * @param integer time 展示时间
	 * @return void
	 */
	error: function(message, time) {
		var t = "undefined" == typeof(time) ? 2 : time;
		ui.showMessage(message, 1, t);
	},
	/**
	 * 确认弹框显示API - 浮窗型
	 * @example
	 * 可以加入callback，回调函数
	 * @param object o 定位对象
	 * @param string text 提示语言
	 * @param string|function _callback 回调函数名称
	 * @return void
	 */
	confirm: function(o, text, _callback) {
		// 判断弹窗是否存在
		document.getElementById('ts_ui_confirm') !== null && $('#ts_ui_confirm').remove();
		var callback = "undefined" == typeof(_callback) ? $(o).attr('callback') : _callback;
		text = text || L('PUBLIC_ACCONT_TIPES');
		text = "<i class='ico-error'></i>"+text;
		this.html = '<div id="ts_ui_confirm" class="ts_confirm"><div class="layer-mini-info"><dl><dt class="txt"> </dt><dd class="action"><a class="btn-green-small mr10 btn_ok" href="javascript:void(0)"><span>'+L('PUBLIC_QUEDING')+'</span></a><a class="btn-cancel" href="javascript:void(0)"><span>'+L('PUBLIC_QUXIAO')+'</span></a></dd></dl></div></div>';
		$('body').append(this.html);
		var position = $(o).offset();
		$('#ts_ui_confirm').css({"top":position.top+"px","left":position.left+"px","display":"none"});
		$("#ts_ui_confirm .txt").html(text);
		$('#ts_ui_confirm').fadeIn("fast");
		$("#ts_ui_confirm .btn-cancel").one('click',function(){
			$('#ts_ui_confirm').fadeOut("fast");
			// 修改原因: ts_ui_confirm .btn_b按钮会重复提交
			$('#ts_ui_confirm').remove();
			return false;
		});
		$("#ts_ui_confirm .btn_ok").one('click',function(){
			$('#ts_ui_confirm').fadeOut("fast");
			// 修改原因: ts_ui_confirm .btn_b按钮会重复提交
			$('#ts_ui_confirm').remove();
			if("undefined" == typeof(callback)){
				return true;
			}else{
				if("function"==typeof(callback)){
					callback();
				}else{
					eval(callback);
				}
			}
		});
		$('body').bind('keyup', function(event) {
            $("#ts_ui_confirm .btn_ok").click();
        });
		return false;
	},
	/**
	 * 确认框显示API - 弹窗型
	 * @param string title 弹窗标题
	 * @param string text 提示语言
	 * @param string|function _callback 回调函数名称
	 * @return void
	 */
	confirmBox: function(title, text, _callback) {
		this.box.init(title);
		text = text || L('PUBLIC_ACCONT_TIPES');
		text = "<i class='ico-error'></i>"+text;

		var content = '<div class="pop-create-group"><dl><dt class="txt">'+ text + '</dt><dd class="action"><a class="btn-green-small mr10" href="javascript:void(0)"><span>'+L('PUBLIC_QUEDING')+'</span></a><a class="btn-cancel" href="javascript:void(0)"><span>'+L('PUBLIC_QUXIAO')+'</span></a></dd></dl></div>';

		this.box.setcontent(content);
		this.box.center();

		var callback = "undefined" == typeof(_callback) ? $(o).attr('callback') : _callback;

		var _this = this;
		$("#tsbox .btn-cancel").one('click',function(){
			$('#tsbox').fadeOut("fast",function(){
				$('#tsbox').remove();	
			});
			_this.box.close();
			return false;
		});
		$("#tsbox .btn-green-small").one('click',function(){
			$('#tsbox').fadeOut("fast",function(){
				$('#tsbox').remove();
			});
			_this.box.close();
			if("undefined" == typeof(callback)){
				return true;
			}else{
				if("function"==typeof(callback)){
					callback();
				}else{
					eval(callback);
				}
			}
		});
		return false;
	},
	/**
	 * 私信弹窗API
	 * @param string touid 收件人ID
	 * @return void
	 */
	sendmessage: function(touid, editable) {
		if(typeof(editable) == "undefined" ) {
			editable = 1;
		}
		touid = touid || '';
		this.box.load(U('public/Message/post')+'&touid='+touid+'&editable='+editable, L('PUBLIC_SETPRIVATE_MAIL'));
	},
	/**
	 * @Me弹窗API
	 * @param string touid @人ID
	 * @return void
	 */
	sendat: function(touid) {
		touid = touid || '';
		this.box.load(U('public/Mention/at')+'&touid='+touid, '@TA');
	},
	/**
	 * 弹窗发布分享
	 * @param string title 弹窗标题
	 * @param string initHTML 插入内容
	 * @return void
	 */
	sendbox: function(title, initHtml,channelID) {
		if($.browser.msie) {
			initHtml = encodeURI(initHtml);
		}
		initHtml = initHtml.replace(/\#/g, "%23"); 
		this.box.load(U('public/Index/sendFeedBox')+'&initHtml='+initHtml+'&channelID='+channelID, title,function(){
			$('#at-view').hide();
		});
	},
	/**
	 * 回复弹窗API
	 * @param integer comment_id 评论ID
	 * @return void
	 */
	reply: function(comment_id) {
		this.box.load(U('public/Comment/reply')+'&comment_id='+comment_id,L('PUBLIC_RESAVE'),function (){$('#at-view').hide();});
	},
	groupreply: function(comment_id,gid) {
		this.box.load(U('group/Group/reply')+'&gid='+gid+'&comment_id='+comment_id,L('PUBLIC_RESAVE'),function (){$('#at-view').hide();});
	},
	/**
	 * 选择部门弹窗API - 暂不使用
	 */
	changeDepartment: function(hid,showname,sid,nosid,notop) {
		this.box.load(U('widget/Department/change')+'&hid='+hid+'&showName='+showname+'&sid='+sid+'&nosid='+nosid+'&notop='+notop,L('PUBLIC_DEPATEMENT_SELECT'));
	},
	/**
	 * 自定弹窗API接口
	 */
	box: {
		WRAPPER: '<div class="wrap-layer" id="tsbox" style="display:none">\
     			  <div class="content-layer">\
     			  <div class="layer-content" id="layer-content"></div>\
     			  </div></div>',
		inited: false,
		IE6: $.os.ie,
		init: function(title, callback) {
			this.callback = callback;
			// 弹窗中隐藏小名片
			if("undefined" != typeof(core.facecard)){
				core.facecard.dohide();
			}

			if($('#tsbox').length >0) {
				return false;
			} else {
				$('body').prepend(this.WRAPPER);
			}
			var url = THEME_URL+'/js/tbox/box.css';
			core.loadCss(url);
			// 添加头部
			if("undefined" != typeof(title)) {
				$("<div class='hd'>"+title+"<a class='ico-close' href='#'></a></div>").insertBefore($('#tsbox .layer-content'));
			}
			
			ui.showblackout();
			
			$('#tsbox').stop().css({width: '', height: ''});
			// 添加键盘事件
			jQuery(document.body).bind('keypress.tsbox', function(event) {
				var key = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
				if (key == 27) {
					jQuery(document.body).unbind('keypress.tsbox');
					ui.box.close(callback);
					return false;
				}
			});
			// 关闭弹窗，回调函数
			$('#tsbox').find('.ico-close').click(function() {
				ui.box.close(callback);
				return false;
			});
			
			this.center();
			var show = function(){
				$('#tsbox').show();
			}
			setTimeout(show, 200);
			
			$('#tsbox').draggable({ handle: '.hd' });

			$('.hd').mousedown(function(){
				$('.mod-at-wrap').remove();
			});
		},
		/**
		 * 设置弹窗中的内容
		 * @param string content 内容信息
		 * @return void
		 */
		setcontent: function(content) {
			$('#layer-content').html(content);
		},
		/**
		 * 关闭窗口
		 * @param function fn 回调函数名称
		 * @return void
		 */
		close: function(fn) {
			// $('body').css({'overflow': ''});

			$('#ui-fs .ui-fs-all .ui-fs-allinner div.list').find("a").die("click");

			// 关闭弹窗，同步弹窗同步消失
			var $sync = $('#Sync');
			if (typeof $sync[0] !== 'undefined') {
				$sync.hide();
			}

			$('.talkPop').remove();
			if (core.multimage != undefined) {
				core.multimage.removeDiv();
			}
			$('#tsbox').remove();
			$('.mod-at-wrap').remove();
			jQuery('.boxy-modal-blackout').remove();
			var back ='';
			if("undefined" != typeof(fn)){
			 	back = fn;
			}else if("undefined" != typeof(this.callback)){
				back = this.callback;
			}
			if("function" == typeof(back)){
				back();
			}else{
				eval(back);
			}
		},
		/**
		 * 提示弹窗
		 * @param string data 信息数据
		 * @param string title 标题信息
		 * @param function callback 回调函数
		 * @return void
		 */
		alert:function(data,title,callback){
			this.init(title,callback);
			this.setcontent('<div class="question">'+data+'</div>');
			this.center();
		},
		/**
		 * 显示弹窗
		 * @param string content 信息数据
		 * @param string title 标题信息
		 * @param function callback 回调函数
		 * @return void
		 */
		show:function(content,title,callback){
			this.close();
			this.init(title,callback);
			this.setcontent(content);
			this.center();
		},
		/**
		 * 载入弹窗API
		 * @param string requestUrl 请求地址
		 * @param string title 弹窗标题
		 * @param string callback 窗口关闭后的回调事件
		 * @param object requestData requestData
		 * @param string type Ajax请求协议，默认为GET
		 * @return void
		 */
		load:function(requestUrl,title,callback,requestData,type) {
			$('#tsbox').remove();
			this.init(title,callback);
			if("undefined" != typeof(type)) {
				var ajaxType = type;
			}else{
				var ajaxType = "GET";
			}
			this.setcontent('<div style="width:150px;height:70px;text-align:center"><div class="load">&nbsp;</div></div>');
			var obj = this;
			if("undefined" == requestData) {
				var requestData = {};
			}
			jQuery.ajax({url:requestUrl,
				type:ajaxType,
				data:requestData,
				cache:false,
				dataType:'html',
				success:function(html){
					obj.setcontent(html);
					obj.center();
					// $('body').css({'overflow': 'hidden'});
				}
			});
		},	
		/**
		 * 弹窗定位
		 * @return void
		 */
		_viewport: function() {
			var d = document.documentElement, b = document.body, w = window;
			return jQuery.extend(
				jQuery.browser.msie ? { left: b.scrollLeft || d.scrollLeft, top: b.scrollTop || d.scrollTop } : { left: w.pageXOffset, top: w.pageYOffset },
				!ui.box._u(w.innerWidth) ? { width: w.innerWidth, height: w.innerHeight } : (!ui.box._u(d) && !ui.box._u(d.clientWidth) && d.clientWidth != 0 ? { width: d.clientWidth, height: d.clientHeight } : { width: b.clientWidth, height: b.clientHeight }) );
		},
		/**
		 * 验证参数
		 * @return void
		 */
		_u: function() {
			for(var i = 0; i < arguments.length; i++) {
				if(typeof arguments[i] != 'undefined') return false;
			}
			return true;
		},
		/**
		 * 样式覆盖
		 * @return void
		 */
		_cssForOverlay: function() {
			if (ui.box.IE6) {
				return ui.box._viewport();
			} else {
				return {width: '100%', height: jQuery(document).height()};
			}
		},
		/**
		 * 中间定位
		 * @param string axis 横向，纵向
		 * @return void
		 */
		center: function(axis) {
			var v = ui.box._viewport();
			var o =  [v.left, v.top];
			if (!axis || axis == 'x') this.centerAt(o[0] + v.width / 2 , null);
			if (!axis || axis == 'y') this.centerAt(null, o[1] + v.height / 2);
			return this;
		},
		/**
		 * 横向移动
		 * @param integer x 数值
		 * @return void
		 */
		moveToX: function(x) {
			if (typeof x == 'number') $('#tsbox').css({left: x});
			else this.centerX();
			return this;
		},
		/**
		 * 纵向移动
		 * @param integer y 数值
		 * @return void
		 */
		moveToY: function(y) {
			if (typeof y == 'number') $('#tsbox').css({top: y});
			else this.centerY();
			return this;
		},      
		centerAt: function(x, y) {
			var s = this.getSize();
			var xval = x - s[0] / 2;
			var yval = y - s[1] / 2;
			if (s[1] > $(window).height()) {
				yval = 50;
			}
			if (typeof x == 'number') this.moveToX(xval);
			if (typeof y == 'number') this.moveToY(yval);
			return this;
		},
		centerAtX: function(x) {
			return this.centerAt(x, null);
		},
		centerAtY: function(y) {
			return this.centerAt(null, y);
		},
		getSize: function() {
			return [$('#tsbox').width(), $('#tsbox').height()];
		},
		getContent: function() {
			return $('#tsbox').find('.boxy-content');
		},
		getPosition: function() {
			var b = $('#tsbox');
			return [b.offsetLeft, b.offsetTop];
		},        
		getContentSize: function() {
			var c = this.getContent();
			return [c.width(), c.height()];
		},
		_getBoundsForResize: function(width, height) {
			var csize = this.getContentSize();
			var delta = [width - csize[0], height - csize[1]];
			var p = this.getPosition();
			return [Math.max(p[0] - delta[0] / 2, 0), Math.max(p[1] - delta[1] / 2, 0), width, height];
		}
	}
};

$(function() {
	var $main = $('div.line-b-animate').offset();
	var $currentChecked = $('div.line-b-animate ul').find('li.current').offset();
	if ($main && $currentChecked !== null) {
		var mainLeft = $main.left;
		// init
		var $line = $('<div class="line-b"></div>');
		var $current = $('div.line-b-animate ul').find('li.current');
		var left = $current.offset().left - mainLeft;
		var width = $current.width();
		$line.css({width:width,left:left,overflow:'hidden'});
		$('div.line-b-animate').append($line);
		
		// mouseover event li
		$('div.line-b-animate').find('li').each(function(i, n) {
			$(this).bind('mouseover', function() {
				var left = $(this).offset().left - mainLeft;
				var width = $(this).width();
				animateTab(left, width);
			});
		});

		// mouseout event ul
		$('div.line-b-animate').find('ul').bind('mouseout', function(i) {
			var $li = $(this).find('li.current');
			var left = $li.offset().left - mainLeft;
			var width = $li.width();
			animateTab(left, width);
		});

		// animate method
		var animateTab = function (left, width) {
			$line.stop();
			$line.animate({
				width: width,
				left: left
			}, 'fast');
		};
	}
});
