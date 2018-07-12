//追加核心图片选择对象 临时放在这里 
core.image ={
		//给工厂调用的接口
		_init:function(attrs){
			if(attrs.length == 3){
				core.image.init(attrs[1],attrs[2]);
			}else{
				return false;	//只是未了加载文件
			}
		},
		init:function(img,type){
			var n = img.getAttribute('step');
			// 保存图片大小数据
			if (!this.data('width') && !$(this).data('height')) {
				this.data('width', img.width);
				this.data('height', img.height);
			};
			this.data('maxWidth',img.getAttribute('maxWidth'))

			if(n == null) n = 0;
			if(p == 'left'){
				(n == 0)? n = 3 : n--;
			}else if(p == 'right'){
				(n == 3) ? n = 0 : n++;
			};
			img.setAttribute('step', n);

			// IE浏览器使用滤镜旋转
			if(document.all) {
				if(this.data('height')>this.data('maxWidth') && (n==1 || n==3) ){
					if(!this.data('zoomheight')){
						this.data('zoomwidth',this.data('maxWidth'));
						this.data('zoomheight',(this.data('maxWidth')/this.data('height'))*this.data('width'));
					}
					img.height = this.data('zoomwidth');
					img.width  = this.data('zoomheight');
					
				}else{
					img.height = this.data('height');
					img.width  = this.data('width');
				}
				
				img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ n +')';
				// IE8高度设置
				if ($.browser.version == 8) {
					switch(n){
						case 0:
							this.parent().height('');
							//this.height(this.data('height'));
							break;
						case 1:
							this.parent().height(this.data('width') + 10);
							//this.height(this.data('width'));
							break;
						case 2:
							this.parent().height('');
							//this.height(this.data('height'));
							break;
						case 3:
							this.parent().height(this.data('width') + 10);
							//this.height(this.data('width'));
							break;
					};
				};
			// 对现代浏览器写入HTML5的元素进行旋转： canvas
			}else{
				var c = this.next('canvas')[0];
				if(this.next('canvas').length == 0){
					this.css({'visibility': 'hidden', 'position': 'absolute'});
					c = document.createElement('canvas');
					c.setAttribute('class', 'maxImg canvas');
					img.parentNode.appendChild(c);
				}
				var canvasContext = c.getContext('2d');
				switch(n) {
					default :
					case 0 :
						img.setAttribute('height',this.data('height'));
						img.setAttribute('width',this.data('width'));
						c.setAttribute('width', img.width);
						c.setAttribute('height', img.height);
						canvasContext.rotate(0 * Math.PI / 180);
						canvasContext.drawImage(img, 0, 0);
						break;
					case 1 :
						if(img.height>this.data('maxWidth') ){
							h = this.data('maxWidth');
							w = (this.data('maxWidth')/img.height)*img.width;
						}else{
							h = this.data('height');
							w = this.data('width');
						}
						c.setAttribute('width', h);
						c.setAttribute('height', w);
						canvasContext.rotate(90 * Math.PI / 180);
						canvasContext.drawImage(img, 0, -h, w ,h );
						break;
					case 2 :
						img.setAttribute('height',this.data('height'));
						img.setAttribute('width',this.data('width'));
						c.setAttribute('width', img.width);
						c.setAttribute('height', img.height);
						canvasContext.rotate(180 * Math.PI / 180);
						canvasContext.drawImage(img, -img.width, -img.height);
						break;
					case 3 :
						if(img.height>this.data('maxWidth') ){
							h = this.data('maxWidth');
							w = (this.data('maxWidth')/img.height)*img.width;
						}else{
							h = this.data('height');
							w = this.data('width');
						}
						c.setAttribute('width', h);
						c.setAttribute('height', w);
						canvasContext.rotate(270 * Math.PI / 180);
						canvasContext.drawImage(img, -w, 0,w,h);
						break;
				};
			};
		}
};	