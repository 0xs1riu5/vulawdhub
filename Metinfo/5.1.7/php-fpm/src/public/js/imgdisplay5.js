	function getid(o){ return (typeof o == "object")?o:document.getElementById(o);}
	function getNames(obj,name,tij)
	{
		var plist = getid(obj).getElementsByTagName(tij);
		var rlist = new Array();
		for(i=0;i<plist.length; ++i){if(plist[i].getAttribute("name") == name){rlist[rlist.length] = plist[i];}}
		return rlist;
	}

	function fiterplay(obj,num,t,name,c1,c2)
	{
		var fitlist = getNames(obj,name,t);
		for(i=0;i<fitlist.length;++i)
		{
			if(i == num)
			{
				fitlist[i].className = c1;
			}
			else
			{
				fitlist[i].className = c2;
			}
		}
	}
	function metplay(obj,num)
	{
		var s = getid('metsimg');
		var i = getid('metimginfo');
		var b = getid('metbimg');
		try	
		{
			with(b)
			{
				//filters[0].Apply();	
				fiterplay(b,num,"div","f","metdis","unmetdis");	
				fiterplay(s,num,"div","f","","f1");
				fiterplay(i,num,"div","f","metdis","unmetdis");
				//filters[0].metplay();
			}
		}
		catch(e)
		{
				fiterplay(b,num,"div","f","metdis","unmetdis");
				fiterplay(s,num,"div","f","","f1");	
				fiterplay(i,num,"div","f","metdis","unmetdis");
		}
	}

	var autoStart = 0;
	var n = 0;		var s = getid("metsimg");
		var x = getNames(s,"f","div");
	function clearAuto() {clearInterval(autoStart);};
	function setAuto(){autoStart=setInterval("auto(n)", 3000)}
	function auto()	{


		n++  ;
		if(n>(x.length-1))
		{ n = 0;
		clearAuto();
		 }
		metplay(x[n],n);
		
	}
	function ppp(){
	setAuto();
	
	}
ppp();