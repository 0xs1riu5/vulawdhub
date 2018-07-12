/*
 * 	Easy Slider 1.5 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/4004/easy-slider-15-the-easiest-jquery-plugin-for-sliding
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
/*
 *	markup example for $("#slider").easySlider();
 *	
 * 	<div id="slider">
 *		<ul>
 *			<li><img src="images/01.jpg" alt="" /></li>
 *			<li><img src="images/02.jpg" alt="" /></li>
 *			<li><img src="images/03.jpg" alt="" /></li>
 *			<li><img src="images/04.jpg" alt="" /></li>
 *			<li><img src="images/05.jpg" alt="" /></li>
 *		</ul>
 *	</div>
 *
 */

(function($) {

	$.fn.easySlider = function(options){
	  
		// default configuration properties
		var defaults = {			
			prevId: 		'prevBtn',
			prevText: 		'Previous',
			nextId: 		'nextBtn',	
			nextText: 		'Next',
			controlsShow:	true,
			controlsBefore:	'',
			controlsAfter:	'',	
			controlsFade:	false,
			firstId: 		'firstBtn',
			firstText: 		'First',
			firstShow:		false,
			lastId: 		'lastBtn',	
			lastText: 		'Last',
			lastShow:		false,				
			vertical:		false,
			speed: 			800,
			auto:			false,
			pause:			2000,
			photonum:       5,
			movenum :       1,
			continuous:		false,
			defaultnum:     9
		}; 
		
		var options = $.extend(defaults, options);  
				
		this.each(function() {
			var obj = $(this); 
			var s = $("li", obj).length;
			var w = $("li", obj).width(); 
			var h = $("li", obj).height();
			var ts = s;
			if(options.defaultnum<=options.photonum){
				t = 0;
			}else{
				t = Math.ceil((options.defaultnum-options.photonum)/3);
			}
			var wl = w*options.photonum;
			
			var temp_height = h*options.photonum;
			
			if(!options.vertical){
				obj.width(wl);
				obj.height(h);
				$("ul", obj).css('width',s*w);					
			}else{
				obj.width(w);
				obj.height(temp_height); 
				$("ul", obj).css('width',w);
			}
			
			obj.css("overflow","hidden");

			
			if(!options.vertical){
				$("li", obj).css('float','left');
			}else{
				$("ul", obj).css('width',w);
				if(t){
					$("ul", obj).css('margin-top',-h*t*options.movenum);
				}
			}

			if(options.controlsShow){
				var html = options.controlsBefore;
				if(options.firstShow) html += '<span id="'+ options.firstId +'"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
				html += ' <span id="'+ options.prevId +'"><a href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
				html += ' <span id="'+ options.nextId +'"><a href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
				if(options.lastShow) html += ' <span id="'+ options.lastId +'"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';
				html += options.controlsAfter;						
				$(obj).after(html);										
			};
	
			$("a","#"+options.nextId).click(function(){
				if( (s - t*options.movenum - options.photonum)  >0 ){
					animate("next",true);
				}
			});
			$("a","#"+options.prevId).click(function(){
				animate("prev",true);				
			});	
			$("a","#"+options.firstId).click(function(){	
				animate("first",true);
			});				
			$("a","#"+options.lastId).click(function(){		
				animate("last",true);				
			});		
			
			function animate(dir,clicked){
				var ot = t;				
				switch(dir){
					case "next":
						t = (t>=s) ? (options.continuous ? 0 : ts) : t+1;
						//t = (ot*options.photonum>=ts) ? s : t+1;
						break; 
					case "prev":
						t = (t<=0) ? (options.continuous ? ts : 0) : t-1;
						break;
					case "first":
						t = 0;
						break; 
					case "last":
						t = ts;
						break; 
					default:
						break; 
				};	
				
				var diff = Math.abs(ot-t);
				var speed = diff*options.speed;						
				if(!options.vertical) {
					p = (t*w*-1*options.movenum);
					$("ul",obj).animate(
						{ marginLeft: p }, 
						speed
					);				
				} else {
					p = (t*h*-1*options.movenum);
					$("ul",obj).animate(
						{ marginTop: p }, 
						speed
					);
				};
				
				if(!options.continuous && options.controlsFade){					
					if(t==ts){
						$("a","#"+options.nextId).hide();
						$("a","#"+options.lastId).hide();
					} else {
						$("a","#"+options.nextId).show();
						$("a","#"+options.lastId).show();					
					};
					if(t==0){
						$("a","#"+options.prevId).hide();
						$("a","#"+options.firstId).hide();
					} else {
						$("a","#"+options.prevId).show();
						$("a","#"+options.firstId).show();
					};					
				};				
				
				if(clicked) clearTimeout(timeout);
				if(options.auto && dir=="next" && !clicked){;
					timeout = setTimeout(function(){
						animate("next",false);
					},diff*options.speed+options.pause);
				};
			};
			// init
			var timeout;
			if(options.auto){;
				timeout = setTimeout(function(){
					animate("next",false);
				},options.pause);
			};		
		
			if(!options.continuous && options.controlsFade){					
				$("a","#"+options.prevId).hide();
				$("a","#"+options.firstId).hide();				
			};				
			
		});
	};

})(jQuery);



