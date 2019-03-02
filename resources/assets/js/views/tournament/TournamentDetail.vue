<template>
    <section class="confirmation-section section-padding pb-0">
        <div class="tournament-section section-padding">
            <div class="container">
                <div class="row" v-if="tournamentData">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <img src='/images/dummy.png' class="img-fluid tournament-image">
                            </div>
                            <div class="col-sm-8 col-md-9">
                                <h6 class="text-uppercase mb-0 mt-4 mt-sm-0">License: #{{tournamentData.access_code}}</h6>
                                <h2 class="font-weight-bold mb-0">{{tournamentData.name}}</h2>
                                <h4 class="text-uppercase font-weight-bold mb-4">{{tournamentData.start_date}} - {{tournamentData.end_date}}</h4>

                                <h6 v-if="contactData"  class="text-uppercase mb-0 font-weight-bold">Main Contact</h6>
                                <p class="mb-4"  v-for="item in contactData" >{{item.first_name}} {{item.last_name}} <a :href="'tel:' + item.telephone">{{item.telephone}}</a></p>

                                 <h6 v-if="tournamentSponsers"  class="text-uppercase font-weight-bold">Sponsored by</h6>

                                <ul class="list-unstyled sponsored-list mb-0">
                                    <li class="d-inline" v-for="sponser in tournamentSponsers">
                                        <!-- <img src="/images/macd-logo.png" alt="sponsored"> -->
                                        <img :src="sponser.logo" alt="sponsored">
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 bordered-div mt-5 mt-xl-0">
                        <h4 class="text-uppercase font-weight-bold">Follow on your phone</h4>
                        <p class="mb-4">Download and open the tournament planner app and enter the following code to follow Brighton Champions Cup.</p>

                        <div class="app-code text-center py-3">
                            <h3 class="font-weight-bold m-0">{{tournamentData.access_code}}</h3>
                        </div>

                        <ul class="list-unstyled get-app mb-0 text-xl-center mt-4">
                            <li class="d-inline pr-2"><a href="#"><img src="/images/app-store.png"></a></li>
                            <li class="d-inline"><a href="#"><img src="/images/google-play.png"></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row" v-if="!tournamentData">
                    <div class="col-xl-12">
                        Tournament details not found
                        
                    </div>
                </div>
            </div>
        </div>
        <schedule-and-result :tournamentData="tournamentData"></schedule-and-result>
    </section>
</template>
<script type="text/babel">
    // console.log("register  page");
    import Constant from '../../services/constant';
    import ScheduleAndResult from '../front/scheduleandresults/pages/ScheduleAndResults.vue';
    export default {
        data() {
            return {
                tournamentData:{},
                contactData:[],
                tournamentSponsers:[],
                code:""
            }
        },
        components: {
            ScheduleAndResult,
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
                             this.tournamentInfo = response.data.data.tournament_details;
                             this.tournamentData = response.data.data.tournament_details;
                             this.contactData = response.data.data.contact_details;
                             this.tournamentSponsers = response.data.data.tournament_sponsor;
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