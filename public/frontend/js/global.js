$(document).ready(function() {
	Lang.setLocale(Site.currentLocale);

	$(document).on('click', 'ul.js-list .more', function() {
		if($(this).hasClass('less')){
			$(this).text(Lang.get('messages.more')).removeClass('less');
		} else {
			$(this).text(Lang.get('messages.less')).addClass('less');
		}
		$(this).siblings('li.toggleable').slideToggle();
	});

	$(".js-locale-selection").on("change", function() {
		window.location.href = $(".js-locale-selection option:selected").data("href");
	});

	// Custom validation messages
	customValidationMessages();

	// More / Less links
	initializeList();
});

function customValidationMessages() {
	if ($.validator) {
		jQuery.extend(jQuery.validator.messages, {
			required: Lang.get('messages.required_field_message')
		});
	}
}

function initializeList() {
	$('ul.js-list').each(function() {
		var teamsLength = $(this).find('li').length;
		$('li.more', this).remove();
		$('li.less', this).remove();
		$('li', this).eq(4).nextAll().show().removeClass('toggleable');
		if(teamsLength > 5) {
			$('li', this).eq(4).nextAll().hide().addClass('toggleable');
			$(this).append('<li class="more">' + Lang.get('messages.more') + '</li>');
		}
	});
}
