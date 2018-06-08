function OnDom(dt,dd){
    var H4=dd.find("h4");
if(H4.length>0){
    if(Type==1){
		dd.css("display","block");
		dt.addClass("part-on");
	}else if(Type==2){
	    var X=dt.outerWidth(true);
		dt.addClass("part-on");
	    dd.css({
		    "display":"block",
			"position":"absolute",
			"top":"0px",
			"left":X*0.6,
			"width":X*0.8,
			"z-index":"999"
		});
		var H4y=H4.outerHeight(true)*H4.length;
		dd.css("height",H4y);
	}
}
}
function OutDom(dt,dd){
		dd.css("display","none");
		dt.removeClass("part-on");
}

        var P=0;
		var Part2Next=Part2.next("dd");	
        var Dom_dl=Dom.find("dl");
        var Dom_dt=Dom.find("dt");
		if(Type==1){
            if(Part2.length>0){ Part2Next.css("display","block"); Part2.addClass("Parted"); }
		    if(Part3.length>0){ Part3.addClass("Parted"); }
		    Dom_dt.click(function(){
			    var Cdom_dt=$(this);
			    var Cdom_dd=Cdom_dt.next('dd');
				if(Cdom_dd.css("display")=="block"){ P=1;}else{P=0;}
			    if(P==0){OnDom(Cdom_dt,Cdom_dd);}else{OutDom(Cdom_dt,Cdom_dd);}
			});
			
		}else if(Type==2){
		        Dom_dl.addClass("type2");
		    Dom_dl.hover(
			    function(){
			        var Cdom_dt=$(this).find('dt');
			        var Cdom_dd=$(this).find('dd');
					$(this).css("position","relative");
			        OnDom(Cdom_dt,Cdom_dd);
			    },
			    function(){
			        var Cdom_dt=$(this).find('dt');
			        var Cdom_dd=$(this).find('dd');
			        OutDom(Cdom_dt,Cdom_dd);
			    }
			);
		}