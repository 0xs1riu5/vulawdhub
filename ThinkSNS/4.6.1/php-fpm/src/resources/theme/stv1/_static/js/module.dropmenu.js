
M.addModelFns({

drop_menu_list: {
	load: function() {
		var parentModel = this.parentNode,
			list = this;
		// 鼠标进入父Model，显示Menu；反之，则隐藏Menu。
		M.addListener( parentModel, {
			mouseenter: function() {
				var className = this.className;
				this.className = [ className, " drop" ].join( "" );
				list.style.display = "block";
			},
			mouseleave: function() {
				var className = this.className;
				this.className = className.replace(/(\s+drop)+(\s+|$)/g, "");
				list.style.display = "none";
			}
		});
	}
	
}
});