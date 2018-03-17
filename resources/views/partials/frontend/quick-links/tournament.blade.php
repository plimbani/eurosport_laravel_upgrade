<div class="hero__wrapper-child">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <ul class="nav align-items-center justify-content-center">
                    @if(in_array('rules.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'rules.page.details' ? 'active' : '' }}" href="{{ route('rules.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="fas fa-utensils"></i></span>
                                <span>{!! __('messages.rules') !!}</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('history.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'history.page.details' ? 'active' : '' }}" href="{{ route('history.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="fas fa-bed"></i></span>
                                <span>{!! __('messages.history') !!}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
