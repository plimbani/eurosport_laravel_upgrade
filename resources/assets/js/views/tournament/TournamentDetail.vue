<template>
    <div class="main-section">
        <section class="confirmation-section">
            <div class="tournament-section section-padding">
                <div class="container">
                    <div class="row" v-if="tournamentData.id">
                        <div class="col-xl-8">
                            <div class="row">
                                <div class="col-sm-4 col-md-3">
                                    <img v-if="tournamentData.logo" :src='tournamentData.logo' class="img-fluid tournament-image">
                                    
                                </div>
                                <div class="col-sm-8 col-md-9">
                                    <h6 class="text-uppercase mb-0 mt-4 mt-sm-0">License: #{{tournamentData.access_code}}</h6>
                                    <h2 class="font-weight-bold mb-0">{{tournamentData.name}}</h2>
                                    <h4 class="text-uppercase font-weight-bold mb-4">{{tournamentData.start_date}} - {{tournamentData.end_date}}</h4>

                                    <h6 v-if="contactDetail.first_name || contactDetail.telephone"  class="text-uppercase mb-0 font-weight-bold">Main Contact</h6>
                                    <p class="mb-4">{{contactDetail.first_name}} {{contactDetail.last_name}} <a :href="'tel:' + contactDetail.telephone">{{contactDetail.telephone}}</a></p>

                                     <h6 v-if="tournamentSponsers.length > 0"  class="text-uppercase font-weight-bold">Sponsored by</h6>

                                    <ul class="list-unstyled sponsored-list mb-0">
                                        <li class="d-inline" v-for="sponser in tournamentSponsers">
                                            
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
                                <li class="d-inline pr-2"><a href="javascript:void(0);" @click="tournamentDetailAppStoreLink()"><img src="/images/app-store.png"></a></li>
                                <li class="d-inline"><a href="javascript:void(0);" @click="tournamentDetailGoogleStoreLink()"><img src="/images/google-play.png"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" v-if="!tournamentData.id">
                        <div class="col-xl-12 text-center">
                            Tournament details not found
                        </div>
                    </div>
                </div>
            </div>
            <schedule-and-result :tournamentData="tournamentData" v-if="tournamentData.id"></schedule-and-result>
        </section>
    </div>
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
                code:"",
                baseUrl:"",
                googleAppStoreLink:"",
                appleStoreLink:"",
                appleStoreDeepLink:"", 
                contactDetail:{
                    first_name:"",
                    last_name:"",
                    telephone:""
                }
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
                           
                            vm.getTournamentDetail();
                        }else{
                           
                            vm.$router.push({ path: 'enter-tournament'})
                            
                        }
                    }, 100); 
               })
            }else{
                next(vm => {    
                    setTimeout(function(){   
                    
                        vm.$router.push({ path: 'enter-tournament'})
                    },200)
               })

            }
            next()
        },
        methods: {
            getTournamentDetail(){
               
                 axios.get(Constant.apiBaseUrl+'tournament-by-code?tournament='+this.code, {}).then(response =>  {  
                        if (response.data.success && typeof response.data.data != "undefined" && typeof response.data.data.tournament_details != "undefined") {
                             this.tournamentData = response.data.data.tournament_details;
                             this.contactData = response.data.data.contact_details;
                             if((this.contactData).length > 0){
                                this.contactDetail.first_name = this.contactData[0].first_name;
                                this.contactDetail.last_name = this.contactData[0].last_name;
                                this.contactDetail.telephone = this.contactData[0].telephonee;
                             }

                             this.tournamentSponsers = response.data.data.tournament_sponsor;
                             this.baseUrl = response.data.data.baseUrl;
                             this.googleAppStoreLink = response.data.data.googleAppStoreLink;
                             this.appleStoreLink = response.data.data.appleStoreLink;
                             this.appleStoreDeepLink = response.data.data.appleStoreDeepLink;
                             
                         }else{ 
                            this.tournamentData = {};
                            this.contactData= [];
                            this.tournamentSponsers= [];
                            toastr['error']("Tournament detail not found.", 'Error');
                         }
                 }).catch(error => {
                    this.tournamentData = {};
                    this.contactData= [];
                    this.tournamentSponsers= [];
                 }); 
               
            },
            tournamentDetailAppStoreLink(){

                if(/iPhone|iPad|iPod/i.test(navigator.userAgent)){ 
                    //this.$router.push({ path: 'mtournament-detail', query: { code: this.code }})
                    window.location.href = this.appleStoreDeepLink+'?code='+this.code;
                }
 
                if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    //this.$router.push({ path: 'tournament-detail', query: { code: this.code }})
                    window.location.href = this.appleStoreLink;
                }
            },
            tournamentDetailGoogleStoreLink() {
                if(/Android/i.test(navigator.userAgent)){ 
                    //this.$router.push({ path: 'mtournament-detail', query: { code: this.code }})
                    window.location.href = this.baseUrl+'/tournament/openApp?code='+this.code;
                }

                if (!navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry|IEMobile)/)) {
                    // this.$router.push({ path: 'tournament-detail', query: { code: this.code }})
                    window.location.href = this.googleAppStoreLink;  
                }
            }
        }
    }
</script>