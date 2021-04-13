$(document).ready(function() {
	var set = $('#trombi .item');
	set.each(function() {
		var data = {};
		$(this).find('span').each(function() {
			data[$(this).attr('title')] = $(this).text();
		});
		
		$(this).hover(
			function() {
				$(this).find('img').first().animate({width:'90px'}, 200);
				
				var index = set.index(this);
				displayInfos(data, index);
			},
			function() {
				$(this).find('img').first().animate({width:'80px'}, 200);
				$('#infos').css('display', 'none');
			}
		);
		
		var string = '';
		for (var v in data) {
			string += v+': '+data[v]+'<br />';
		}
		
		var img = $(this).find('img').first();
		
		$(this).fancybox({
			'hideOnContentClick': false,
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	600, 
			'speedOut'		:	200,
			'content': '<img style="width: 150px" src="'+$(img).attr('src')+'" alt="'+$(img).attr('alt')+'" class="fleft" /><div class="fleft">'+string+'</div><div class="spacer"></div>'
		});
	});
	
	function displayInfos(data, index) {
		for (var v in data) {
			$('#infos_'+v).text(data[v]);
		}
		var marginTop =  parseInt(index/3)*120;
		marginTop-=600;
		$('#infos').css('margin-top',"-110px");// marginTop+'px');
		$('#infos').css('top',marginTop+"px");
		$('#infos').css('display', 'block');
	}
});