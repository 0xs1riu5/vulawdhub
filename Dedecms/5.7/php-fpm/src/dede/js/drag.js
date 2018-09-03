var $ = jQuery;
var disabelsort = true; //是否停止使用块级移动
$(function(){
	$('.dbox')
	.each(function(){
		$(this).hover(function(){
			$(this).find('dt').addClass('collapse');
		}, function(){
			$(this).find('dt').removeClass('collapse');
		})
		//.find('h2').hover(function(){
		//	$(this).find('.configure').css('visibility', 'visible');
		//}, function(){
		//	$(this).find('.configure').css('visibility', 'hidden');
		//})
		.click(function(){
			$(this).siblings('dd').toggle();
		})
		.end()
		//.find('.configure').css('visibility', 'hidden');
	});
	$('.column').sortable({
		connectWith: '.column',
		handle: 'dt',
		disable:true,
		cursor: 'pointer',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,
		stop: function(event, ui){
			$(ui.item).find('h2').click();
			var items=[];
			$('.column').each(function(){
				var columnId=$(this).attr('id');
				$('.dbox', this).each(function(i){
					var item={
						id: $(this).attr('id'),
						order : i,
						column: columnId
					};
					items.push(item);
				});
			});
			var sortorder = { items: items };
			$.post('index_body.php?dopost=movesave', 'sortorder='+$.toJSON(sortorder), function(response){
        	});
		}
	})
	.disableSelection();
	if(disabelsort) $('.column').sortable("option", "disabled", true );
});

