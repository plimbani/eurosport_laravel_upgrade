<div class="hero__wrapper-child">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <ul class="nav align-items-center justify-content-center">
                    @if(in_array('rules.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'rules.page.details' ? 'active' : '' }}" href="{{ route('rules.page.details', ['domain' => $websiteDomain]) }}">
                                <span class="icon"><i class="fas fa-list-alt"></i></span>
                                <span>{!! __('messages.rules') !!}</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array('history.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'history.page.details' ? 'active' : '' }}" href="{{ route('history.page.details', ['domain' => $websiteDomain]) }}">
                                <span class="icon"><i class="fas fa-history"></i></span>
                                <span>{!! __('messages.history') !!}</span>
                            </a>
                        </li>
                    @endif
                    @foreach($additionalPages as $additionalPage)
                        @if($additionalPage->is_published == 1)
                            <li class="nav-item">
                                <a class="nav-link {{ app()->request->route('additionalPageName') == $additionalPage->page_name ? 'active' : '' }}" href="{{ route('additional.tournament.page.details', ['domain' => $websiteDomain, 'additionalPageName' => $additionalPage->page_name]) }}">
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
