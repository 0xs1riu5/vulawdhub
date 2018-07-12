(function(){

M.addModelFns({

invite_colleague_form: {
	callback: function( txt ) {
		ui.success( txt.info );
		ui.box.close();
	}
}

}).addEventFns({

invite_colleague: {
	click: function() {
		ui.box.load( this.href, L('PUBLIC_INVITE_COLLEAGUE') );
		return false;
	}
},	
invite_addemail:{
	click: function() {
		var input1 = document.getElementById("email_input").value,
			$email_input = $("#email_input"),
			dInput = this.parentModel.childEvents["email"][0],
			dInputClone = dInput.cloneNode( true );

		dInputClone.value = "";
		$email_input.append( dInputClone );
		M( dInputClone );

		return false;
	}
}

});

})();