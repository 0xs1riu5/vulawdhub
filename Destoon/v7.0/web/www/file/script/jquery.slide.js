jQuery.fn.imgOnload = function(options) {
	options = $.extend({
		 src : "",
		 bigW:1180,
		 bigH:800
	},options);
	var _self = this;
	_self.hide();
	options.loading.css('display','block');
	var img = new Image();
	$(img).load(function(){
		_self.attr("src", options.src);	
		_self.fadeIn("slow");		
		if(img.height>options.bigH){
			_self.attr('width',Math.ceil(img.width*options.bigH/img.height));
			_self.attr('height',options.bigH);
		}else if(img.width>options.bigW){
			_self.attr('width',options.bigW);
			_self.attr('height',Math.ceil(img.width*options.bigH/options.bigW));
		}else{
			_self.attr('width',img.width);
			_self.attr('height',img.height);
		}		
		options.loading.css('display','none');
	}).attr("src", options.src);


}
$(function(){
	function Gallery(option){
		var t = this, ids = ['photo','pload','prevbtn','nextbtn','pintro','photolist','scprev','scnext','photoinfo','scb','vbig'], i = ids.length;
		while(--i >= 0){
			t[ids[i]] = $('#' + ids[i]);
		}
		var scrollMode = {
			prev:function(){
				var temp = t.nowli == 0 ? load_item - 1 : t.nowli - 1;
				$(t.photolist.find('li img')[temp]).click();			
			},
			next:function(){
				if(t.nowli + 1 == load_item) {
					PhotoLast();return;
				}
				var temp = t.nowli + 1 == load_item ? 0 : t.nowli + 1;
				$(t.photolist.find('li img')[temp]).click();			
			}
		};
		t.scprev.bind("click",scrollMode.prev);
		t.prevbtn.bind("click",scrollMode.prev);
		t.scnext.bind("click",scrollMode.next);
		t.nextbtn.bind("click",scrollMode.next);
		t.scb.mousedown(function(event){
			var e = event || window.event;
			var scbL = parseInt($(this).css('left').match(/\d+/));
			scbL = isNaN(scbL) ? 0 : scbL;
			var ulML = parseInt(t.photolist.css('margin-left').match(/\d+/));
			ulML = isNaN(ulML) ? 0 : ulML;
			var ulW = option.simgW * (load_item - 7);
			var pml;
			if(ulW<0)return false;
			$(document).mousemove(function(event){
				var ev = event || window.event;
				var moveX = ev.pageX - e.pageX;
				if(moveX > 0){					
					t.scb.css('left',(moveX + scbL >= option.scrlscbW + 150 ? option.scrlscbW + 150 : moveX + scbL)+'px');
					pml = Math.max(-ulW,(-ulW / option.scrlscbW * moveX - ulML))
					t.photolist.css('margin-left',pml + 'px');
				}else if(moveX < 0){
					t.scb.css('left',(scbL + moveX <= 150 ? 150 : scbL + moveX)+'px');
					pml = Math.min(0,(-ulW / option.scrlscbW * moveX-ulML));
					t.photolist.css('margin-left',pml + 'px');
				}
				t.imgnum(-parseInt(pml/128)+3);
				$(t.photolist.find('li span')[-parseInt(pml/128)]).hide();
			});
			$(document).mouseup(function(){document.onselectstart = null;$(this).unbind("mousemove");t.imgnum(-parseInt(pml/128)+3);});
			document.onselectstart = function(){return false;};
		});
		t.scroll = function(){
			var ulW = option.simgW * (load_item - 7);
			var bw = option.scrlscbW/ulW * parseInt(t.photolist.css('margin-left').match(/\d+/));
			t.scb.css('left',150 + bw);
		}
		t.loading = function(){
			t.photoarr = [];
			t.photoinfo.find('li').each(function(i){
				var objLi = $(this);
				t.photoarr.push([objLi.find("i[title=bimg]").html(),objLi.find("i[title=simg]").html(),objLi.find('p').html()]);
				t.photolist.append('<li>' + t.photoarr[i][1] + '</li>');
				if(i==(load_page-1)){
					t.nowli = i;
					t.photolist.find('li img:eq('+i+')').css({border:'1px solid #FF6600',background:'#FF6600'});
					t.photo.imgOnload({loading:t.pload,src:t.photoarr[i][0]});
					t.pintro.html(t.photoarr[i][2]);
					t.vbig.attr('href', DTPath+'api/view.php?img='+t.photoarr[i][0]);
					t.imgpos(i);
					t.scroll();
				}
			});
			t.imgnum(load_page-1);				
			t.photolist.find('li img').each(function(i){
				$(this).click(function(){
					t.photolist.find('li img').each(function(j){
						$(this).css(i==j?{border:'1px solid #FF6600',background:'#FF6600'}:{border:'1px solid #BFBFBF',background:'#FFFFFF'});
						t.nowli = i;
					});
					t.photo.imgOnload({loading:t.pload,src:t.photoarr[i][0]});
					$('.count_a').html(i+1);
					t.pintro.html(t.photoarr[i][2]);	
					t.vbig.attr('href',DTPath+'api/view.php?img='+t.photoarr[i][0]);		
					t.imgpos(i);
					t.imgnum(i);
					t.scroll();
				});	
			});
		}
		t.imgpos = function(n){
			if(load_item < 7) return false;
			t.photolist.css('margin-left',(n - 3 > 0 ? (n > load_item - 4 ? (7 - load_item)*option.simgW + 'px' : (3 - n)*option.simgW + 'px') : '0px'));
		}
		t.imgnum = function(n){
			var min_i, max_i;
			if(load_item > 7) {
				if(n < 3) {
					min_i = 0; max_i = 6;
				} else if(n > load_item - 3) {
					min_i = load_item - 7; max_i = load_item - 1;
				} else {
					min_i = n - 3; max_i = n + 3;
				}
			} else {
				min_i = 0; max_i = load_item - 1;
			}
			t.photolist.find('span').each(function(jj) {
				if(jj >= min_i && jj <= max_i) { $(this).show(); } else { $(this).hide(); }
			});
		}
	}
	var G = new Gallery({simgW:128,scrlscbW:650});
	G.loading();
	document.onkeydown = function(e) {
		var k = typeof e == 'undefined' ? event.keyCode : e.keyCode;
		if(k == 37) {
			$('#scprev')[0].click();
		} else if(k == 39) {
			$('#scnext')[0].click();
		}
	}
});