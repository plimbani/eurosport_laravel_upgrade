<div class="hero__wrapper-child">
    <div class="container">
        <div class="row align-items-center">
            <div class="grid-22"></div>
            <div class="col-lg-8">
                <ul class="nav align-items-center justify-content-between">
                    @if(in_array('match.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link{{ Route::currentRouteName() == 'match.page.details' ? ' active' : '' }}" href="{{ route('match.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="far fa-calendar-alt"></i></span>
                                <span>Match Schedule</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('team.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link{{ Route::currentRouteName() == 'team.page.details' ? ' active' : '' }}" href="{{ route('team.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="fas fa-shield-alt"></i></span>
                                <span>The teams</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('venue.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link{{ Route::currentRouteName() == 'venue.page.details' ? ' active' : '' }}" href="{{ route('venue.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="fas fa-map-marker-alt"></i></span>
                                <span>The Venue</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('accommodation.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link{{ Route::currentRouteName() == 'accommodation.page.details' ? ' active' : '' }}" href="{{ route('accommodation.page.details', ['domain' => $websiteDetail->domain_name]) }}">
                                <span class="mr-2"><i class="fas fa-suitcase"></i></span>
                                <span>Travel & Accommodation</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
