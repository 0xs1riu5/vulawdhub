/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function dmarquee(lineheight, speed, delay, id) {	
	this.t; this.p = false; this.o = Dd(id); this.h = this.o.innerHTML; if(this.h.length < 10) return;
	this.o.innerHTML = '<div id="'+id+'_tmp"><div>'+this.h+'</div></div>';
	var h1 = Number(Dd(id).style.height.replace('px', '')); var h2 = Dd(id+'_tmp').scrollHeight;
	if(lineheight == -1) return;
	if(h2*2 <= h1) { this.o.innerHTML = this.h; return; } else if(h2 >= h1) { this.o.innerHTML = this.h + this.h; } else { this.o.innerHTML = this.h + this.h + this.h;	}
	this.o.scrollTop = 0; var _this = this;
	this.o.onmouseover = function() {_this.p = true;} 
	this.o.onmouseout = function() {_this.p = false;}
	this.start = function() { this.t = setInterval(function() {_this.scrolling();}, speed); if(!this.p) {this.o.scrollTop += 1;} } 
	this.scrolling = function() { if(this.o.scrollTop%lineheight !=0) { this.o.scrollTop += 1; if(this.o.scrollTop == h2) this.o.scrollTop = 0; } else { clearInterval(this.t); setTimeout(function() {_this.start();}, delay); } }
	setTimeout(function() {_this.start();}, delay);
}
//e.g. new dmarquee(20, 20, 1000, 'id');