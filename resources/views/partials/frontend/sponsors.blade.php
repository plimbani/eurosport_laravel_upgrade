<!-- Sponsored section -->
<section class="tournament_sponsored">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <hr class="hr">
            </div>
        </div>
        <div class="row my-5">
            <div class="col-12 text-center">
                <span class="text-uppercase small text-muted">Tournament sponsored by</span>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap justify-content-center mb-5">
            @foreach($sponsors as $sponsor)
                <div class="sponsored-logo">
                    <a target="_blank" href="{{ $sponsor->website }}"><img src="{{ $sponsor->sponsorLogo('small_thumbnail') }}" alt="{{ $sponsor->name }}"></a>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End of sponsored section -->
