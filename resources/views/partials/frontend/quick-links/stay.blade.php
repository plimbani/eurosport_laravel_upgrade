<div class="hero__wrapper-child">
    <div class="container">
        <div class="row align-items-center">
            {{ dd($_SERVER['REQUEST_URI']) }}
            <div class="col-lg-12">
                <ul class="nav align-items-center justify-content-center">
                    @if(in_array('visitor.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'visitor.page.details' ? 'active' : '' }}" href="{{ route('visitor.page.details', ['domain' => $websiteDomain]) }}">
                                <span class="icon"><i class="fas fa-key"></i></span>
                                <span>{!! __('messages.visitors') !!}</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('visitor.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('visitor.page.details', ['domain' => $websiteDomain]) . '/#public-transport' ? 'active' : '' }}" href="{{ route('visitor.page.details', ['domain' => $websiteDomain]) }}/#public-transport">
                                <span class="icon"><i class="fas fa-shuttle-van"></i></span>
                                <span>{!! __('messages.public_transport') !!}</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('tourist.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'tourist.page.details' ? 'active' : '' }}" href="{{ route('tourist.page.details', ['domain' => $websiteDomain]) }}">
                                <span class="icon"><i class="fas fa-address-card"></i></span>
                                <span>{!! __('messages.tourist_information') !!}</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('visitor.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('visitor.page.details', ['domain' => $websiteDomain]) . '/#tips' ? 'active' : '' }}" href="{{ route('visitor.page.details', ['domain' => $websiteDomain]) }}/#tips">
                                <span class="icon"><i class="fas fa-utensils"></i></span>
                                <span>{!! __('messages.tips') !!}</span>
                            </a>
                        </li>
                    @endif

                    @if(in_array('accommodation.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'accommodation.page.details' ? 'active' : '' }}" href="{{ route('accommodation.page.details', ['domain' => $websiteDomain]) }}">
                                <span class="icon"><i class="fas fa-bed"></i></span>
                                <span>{!! __('messages.accommodation') !!}</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('meals.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'meals.page.details' ? 'active' : '' }}" href="{{ route('meals.page.details', ['domain' => $websiteDomain]) }}">
                                <span class="icon"><i class="fas fa-utensils"></i></span>
                                <span>{!! __('messages.meals') !!}</span>
                            </a>
                        </li>
                    @endif
                    @foreach($additionalPages as $additionalPage)
                        @if($additionalPage->is_published == 1)
                            <li class="nav-item">
                                <a class="nav-link {{ app()->request->route('additionalPageName') == $additionalPage->page_name ? 'active' : '' }}" href="{{ route('additional.stay.page.details', ['domain' => $websiteDomain, 'additionalPageName' => $additionalPage->page_name]) }}">
                                    <span class="icon"><i class="fas fa-file-alt"></i></span>
                                    <span>{{ $additionalPage->title }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
