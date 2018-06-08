(function($) {
	var jspath = $('script').last().attr('src');
	if (jspath.indexOf('/') != -1) {
		basepath += jspath.substr(0, jspath.lastIndexOf('/') + 1);
	}
	$.fn.fixpng = function(options) {
		function _fix_img_png(el, emptyGIF) {
			var images = $('img[src*="png"]', el || document),
			png;
			images.each(function() {
				png = this.src;
				width = this.width;
				height = this.height;
				this.src = emptyGIF;
				this.width = width;
				this.height = height;
				this.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + png + "',sizingMethod='scale')";
			});
		}
		function _fix_bg_png(el) {
			var bg = $(el).css('background-image');
			if (/url\([\'\"]?(.+\.png)[\'\"]?\)/.test(bg)) {
				var src = RegExp.$1;
				$(el).css('background-image', 'none');
				$(el).css("filter", "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "',sizingMethod='scale')");
			}
		}
		if ($.browser.msie && $.browser.version < 7) {
			return this.each(function() {
				var opts = {
					scope: '',
					emptyGif: basepath + 'blank.gif'
				};
				$.extend(opts, options);
				switch (opts.scope) {
				case 'img':
					_fix_img_png(this, opts.emptyGif);
					break;
				case 'all':
					_fix_img_png(this, opts.emptyGif);
					_fix_bg_png(this);
					break;
				default:
					_fix_bg_png(this);
					break;
				}
			});
		}
	}
})(jQuery);
function copyfromlist(my) {
	var copyculmnid = $("select[name='copyculmnid']").val();
	if (copyculmnid == 0) {
		alert(user_msg['jsx3']);
		$("select[name='copyculmnid']").focus();
	} else {
		var urls = my.attr('href') + '&copyculmnid=' + copyculmnid;
		$.ajax({
			url: urls,
			type: "POST",
			success: function(data) {
				if (data == 'metinfo') {
					alert(user_msg['jsx4']);
					my.after("<span class='color390'>" + user_msg['jsx4'] + "</span>");
					my.remove();
				}
			}
		});
	}
	return false;
}
function metHeight(group) {
	tallest = 0;
	group.each(function() {
		thisHeight = $(this).height();
		if (thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}
function ifreme_methei() {
	var m = $("body").height();
	var l = $(window.parent.document).height() - 63;
	l = m > l ? m: l;
	if (m < 700 && l > 700) l = 700;
	$('#metleft', parent.document).height(l);
	$('#metright', parent.document).height(l);
	$(window.parent.document).find("#main").height(l);
	var n = l - 10 - 25 - 35;
	$('#leftnav', parent.document).height(n);
}
function met_ckeditor(depthm, name, type) {
	$("textarea[name='" + name + "']").before('<div id="linzai_' + name + '">' + user_msg['jsx5'] + '</div>');
	var wi = $(".ckeditormetbox");
	var width = '';
	if (wi.length > 0) {
		width = wi.width();
	}
	var config = {};
	config.filebrowserBrowseUrl = depth + '../ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = depth + '../ckfinder/ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl = depth + '../ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = depth + '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = depth + '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = depth + '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	if (type == 1) {
		config.toolbar_Full = [['FontSize', 'Bold', 'TextColor', 'Link', 'Unlink', 'Image', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Source']];
		config.height = 160;
		config.width = '80%';
		config.enterMode=2;
	} else if (type == 2) {
		config.toolbar_Full = [['FontSize', 'Bold', 'TextColor', 'Link', 'Unlink', 'Image', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Source']];
		config.height = 100;
		config.width = '80%';
		config.enterMode=2;
	} else {
		config.height = 400;
		config.width = width;
	}
	CKEDITOR.replace(name, config);
	CKEDITOR.on('instanceReady',
	function() {
		$('#linzai_' + name).remove();
		ifreme_methei();
		wi.find('iframe').css('width', width + 'px');
	});
}
function changelocation_contents(locationid, classtype) {
	changelocation1(locationid, classtype);
	var onecount1=onecount+1;
	var onecount2=onecount+2;
	if (classtype == 1) {
		if(document.myform.class2.length>1){
			document.myform.class2.options[0] = new Option(lev[onecount][0], lev[onecount][2]);
			$('#class2select').show();
		}else{
			document.myform.class2.options[0] = new Option(lev[onecount2][0], lev[onecount2][2]);
			$('#class2select').hide();
		}
		$.ajax({
			url: '../paralist.php?lang='+$("[name='lang']").val()+'&id='+$("[name='id']").val()+'&module='+$("[name='module']").val()+'&class1='+$('#class1select').val(),
			type: "GET",
			success: function(data) {
			//alert(data);

				$("[name='paralist']").each(function(){
					$(this).remove();
				});
				$('#parastart').after(data);
				ifreme_methei();
			}
		});
	}
	if(document.myform.class3.length>1){
		document.myform.class3.options[0] = new Option(lev[onecount1][0], lev[onecount1][2]);
		$('#class3select').show();
	}else{
		document.myform.class3.options[0] = new Option(lev[onecount2][0], lev[onecount2][2]);
		$('#class3select').hide();
	}
	changepower(classtype);
}
function changepower(classtype){
    var lang=$("[name='lang']").val();
	var acesss_contents=$("#acesss_contents").val();
	var i;
	var levev;
	var classnow;
	//classnow=$('#class3select').val()?$('#class3select').val():($('#class2elect').val()?$('#class2select').val():$('#class1select').val());
	if(classtype==1){classnow=$('#class1select').val();}
	if(classtype==2){classnow=$('#class2select').val();}
	if(classtype==3){classnow=$('#class3select').val();}
	for(i=0;i<onecount;i++){
		if(lev[i][2]==classnow){
			levev=lev[i][3];
		}
	}
	//alert($(this).val());
	$.ajax({
		url: '../access.php?lang='+lang+'&depth=1&action=js&lev='+levev+'&acesss_contents='+acesss_contents,
		type: "GET",
		success: function(data) {
			$('#access').html(data);
		}
	});
	if($('#acesss_contents_dl').val()){
		var acesss_contents_dl=$('#acesss_contents_dl').val();
		$.ajax({
		url: '../access.php?lang='+lang+'&depth=1&action=js&lev='+levev+'&acesss_contents='+acesss_contents_dl,
		type: "GET",
		success: function(data) {
			$('#downloadaccess').html(data);
		}
	});
	}
	
}
function changelocation1(locationid, classtype) {
	var locationid = locationid;
	var classtype = classtype;
	var i;
	if (classtype == 1 && document.myform.class2.length > 1) {
		document.myform.class2.length = 1;
		document.myform.class3.length = 1;
	}
	if (classtype == 2 && document.myform.class3.length > 1) document.myform.class3.length = 1;
	for (i = 0; i < onecount; i++){
		if (lev[i][1] == locationid){
			if (classtype == 1) document.myform.class2.options[document.myform.class2.length] = new Option(lev[i][0], lev[i][2]);
			else document.myform.class3.options[document.myform.class3.length] = new Option(lev[i][0], lev[i][2]);
		}
	}
}
function htmxun(json, my, k, data, tp) {
	if (my) {
		//var nxt = my.next('span.tips');
		var nxt = tp==2?my.next('span.tips'):$('#htmlloading .listbox');
	}
	var txtt = tp==2?'[' + json.length + '/' + (k + 1) + '] ':'';
	var fodname = json[k].split('/');
	var fname = json[k].split('&html_filename=');
	fname = fname[1].split('&metinfonow=');
	fname[0] = unescape(fname[0]);
	var finame = !fodname[1] ? fname[0] + hz: fodname[0] + '/' + fname[0] + hz;
	if(tp==2){
		var chengg = data == 0 ? user_msg['jsx6'] : user_msg['jsx31'];
		txtt += finame + ' ' + chengg;
	}else{
		var chengg = data == 0 ? '<span class="ok">'+user_msg['jsx6']+'</span>' : '<span class="err">'+user_msg['jsx31']+'</span>';
		txtt += chengg + ' ' +'<a href="'+weburl+finame+'" target="_blank">/'+finame+'</a>';
	}
	if (my) {
		if(tp!=2)$('#htmlloading h3').html('<img src="' + metimgurl + 'loadings.gif" style="position:relative; top:3px;" /> ' +user_msg['js54']+'[' + json.length + '/' + (k + 1) + ']');
		nxt.prepend('<div class="xlist">'+txtt+'</div>');
	}
	if (k == json.length - 1) {
		if (tp != 1 && tp != 2){
			$('#htmlloading h3').html('<span class="ok">'+user_msg['js53']+'[' + json.length + '/' + (k + 1) + ']'+'</span>');
			alert(user_msg['js53']);
		}
		if (my) {
			my.css('color', '#FF34B3');
			if(tp==2)nxt.empty();
		}
		hl = 0;
		if (tp == 1) {
			$('#htmlloading h3').html('<span class="ok">'+user_msg['js53']+'[' + json.length + '/' + (k + 1) + ']'+'</span>');
			location.href = 'htm.php?lang=' + lang + '&action=htmzip';
		}
		if (tp == 2) turnaphtm();
	} else {
		k++;
		generatehtm(json, my, k, tp);
	}
}
function generatehtm(json, my, y, tp) {
	var k = y ? y: 0;
	var fd = json[k].split('&');
	for (var x = 0; x < fd.length; x++) {
		if ((fd[x]).indexOf('html_filename') != -1) {
			var fx = fd[x].split('=');
			fx = fx[1];
		}
	}
	var cz = json[k].split('html_filename=');
	var nm = cz[1].split('&metinfonow');
	json[k] = cz[0] + 'html_filename=' + escape(fx) + '&metinfonow' + nm[1];
	$.ajax({
		url: '../../' + json[k],
		type: "POST",
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		error: function() {
			data = user_msg['jsx9'];
			htmxun(json, my, k, data, tp);
		},
		success: function(data) {
			if (data != 1 && data != 2 && data != 0) data = user_msg['jsx10'];
			htmxun(json, my, k, data, tp);
		}
	});
}
function methtml(my, tp) {
	var nxt = $('#htmlloading h3');
		$('#htmlloading .listbox').empty();
	if (hl == 0) {
		nxt.empty();
		nxt.append('<img src="' + metimgurl + 'loadings.gif" style="position:relative; top:3px;" />' + user_msg['jsx11']);
		hl = 1;
		$.ajax({
			url: my.attr('href'),
			type: "POST",
			success: function(data) {
				if (data == 0) {
					alert(user_msg['jsx12']);
					nxt.empty();
					hl = 0;
				} else {
					var json = eval(data);
					generatehtm(json, my, '', tp);
				}
			}
		});
	} else {
		alert(user_msg['jsx13']);
	}
	return false;
}
function changes(th) {
	location.href = th.val();
}
function changelocation(my) {
	document.myform.class3.length = 1;
	var locationid = my.val();
	if (my.val() != 0) {
		var i,
		lev;
		for (i = 0; i < subcat.length; i++) {
			if (subcat[i][2] == locationid)
			 lev = subcat[i][3];
			if (subcat[i][1] == locationid) {
				document.myform.class3.options[document.myform.class3.length] = new Option(subcat[i][0], subcat[i][2]);
			}
		}
		document.myform.access.length = 0;
		if (lev == "all") lev = "0";
		switch (parseInt(lev)) {
		case 0:
			document.myform.access.options[document.myform.access.length] = new Option(user_msg['js28'], 'all');
		case 1:
			document.myform.access.options[document.myform.access.length] = new Option(user_msg['js29'], '1');
		case 2:
			document.myform.access.options[document.myform.access.length] = new Option(user_msg['js30'], '2');
		case 3:
			document.myform.access.options[document.myform.access.length] = new Option(user_msg['js31'], '3');
		}
	}
}
function changelocation2(my) {
	var locationid = my.val();
	var i;
	var lev;
	for (i = 0; i < onecount; i++)
	 {
		if (subcat[i][2] == locationid)
		 lev = subcat[i][3];
	}
	document.myform.access.length = 0;
	if (lev == "all") lev = "0";
	switch (parseInt(lev))
	 {
	case 0:
		document.myform.access.options[document.myform.access.length] = new Option(user_msg['js28'], 'all');
	case 1:
		document.myform.access.options[document.myform.access.length] = new Option(user_msg['js29'], '1');
	case 2:
		document.myform.access.options[document.myform.access.length] = new Option(user_msg['js30'], '2');
	case 3:
		document.myform.access.options[document.myform.access.length] = new Option(user_msg['js31'], '3');
	}
}
function seachinput(my, txt) {
	my.focus(function() {
		if ($(this).val() == txt) $(this).val('');
	});
	my.focusout(function() {
		if ($(this).val() == '') $(this).val(txt);
	});
}
function cparinc(m, f, t) {
	var g = 0;
	m.nextAll().find("option").remove("option[value!='']");
	m.nextAll().hide();
	if (m.val() != '') {
		var next = m.next('select');
		for (k = 0; k < cplang.length; k++) {
			if (cplang[k][1] == m.val()) {
				next.append("<option value='" + cplang[k][2] + "'>" + cplang[k][0] + "</option>");
				g = 1;
			}
		}
		if (g == 1) {
			next.show();
		} else {
			if (m.attr('name') == 'copylang' || m.attr('name') == 'movelang') {
				next.hide();
				alert(user_msg['jsx14']);
				m.find("option[value='']").attr('selected', true);
			} else {
				var id = $("input[name='id']");
				var all = f.find("input[name='allid']");
				allid(id, all);
				for (k = 0; k < cplang.length; k++) {
					if (cplang[k][1] == m.val() || cplang[k][2] == m.val()) f.find("input[name='access']").val(cplang[k][3]);
				}
				if (all.val() == '') {
					Problem(user_msg['js23']);
				} else {
					if (linkSmit('', 1, t)) f.submit();
				}
			}
		}
	}
}
function modifyview(modify) {
	if (modify != '') {
		var modifytr = $("input[value='" + modify + "']").parents("tr");
		modifytr.addClass('modify');
		$("input[value='" + modify + "']").focus();
		setTimeout(function() {
			modifytr.removeClass('modify');
		},
		5000);
	}
}
function metuploadify(id, type, ureturn, module, wate, fileExt, fileDesc) {
	$(id).wrap("<div class='file_uploadfrom'></div>");
	$(id).parent().wrap("<div class='metuplaodify'></div>");
	$(id).parent().after("<a href='javascript:;' title='" + user_msg['jsx15'] + "' class='upbutn round'>" + user_msg['jsx15'] + "</a>");
	if (module != 67) $(id).parent().parent().after("<span class='uptips'></span>");
	var img_url = metimgurl + 'js/uploadify/uploadify.swf';
	var depths = depth + '../include/uploadify.php';
	var tips = module == 67 ? $('#uptips-' + ureturn) : $(id).parent().parent().next('.uptips');
	$(id).uploadify({
		'uploader': img_url,
		'script': depths,
		'hideButton': true,
		'auto': true,
		'fileExt': fileExt,
		'fileDesc': fileDesc,
		'height': '25',
		'scriptData': {
			'type': type,
			'wate': wate,
			'module': module,
			'metinfo_admin_id': metinfo_admin_id,
			'metinfo_admin_pass': metinfo_admin_pass,
			'lang': lang
		},
		'onComplete': function(event, queueId, fileObj, response, data) {
			uponComplete(response, ureturn, tips, module,type);
		},
		'onProgress': function(event, ID, fileObj, data) {
			$("input[type='submit']").attr("disabled", true);
			tips.html('<img src="' + metimgurl + 'loadings.gif" style="position:relative; top:5px;" /> ' + user_msg['jsx16'] + '(' + data.percentage + ' %)');
			return false;
		}
	});
	$("div.file_uploadfrom").css("opacity", "0");
	if (module == 67) $("div.metuplaodify").css("opacity", "0");
}
function uponComplete(response, path, dom, module,type) {
	var res = response.split('$'),
	t;
	var text = res[0] == 1 ? user_msg['jsx17'] : response;
	dom.empty();
	alert(text);		
	$("input[type='submit']").removeAttr('disabled');
	if (path.indexOf('csvup.php?lang=') != -1) {
		var yx = res[1].split('|');
		var csvname = encodeURIComponent(yx[0]);
		var fileField = encodeURIComponent(yx[1]);
		location.href = path + '&flienamecsv=' + csvname + '&fileField=' + fileField;
	}
	if (res[1].indexOf('|') == -1) {
		$("input[name='" + path + "']").val(res[1]);

		if (module == 67) {
			$("#" + path).attr('src', '../../' + res[1]);
			var pc = path.split('-');
			var dis = $("input[name='dis-" + pc[1] + "']");
			var alu = dis.val().split('|');
			var nt = '';
			for (var i = 0; i < alu.length; i++) {
				k = i + 1;
				if (k == pc[2]) {
					var xm = alu[i].split('*');
					nt += i == (alu.length - 1) ? xm[0] + '*' + res[1] : xm[0] + '*' + res[1] + '|';
				} else {
					nt += i == (alu.length - 1) ? alu[i] : alu[i] + '|';
				}
			}
			dis.val(nt);
			t = setTimeout(function() {
				dom.empty();
			},
			2000);
		}
	} else {
	
		var yx = res[1].split('|');
		var ph = path.split('-');
		if (ph[1].indexOf('thumbold') != -1) {
			$("#" + ph[1]).attr('src', '../../' + yx[1]);
			t = setTimeout(function() {
				dom.empty();
			},
			2000);
		}
		$("input[name='" + ph[0] + "']").val(yx[0]);
		$("input[name='" + ph[1] + "']").val(yx[1]);
	}
	if(type=='sql'){
		window.location.reload();
	}
}
function metsubgeturl(my) {
	window.open(my.attr('url'));
	return false;
}
function showclass(id) {
	var dom = $('[id^=class_' + id + '_]');
	dom.is(':hidden') ? dom.show() : dom.hide();
	ifreme_methei();
}
function emailtest(met, id) {
	var usename = $("input[name='met_fd_usename']").val();
	var smtp = $("input[name='met_fd_smtp']").val();
	var password = $("input[name='met_fd_password']").val();
	var fromname = $("input[name='met_fd_fromname']").val();
	var aurl = met.attr('href');
	$('#emailtest').empty();
	$('#emailtest').append(user_msg['jsx18']);
	$.ajax({
		url: aurl,
		type: "POST",
		data: { 'usename': usename, 'smtp': smtp ,'password':password,'fromname':fromname },
		timeout: 30000,
		error: function(dom, text, errors) {
			if (text == 'timeout'){
				$('#emailtest').empty();
				$('#emailtest').append(user_msg['jsx19']);
			}
		},
		success: function(data) {
			$('#emailtest').empty();
			$('#emailtest').append(data);
		}
	});
	return false;
}
function visittype(type, sct, url) {
	location.href = url + '&' + type + '=' + sct;
}
function getchangexml(type, st, et) {
	var wdth = $('#charttd').width() - 20;
	var heth = '220';
	var chart = new FusionCharts(metimgurl + "/Chart/swf/FCF_Column2D.swf", "ChartId", wdth, heth);
	chart.setDataURL("data.php?action=index-all%26st=" + st + "%26et=" + et + "%26dtype=" + type + '%26lang=' + lang);
	chart.render("chartContainer");
}
function onlineposition(onlineid, lng) {
	$('#onlineleft' + lng).hide();
	$('#onlineright' + lng).hide();
	if (onlineid < 2) {
		$('#onlineleft' + lng).show();
	} else if (onlineid != 3) {
		$('#onlineright' + lng).show();
	} else {
		$('#onlineleft' + lng).hide();
		$('#onlineright' + lng).hide();
	}
}
function closediv(dom) {
	$('#metboard').remove();
	$('#' + dom).hide();
}
function metboard() {
	$('#metboard').remove();
	$('body').append('<div id="metboard"></div>');
	$('#metboard').height($(window).height());
	$('#metboard').css({
		'opacity': 0.1,
		'position': 'absolute',
		'left': '0px',
		'top': '0px',
		'z-index': 99,
		'width': '100%',
		'background': '#000'
	});
}
function divshow(dom) {
	var dom = $('#' + dom);
	metboard();
	var pinx = (820 - 600) / 2;
	var piny = ($(window).height() - dom.height()) / 3;
	if (piny < 0) piny = 0;
	if (pinx < 0) pinx = 0;
	dom.css('left', pinx + 'px');
	dom.css('top', piny + 'px');
	dom.show();
}
function okonlineqq(type) {
	var hz = type == 'msn' || type == 'skype' ? '.gif': '.jpg';
	$('#met' + type + 'img').attr('src', '../../../public/images/' + type + '/' + type + '_' + $("input[name='met_" + type + "_type']:checked").val() + hz);
	type = 'online_box_' + type;
	closediv(type);
}
function sitemp(url) {
	if ($('#sitemap').html() == '') {
		$.ajax({
			url: url,
			type: "POST",
			data: '',
			success: function(data) {
				$('#sitemap').empty();
				$('#sitemap').append(data);
			}
		});
	}
	if ($('#sitemap').is(':hidden')) {
		$('#sitemap').show();
	} else {
		$('#sitemap').hide();
	}
}
function olupdate(id, ver, action) {
	var addr = $('#addr_' + id).val();
	var url_r = $('#met_url_r').val() + 'olid=' + id;
	var url_h = $('#met_url_h').val() + 'olid=' + id + '&ver=' + ver + '&addr=' + addr;
	if ($('#met_url_p').val() == 1) {
		url_r += '&met_code=' + $('#met_code').val() + '&met_key=' + $('#met_key').val();
	}
	if ($('#met_url_p').val() == 1 && !(action == "test" || action == "testc")) {
		url_h += '&checksum=' + $('#checksum_' + id).val();
	}
	if (action == "test" || action == "testc") {
		if (action == "testc") {
			url_h += '&ver=' + ver + '&test=continue';
			$('#ver_now_' + id).val(ver);
		}
		 else {
			url_h += '&ver=' + $('#ver_now_' + id).val();
		}
		$('#oltest_' + id).empty();
		$('#oltest_' + id).append(user_msg['jsx20']);
		$('#ver_to_' + id).val(ver);
		url_h += '&action=check';
		setTimeout(function() {
			$.ajax({
				url: url_h,
				type: 'POST',
				data: '',
				success: function(result) {
					result = result.split("|");
					result[0] = $.trim(result[0]);
					if (result[0] != "nohost") {
						$('#oltest_' + id).empty();
						$('#addr_' + id).val(result[2]);
						$('#oltest_' + id).append(result[1]);
						$('#checksum_' + id).val(result[3]);
					}
					 else {
						$('#oltest_' + id).empty();
						$('#oltest_' + id).append(user_msg['jsx21']);
					}
				}
			});
		},
		500);
	}
	 else if (action == 'dateback') {
		$('#oltest_' + id).empty();
		$('#oltest_' + id).append(user_msg['jsx22']);
		url_h += '&action=dateback';
		if($('#date_back').val()==0){
			url_h += '&date_back=0';
			$('#date_back').val(1);
		}else{
			url_h += '&date_back=1';
		}
		setTimeout(function() {
			$.ajax({
				url: url_h,
				type: 'POST',
				data: '',
				success: function(result) {
					$('#oltest_' + id).empty();
					$('#oltest_' + id).append(result);
				}
			});
		},
		500);
	}
	 else if (action == 'datebackall') {
		$('#oltest_' + id).empty();
		$('#oltest_' + id).append(user_msg['jsx22']);
		url_h += '&action=datebackall';
		setTimeout(function() {
			$.ajax({
				url: url_h,
				type: 'POST',
				data: '',
				success: function(result) {
					$('#oltest_' + id).empty();
					$('#oltest_' + id).append(result);
				}
			});
		},
		500);
	}
	 else if (action == 'dirpower') {
		$('#oltest_' + id).empty();
		$('#oltest_' + id).append(user_msg['jsx23']);
		url_h += '&action=dirpower';
		setTimeout(function() {
			$.ajax({
				url: url_h,
				type: 'POST',
				data: '',
				success: function(result) {
					$('#oltest_' + id).empty();
					$('#oltest_' + id).append(result);
				}
			});
		},
		500);
	}
	 else if (action == 'down') {
		$('#oltest_' + id).empty();
		$('#oltest_' + id).append(user_msg['jsx24']);
		url_h += '&action=down';
		setTimeout(function() {
			$.ajax({
				url: url_h,
				type: 'POST',
				data: '',
				success: function(result) {
					$('#oltest_' + id).empty();
					$('#oltest_' + id).append(result);
				}
			});
		},
		500);
	}
	 else if (action == 'sql') {
		$('#oltest_' + id).empty();
		$('#oltest_' + id).append(user_msg['jsx25']);
		url_h += '&action=sql';
		setTimeout(function() {
			$.ajax({
				url: url_h,
				type: 'POST',
				data: '',
				success: function(result) {
					$('#oltest_' + id).empty();
					$('#oltest_' + id).append(result);
				}
			});
		},
		500);
	}
	 else if (action == 'update') {
		$('#oltest_' + id).empty();
		$('#oltest_' + id).append(user_msg['jsx26']);
		url_h += '&action=update';
		setTimeout(function() {
			$.ajax({
				url: url_h,
				type: 'POST',
				data: '',
				success: function(result) {
					$('#oltest_' + id).empty();
					$('#oltest_' + id).append(result);
				}
			});
		},
		500);
	}
	else if (action == 'error') {
		//$('#oltest_' + id).empty();
		//$('#oltest_' + id).append(user_msg['jsx26']);
		url_h += '&action=error';
		$.ajax({
			url: url_h,
			type: 'POST',
			data: '',
			success: function(result) {
				$('#oltest_' + id).empty();
				$('#oltest_' + id).append(result);
			}
		});
	}
	return false;
}
function olflie(id, ver, action, numnow) {
	var addr = $('#addr_' + id).val();
	var checksum = $('#checksum_' + id).val();
	var url_h = $('#met_url_h').val() + 'olid=' + id + '&ver=' + ver + '&addr=' + addr + '&numnow=' + numnow + '&checksum=' + checksum + '&action=dl';
	$.ajax({
		url: url_h,
		type: 'POST',
		data: '',
		success: function(result) {
			$('#oltest_' + id).empty();
			$('#oltest_' + id).append(result);
		}
	});
}
function appdel(my, id) {
	url = my.attr('href');
	$.ajax({
		url: url,
		type: 'POST',
		data: '',
		success: function(result) {
			$('#del_' + id).empty();
			$('#del_' + id).append(result);
		}
	});
	return false;
}
function parantfrs(nav, list) {
	$.cookie('conav', null);
	$.cookie('conav', nav, {
		path: '/'
	});
	$.cookie('coul', null);
	$.cookie('coul', list, {
		path: '/'
	});
	parent.location.reload();
	return false;
}
function CheckAllx(my, fm,name) {
	var form = $("form[name='" + fm + "']");
	var checok = my.attr('checked') ? true: false;
	var sid = name?$("input[name='"+name+"']"):$("input[name='id']");
	sid = sid.size() > 0 ? sid: $("input[name^='met_flash_']");
	sid.attr('checked', checok);
}
function Problem(text) {
	alert(text);
}
function allid(ary, all) {
	var value = '';
	ary.each(function() {
		if ($(this).attr("checked")) value += $(this).val() + ',';
	});
	all.val(value);
}
function Atoform(form, type) {
	var tn;
	var noselect = form.find("select.noselect");
	if (noselect.size() > 0) {
		noselect.each(function() {
			var tit = type == 1 ? '': $(this).parent("td").parent("tr").find("td:eq(0)").text();
			tn = $(this).val() == 0 ? tit + user_msg['js41'] : ($(this).val() == '' ? tit + user_msg['js41'] : '');
			if ($(this).find('option').size() == 1) tn = '';
			if (tn != '') {
				$(this).focus();
				return false;
			}
		});
		if (tn != '') return tn;
	}
	var e = form.find("input.email");
	if (e.size() > 0) {
		var x = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
		var ok = x.test(e.val());
		tn = !ok ? user_msg['js44'] : '';
		if (tn != '') {
			e.focus();
			return tn;
		}
	}
	var namenul = form.find("input.namenonull");
	if (namenul.size() > 0) {
		namenul.each(function() {
			if (!$(this).is(":hidden")) {
				var tit = user_msg['js51'];
				tn = $(this).val() == '' ? tit: '';
				if (tn != '') {
					$(this).focus();
					return false;
				}
			}
		});
		if (tn != '') return tn;
	}
	var foldernul = form.find("input.foldernonull");
	if (foldernul.size() > 0) {
		foldernul.each(function() {
			if (!$(this).is(":hidden")) {
				var tit = user_msg['js52'];
				tn = $(this).val() == '' ? tit: '';
				if (tn != '') {
					$(this).focus();
					return false;
				}
			}
		});
		if (tn != '') return tn;
	}
	var nul = form.find("input.nonull,textarea.nonull");
	if (nul.size() > 0) {
		nul.each(function() {
			if (!$(this).is(":hidden")) {
				var tit = type == 1 ? '': $(this).parent("td").parent("tr").find("td:eq(0)").text();
				tn = $(this).val() == '' ? tit + user_msg['js41'] : '';
				if (tn != '') {
					$(this).focus();
					return false;
				}
			}
		});
		if (tn != '') return tn;
	}
	var lank = form.find("input[name='langmark']");
	if (lank.size() > 0) {
		var tit = lank.parent("td").parent("tr").find("td:eq(0)").text();
		for (var i = 0; i < langmarks.length; i++) {
			tn = lank.val() == langmarks[i] ? tit + user_msg['js46'] : '';
			if (tn != '') {
				lank.focus();
				return tn;
			}
		}
	}
}
function SmitMeturn(data, firtext) {
	var data = data.split('$');
	if (confirm(firtext)) {
		var url = data[1];
		setTimeout(function() {
			AjaxSmit(url, '');
		},
		time);
	} else {
		location.href = data[0];
		Problem(user_msg['js42']);
	}
}
function nouhew(M, form) {
	var nofilname = form.find("input[name='filename']");
	if (nofilname.size() > 0) {
		var nofilenameold = form.find("input[name='filenameold']");
		if (nofilname.val() != '' && nofilname.val() != nofilenameold.val()) {
			var ca1val = $("input[name='class1']").size() > 0 ? $("input[name='class1']").val() : $("input[name='id']").val();
			var urle = 'save.php?lang=' + lang + '&filename_okno=1&filename=' + escape(nofilname.val()) + '&class1=' + ca1val;
			$.ajax({
				url: urle,
				type: "POST",
				success: function(data) {
					if (data == 0) {
						data = user_msg['jsx27'];
						Problem(data);
						nofilname.focus();
					} else if (data == 2) {
						data = user_msg['jsx30'];
						Problem(data);
						nofilname.focus();
					} else {
						if ($.browser.msie) {
							form.submit();
						} else {
							M.attr('onclick', '').click();
							form.submit();
						}
					}
				}
			});
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}
function Smit(M, fn) {
	var form = $("form[name='" + fn + "']");
	var Ato = Atoform(form);
	if (Ato) {
		Problem(Ato);
		return false;
	} else {
		$("input").attr("disabled", false);
		return nouhew(M, form);
	}
}
function linkSmit(my, type, txt) {
	text = txt ? txt: user_msg['js7'];
	var tp = type != 1 ? 1: confirm(text) ? 1: '';
	if (tp == 1) {
		return true;
	}
	return false;
}
function met_modify(my, form, type) {
	var form = $("form[name='" + form + "']");
	var id = $("input[name='id']");
	var all = $("input[name='allid']");
	allid(id, all);
	var aller = all.val();
	var recycle = 0;
	if ($("input[name='recycle']").length != 0) {
		recycle = $("input[name='recycle']").val();
	}
	if (aller == '') {
		Problem(user_msg['js23']);
	} else {
		var Ato = Atoform(form, 1);
		if (Ato && type == 'editor') {
			Problem(Ato);
		}
		 else {
			msg = recycle == 1 ? user_msg['jsx28'] : user_msg['js7'];
			var df = type ? (type == 'editor' ? 1: (confirm(msg) ? 1: 0)) : 1;
			if (df == 1) {
				if (type) $("input[name='action']").val(type);
				form.submit();
			}
		}
	}
	return false;
}
function physical_check(my, type, form) {
	var form = $("form[name='" + form + "']");
	$("input[name='action']").val(type);
	form.submit();
}
function physical_ajax(my, valphy, type, op) {
	if (type == 4) {
		if (confirm(user_msg['jsx29'])) {
			var a = $("a[name='" + op + "']");
			a.each(function() {
				$(this).click();
			});
		}
	}
	 else {
		$.post("physical.php", {
			action: "op",
			valphy: valphy,
			type: type,
			op: op
		},
		function(msg) {
			if (op == 4) {
				alert(msg);
			}
			 else {
				my.replaceWith(msg);
			}
		});
	}
}
function metflag(my, lang) {
	if (my.next("#flag").length > 0) {
		if ($("#flag").is(':hidden')) $("#flag").show();
		else
		 $("#flag").hide();
	} else {
		var offset = my.offset();
		my.after("<div id='flag' style='left:" + (offset.left + 30) + "px;top:" + offset.top + "px;'><div id='andlaod'>" + user_msg['js48'] + "</div><div style='margin-top:3px; padding-left:5px;'></div></div>");
		var url = "lang.php?action=flag&lang=" + lang;
		var data = "";
		$.ajax({
			url: url,
			type: "POST",
			data: data,
			success: function(data) {
				$("#flag").find('#andlaod').remove();
				$("#flag").prepend(data);
				$("#flag").find("img").click(function() {
					var ts = $(this).attr("src").split("/");
					var p = ts.length - 1;
					var src = ts[p];
					$("input[name='langflag']").val(src);
					$("#flag").hide();
				});
			}
		});
	}
	return false;
}
function addsave(met, i) {
	met.after('<span id="loadtxt">' + '<img src=\"' + metimgurl + 'loadings.gif\" style=\"position:relative; top:4px;\" />' + user_msg['js48'] + '</span>');
	var url = met.attr('href');
	var dom = $("tr.mouse");
	var at = dom.length > 0 ? dom.eq(dom.length - 1) : $('#list-top');
	var lp = $('.newlist') ? $('.newlist').length: 0;
	$.ajax({
		url: url,
		type: "POST",
		data: 'lp=' + lp,
		success: function(data) {
			metaddtr(at, data, i);
			$('#loadtxt').remove();
			ifreme_methei();
		}
	});
	return false;
}
function oncolumn(my, id, tp) {
	var comnn = $('tr.columnz_' + id);
	if (comnn) {
		if (comnn.is(':hidden')) {
			if (!tp) {
				comnn.show();
				my.addClass('columnimgon');
				my.attr('src', metimgurl + '/colum1nx2.gif');
				ifreme_methei();
			}
		} else {
			comnn.hide();
			my.removeClass('columnimgon');
			my.attr('src', metimgurl + '/colum1nx.gif');
			comnn.each(function() {
				var idy = $(this).find("input[type='checkbox']").val();
				var myy = $(this).find("img.columnimg");
				if (myy) oncolumn(myy, idy, 1);
				ifreme_methei();
			});
		}
	}
}
function imgnumfu() {
	$("input[name='imgnum']").val(function() {
		return parseInt($(this).val()) - 1
	});
}
function displayimg_cs(lp) {
	var x = $("input[name='displayname" + lp + "']").length;
	if (x > 0) {
		lp++;
		return displayimg_cs(lp);
	} else {
		return lp;
	}
}
function adddisplayimg(my) {
	$('#loadtxt').html('<img src=\"' + metimgurl + 'loadings.gif\" style=\"position:relative; top:4px;\" />' + user_msg['js48']);
	var url = my.attr('href');
	var lp = $('.newlist') ? $('.newlist').length: 0;
	lp = displayimg_cs(lp);
	var dom = $("tr.newlist");
	var at = dom.length > 0 ? dom.eq(dom.length - 1) : $('#list-top');
	$.ajax({
		url: url,
		type: "POST",
		data: 'lp=' + lp,
		success: function(data) {
			metaddtr(at, data, 0);
			$('#loadtxt').empty();
			$("input[name='imgnum']").val(function() {
				return parseInt($(this).val()) + 1
			});
			ifreme_methei();
		}
	});
	return false;
}
function addcolumn(my, id, tp) {
	if($('#loadtxt').html())return false;
	$('#loadtxt').html('<img src=\"' + metimgurl + 'loadings.gif\" style=\"position:relative; top:4px;\" />' + user_msg['js48']);
	var h;
	var url = my.attr('href') + '&action_ajax=1';
	var trcom = my.parents('tr').eq(0);
	if (id) var comnn = $('tr.columnz_' + id);
	var lp = $('.newlist') ? $('.newlist').length: 0;
	$.ajax({
		url: url,
		type: "POST",
		data: 'lp=' + lp,
		success: function(data) {
			if (comnn && comnn.length > 0) {
				var cnum = comnn.length - 1;
				if (tp == 2) {
					var idy = comnn.eq(cnum).find("input[type='checkbox']").val();
					var numd = $('tr.columnz_' + idy);
					if (numd.length > 0) {
						var dnum = numd.length - 1;
						h = numd.eq(dnum);
					} else {
						h = comnn.eq(cnum);
					}
				} else {
					h = comnn.eq(cnum);
				}
				var nexta = my.prev().prev();
				if (comnn.is(':hidden')) oncolumn(nexta, id);
			} else {
				h = trcom;
				if (id == '') {
					var he = $('tr.column_1').length - 1;
					var hi = $('tr.column_1').eq(he).find("input[type='checkbox']").val();
					var hn = $('tr.columnz_' + hi);
					if (hn.length > 0) {
						var hm = hn.length - 1;
						var hmn = hn.eq(hm).find("input[type='checkbox']").val();
						var hnn = $('tr.columnz_' + hmn);
						if (hnn.length > 0) {
							var hmm = hnn.length - 1;
							h = hnn.eq(hmm);
						} else {
							h = hn.eq(hm);
						}
					} else {
						h = $('tr.column_1').eq(he);
						if (he < 0) h = $('#list-top');
					}
				}
			}
			metaddtr(h, data, 1);
			$('#loadtxt').empty();
			ifreme_methei();
		}
	});
	return false;
}
function delettr(my) {
	my.parent('td').parent('tr').remove();
}
function metaddtr(h, t, i) {
	h.after(t);
	var l = i != 1 ? i: 1;
	h.next('tr').find("input[type='text']").eq(l).focus();
}
function flashow(my, fx, fy) {
	var url = my.attr('href');
	var lodf = $('#lodfalsh');
	var fxm = fx != '' ? parseInt($("input[name='" + fx + "']").val()) + 5: 200;
	var fym = fy != '' ? parseInt($("input[name='" + fy + "']").val()) + 51: 400;
	if (!fx) {
		lodf.load(url);
		lodf.css({
			'position': 'absolute',
			'right': '150px',
			'top': '50px',
			'z-index': '999'
		});
	} else {
		lodf.attr('src', url);
		lodf.css({
			'position': 'absolute',
			'left': '50px',
			'top': '50px',
			'width': fxm + 'px',
			'height': fym + 'px',
			'z-index': '999',
			'background': '#fff'
		});
	}
	lodf.show();
	return false;
}
function metfocus(intext) {
	intext.focus(function() {
		$(this).next("span.tips").css("color", "#f00");
	});
	intext.focusout(function() {
		$(this).next("span.tips").css("color", "");
	});
}
function tipsbox(my) {
	var xp = my.parent("td").next("td").find(".tips-text");
	if (xp.css("display") == "none") {
		xp.show();
		$(".tips-text").not(xp).hide();
	} else {
		xp.hide();
	}
}
function titletipsbox(my) {
	var xp = my.parent("td").parent("tr").next("tr").find("td.title-tips");
	if (xp.css("display") == "none") {
		xp.show();
		$(".tips-text").not(xp).hide();
	} else {
		xp.hide();
	}
}
function handle_form(name) {
	var fname = "form[name='" + name + "']";
	$(fname).submit();
	return false;
}
function onfckeditor(my, id) {
	my.hide();
	$("#" + id).show();
}
function Problemclose(smmm) {
	var blem = $(window.parent.document).find("#Problem");
	blem.empty();
}
$(document).ready(function() {
	var radios = $("input[type='radio'],input[type='checkbox']");
	if (radios) radios.change(function() {
		radios.not($(this)).next("label").removeClass("red");
		$(this).next("label").addClass("red");
	});
	var tr = $("tr.mouse");
	if (tr) tr.live('hover',
	function(tm) {
		if (tm.type == 'mouseover' || tm.type == 'mouseenter') $(this).addClass("ontr");
		if (tm.type == 'mouseout' || tm.type == 'mouseleave') $(this).removeClass("ontr");
	});
	var titletips = $("td.title a.tips");
	if (titletips) titletips.click(function() {
		titletipsbox($(this));
	});
	var tips = $("td.text a.tips");
	if (tips) tips.click(function() {
		tipsbox($(this));
	});
	var inputps = $("input[type='text'],input[type='password'],textarea.textarea");
	if (inputps) metfocus(inputps);
	var lefthide = $("div.left-hiden");
	if (lefthide) lefthide.click(function() {
		var mdt = $(this).parent().parent('td');
		var ydt = mdt.prev('td');
		if (ydt.css("display") == "none") {
			ydt.show();
			mdt.attr("colspan", "2");
			$(this).removeClass("left-hiden-hover");
		} else {
			ydt.hide();
			mdt.attr("colspan", "3");
			$(this).addClass("left-hiden-hover");
		}
	});
});
function list_all(my, type) {
	var y = my.parent('td').parent('tr');
	var e = type == 1 ? 'tr.column_1': 'tr.column_2';
	var q = y.nextUntil(e);
	var p = my.attr('checked') ? true: false;
	if (q.size() > 0) q.each(function() {
		var t = $(this).find('#id');
		t.attr('checked', p);
		if (type == 1) {
			$(this).nextUntil('tr.column_2').p;
		}
	});
}
$(document).ready(function() {
	$("body").click(function() {
		$("#langcig", parent.document).removeClass('nowt');
		$(".langlist", parent.document).hide();
	})
	 var nofocu = $("input[name='nofocu']").length == 0 ? true: false;
	var fints = $(".list-text input.text,.list-text select,.list-text2 input.text,.list-text2 select,.list-text3 input.text,.list-text3 select");
	fints.each(function() {
		$(this).data($(this).attr('name'), $(this).val());
	});
	fints.focusout(function() {
		var tr = $(this).parents("tr.mouse");
		if ($(this).val() != $(this).data($(this).attr('name'))) tr.find("input[type='checkbox']").attr('checked', nofocu);
	});
	$(".list-text input[type='checkbox']").change(function(){
		if($(this).attr('name')!='id'){
			var tr = $(this).parents("tr.mouse");
			tr.find("input[name='id']").attr('checked', nofocu);
		}
	});
	Array.prototype.unique = function() {
		var o = {};
		for (var i = 0, j = 0; i < this.length; ++i) {
			if (o[this[i]] === undefined) {
				o[this[i]] = j++;
			}
		}
		this.length = 0;
		for (var key in o) {
			this[o[key]] = key;
		}
		return this;
	};
	var keys = [];
	$(document).keydown(function(event) {
		keys.push(event.keyCode);
		keys.unique();
	}).keyup(function(event) {
		if (keys.length > 2) keys = [];
		keys.push(event.keyCode);
		keys.unique();
		if (keys.join('') == '1713') {
			var input = $("input[type='submit']");
			if (input.length == 1) {
				if (!input.attr('disabled')) {
					input.click();
				}
			}
		}
		keys = [];
	});
});