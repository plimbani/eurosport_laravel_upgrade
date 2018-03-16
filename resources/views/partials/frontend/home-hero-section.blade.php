<div class="hero__wrapper">
    @include('partials.frontend.header-menu')
    <div class="hero__wrapper-banner">
        <img src="{{ $hero_image }}" alt="">
    </div>
    <div class="hero__wrapper-overlay">
        <div class="container">
            <div class="row flex-column flex-lg-row align-items-center margin-area">
                <div class="grid-22">
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
                <div class="grid-full club_info">
                    <h1 class="club_info-title">{{ $websiteDetail->tournament_name }}</h1>
                    <span class="club_info-detail">{{ $websiteDetail->tournament_dates . ', ' . $websiteDetail->tournament_location }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
