<ul>
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