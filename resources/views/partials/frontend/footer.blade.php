<!-- Footer -->
<footer class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-6 col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li><a href="">Team</a></li>
                            <li><a href="">Matches</a></li>
                            <li><a href="">Venue</a></li>
                            <li><a href="">Program</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <a href="">Tournament</a>
                                <ul>
                                    <li><a href="">Rules</a></li>
                                    <li><a href="">History</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3 mt-5 mt-md-0">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <a href="">Stay</a>
                                <ul>
                                    <li><a href="">Meals</a></li>
                                    <li><a href="">Accomodation</a></li>
                                    <li><a href="">Aditional Page</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3 mt-5 mt-md-0">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <a href="">Visitors</a>
                                <ul>
                                    <li><a href="">Tourist Information</a></li>
                                </ul>
                            </li>
                            <li><a href="">Media</a></li>
                            <li><a href="">Contact</a></li>
                        </ul>
                    </div>
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
                    <li class="list-inline-item">Copyright © 2018 Euro Sportring</li>
                    <li class="list-inline-item"><a href="#">Terms & Conditions</a></li>
                    <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-12 text-center d-block d-md-none">
                <ul class="list-inline mb-2">
                    <li class="list-inline-item">Copyright © 2018 Euro Sportring</li>
                </ul>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item mr-4"><a href="#">Terms & Conditions</a></li>
                    <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
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

{{--<nav class="navbar navbar-default">
	<footer class="container-fluid text-center">
		<ul class="nav navbar-nav">
			@foreach($menu_items as $item)
				<li>
					<a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
					@if(isset($item['children']) && count($item['children']) > 0)
						<ul class="col-sm-12">
							@foreach($item['children'] as $childItem)
								<li>
									<a href="{{ $childItem['url'] }}">{{ $childItem['title'] }}</a>
								</li>
							@endforeach
						</ul>
					@endif
				</li>
			@endforeach
		</ul>
	</footer>
</nav>--}}
