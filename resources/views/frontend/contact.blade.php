@extends('layouts.frontend')

@section('content')
	<h2 class="text-center">{!! __('messages.contact') !!}</h2>
	<div class="container contacts-page">
		<div class="row">
			<div class="col-md-6">
				<h6>{!! __('messages.tournament_organiser') !!}</h6>
				<div>
					<p> {{ $contactPageDetail->contact_name }}<br>
					{!! nl2br($contactPageDetail->address) !!}</p>
					<p> {{ $contactPageDetail->email_address }}</p>
					<p>{{ $contactPageDetail->phone_number }}</p>
				</div>
			</div>
			<div class="col-md-6">
				<h6>{!! __('messages.contact_euro_sportring') !!}</h6>
				<div class="row">
					<div class="col-md-6">
						<div class="alert alert-success js-inquiry-success-message" role="alert" style="display: none">
							{!! __('messages.inquiry_form_success_message') !!}
						</div>
					</div>
				</div>

				{!! Form::open(['class' => 'js-frm-create-inquiry form-horizontal', 'role' => 'form']) !!}
					<div class="form-body">
						<div class="form-group">
							<div class="col-md-9">
								{!! Form::text('name', null,['class' => 'form-control',
								'id' => 'contact_name' ,'placeholder' => __('messages.contact_form_name') ]) !!}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-9">
								{!! Form::text('email', null,['class' => 'form-control',
								'id' => 'contact_email' ,'placeholder' => __('messages.contact_form_email')  ]) !!}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-9">
								{!! Form::text('telephone_number', null,['class' => 'form-control',
								'id' => 'contact_telephone_number' ,'placeholder' => __('messages.contact_form_telephone') ]) !!}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-9">
								{!! Form::text('subject', null,['class' => 'form-control',
								'id' => 'contact_subject' ,'placeholder' => __('messages.contact_form_message')]) !!}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-9">
								{!! Form::textarea('message', null,['class' => 'form-control',
								'id' => 'contact_message' ,'placeholder' => __('messages.contact_form_subject')]) !!}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-9">
								<div class="g-recaptcha" data-sitekey="{{ config('wot.google_re_captcha_site_key') }}"></div>
								<span class="recaptcha-errorspan" style="color:#f00; display: none;">{!! __('messages.recaptcha_error_message') !!}</span>
							</div>
						</div>
					</div>
					<div class="form-actions">
					    <div class="row">
					        <div class="col-md-offset-3 col-md-9">
					            <button type="submit" class="btn button btn-primary js-contact-frm-submit-btn">{!! __('messages.contact_form_send_btn') !!}</button>
					        </div>
					    </div>
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
@endsection

@section('page-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js" type="text/javascript"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
	<script src="{{ asset('assets/js/frontend/contact.js') }}"></script>
@endsection