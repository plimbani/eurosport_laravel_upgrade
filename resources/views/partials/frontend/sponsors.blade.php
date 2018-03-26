<!-- Sponsored section -->
<section class="tournament_sponsored">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <hr class="hr my-0">
            </div>
        </div>
        <div class="row my-h2">
            <div class="col-12 text-center">
                <div class="text-uppercase h8 text-muted">Tournament sponsored by</div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap justify-content-center mb-h2">
            @foreach($sponsors as $sponsor)
                <div class="sponsored-logo">
                    <a target="_blank" href="{{ $sponsor->website }}"><img src="{{ $sponsor->sponsorLogo('small_thumbnail') }}" alt="{{ $sponsor->name }}"></a>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End of sponsored section -->
