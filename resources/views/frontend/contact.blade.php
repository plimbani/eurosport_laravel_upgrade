@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.contact') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            	<div class="col-lg-12 club_content contact-information">
	                <div class="row">
						<div class="col-md-6">
							<h6 class="font-weight-bold">{!! __('messages.tournament_organiser') !!}</h6>
							<div class="{{ $brand_font_class }}">
								<p class="h6"> {{ $contactPageDetail->contact_name }}<br>
								{!! nl2br($contactPageDetail->address) !!}</p>
								<p class="h6"> {{ $contactPageDetail->email_address }}</p>
								<p class="h6">{{ $contactPageDetail->phone_number }}</p>
							</div>
						</div>
						<div class="col-md-6">
							<h6 class="font-weight-bold mb-3 mt-3 mt-md-0">{!! __('messages.contact_euro_sportring') !!}</h6>
							<div class="row">
								<div class="col-md-12">
									<div class="alert alert-success js-inquiry-success-message" role="alert" style="display: none">
										{!! __('messages.inquiry_form_success_message') !!}
									</div>
								</div>
							</div>

							{!! Form::open(['class' => 'js-frm-create-inquiry form-horizontal', 'role' => 'form']) !!}
								<div class="form-body">
									<div class="form-group">
										{!! Form::text('name', null,['class' => 'form-control',
										'id' => 'contact_name' ,'placeholder' => __('messages.contact_form_name') ]) !!}										
									</div>
									<div class="form-group">
										{!! Form::text('email', null,['class' => 'form-control',
										'id' => 'contact_email' ,'placeholder' => __('messages.contact_form_email')  ]) !!}
									</div>
									<div class="form-group">
										{!! Form::text('telephone_number', null,['class' => 'form-control',
										'id' => 'contact_telephone_number' ,'placeholder' => __('messages.contact_form_telephone') ]) !!}
									</div>
									<div class="form-group">
										{!! Form::text('subject', null,['class' => 'form-control',
										'id' => 'contact_subject' ,'placeholder' => __('messages.contact_form_subject')]) !!}
									</div>
									<div class="form-group">
										{!! Form::textarea('message', null,['class' => 'form-control',
										'id' => 'contact_message' ,'placeholder' => __('messages.contact_form_message')]) !!}
									</div>
									<div class="form-group">
										<div class="g-recaptcha" data-sitekey="{{ config('wot.google_re_captcha_site_key') }}"></div>
										<span class="recaptcha-errorspan" style="color:#f00; display: none;">{!! __('messages.recaptcha_error_message') !!}</span>
									</div>
								</div>
								<div class="form-actions">
								    <div class="row">
								        <div class="col-md-12 text-center text-sm-right">
								            <button type="submit" class="position-relative btn button btn-primary js-contact-frm-submit-btn btn-round text-uppercase text-left font-weight-bold">{!! __('messages.contact_form_send_btn') !!}</button>
								        </div>
								    </div>
								</div>
							{{ Form::close() }}
						</div>
					</div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection


@section('page-scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js" type="text/javascript"></script>
    <script src='//www.google.com/recaptcha/api.js'></script>
	<script src="{{ asset('frontend/js/contact.js') }}"></script>
@endsection