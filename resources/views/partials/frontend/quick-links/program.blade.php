<div class="hero__wrapper-child">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <ul class="nav align-items-center justify-content-center">
                    @foreach($additionalPages as $additionalPage)
                        @if($additionalPage->is_published == 1)
                            <li class="nav-item">
                                <a class="nav-link {{ app()->request->route('additionalPageName') == $additionalPage->page_name ? 'active' : '' }}" href="{{ route('additional.program.page.details', ['domain' => $websiteDetail->domain_name, 'additionalPageName' => $additionalPage->page_name]) }}">
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
