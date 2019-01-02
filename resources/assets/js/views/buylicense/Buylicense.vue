<template> 
     
    <form action="https://ogone.test.v-psp.com/ncol/test/orderstandard_utf8.asp"  method="post">    
    <!-- <form action=""  method="post" @submit.prevent="buyALicence">     -->

        <input type="hidden" name="PSPID" v-model="pspid">

        <input type="hidden" name="ORDERID" v-model="orderId">

        <input type="hidden" name="AMOUNT" v-model="amount">

        <input type="hidden" name="CURRENCY" value="EUR">

        <input type="hidden" name="LANGUAGE" value="">

        <input type="hidden" name="CN" value="">

        <input type="hidden" name="EMAIL" value="">

        <input type="hidden" name="OWNERZIP" value="">

        <input type="hidden" name="OWNERADDRESS" value="">

        <input type="hidden" name="OWNERCTY" value="">

        <input type="hidden" name="OWNERTOWN" value="">

        <input type="hidden" name="OWNERTELNO" value="">



        <!-- check before the payment: see Security: Check before the payment -->

        <input type="hidden" name="SHASIGN" v-model="shaSignIn">

        <!-- layout information: see Look and feel of the payment page -->

        <input type="hidden" name="TITLE" value="">

        <input type="hidden" name="BGCOLOR" value="">

        <input type="hidden" name="TXTCOLOR" value="">

        <input type="hidden" name="TBLBGCOLOR" value="">

        <input type="hidden" name="TBLTXTCOLOR" value="">

        <input type="hidden" name="BUTTONBGCOLOR" value="">

        <input type="hidden" name="BUTTONTXTCOLOR" value="">

        <input type="hidden" name="LOGO" value="">

        <input type="hidden" name="FONTTYPE" value="">

        <input type="submit" id="paymentSubmit" ref="paymentSubmit" name="paymentSubmit" style="display:none">
        
        <section class="buy-license-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h1 class="font-weight-bold">Buy a License</h1>
                        <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. </p>

                        <h4 class="text-uppercase font-weight-bold">Number of teams competing</h4>

                        <p><vue-slider :min='2' :max='60' v-model="tournamentData.tournament_max_teams"></vue-slider></p>

                        <h4 class="text-uppercase font-weight-bold">When will the tournament run?</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <datepicker  @selected="selectStartDate" id="startDate" :value="tournamentData.tournament_start_date" :disabled-dates="startDisabledDates" :format="customFormatter" v-validate="{ rules: { required: true } }"></datepicker>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <datepicker @selected="selectEndDate" id="endDate" :value="tournamentData.tournament_end_date" :disabled-dates="endDisabledDates" :format="customFormatter" v-validate="{ rules: { required: true } }"></datepicker>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tournament-name">Name of your tournament</label>
                            <input type="text" class="form-control form-control-danger" placeholder="Tournament Name" id="tournament_name" name="tournament_name" v-model="tournamentData.tournament_name" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('tournament_name')">The tournament name field is required.</span> 
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card shaded-card">
                            <div class="card-body">
                                <div class="card-title">
                                    <div class="row align-items-center">
                                        <div class="col-lg-7">
                                            <h3 class="mb-0 text-uppercase font-weight-bold">Your Cart</h3>
                                        </div>
                                        <div class="col-lg-5">
                                            <select class="form-control" id="gbp">
                                                <option selected value="GBP">GBP</option>
                                                <option value="EURO">EURO</option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider my-3"></div>

                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <p class="mb-0">32 team license for a 4 day tournament</p>
                                        </div>
                                        <div class="col-lg-5">
                                            <p class="text-right mb-0">£100.00</p>
                                        </div>
                                    </div>

                                    <div class="divider my-3"></div>

                                    <p class="text-right font-weight-bold">£100.00</p>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-lg-6">
                                        <p v-if ="!disabled" class="btn btn-success btn-block"  v-on:click="buyALicence()">Buy your license</p>
                                        <button v-else="disabled" class="btn btn-success btn-block" disabled="true">Buy your license</button> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
      </form>  
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Constant from '../../services/constant';
    import vueSlider from 'vue-slider-component';
    import Datepicker from 'vuejs-datepicker';
    // console.log("register  page");
    // $string = 'AMOUNT=2000b709e0ae-ab5b-4a78-bfc7-0bd54612d622CURRENCY=EURb709e0ae-ab5b-4a78-bfc7-0bd54612d622ORDERID=ORD22b709e0ae-ab5b-4a78-bfc7-0bd54612d622PSPID=EasymatchmanagerQAb709e0ae-ab5b-4a78-bfc7-0bd54612d622';
    export default {
        components: {
            vueSlider,
            Datepicker 
        },
        data() {
            return {
              tournamentData:{
                tournament_max_teams: 2,  
                tournament_name: "",
                // tournament_start_date:"12/25/2018",  
                // tournament_end_date:"12/25/2018", 
                tournament_start_date:new Date(),  
                tournament_end_date:new Date(), 
                total_amount:100, 
              },
              startDisabledDates:{
                to: new Date(Date.now() - 8640000),
              },
              endDisabledDates:{
                 to: new Date(Date.now() - 8640000),
                // from: new Date(Date.now() - 8640000),
                // from: new Date(this.tournamentData.tournament_start_date + 8640000),
              },
              shaSignIn:"", 
              orderId:"", 
              pspid:"", 
              amount:"",
              disabled:false
            }
        },
        beforeRouteEnter(to, from, next) {
            // generateHashKey
              if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
                next(vm => { 
                    setTimeout(function(){ 
                         vm.tournamentData.tournament_max_teams = to.query.teams; 
                    }, 100);
                   
               })
            }
            next()
        },
        methods: {
            selectStartDate(date){
                this.tournamentData.tournament_start_date = date;
            },
            selectEndDate(date){
                this.tournamentData.tournament_end_date = date;
            },
            buyALicence(e){
                this.$validator.validateAll();
                if (!this.errors.any()) {
                    this.disabled = true;
                    console.log("this.disabled::",this.disabled);
                    this.tournamentData.tournament_start_date = moment(this.tournamentData.tournament_start_date).format('MM/DD/YYYY')
                    this.tournamentData.tournament_end_date = moment(this.tournamentData.tournament_end_date).format('MM/DD/YYYY')
                    axios.post(Constant.apiBaseUrl+'buy-license', this.tournamentData).then(response =>  {
                            if (response.data.success) {
//                            // console.log("response.data::",response.data.payment_details);
                            this.shaSignIn = response.data.payment_details.shaSignIn;
                            this.orderId = response.data.payment_details.orderId;
                            this.pspid = response.data.payment_details.pspid;
                            this.amount = this.tournamentData.total_amount;
//                            
                            let self = this;
                            setTimeout(function(){
                                // console.log("after timeout")
                                self.$refs.paymentSubmit.click();
                                self.disabled = false;
                            },500)
                            // this.$router.push({'name':'welcome'})
                         }else{
                            this.disabled = true;
                             toastr['error'](response.data.message, 'Error');
                         }
                     }).catch(error => {
                        this.disabled = true;
                         console.log("error in buyALicence::",error);
                     });
                    // this.$router.push({'name':'welcome'}) 
                }
            },
            customFormatter(date) {
              return moment(date).format('MM/DD/YYYY');
            },
            getGenerateHashKey(){
                // axios.get(Constant.apiBaseUrl+'generateHashKey').then(response =>  {
                //     // console.log("response.data::",response.data.data);
                //     if(response.data.success){
                //           this.shaSignIn = response.data.data; 
                //     }
                //  })
            }, 
        },
        beforeMount(){ 
            // this.getGenerateHashKey(); 
        }
    }
</script>