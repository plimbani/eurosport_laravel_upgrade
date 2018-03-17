<!-- Header -->
<header>
    <div class="container">
        <div class="img-logo">
            <a href="{{ url('/') }}"><img src="{{ asset('frontend/images/logo-header.svg') }}" alt=""></a>
        </div>
        <div class="text-uppercase text-white">
            <div class="d-flex align-items-center justify-content-end">
                <span class="mr-2 small">Language</span>
                <div class="lang-bar">
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
