<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-primary js-header-menu-section">
    <div class="container">
        <div class="d-lg-none">
            <button class="navbar-toggler js-menu-open-button border-0 p-0" type="button">
                <i class="fal fa-bars"></i>
            </button>
            <span class="h6 text-white text-uppercase pl-1">{!! __('messages.main_menu') !!}</span>
        </div>
        <div style="display: none;" class="close_menu_link text-primary d-lg-none">
            <button class="navbar-toggler js-menu-close-button border-0 text-primary p-0" type="button">
                <i class="fal fa-times"></i>
            </button>
            <span class="h6 text-uppercase pl-1">{!! __('messages.close') !!}</span>
        </div>
        <div class="collapse navbar-collapse justify-content-lg-center js-header-menus">
            <ul class="navbar-nav">
                @foreach($menu_items as $item)
                    @if(!in_array($item['page_name'], config('wot.hide_header_menus')))
                        @php($childRoutes = array_key_exists($item['name'], config('wot.parents_child_routes')) ? config('wot.parents_child_routes.' . $item['name']) : [])
                        <li class="nav-item{{ (isset($item['children']) && count($item['children']) > 0) ? ' dropdown' : '' }}{{ ( ($item['accessible_routes'] && in_array(Route::currentRouteName(), $item['accessible_routes'])) || in_array(Route::currentRouteName(), $childRoutes)) ? ' active' : '' }}">
                            @if(isset($item['children']) && count($item['children']) > 0)
                                <a class="nav-link dropdown-toggle" href="{{  ($item['accessible_routes'] == null || in_array(Route::currentRouteName(), $item['accessible_routes'])) ? 'javascript:void(0)' : route(config('wot.page_routes')[$item['name']], ['domain' => $websiteDomain]) }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{!! __('messages.' . $item['name']) !!}</a>
                                <div class="dropdown-menu">
                                    <ul>
                                        <li class="nav-value d-none d-lg-block">
                                            <a href="{{  ($item['accessible_routes'] == null || in_array(Route::currentRouteName(), $item['accessible_routes'])) ? 'javascript:void(0)' :route(config('wot.page_routes')[$item['name']], ['domain' => $websiteDomain]) }}" class="current-tab">{!! __('messages.' . $item['name']) !!}</a>
                                        </li>
                                        @foreach($item['children'] as $childItem)
                                            <li>
                                                @if($childItem['is_additional_page'] == 1)
                                                    <a href="{{ app()->request->route('additionalPageName') == $childItem['page_name'] ? 'javascript:void(0)' :'/' . LaravelLocalization::getCurrentLocale() .$childItem['url'] }}" class="{{ app()->request->route('additionalPageName') == $childItem['page_name'] ? 'active' : '' }}">{{ $childItem['title'] }}</a>
                                                @else
                                                    <a href="{{ in_array($item['accessible_routes'] && Route::currentRouteName(), $childItem['accessible_routes']) ? 'javascript:void(0)' : '/' . LaravelLocalization::getCurrentLocale() .$childItem['url'] }}" class="{{ in_array(Route::currentRouteName(), $childItem['accessible_routes']) ? 'active' : '' }}">{!! __('messages.' . $childItem['name']) !!}</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a class="nav-link" href="{{ (in_array(Route::currentRouteName(), $item['accessible_routes']) || in_array(Route::currentRouteName(), $childRoutes)) ? 'javascript:void(0)' : route(config('wot.page_routes')[$item['name']], ['domain' => $websiteDomain]) }}">{!! __('messages.' . $item['name']) !!}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<!-- End of navbar -->
