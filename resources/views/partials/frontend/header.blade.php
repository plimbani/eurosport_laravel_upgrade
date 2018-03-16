<!-- Header -->
<header>
    <div class="container">
        <div class="img-logo">
            <img src="{{ asset('images/img/logo-desk.svg') }}" alt="">
        </div>
        <div class="text-uppercase text-white">
            <div class="d-flex align-items-center justify-content-end">
                <span class="mr-2 small">Language</span>
                <div class="lang-bar">
                    <label class="custom_select round custom_select-1" for="styledSelect2" id="LangCode">
                        <select id="styledSelect2" name="options">
                            <option data-text="English" value="EN">
                                English
                            </option>
                            <option data-text="Français" value="FR">
                                Français
                            </option>
                            <option data-text="Español" value="ES">
                                Español
                            </option>
                        </select>
                    </label>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End of Header -->


{{--<nav class="navbar navbar-default">
	<div class="container-fluid">
		<ul class="nav navbar-nav">
		  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
		    <li>
		      <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
		         {{ $properties['native'] }}
		      </a>
		    </li>
		  @endforeach
		</ul>
	</div>
</nav>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<ul class="nav navbar-nav">
			@foreach($menu_items as $item)
				<li>
					<a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
					@if(isset($item['children']) && count($item['children']) > 0)
						<ul class="col-sm-12">
							@foreach($item['children'] as $childItem)
								<li>
									<a href="{{ $childItem['url'] }}">{{ $childItem['title'] }}</a>
								</li>
							@endforeach
						</ul>
					@endif
				</li>
			@endforeach
		</ul>
	</div>
</nav>--}}