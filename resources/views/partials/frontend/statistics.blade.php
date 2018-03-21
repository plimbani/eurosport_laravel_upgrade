<!-- Statistics -->
<section class="club_stats" style="background-image: url({{ asset('frontend/images/banner.png') }});">
    <div class="club_stats-banner"></div>
    <div class="club_stats-overlay">
        <div class="container">
            <div class="row align-items-center justify-content-center h-100">
                @foreach($statistics as $statistic)
                    <div class="col-6 col-md-3 col-lg-3 col-xl-2">
                        <div class="d-flex justify-content-center w-100">
                            <div class="info_tile-circle">
                                <h2>{{ $statistic->statisticCount() }}</h2>
                                <span>{{ $statistic->statisticText() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End of Statistics -->
