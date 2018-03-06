<nav class="navbar navbar-default">
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
</nav>