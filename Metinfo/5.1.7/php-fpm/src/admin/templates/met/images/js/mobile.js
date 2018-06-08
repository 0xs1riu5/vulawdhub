function changelocation(my){
    document.myform.class3.length = 1; 
    var locationid=my.val();
	if(my.val()!=0){
		var i,lev;
		for(i=0;i < subcat.length; i++){			
				if(subcat[i][2] == locationid)
					lev=subcat[i][3];
				if (subcat[i][1] == locationid){ 
					document.myform.class3.options[document.myform.class3.length] = new Option(subcat[i][0], subcat[i][2]);
				}
		}
		document.myform.access.length = 0;
		if(lev=="all") lev="0";
		switch(parseInt(lev)){
			case 0:document.myform.access.options[document.myform.access.length] = new Option(user_msg['js28'], 'all');
			case 1:document.myform.access.options[document.myform.access.length] = new Option(user_msg['js29'], '1');
			case 2:document.myform.access.options[document.myform.access.length] = new Option(user_msg['js30'], '2');
			case 3:document.myform.access.options[document.myform.access.length] = new Option(user_msg['js31'], '3');
		}
	}
}