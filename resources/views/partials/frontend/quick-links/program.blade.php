<div class="hero__wrapper-child">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <ul class="nav align-items-center justify-content-center">
                    @if(in_array('program.page.details', $accessible_routes))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'program.page.details' ? 'active' : '' }}" href="{{ route('program.page.details', ['domain' => $websiteDomain]) }}">
                                <span class="icon"><i class="fas fa-eye"></i></span>
                                <span>{!! __('messages.program_overview') !!}</span>
                            </a>
                        </li>
                    @endif
                    @foreach($additionalPages as $additionalPage)
                        @if($additionalPage->is_published == 1)
                            <li class="nav-item">
                                <a class="nav-link {{ app()->request->route('additionalPageName') == $additionalPage->page_name ? 'active' : '' }}" href="{{ route('additional.program.page.details', ['domain' => $websiteDomain, 'additionalPageName' => $additionalPage->page_name]) }}">
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
