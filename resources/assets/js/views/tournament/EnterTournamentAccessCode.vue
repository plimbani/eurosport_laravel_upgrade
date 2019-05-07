<template>
    <div class="main-section">
        <section class="confirmation-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <h1>Access Your Tournament</h1>
                        <p class="mb-5">If you have been given four digit code please enter it below to access your tournament </p>
                            
                                <!-- <input type="text" class="form-control" placeholder="Enter your code" id = "code" name="code" v-model="code">
                                <button class="btn btn-success btn-block" v-on:click="redirectTournamentDetail()">Access Your Tournament</button> -->
                            
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter your code" id = "code" name="code" v-model="code">
                            <span class="input-group-addon p-0 ml-2">
                                <button class="btn btn-success" v-on:click="redirectTournamentDetail()">Access Your Tournament</button>
                            </span>
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
                    
                            if (response.data.success && typeof response.data.data != "undefined" && typeof response.data.data.tournament_details != "undefined") {

                                 this.$router.push({ path: 'tournament-detail', query: { code: this.code }})
                             }else{ 
                                toastr['error']("Please enter valid tournament code", 'Error');
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