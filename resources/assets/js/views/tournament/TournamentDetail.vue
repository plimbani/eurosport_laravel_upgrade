<template>
    <section class="confirmation-section section-padding">
        <div class="tournament-section section-padding">
            <div class="container">
                <div class="row" v-if="tournamentInfo">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <img src='/images/dummy.png' class="img-fluid tournament-image">
                            </div>
                            <div class="col-sm-8 col-md-9">
                                <h6 class="text-uppercase mb-0 mt-4 mt-sm-0">License: #{{tournamentInfo.access_code}}</h6>
                                <h2 class="font-weight-bold mb-0">{{tournamentInfo.name}}</h2>
                                <h4 class="text-uppercase font-weight-bold mb-4">{{tournamentInfo.start_date}} - {{tournamentInfo.end_date}}</h4>

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
                            <h3 class="font-weight-bold m-0">{{tournamentInfo.access_code}}</h3>
                        </div>

                        <ul class="list-unstyled get-app mb-0 text-xl-center mt-4">
                            <li class="d-inline pr-2"><a href="#"><img src="/images/app-store.png"></a></li>
                            <li class="d-inline"><a href="#"><img src="/images/google-play.png"></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row" v-if="!tournamentInfo">
                    <div class="col-xl-12">
                        Tournament details not found
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script type="text/babel">
    // console.log("register  page");
    import Constant from '../../services/constant'
    export default {
        data() {
            return {
                tournamentInfo:{},
                code:""
            }
        },
        beforeRouteEnter(to, from, next) { 
              if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
                next(vm => {    
                    setTimeout(function(){   
                        if(typeof to.query.code != "undefined"){
                            vm.code = to.query.code;
                            // console
                            vm.getTournamentDetail();
                        }
                    }, 100); 
               })
            }
            next()
        },
        methods: {
            getTournamentDetail(){
               // console.log("tournamentDetail::",this.code);
                 axios.get(Constant.apiBaseUrl+'tournament-by-code?tournament='+this.code, {}).then(response =>  {  
                        if (response.data.success) { 
                             this.tournamentInfo = response.data.data;
                             // console.log("tournaments::",this.tournamentInfo)
                         }else{ 
                            toastr['error'](response.data.message, 'Error');
                         }
                 }).catch(error => {
                     
                 }); 
               // this.$router.push({'name':'buylicense'}) 
            } 
        },
        beforeMount(){  
            // this.getTournamentDetail();
        }
    }
</script>