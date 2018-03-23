<!-- Header -->
<header>
    <div class="container">
        <div class="img-logo">
            <a href="{{ route('home.page.details', ['domain' => $websiteDetail->domain_name]) }}"><img src="{{ asset('frontend/images/logo-header.svg') }}" alt=""></a>
        </div>
        <div class="text-uppercase text-white">
            <div class="d-flex align-items-center justify-content-end">
                <span class="mr-2 h7">{!! __('messages.language') !!}</span>
                <div class="lang-bar js-locale-selection">
                    <div class="lang-bar-status">
                        <span class="font-weight-bold">{{ strtoupper(LaravelLocalization::getCurrentLocale()) }}</span>
                        <span>
                          <i class="fas fa-caret-down"></i>
                        </span>
                    </div>
                    <div class="lang-bar-list">
                        <ul>
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <li>
                                  <a hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="{{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'active ' : '' }}">{{ $properties['native'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End of Header -->
