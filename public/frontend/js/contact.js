var Contact = function() {
	var handleValidation = function() {
		$('.js-frm-create-inquiry').validate({
			ignore: [],
            lang: 'fi',
			errorClass: 'invalid-feedback animated fadeInDown',
            messages: {
            },
            rules: {
            	name: {
            		required: true
            	},
            	email: {
            		required: true,
            		email: true
            	},
            	telephone_number: {
            		required: true
            	},
            	subject: {
            		required: true
            	},
            	message: {
            		required: true
            	}
            },
            errorPlacement: function(error, e){
                $(e).parent().append(error);
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                $(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
            },
            submitHandler: function (form) {
                if (grecaptcha.getResponse().length === 0) {
                    return false;
                }
                $('.js-contact-frm-submit-btn').attr('disabled', 'true');
                if(Site.googleAnalyticsId !== null) {
                    gtag('event', 'submit', { event_category: 'form', event_label: 'contact'});
                }
                $.ajax({
                    url: "/"+ Site.currentLocale + "/submitInquiry",
                    method: 'POST',
                    data: $('.js-frm-create-inquiry').serialize(),
                    success: function(response){
                        $('.js-contact-frm-submit-btn').removeAttr('disabled');
                        $('input[type=text], textarea').val('');
                        $('.js-frm-create-inquiry').hide();
                        $('.js-inquiry-success-message').show();
                        grecaptcha.reset();
                    }
                });
            }
		});
	};
	return {
		init: function() {
			handleValidation();
		}
	}
}();

$(document).ready(function() {
	Contact.init();

    $(document).on('click', '.js-submit-inquiry', function() {
        $('.recaptcha-errorspan').hide();
        if (grecaptcha.getResponse().length === 0) {
            $('.recaptcha-errorspan').show();
            return;
        }
    });
});