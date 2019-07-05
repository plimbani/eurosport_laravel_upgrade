<template>
    <div class="main-section">
        <section class="confirmation-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-5">
                        <h1 class="text-uppercase h3 font-weight-bold">Access Your Tournament</h1>
                        <p class="mb-5">If you have been given four digit code please enter it below to access your tournament. </p>
                            
                                <!-- <input type="text" class="form-control" placeholder="Enter your code" id = "code" name="code" v-model="code">
                                <button class="btn btn-success btn-block" v-on:click="redirectTournamentDetail()">Access Your Tournament</button> -->
                            
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter your code" id = "code" name="code" v-model="code">
                            <span class="input-group-addon p-0 ml-2">
                                <button class="btn btn-success" v-on:click="redirectTournamentDetail()">Access Your Tournament</button>
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-6 mt-4 mt-lg-0">
                        <div class="d-md-flex">
                            <div class="img-device text-center"><img src="/images/device.png" class="" alt="Mock Up"></div>
                            <div class="d-flex flex-column justify-content-between ml-3 mt-4 mt-md-0">
                                <h1 class="text-uppercase h3 font-weight-bold mb-md-0">Get our mobile app</h1>
                                <ul class="pl-4 mb-md-0">
                                    <li class="py-1">Lorem ipsum dolor sit amet, consectetur</li>
                                    <li class="py-1">Lorem ipsum dolor sit amet, consectetur</li>
                                    <li class="py-1">Lorem ipsum dolor sit amet, consectetur</li>
                                    <li class="py-1">Lorem ipsum dolor sit amet, consectetur</li>
                                </ul>

                                <ul class="list-unstyled get-app mb-0 pl-2">
                                    <li class="d-inline pr-2">
                                        <a href="javascript:void(0);">
                                            <img src="/images/app-store.png">
                                        </a>
                                    </li> 
                                    <li class="d-inline">
                                        <a href="javascript:void(0);">
                                            <img src="/images/google-play.png">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script type="text/babel">
import Constant from '../../services/constant';
   
    export default {
        data() {
            return {
                code:""
            }
        },
        methods: {

            redirectTournamentDetail(){
               
                if(this.code.length == 4){
                    axios.get(Constant.apiBaseUrl+'tournament-by-code?tournament='+this.code, {}).then(response =>  {
                    
                            if (response.data.success && typeof response.data.data != "undefined" && typeof response.data.data.tournament_details != "undefined" && response.data.data.tournamentStatus != 'Unpublished') {

                                 this.$router.push({ path: 'tournament-detail', query: { code: this.code }})
                             }else{
                                if (response.data.data.tournamentStatus == 'Unpublished'){
                                    toastr['error']("Tournament is not yet published or in preview mode", 'Error');
                                }else
                                {
                                    toastr['error']("Please enter valid tournament code", 'Error');
                                }
                             }
                     }).catch(error => {
                        toastr['error']("Please enter valid tournament code", 'Error');    
                     });
                    
                    
                }else{
                    toastr['error']("Please enter valid tournament code", 'Error');
                }
               
            } 
        },
    }
</script>