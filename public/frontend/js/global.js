$(document).ready(function() {
	Lang.setLocale(Site.currentLocale);

	$(document).on('click', '.js-list-parent-div .more', function() {
		if($(this).hasClass('less')){
			$(this).text(Lang.get('messages.more')).removeClass('less');
		} else {
			$(this).text(Lang.get('messages.less')).addClass('less');
		}
		$(this).closest('.js-list-parent-div').find('ul.js-list li.toggleable').slideToggle();
	});

	$(".js-locale-selection").click(function(e) {
	    $(this).toggleClass('show');
	    e.stopPropagation();
	});

	$('html').click(function() {
		$(".js-locale-selection").removeClass("show");
	});

	$(document).on('mouseenter mouseleave', '.navbar-nav li.dropdown', function(e) {
	    var _d = $(e.target).closest('.dropdown');
	    _d.addClass('show');
	    setTimeout(function() {
	        _d[_d.is(':hover') ? 'addClass' : 'removeClass']('show');
	    }, 50);
	});

	$(document).on('click', '.js-menu-open-button, .js-menu-close-button', function(e) {
		if($('.js-header-menus').hasClass('show')) {
			$('.js-header-menus').collapse('hide');
			$('.js-menu-close-button').parent().hide();
			$('.js-menu-open-button').parent().show();
		} else {
			$('.js-header-menus').collapse('show');
			$('.js-menu-open-button').parent().hide();
			$('.js-menu-close-button').parent().show();
			$('.js-header-menu-section').addClass('mobile-menu-open-background');
		}
	});

	$( window ).resize(function() {
		if($(window).width() < 992) {
			$('.js-header-menus').collapse('hide');
			$('.js-menu-close-button').parent().hide();
			$('.js-menu-open-button').parent().show();
		}
		setHeaderMenu();
	});

	$('.js-header-menus').on('hidden.bs.collapse', function () {
	    $('.js-header-menu-section').removeClass('mobile-menu-open-background');
	});

	// Custom validation messages
	customValidationMessages();

	// More / Less links
	initializeList();

	// Set header menu based on device width
	setHeaderMenu();
});

function customValidationMessages() {
	if ($.validator) {
		jQuery.extend(jQuery.validator.messages, {
			required: Lang.get('messages.required_field_message')
		});
	}
}

function initializeList() {
	$('.js-list-parent-div').find('.more').remove();
	$('.js-list-parent-div').find('.less').remove();
	$('ul.js-list').each(function() {
		var teamsLength = $(this).find('li').length;
		$('li', this).eq(4).nextAll().addClass('d-flex').show().removeClass('toggleable');
		if(teamsLength > 5) {
			$('li', this).eq(4).nextAll().removeClass('d-flex').hide().addClass('toggleable');
			$(this).closest('.js-list-parent-div').append('<button type="button" class="btn btn-outline-primary btn-round px-h4 text-uppercase font-weight-bold more">' + Lang.get('messages.more') + '</button>');
		}
	});
}

function setHeaderMenu() {
	if($(window).width() < 992) {
		$('.js-header-menus ul li.dropdown > a').removeClass('dropdown-toggle').removeAttr('role').removeAttr('data-toggle');
		$('.js-header-menus ul li.dropdown div').removeClass('dropdown-menu');
		$('.js-header-menus ul li.dropdown').addClass('js-small-screen-dropdown-view').removeClass('dropdown');
	} else {
		$('.js-header-menus ul li.js-small-screen-dropdown-view').removeClass('js-small-screen-dropdown-view').addClass('dropdown');
		$('.js-header-menus ul li.dropdown > a').addClass('dropdown-toggle').attr('role', 'button').attr('data-toggle', 'dropdown');
		$('.js-header-menus ul li.dropdown div').addClass('dropdown-menu');
	}
}
