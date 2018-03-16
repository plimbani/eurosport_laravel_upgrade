<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-primary">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-lg-center" id="navbarText">
            <ul class="navbar-nav">
                @foreach($menu_items as $item)
                    @if(!in_array($item['page_name'], config('wot.hide_header_menus')))
                        <li class="nav-item{{ (isset($item['children']) && count($item['children']) > 0) ? ' dropdown' : '' }}{{ in_array(Route::currentRouteName(), $item['accessible_routes']) ? ' active' : '' }}">
                            @if(isset($item['children']) && count($item['children']) > 0)
                                <a class="nav-link dropdown-toggle" href="{{ $item['url'] }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $item['title'] }}</a>
                                <div class="dropdown-menu">
                                    <ul>
                                        <li class="nav-value">
                                            <a href="{{ $item['url'] }}" class="current-tab">{{ $item['title'] }}</a>
                                        </li>
                                        @foreach($item['children'] as $childItem)
                                            <li>
                                                <a href="{{ $childItem['url'] }}" class="{{ in_array(Route::currentRouteName(), $childItem['accessible_routes']) ? 'active' : '' }}">{{ $childItem['title'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a class="nav-link" href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<!-- End of navbar -->
