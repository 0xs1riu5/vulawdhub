// PlotChart报表图形Js
var plotChart = function(){};
// 添加对象的原生方法
plotChart.prototype = {
	// 报表配置
	options: {
		show: false,
		location: 'e'
	},
	// 设置报表的参数
	setOptions: function(args){
		this.options.id = args.id;
		this.options.type = args.type;
		switch(this.options.type) {
			case 'pieChart':
				var key = args.key.split(',');
				var value = args.value.split(',');
				var color = args.color.split(',');
				var data = new Array();
				for(var i in key) {
					data.push([key[i], parseInt(value[i])]);
				}
				// 配置显示数据
				this.options.data = data;
				// 配置显示数据颜色
				this.options.seriesColors = color;
				this.options.show = args.show;
				this.options.location = args.location;
				break;
			case 'pointLabelsChart':
				// 配置显示数据
				this.options.data = args.value.split(',');
				// 报表表头
				this.options.title = args.title;
				break;
			case 'barChart':
				// 配置显示数据
				this.options.value = args.value.split(',');
				this.options.labels = args.key.split(',');
				// 报表表头
				this.options.title = args.title;
				break;
		}
	},
	// 获取对应报表的数据
	// getData: function(args) {

	// },
	// 设置图形类型
	setType: function(type) {
		switch(type) {
			case 'pieChart':
				this.options.fileUrl = ['jqplot.pieRenderer.min.js'];
				this.pieChart();
				break;
			case 'pointLabelsChart':
				this.options.fileUrl = ['jqplot.pointLabels.min.js'];
				
				this.pointLabelsChart();
				break;
			case 'barChart':
				this.options.fileUrl = ['jqplot.barRenderer.min.js', 'jqplot.categoryAxisRenderer.min.js', 'jqplot.pointLabels.min.js'];
				this.barChart();
				break;
		}

	},
	// 对应Js插件引入
/*	loadJsPlugins: function(){
		var url = THEME_URL + '/js/jqplot/plugins/';
		document.write('<!--[if lt IE 9]><script type="text/javascript" src="' + THEME_URL + '/js/jqplot/excanvas.min.js"></script><![endif]-->');
		document.write('<script type="text/javascript" src="' + THEME_URL + '/js/jqplot/jquery.jqplot.min.js"></script>');
		for(var i in this.options.fileUrl) {
			document.write('<script type="text/javascript" src="' + url + this.options.fileUrl[i] +'"></script>');
		}
		document.write('<link rel="stylesheet" type="text/css" href="' + THEME_URL + '/js/jqplot/jquery.jqplot.min.css" />');
	},*/
	// 饼状图实现
	pieChart:function(){
		$.jqplot('plot_' + this.options.type +'_' + this.options.id, [this.options.data], 
		{
			seriesDefaults: {
				renderer: jQuery.jqplot.PieRenderer,
				rendererOptions: {
					showDataLabels: true,
					diameter: 80,
					shadow: false,
					sliceMargin: 1
				}
			},
			legend: { show: this.options.show, location: this.options.location },
			seriesColors: this.options.seriesColors,
			grid: {
	            drawBorder: false,
	            drawGridlines: false,
	            background: '#FFFFFF',
	            shadow:false
	        }
	    });
	},
	// 折线图实现
	pointLabelsChart:function(){
		$.jqplot('plot_' + this.options.type +'_' + this.options.id, [this.options.data], 
		{
			title: this.options.title,
			seriesDefaults: {
				showMarker: false,
				pointLabels: {
					show: true
				}
			}
		});
	},
	// 柱状图实现
	barChart:function(){
		$.jqplot('plot_' + this.options.type +'_' + this.options.id, [this.options.data], 
		{
			title: this.options.title,
			seriesDefaults: {
				renderer: $.jqplot.BarRenderer
			},
			// series: [
			// 	{
			// 		pointLabels: {
			// 			show: true,
			// 			labels: this.options.labels
			// 		}
			// 	}
			// ],
			axes: {
				xaxis: {
					renderer: $.jqplot.CategoryAxisRenderer
				},
				yaxis: {
					padMax: 1.3
				}
			}
		});
	},
	// 显示图形
	showPlot: function(args) {
		this.setOptions(args);
		this.setType(this.options.type);
	}
};