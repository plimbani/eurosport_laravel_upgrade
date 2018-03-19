<!-- Footer -->
<footer class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    @php($itemsPerColumn = ceil( ($menu_items_count - count(config('wot.hide_header_menus'))) / 4))
                    @php($newColumnFlag = 1)
                    @php($itemCount = 0)
                    @php($columnCount = 0)
                    @foreach($menu_items as $item)
                        @if(!in_array($item['page_name'], config('wot.hide_header_menus')))
                            @if($newColumnFlag == 1)
                                @php($newColumnFlag = 0)
                                @php($columnCount++)
                                <div class="col-6 col-md-3{{ $columnCount > 2 ? ' mt-5 mt-md-0' : '' }}">
                                    <ul class="list-unstyled mb-0">
                            @endif
                                    <li>
                                        <a href="{{ $item['url'] }}">{!! __('messages.' . $item['name']) !!}</a>
                                        @php($itemCount++)
                                        @if(isset($item['children']) && count($item['children']) > 0)
                                            <ul>
                                                @foreach($item['children'] as $childItem)
                                                    <li>
                                                        @if($childItem['is_additional_page'] == 1)
                                                            <a href="{{ $childItem['url'] }}">{{ $childItem['title'] }}</a>
                                                        @else
                                                            <a href="{{ $childItem['url'] }}">{!! __('messages.' . $childItem['name']) !!}</a>
                                                        @endif
                                                    </li>
                                                    @php($itemCount++)
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                    @if( ($itemCount >= $itemsPerColumn) || $loop->last)
                                        @php($newColumnFlag = 1)
                                        @php($itemCount = 0)
                                    @endif
                            @if($newColumnFlag == 1)
                                    </ul>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="d-flex justify-content-center justify-content-lg-end">
                    <div class="d-inline-block flex-column">
                        <div class="w-100 text-center">
                            <span class="small text-uppercase text-muted">Organised by</span>
                        </div>
                        <div class="organiser_logo_footer">
                            @foreach($organisers as $organiser)
                                <img src="{{ $organiser->organiserLogo('small_thumbnail') }}" alt="{{ $organiser->name }}">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr class="hr my-5">
            </div>
        </div>
        <div class="row align-items-center copyright_section">
            <div class="col-md-9 d-none d-md-block">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">Copyright © {{ Carbon\Carbon::now()->format('Y') }} Euro Sportring</li>
                </ul>
            </div>
            <div class="col-12 text-center d-block d-md-none">
                <ul class="list-inline mb-2">
                    <li class="list-inline-item">Copyright © {{ Carbon\Carbon::now()->format('Y') }} Euro Sportring</li>
                </ul>
            </div>
            <div class="col-12 d-block d-md-none">
                <hr class="hr my-5">
            </div>
            <div class="col-md-3">
                <div class="d-flex justify-content-center justify-content-lg-end">
                    <div class="d-inline-block flex-column">
                        <div class="copyright_section-logo">
                            <img src="{{ asset('frontend/images/logo-footer.svg') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End of footer -->
