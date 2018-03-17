<div class="hero__wrapper-child">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <ul class="nav align-items-center justify-content-center">
                    @if(in_array('meals.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'meals.page.details' ? 'active' : '' }}" href="{{ route('meals.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="fas fa-utensils"></i></span>
                                <span>{!! __('messages.meals') !!}</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('accommodation.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'accommodation.page.details' ? 'active' : '' }}" href="{{ route('accommodation.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="fas fa-bed"></i></span>
                                <span>{!! __('messages.accommodation') !!}</span>
                            </a>
                        </li>
                    @endif
                    @foreach($additionalPages as $additionalPage)
                        @if($additionalPage->is_published == 1)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('additional.stay.page.details', ['domain' => $websiteDetail->domain_name, 'additionalPageName' => $additionalPage->name]) }}">
                                    <span class="mr-2"><i class="fas fa-file-alt"></i></span>
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
