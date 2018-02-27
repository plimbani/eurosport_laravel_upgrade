// teams list more less

$(document).ready(function() {
	$('ul.js-list').each(function(){
		var teamsLength = $(this).find('li').length;
		if(teamsLength > 5) {
			$('li', this).eq(4).nextAll().hide().addClass('toggleable');
		  $(this).append('<li class="more">More...</li>');
		}
	});

	$(document).on('click', 'ul.js-list .more', function() {
		if($(this).hasClass('less')){
			$(this).text('More...').removeClass('less');
		} else {
			$(this).text('Less...').addClass('less');
		}
		$(this).siblings('li.toggleable').slideToggle();
	});
});