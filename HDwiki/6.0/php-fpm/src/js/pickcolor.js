var PickColor = {
	input:null,
	cswatch : [
	    [ '000000', '111111', '2d2d2d', '434343', '5b5b5b', '737373', '8b8b8b', 'a2a2a2', 'b9b9b9', 'd0d0d0', 'e6e6e6', 'ffffff' ],
	    [ '7f7f00', 'bfbf00', 'ffff00', 'ffff40', 'ffff80', 'ffffbf', '525330', '898a49', 'aea945', 'c3be71', 'e0dcaa', 'fcfae1' ],
	    [ '407f00', '60bf00', '80ff00', 'a0ff40', 'c0ff80', 'dfffbf', '3b5738', '668f5a', '7f9757', '8a9b55', 'b7c296', 'e6ebd5' ],
	    [ '007f40', '00bf60', '00ff80', '40ffa0', '80ffc0', 'bfffdf', '033d21', '438059', '7fa37c', '8dae94', 'acc6b5', 'ddebe2' ],
	    [ '007f7f', '00bfbf', '00ffff', '40ffff', '80ffff', 'bfffff', '033d3d', '347d7e', '609a9f', '96bdc4', 'b5d1d7', 'e2f1f4' ],
	    [ '00407f', '0060bf', '0080ff', '40a0ff', '80c0ff', 'bfdfff', '1b2c48', '385376', '57708f', '7792ac', 'a8bed1', 'deebf6' ],
	    [ '00007f', '0000bf', '0000ff', '4040ff', '8080ff', 'bfbfff', '212143', '373e68', '444f75', '585e82', '8687a4', 'd2d1e1' ],
	    [ '40007f', '6000bf', '8000ff', 'a040ff', 'c080ff', 'dfbfff', '302449', '54466f', '655a7f', '726284', '9e8fa9', 'dcd1df' ],
	    [ '7f007f', 'bf00bf', 'ff00ff', 'ff40ff', 'ff80ff', 'ffbfff', '4a234a', '794a72', '936386', '9d7292', 'c0a0b6', 'ecdae5' ],
	    [ '7f003f', 'bf005f', 'ff007f', 'ff409f', 'ff80bf', 'ffbfdf', '451528', '823857', 'a94a76', 'bc6f95', 'd8a5bb', 'f7dde9' ],
	    [ '800000', 'c00000', 'ff0000', 'ff4040', 'ff8080', 'ffc0c0', '441415', '82393c', 'aa4d4e', 'bc6e6e', 'd8a3a4', 'f8dddd' ],
	    [ '7f3f00', 'bf5f00', 'ff7f00', 'ff9f40', 'ffbf80', 'ffdfbf', '482c1b', '855a40', 'b27c51', 'c49b71', 'e1c4a8', 'fdeee0' ]
	],
	
	init:function(input){
		var left=-10000, top=-10000, color='#ffffff';
		
		if (input){
			if($(input).attr('type').toLowerCase() == 'button'){
				input = $(input).prev('input');
			}else if($(input).attr('type').toLowerCase() == 'text'){
				input = $(input);
			}else{
				return alert('error');
			}
			
			this.input = input;
			top = input.offset().top + 22;
			left = input.offset().left + 2;
			color = input.val();
		}
		try{
			$("#PickColorTable_oldColor").css('background-color', color);
		} catch(err) {}
		if ($('#PickColorTable').length > 0){
			$('#PickColorTable').css({left:left, top:top}).show();
			return ;
		}
		
		html = '<div id="PickColorTable" style="position:absolute;display:none;border:3px solid #d0d0d0;background-color:#ffffff">'
			+' <table width="168" height="168" border="0" cellspacing="0" cellpadding="0">';
		for (i=0; i<12; i++) {
			html += "<tr>";
			for (ii=0; ii<12; ii++) {
				html += '<td valign="top" width="12" height="12" style="cursor:pointer;border:1px solid white;background-color:#'+this.cswatch[i][ii]+'"'
					+ ' onmouseover="PickColor.mouseover(this)" '
					+ ' onmouseout="PickColor.mouseout(this)" '
					+ ' onclick="PickColor.get(this, \'#'+this.cswatch[i][ii]+'\');return false;"></td>';
			}
			html += '</tr>';
		}
		html += '<tr><td colspan="4" id="PickColorTable_oldColor" style="background-color:'+color+'"></td><td colspan="4" id="PickColorTable_newColor"></td><td colspan="2"></td>'
			+ '<td colspan="2" align="center"><img src="style/default/close.jpg" style="cursor:pointer" onclick="PickColor.hide()"></td></tr>'
			+ '</table></div>';
		$("body").append(html);
		$('#PickColorTable').css({left:left, top:top}).show();
	},
	
	mouseover:function(E){
		$(E).css('border', '1px inset black');
		$("#PickColorTable_newColor").css('background-color', $(E).css('background-color'));
	},
	
	mouseout:function(E){
		$(E).css('border', '1px solid white');
	},
	
	get:function(E, color){
		this.input.val(color);
		this.input.next('input').css('background-color', color);
		this.hide();
	},
	
	hide:function(){
		$('#PickColorTable').hide();
		$("#PickColorTable_newColor").css('background-color', '#ffffff');
	},
	
	change:function(color){
		try{
			this.input.next('input').css('background-color', color);
		} catch (err) {}
	},
	
	keyup:function(color){
		if ($.browser.msie || !color) return false;
		this.input.next('input').css('background-color', color);
		$("#PickColorTable_oldColor").css('background-color', color);
	}
}