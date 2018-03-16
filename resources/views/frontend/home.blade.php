@extends('layouts.frontend.home')

@section('content')
	<!-- Content wrapper for desktop -->
    <div class="content__wrapper d-none d-lg-block">
        <div class="container">
            <div class="row my-5">
                <div class="grid-22">
                    <div class="row mb-4">
                        <div class="col-12 text-center text-uppercase text-muted">
                            <small>Organised by</small>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center organiser">
                        <div class="col-6 col-md-3 col-lg-12">
                            <div class="organiser_logo">
                                <img src="images/organizer/1.png" alt="" class="mx-auto d-block">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-12">
                            <div class="organiser_logo">
                                <img src="images/organizer/2.png" alt="" class="mx-auto d-block">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 club_content">
                    <h2 class="club_content-heading font-weight-bold">Heading lorem ipsum dolor</h2>
                    <p>
                        <figure class="figure right">
                            <img src="images/banner/hero/hero.png" class="figure-img img-fluid ml-1" alt="A generic square placeholder image with rounded corners in a figure.">
                            <figcaption class="figure-caption">A caption for the above image.</figcaption>
                        </figure>
                        Nunc vel tortor iaculis, bibendum ipsum in, consequat odio. Mauris dolor eros, lacinia et pretium sit amet, mollis id dolor. Ut lacinia lacinia lectus, ac hendrerit nunc suscipit vel. Sed sit amet tortor scelerisque, mattis erat sed, tristique justo. Maecenas malesuada, turpis at pellentesque dignissim, turpis nunc hendrerit lacus, id elementum arcu turpis et libero. Fusce tempus lobortis ligula, ut gravida elit volutpat in. Aliquam at rutrum ipsum. Praesent at blandit risus, vitae commodo nisi. Duis sapien mi, eleifend a dictum non, dapibus et mauris. Aenean lobortis nisi non eros tristique consectetur. Nam vitae ultrices tortor, vitae pellentesque lacus. Morbi orci quam, efficitur quis elit at, finibus varius neque.
                    </p>
                    <p>
                        Mauris fringilla turpis diam, quis facilisis urna tincidunt id. Pellentesque ut nulla at tortor bibendum vehicula pulvinar ut ex. Vivamus euismod ante sit amet arcu rhoncus, ut viverra orci maximus. Nullam eu massa erat. Fusce malesuada efficitur porta. Vivamus ultrices velit in convallis consectetur. Mauris varius dictum velit sit amet ullamcorper. Aliquam porttitor, mauris in pharetra molestie, urna metus tristique enim, convallis lobortis augue sem nec felis. Morbi tortor ipsum, volutpat porta fringilla consequat, placerat pulvinar elit. Vestibulum dictum diam ac eros luctus fermentum. Pellentesque nec volutpat elit, in faucibus nisl.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End of content wrapper for desktop -->
    <!-- Content wrapper for mobile -->
    <div class="content__wrapper d-block d-lg-none">
        <div class="container">
            <div class="row my-5">
                <div class="col-12">
                    <div class="row mb-4">
                        <div class="col-12 text-center text-uppercase text-muted">
                            <small>Organised by</small>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center organiser">
                        <div class="col-6 col-md-3 col-lg-12">
                            <div class="organiser_logo">
                                <img src="images/organizer/1.png" alt="" class="mx-auto d-block">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-12">
                            <div class="organiser_logo">
                                <img src="images/organizer/2.png" alt="" class="mx-auto d-block">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-5">
                <div class="col-12 club_content">
                    <h2 class="club_content-heading font-weight-bold">Heading lorem ipsum dolor</h2>
                </div>
            </div>
        </div>
        <figure class="figure-thumb">
            <img src="images/banner/hero/hero.png" class="figure-img img-fluid" alt="A generic square placeholder image with rounded corners in a figure.">
        </figure>
        <div class="container">
            <div class="row">
                <div class="col-12 club_content">
                    <h6 class="font-weight-bold text-uppercase small">A caption for the above image.</h6>
                    <p>
                        Nunc vel tortor iaculis, bibendum ipsum in, consequat odio. Mauris dolor eros, lacinia et pretium sit amet, mollis id dolor. Ut lacinia lacinia lectus, ac hendrerit nunc suscipit vel. Sed sit amet tortor scelerisque, mattis erat sed, tristique justo. Maecenas malesuada, turpis at pellentesque dignissim, turpis nunc hendrerit lacus, id elementum arcu turpis et libero. Fusce tempus lobortis ligula, ut gravida elit volutpat in. Aliquam at rutrum ipsum. Praesent at blandit risus, vitae commodo nisi. Duis sapien mi, eleifend a dictum non, dapibus et mauris. Aenean lobortis nisi non eros tristique consectetur. Nam vitae ultrices tortor, vitae pellentesque lacus. Morbi orci quam, efficitur quis elit at, finibus varius neque.
                    </p>
                    <p>
                        Mauris fringilla turpis diam, quis facilisis urna tincidunt id. Pellentesque ut nulla at tortor bibendum vehicula pulvinar ut ex. Vivamus euismod ante sit amet arcu rhoncus, ut viverra orci maximus. Nullam eu massa erat. Fusce malesuada efficitur porta. Vivamus ultrices velit in convallis consectetur. Mauris varius dictum velit sit amet ullamcorper. Aliquam porttitor, mauris in pharetra molestie, urna metus tristique enim, convallis lobortis augue sem nec felis. Morbi tortor ipsum, volutpat porta fringilla consequat, placerat pulvinar elit. Vestibulum dictum diam ac eros luctus fermentum. Pellentesque nec volutpat elit, in faucibus nisl.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End of content wrapper for mobile -->
@endsection