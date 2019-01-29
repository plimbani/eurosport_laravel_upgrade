<template>
    <section class="confirmation-section section-padding">
        <div class="tournament-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <img src='/images/dummy.png' class="img-fluid tournament-image">
                            </div>
                            <div class="col-sm-8 col-md-9">
                                <h6 class="text-uppercase mb-0 mt-4 mt-sm-0">License: #000987</h6>
                                <h2 class="font-weight-bold mb-0">Brighton Champions Cup</h2>
                                <h4 class="text-uppercase font-weight-bold mb-4">19th - 22nd October 2018</h4>

                                <h6 class="text-uppercase mb-0 font-weight-bold">Main Contact</h6>
                                <p class="mb-4">Ben Grout <a href="tel:+44 7557 123 456">+44 7557 123 456</a></p>

                                <h6 class="text-uppercase font-weight-bold">Sponsored by</h6>

                                <ul class="list-unstyled sponsored-list mb-0">
                                    <li class="d-inline"><img src="/images/macd-logo.png" alt="sponsored"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 bordered-div mt-5 mt-xl-0">
                        <h4 class="text-uppercase font-weight-bold">Follow on your phone</h4>
                        <p class="mb-4">Download and open the tournament planner app and enter the following code to follow Brighton Champions Cup.</p>

                        <div class="app-code text-center py-3">
                            <h3 class="font-weight-bold m-0">XY99</h3>
                        </div>

                        <ul class="list-unstyled get-app mb-0 text-xl-center mt-4">
                            <li class="d-inline pr-2"><a href="#"><img src="/images/app-store.png"></a></li>
                            <li class="d-inline"><a href="#"><img src="/images/google-play.png"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script type="text/babel">
    // console.log("register  page");
    export default {
        data() {
            return {
                
            }
        },
        methods: {
            tournamentDetail(e){
               console.log("tournamentDetail");
               // this.$router.push({'name':'buylicense'}) 
            } 
        },
    }
</script>