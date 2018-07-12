/*
 * 头像上传
 *
 * @param object args:
 * {
 * 	   
 * }
 *
 *
 */
var avatar = function( args ) {
		// 上传表单
	var uploadForm = args.uploadForm,
		uploadBtn = args.uploadBtn,
		loading = args.loading,

		// 浏览头像
		scanImg = args.scanImg,

		// 设置表单
	    settingForm = args.settingForm,
	    picUrl = args.picUrl,
	    picWidth = args.picWidth,
	    area = args.area,
	    preview = args.preview,
	    npreview = args.npreview,
	    saveBtn = args.saveBtn,
	    resetBtn = args.resetBtn,
	    saveTip = args.saveTip || "Sure to save photo?",

		// 数据
	    selectEnd = args.selectEnd,

		up_pic_width = 640,
		up_pic_height = 215,

		imgrs,

		tmpDate = new Date(),

		// 调用jQuery 插件所需
		$area,
		$area_img,
		$preview_img,
		$npreview_img,

		set_UP_W_H = function( w, h ){
			// up_pic_width = w;
			// up_pic_height = h;
			up_pic_width = 640;
			up_pic_height = Math.floor(640 * h / w);
		},

		onSelectEnd = function( img, selection ) {
		    selectEnd["avatarX1"].value = selection.x1;
		    selectEnd["avatarY1"].value = selection.y1;
		    selectEnd["avatarX2"].value = selection.x2;
		    selectEnd["avatarY2"].value = selection.y2;
		    selectEnd["avatarW"].value = selection.width;
		    selectEnd["avatarH"].value = selection.height;
        },

		previewFn = function( img, selection ) {
        	onSelectEnd( img, selection );
		    var big_scaleX = 640 / ( selection.width || 1 ),
		    	big_scaleY = 215 / ( selection.height || 1 );

		    $preview_img.css({
		        width: Math.round( big_scaleX * up_pic_width ) + 'px',
		        height: Math.round( big_scaleY * up_pic_height ) + 'px',
		        marginLeft: '-' + Math.round( big_scaleX * selection.x1 ) + 'px',
		        marginTop: '-' + Math.round( big_scaleY * selection.y1 ) + 'px'
		    });
		    $npreview_img.css({
		        width: Math.round( big_scaleX * up_pic_width ) + 'px',
		        height: Math.round( big_scaleY * up_pic_height ) + 'px',
		        marginLeft: '-' + Math.round( big_scaleX * selection.x1 +212.5) + 'px',
		        marginTop: '-' + Math.round( big_scaleY * selection.y1 ) + 'px'
		    });
        };

	// 头像上传 [选择图片后自动上传]
	uploadBtn.onchange = function() {
	    $('#button').css('background','#CCC');
	    $('#button').attr('onclick','noisupload()');
		//文件类型检验
		var checkFile=function(){
			var filename = $(uploadBtn).val();
			var pos = filename.lastIndexOf(".");  
		    var str = filename.substring(pos, filename.length)  
		    var str1 = str.toLowerCase();  
		    if (!/\.(gif|jpg|jpeg|png)$/.test(str1)) {  
		    	uploadBtn.value = '';
		    	$(uploadBtn).val('');
		        return false;  
		    } 
		    return true;
		};

		if(!checkFile()){
			ui.error( L('PUBLIC_UPDATE_TYPE_TIPS') );
			return false;
		}

	    uploadBtn.style.display = "none";
	    loading.style.display = "block";
		// 异步提交头像
		
		//M.getJS( THEME_URL + "/js/jquery.form.js?"+SYS_VERSION, function() {
            var options = {
                success: function( txt ) {
                	txt = eval("(" + txt + ")");
                	if ( 1 == txt.status ) {
                		// 头像切割
                		M.getCSS( THEME_URL + "/js/imgareaselect/css/imgareaselect-default.css" );
                		M.getJS( THEME_URL + "/js/imgareaselect/jquery.imgareaselect.min.js", function() {
				            set_UP_W_H(txt.data["picwidth"],txt.data["picheight"]);
				            picUrl.value = txt.data["picurl"];
				            picWidth.value = txt.data["picwidth"];
				            if(txt.data["picwidth"]<640 || txt.data["picheight"]<215){
				            alert('您上传的图片宽度为：'+txt.data["picwidth"]+'像素，高度为：'+txt.data["picheight"]+'像素,不符合要求为了更好的体验效果请更换大图！');
                		    }
				            area.innerHTML = "<img width=640 src=\"" + txt.data["fullpicurl"] + "?t=" + tmpDate.getTime() + "\" />";
				            preview.innerHTML = "<img  src=\"" + txt.data["fullpicurl"] + "?t=" + tmpDate.getTime() + "\" />";
				            npreview.innerHTML = "<img  src=\"" + txt.data["fullpicurl"] + "?t=" + tmpDate.getTime() + "\" />";

				            $area = $( area );
							$area_img = $( area.getElementsByTagName("img")[0] );
							$preview_img = $( preview.getElementsByTagName("img")[0] );
							$npreview_img = $( npreview.getElementsByTagName("img")[0] );

							var now = 640;
							var standard = (up_pic_width > up_pic_height) ? up_pic_height : up_pic_width;
							standard < now && (now = standard);

				            imgrs = $area_img.imgAreaSelect({ 
		                        x1: 0, 
		                        y1: 0,
		                        x2: 640, //初始矩形宽
		                        y2: 215, //初始矩形高
		                        handles: true,
		                        onInit: previewFn,
		                        onSelectChange: previewFn,
		                        onSelectEnd: onSelectEnd,
		                        aspectRatio: '640:215',	//头像长宽比
		                        instance:true,
		                        persistent:false,
		                        resizable:false,
		                        parent:$area
		                    });
				            // 隐藏上传表单，显示设置表单
						    uploadForm.style.display = "none";
						    settingForm.style.display = "block";
                		});
						uploadBtn.value = '';
		    			$(uploadBtn).val('');
                	} else {
                		ui.error(txt.info);
                	}
                	uploadBtn.style.display = "block";
                	loading.style.display = "none";
					//uploadForm.reset();
                }
            };
            $( uploadForm ).ajaxSubmit( options );
		// });
		return false;
	};

	// 头像保存 [点击]
	saveBtn.onclick = function() {
		var args = M.getEventArgs(this);

		// if ( ! confirm( saveTip ) ) {
		// 	return false;
		// }
		// M.getJS( THEME_URL + "/js/jquery.form.js", function() {
	        var options = {
                success: function( txt ) {
                	//fuck 迅雷. 居然自动在程序返回值中加html代码
                	txt = strip_tags(txt);
				    txt = eval("(" + txt + ")");
				    if ( 1 == txt.status ) {
				    	var l = scanImg.length,
				    		time = tmpDate.getTime();
				    	while ( l -- > 0) {
				    		switch ( scanImg[l].size ) {
				    			case "big":
				    				scanImg[l].img.src = txt.data.big + "?t=" + time;
				    				$('#avatar_big').val(txt.data.big);
				    				$('#show_big').attr('src',txt.data.big);
				    				break;
				    			case "middle":
				    				scanImg[l].img.src = txt.data.middle + "?t=" + time;
				    				$('#avatar_middle').val(txt.data.middle);
				    				$('#show_middle').attr('src',txt.data.middle);
				    				break;
				    			case "small":
				    				scanImg[l].img.src = txt.data.small + "?t=" + time;
				    				break;
				    			default:
				    				;
				    		}
				    	}
				    } else {
				        ui.error( txt.info );
				    }
				    avatar_success(txt.data);
		            // 显示上传表单，隐藏设置表单
				    settingForm.style.display = "none";
				    uploadForm.style.display = "block";
				    $('.cut-1').css('display','block');
				    $('#button').css('background','#0096e6');
				    $('#button').attr('onclick','dosubmit()');
                }
            };
	        $( settingForm ).ajaxSubmit( options );
		// });
		return false;
	};

	// 头像重置 [点击]
	resetBtn.onclick = function() {
        // 显示上传表单，隐藏设置表单
        uploadBtn.value = '';
        $(uploadBtn).val('');
	    settingForm.style.display = "none";
	    uploadForm.style.display = "block";
	    $('#button').css('background','#0096e6');
	    $('#button').attr('onclick','dosubmit()');
	    return false;
	}
};