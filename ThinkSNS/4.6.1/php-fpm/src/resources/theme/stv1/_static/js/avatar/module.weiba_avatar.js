(function(){

M.addEventFns({
	select_file: {
		click: function() {
			
			var uploadBtn = this;
			//console.log($(this).parentModel.html()); return false;
			var	uploadForm  = uploadBtn.parentModel.parentModel.childModels["avatar_upload_form"][0],
				settingForm = uploadBtn.parentModel.parentModel.childModels["avatar_setting_form"][0],
				avatarScan = uploadForm.childModels["avatar_scan"][0],
				scanImg = [
						{
							size: "big",
							img: avatarScan.childEvents["avatar_big"][0]
						},
						{
						 	size: "middle",
						 	img: avatarScan.childEvents["avatar_middle"][0]
					 	},

				];
				

				M.nodes.events["header_avatar"] && scanImg.push( { size: "small", img: M.nodes.events["header_avatar"][0] } );

			// M.getJS( THEME_URL + "/js/avatar/avatar.js?"+SYS_VERSION, function() {
				avatar({
					// 上传表单
					uploadForm: uploadForm,
					uploadBtn: uploadBtn,
					loading: uploadForm.childEvents["loading"][0],

					// 展示头像的IMG 元素及大小
					scanImg: scanImg,

					// 设置表单
				    settingForm: settingForm,
				    picUrl: settingForm.childEvents["avatar_picurl"][0],
				    picWidth: settingForm.childEvents["avatar_picwidth"][0],
				    fullPicUrl: settingForm.childEvents["avatar_fullpicurl"][0],
				    area: settingForm.childModels["avatar_area"][0],
				    preview: settingForm.childModels["avatar_preview"][0],
				    saveBtn: settingForm.childEvents["avatar_save"][0],
				    resetBtn: settingForm.childEvents["avatar_reset"][0],
				    saveTip: M.getEventArgs( settingForm.childEvents["avatar_save"][0] ).tip,

					// 数据
				    selectEnd: {
				    	avatarX1: settingForm.childEvents["avatar_x1"][0],
				    	avatarY1: settingForm.childEvents["avatar_y1"][0],
				    	avatarX2: settingForm.childEvents["avatar_x2"][0],
				    	avatarY2: settingForm.childEvents["avatar_y2"][0],
				    	avatarW: settingForm.childEvents["avatar_w"][0],
				    	avatarH: settingForm.childEvents["avatar_h"][0]
				    }
				});
			// });
		}
	}
});

})();