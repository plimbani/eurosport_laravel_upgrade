@extends('layouts.frontend.home')

@section('content')
	<!-- Content wrapper for desktop -->
    <div class="content__wrapper d-none d-lg-block">
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-3">
                    <div class="row mb-4">
                        <div class="col-12 text-center text-uppercase text-muted">
                            <div class="h8">{!! __('messages.organised_by') !!}</div>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center organiser">
                        @foreach($organisers as $organiser)
                            <div class="col-6 col-md-3 col-lg-12">
                                <div class="organiser_logo">
                                    <img src="{{ $organiser->organiserLogo('thumbnail') }}" alt="{{ $organiser->name }}" class="mx-auto d-block">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-8 club_content {{ $brand_font_class }}">
                    <figure class="figure right pl-gg">
                        @if($pageDetails->welcomeImage('thumbnail'))
                            <img src="{{ $pageDetails->welcomeImage('thumbnail') }}" class="figure-img img-fluid ml-1" alt="{!! __('messages.welcome_image') !!}" />
                        @endif
                    </figure>
                    {!! $pageDetails->content !!}
                </div>
            </div>
        </div>
    </div>
    <!-- End of content wrapper for desktop -->
    <!-- Content wrapper for mobile -->
    <div class="content__wrapper d-block d-lg-none">
        <div class="container">
            <div class="row my-5">
                <div class="col-12">
                    <div class="row mb-4">
                        <div class="col-12 text-center text-uppercase text-muted">
                            <div class="h8">{!! __('messages.organised_by') !!}</div>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center organiser">
                        @foreach($organisers as $organiser)
                            <div class="col-6 col-md-3 col-lg-12">
                                <div class="organiser_logo">
                                    <img src="{{ $organiser->organiserLogo('thumbnail') }}" alt="{{ $organiser->name }}" class="mx-auto d-block">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <figure class="figure-thumb">
            <img src="{{ $pageDetails->welcomeImage('medium') }}" class="figure-img img-fluid" alt="{!! __('messages.welcome_image') !!}">
        </figure>
        <div class="container">
            <div class="row">
                <div class="col-12 club_content {{ $brand_font_class }}">
                    {!! $pageDetails->content !!}
                </div>
            </div>
        </div>
    </div>
    <!-- End of content wrapper for mobile -->
@endsection
