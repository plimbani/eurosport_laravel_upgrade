<!-- Hero wrapper -->
<div class="hero__wrapper hero__wrapper-small">
    @include('partials.frontend.header-menu')
    <div class="hero__wrapper-banner">
        <img src="{{ $hero_image }}" alt="">
    </div>
    <div class="hero__wrapper-overlay">
        <div class="container">
            <div class="row flex-column flex-lg-row align-items-center margin-area">
                <div class="col-lg-3">
                    <div class="d-flex justify-content-center justify-content-lg-end">
                        <div class="club_logo">
                            <div class="club_logo-box">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <img src="{{ $websiteDetail->tournamentLogo('small_thumbnail') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @yield('hero-section')
            </div>
        </div>
    </div>
</div>
@yield('quick-links')
<!-- End of hero wrapper -->
