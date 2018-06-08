function creatHexunFlashObject(src_str,w_num,h_num,id_str,wmode,verson){
	if(src_str!=null){
		var temp_str='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=';
		if(verson==null){temp_str+='8,0,0,0"';}else{temp_str+=verson+',0,0,0"';}
		if(w_num!=null){temp_str+=' width="'+w_num+'"';}
		if(h_num!=null){temp_str+=' height="'+h_num+'"';}
		if(id_str!=null){temp_str+=' id="'+id_str+'"';}
		temp_str+='>\n'+'<param name="movie" value="'+src_str+'" />'+'<param name="quality" value="autohigh" />';
		if(wmode==null){temp_str+='<param name="wmode" value="transparent">';
		}else{temp_str+='<param name="wmode" value="'+wmode+'">';}
		temp_str+='<embed  swLiveConnect=true src="'+src_str+'"';
		if(w_num!=null){temp_str+=' width="'+w_num+'"'}
		if(h_num!=null){temp_str+=' height="'+h_num+'" '}
		temp_str+='quality="autohigh" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" ';
		if(wmode==null){temp_str+='wmode="opaque"';
		}else{temp_str+='wmode="'+wmode+'"';}
		if(id_str!=null){temp_str+=' name="'+id_str+'" id="'+id_str+'"';}
		temp_str+='></embed></object>';
		document.write(temp_str);
	}
}