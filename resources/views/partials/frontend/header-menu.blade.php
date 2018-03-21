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
                        @php($childRoutes = array_key_exists($item['name'], config('wot.parents_child_routes')) ? config('wot.parents_child_routes.' . $item['name']) : [])
                        <li class="nav-item{{ (isset($item['children']) && count($item['children']) > 0) ? ' dropdown' : '' }}{{ (in_array(Route::currentRouteName(), $item['accessible_routes']) || in_array(Route::currentRouteName(), $childRoutes)) ? ' active' : '' }}">
                            @if(isset($item['children']) && count($item['children']) > 0)
                                <a class="nav-link dropdown-toggle" href="{{ route(config('wot.page_routes')[$item['name']], ['domain' => $websiteDetail->domain_name]) }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{!! __('messages.' . $item['name']) !!}</a>
                                <div class="dropdown-menu">
                                    <ul>
                                        <li class="nav-value">
                                            <a href="{{ route(config('wot.page_routes')[$item['name']], ['domain' => $websiteDetail->domain_name]) }}" class="current-tab">{!! __($item['title']) !!}</a>
                                        </li>
                                        @foreach($item['children'] as $childItem)
                                            <li>
                                                @if($childItem['is_additional_page'] == 1)
                                                    <a href="{{ '/' . LaravelLocalization::getCurrentLocale() .$childItem['url'] }}" class="{{ app()->request->route('additionalPageName') == $childItem['page_name'] ? 'active' : '' }}">{{ $childItem['title'] }}</a>
                                                @else
                                                    <a href="{{ route(config('wot.page_routes')[$childItem['name']], ['domain' => $websiteDetail->domain_name]) }}" class="{{ in_array(Route::currentRouteName(), $childItem['accessible_routes']) ? 'active' : '' }}">{!! __('messages.' . $childItem['name']) !!}</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a class="nav-link" href="{{ route(config('wot.page_routes')[$item['name']], ['domain' => $websiteDetail->domain_name]) }}">{!! __('messages.' . $item['name']) !!}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<!-- End of navbar -->
