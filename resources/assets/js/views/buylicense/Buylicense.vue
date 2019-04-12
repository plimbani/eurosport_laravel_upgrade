<template> 
    <div class="main-section"> 
        <section class="buy-license-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-6">
                        <h1 class="font-weight-bold" v-if="!id">Buy a License</h1>
                        <h1 class="font-weight-bold" v-if="id">Update License for a {{tournamentData.tournament_name}}<span v-if="tournamentData.access_code">(#{{tournamentData.access_code}})</span></h1>
                        <p class="mb-5" v-if="!id">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. </p>
                        <p class="mb-5" v-if="id">You can add more teams and extend the duration of your tournament. </p>
                        <label> What kind of tournament are you organising?</label>
                        
                        <div class="tournament-details">
                            <div class="form-group">
                                <label class="radio-inline control-label d-inline-flex align-items-center mr-3">
                                    <div class="checkbox checked">
                                        <div class="c-input">
                                          <input type="radio" id="cup" name="tournament_type" value="cup" class="euro-radio mr-2"  v-model="tournamentData.tournament_type" @click="tournamentOrganising()" @change="getTournamentPricing()"> 
                                          <label for="cup">Cup</label>
                                        </div>
                                    </div>
                                </label>

                                <label class="radio-inline control-label d-inline-flex align-items-center">
                                    <div class="checkbox">
                                        <div class="c-input">
                                          <input type="radio" id="league" name="tournament_type" value="league" class="euro-radio mr-2" v-model="tournamentData.tournament_type">
                                          <label for="league">League</label>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div v-if="tournamentData.tournament_type != 'league'">
                            <label>Do you want to create custom tournament formats?</label>
                                <div class="form-group">
                                    <label class="radio-inline control-label d-inline-flex align-items-center mr-3">
                                        <div class="checkbox checked">
                                            <div class="c-input">
                                              <input type="radio" id="no" name="custom_tournament_format" value="0" class="euro-radio mr-2"  v-model="tournamentData.custom_tournament_format">
                                              <label for="no">No <span>£ INCLUDED</span></label>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="radio-inline control-label d-inline-flex align-items-center">
                                        <div class="checkbox">
                                            <div class="c-input">
                                              <input type="radio" id="yes" name="custom_tournament_format" value="1" class="euro-radio mr-2"  v-model="tournamentData.custom_tournament_format">
                                              <label for="yes">Yes <span>+£100</span></label>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <label>Number of teams competing</label>

                        <div class="row my-4 my-lg-5">
                            <div class="col-10 col-md-11 col-lg-12">
                                <vue-slider @callback='changeTeams' :min='2' :max='60' tooltip-dir='right' v-model="tournamentData.tournament_max_teams"></vue-slider>
                            </div>
                        </div>

                        <label>When will the tournament run?</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                   <input type="text" class="form-control ls-datepicker" id="tournament_start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    
                                    <input type="text" class="form-control ls-datepicker" id="tournament_end_date">
                                </div>
                            </div>
                        </div>

                        <label v-if="!id">Name of your tournament</label>
                        <div v-if="!id" class="form-group">
                            <input type="text" class="form-control form-control-danger" placeholder="Tournament name" id="tournament_name" name="tournament_name" v-model="tournamentData.tournament_name" v-validate="{ rules: { required: true } }">
                            <span class="help is-danger" v-show="errors.has('tournament_name')">The tournament name field is required.</span> 
                        </div>
                    </div>
                    <div class="col-lg-5 mt-3 mt-lg-0">
                        <div class="card shaded-card">
                            <div class="card-block">
                                <div class="card-title">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 col-lg-7">
                                            <h3 class="mb-0 text-uppercase font-weight-bold">Your Cart</h3>
                                        </div>
                                        <div class="col-md-6 col-lg-5 mt-2 mt-md-0"> 
                                            <select class="form-control" v-model="tournamentData.currency_type" id="currency_type" @change="changeCurrencyType($event)">
                                                <option selected value="EURO">EURO</option> 
                                                <option value="GBP">GBP</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider my-3"></div>

                                <div class="card-text" v-if="!id"> 
                                    <div class="row">
                                        <div class="col-sm-6 col-md-7 col-lg-7">
                                            <p class="mb-0">{{tournamentData.tournament_max_teams}} team license for a {{dayDifference}} day(s) tournament</p>
                                        </div>
                                        <div class="col-sm-6 col-md-5 col-lg-5">
                                            <p class="text-sm-right mb-0 mt-3 mt-sm-0">
                                             <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                             <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>   
                                            {{returnFormatedNumber(tournamentData.total_amount)}}</p>
                                        </div>
                                    </div>

                                    <div class="divider my-3 opacited"></div>

                                    <p class="text-sm-right font-weight-bold">
                                        <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                        <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>  
                                        {{returnFormatedNumber(tournamentData.total_amount)}}</p>
                                </div>
                                <div class="card-text" v-if="id">
                                    
                                    <div class="row" v-if="new_added_teams > 0">
                                        <div class="col-sm-6 col-md-7 col-lg-7">
                                            <p class="mb-0">Addition {{new_added_teams}} teams</p> 
                                        </div>
                                        <div class="col-sm-6 col-md-5 col-lg-5">
                                            <p class="text-sm-right mb-0 mt-3 mt-sm-0" >
                                             <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                             <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>   
                                            100</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row" v-if="newDaysAdded > 0">
                                        <div class="col-sm-6 col-md-7 col-lg-7">
                                            <p class="mb-0">Addition {{newDaysAdded}} days</p>
                                        </div>
                                        <div class="col-sm-6 col-md-5 col-lg-5">
                                            <p class="text-sm-right mb-0 mt-3 mt-sm-0">
                                             <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                             <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>   
                                            30</p>
                                        </div>
                                    </div>
                                    <div class="row" v-if="newDaysAdded <= 0 && new_added_teams <= 0">
                                        <div class="col-sm-6 col-md-7 col-lg-7">
                                            <p class="mb-0">No change</p>
                                        </div>
                                        
                                    </div>

                                    <div class="divider my-3 opacited"></div>

                                    <p class="text-sm-right font-weight-bold" v-if="newDaysAdded > 0 || new_added_teams > 0">
                                        <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                        <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>  
                                        {{returnFormatedNumber(tournamentData.total_amount)}}</p>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-md-7 col-lg-7 col-xl-6">
                                        
                                        <button v-if ="!disabled && !id" class="btn btn-success btn-block"  v-on:click="buyALicence()">
                                            <span v-if ="!tournamentData.is_renew">Buy your license</span>
                                            <span v-if ="tournamentData.is_renew">Renew your licence</span> 
                                        </button>      
                                        <button v-if="disabled && !id" class="btn btn-success btn-block" disabled="true">Buy your license</button>

                                         <button v-if ="!disabled && id && newDaysAdded <= 0 && new_added_teams <= 0" class="btn btn-success btn-block"  v-on:click="updateALicence()">
                                        Confirm Details </button>
                                         <button v-if ="!disabled && id && (newDaysAdded > 0 || new_added_teams > 0)" class="btn btn-success btn-block"  v-on:click="buyALicence()">
                                        Make Payment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
          
    </div>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls';
    import Constant from '../../services/constant';
    import vueSlider from 'vue-slider-component';
    import Commercialisation from '../../api/commercialisation.js'
   
    export default {
        components: {
            vueSlider 
        },
        data() {
            return {
                tournamentData:{
                    tournament_max_teams: 2,  
                    tournament_name: "", 
                    tournament_start_date:new Date(),  
                    tournament_end_date:new Date(), 
                    total_amount:100, 
                    access_code:"", 
                    currency_type:"EURO",
                    payment_currency:"EUR",
                    is_renew:0,
                    tournament_type: "cup",
                    custom_tournament_format: 0,

                },
                
                shaSignIn:"", 
                orderId:"", 
                pspid:"", 
                amount:"",
                disabled:false,
                dayDifference:1,
                oldDaysDifference:1,
                newDaysAdded:0,
                id:"",
                gpbConvertValue:1,
                tournament_old_teams:2,
                new_added_teams:0
                
            }
        },
        beforeRouteEnter(to, from, next) { 
              if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
                next(vm => {  
                    
                    setTimeout(function(){ 
                        vm.tournamentData.tournament_max_teams = to.query.teams; 
                        if(typeof to.query.teams == "undefined"){
                            vm.tournamentData.tournament_max_teams = 2;
                        }
                        if(typeof to.query.id != "undefined"){
                            vm.id = to.query.id;
                            
                            vm.getTournamentDetail();
                        }
                    }, 100); 
               })
            }
            next()
        },
        methods: {
            changeTeams(){ 
                this.new_added_teams = this.tournamentData.tournament_max_teams - this.tournament_old_teams; 
            },
            changeDays(){
                this.newDaysAdded = this.dayDifference - this.oldDaysDifference; 
            },
            setOldDays(){
                this.oldDaysDifference = this.dayDifference;
                this.changeDays();  
            },
            buyALicence(e){ 
                this.$validator.validateAll();
                if (this.tournamentData.tournament_name) {
                    this.tournamentData.tournament_start_date = document.getElementById('tournament_start_date').value;
                    this.tournamentData.tournament_end_date = document.getElementById('tournament_end_date').value;
                    if(this.id){
                        this.tournamentData.dayDifference =  this.newDaysAdded; 
                    }else{
                        this.tournamentData.dayDifference = this.dayDifference;
                    }
                    // newDaysAdded
                    Ls.set("tournamentDetails",JSON.stringify(this.tournamentData)); 
                    
                    let token = Ls.get('auth.token')
                    if(typeof token != "undefined" && token != undefined && token != "null" && token != null){ 
                        this.$router.push({name: 'checkout'});
                    }else{
                        this.$router.push({name: 'login'});
                    } 
                } 
            },
            updateALicence(){
                this.tournamentData.tournament_start_date = document.getElementById('tournament_start_date').value;
                this.tournamentData.tournament_end_date = document.getElementById('tournament_end_date').value;
                let apiParams = {
                    tournament:this.tournamentData,
                    
                } 
                var url = "manage-tournament";
                axios.post(Constant.apiBaseUrl+url, apiParams).then(response =>  {
                    if (response.data.success) {
                        this.$router.push({name: 'dashboard'});
                     }else{
                         toastr['error'](response.data.message, 'Error');
                     }
             }).catch(error => {
                 console.log("error in updateALicence::",error);
             });
                
            },
            customFormatter(date) {
              return moment(date).format('MM/DD/YYYY');
            }, 

            findDifferenceBetweenDates(){ 
                
                let startDateFromId = document.getElementById('tournament_start_date').value;
                let endDateFromId = document.getElementById('tournament_end_date').value;

                // need to work on it for min and max date management
                // manage min and max Date
                $( "#tournament_start_date" ).datepicker("option","maxDate",endDateFromId);
                $( "#totournament_end_date" ).datepicker("option","minDate",startDateFromId);


                let startDateArr = startDateFromId.split("/");
                let endDateArr = endDateFromId.split("/"); 
                let startDateFormat = startDateArr[2]+"/"+startDateArr[1]+"/"+startDateArr[0];
                let endDateFormat = endDateArr[2]+"/"+endDateArr[1]+"/"+endDateArr[0]; 
                let startDate = moment(startDateFormat);
                let endDate = moment(endDateFormat);
                
                this.dayDifference = endDate.diff(startDate, 'days'); 
                this.changeDays();
                
            },

            getTournamentDetail(){ 
                axios.get(Constant.apiBaseUrl+'get-tournament?tournamentId='+this.id, {}).then(response =>  {  
                        
                        if (response.data.success) {  
                            var start_date = new Date(moment(response.data.data.start_date, 'DD/MM/YYYY').format('MM/DD/YYYY'));
                            var end_date = new Date(moment(response.data.data.end_date, 'DD/MM/YYYY').format('MM/DD/YYYY')); 
                            
                            let today = new Date();
                            if(today.getTime() > end_date.getTime()){
                                this.id = "";
                                this.tournamentData['is_renew'] = 1;
                            }else{
                                let startMonth = start_date.getMonth()+1;                         
                                let endMonth = end_date.getMonth()+1;                         
                                this.tournamentData['tournament_start_date'] = start_date.getDate()+'/'+startMonth + '/'+start_date.getFullYear();
                                this.tournamentData['tournament_end_date'] = end_date.getDate()+'/'+endMonth + '/'+end_date.getFullYear(); 
                                $('#tournament_start_date').datepicker('setDate', this.tournamentData['tournament_start_date'])
                                 $('#tournament_end_date').datepicker('setDate', this.tournamentData['tournament_end_date'])  
                            }
                            
                            this.tournamentData['id'] = this.id;
                            this.tournamentData['old_tournament_id'] = response.data.data.id;
                            this.tournamentData['tournament_name'] = response.data.data.name;
                            this.tournamentData['tournament_max_teams'] = response.data.data.maximum_teams;   
                            this.tournament_old_teams = response.data.data.maximum_teams;   
                            this.tournamentData['access_code'] = response.data.data.access_code;
                            this.tournamentData['custom_tournament_format'] = response.data.data.custom_tournament_format;
                            this.tournamentData['tournament_type'] = response.data.data.tournament_type;   
                           
                
                         }else{ 
                            toastr['error'](response.data.message, 'Error');
                         }
                 }).catch(error => {
                     
                 }); 
            },

            changeCurrencyType(event){
                
                this.tournamentData.currency_type = event.target.value;
                if((this.tournamentData.currency_type).toLowerCase() == "gbp"){
                    this.tournamentData.payment_currency = this.tournamentData.currency_type;
                    this.tournamentData.total_amount = (this.tournamentData.total_amount)*this.gpbConvertValue;
                    
                }else{
                    this.tournamentData.total_amount = this.tournamentData.total_amount/this.gpbConvertValue;
                    this.tournamentData.payment_currency = "EUR";
                }
            },

            getCurrencyValue(){ 
                axios.get(Constant.apiBaseUrl+'get-website-settings?type=currency').then(response =>  { 
                    if (response.data.success) { 
                        this.gpbConvertValue = response.data.data.gbp;
                        
                     }else{ 
                        toastr['error'](response.data.message, 'Error');
                     }
                }).catch(error => {
                     
                }); 
            },

            returnFormatedNumber(value){
                return Number(value).toFixed(2);  
            },

            tournamentOrganising() {
                $('#no').prop("checked",true)
            },

            getTournamentPricing() {
                Commercialisation.getTournamentPricingDetail().then(
                (response) => {
                  console.log('response', response);
                })

            }
        },
        beforeMount(){   
            
        },
        mounted () {
            var vm = this
            $('#tournament_start_date').datepicker({
                autoclose: true,
                minDate: 0,
                onSelect: function( selectedDate ) {
                    
                    $( "#totournament_end_date" ).datepicker( "option", "minDate", selectedDate );
                }
            });
             $('#tournament_end_date').datepicker({
                autoclose: true,
                minDate: 0,
                onSelect: function( selectedDate ) {
                    $( "#tournament_start_date" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
            $('#tournament_start_date').datepicker('setDate', moment().format('DD/MM/YYYY'))
            $('#tournament_end_date').datepicker('setDate', moment().add(1,'days').format('DD/MM/YYYY')) 
           
            $("#tournament_start_date").on("change",function (value){ 
               vm.findDifferenceBetweenDates();
            })

            $("#tournament_end_date").on("change",function (value){ 
               vm.findDifferenceBetweenDates();
            })   
            this.getCurrencyValue();
            setTimeout(function(){
                vm.setOldDays()
            },4000)


            this.getTournamentPricing();

            $('#cup').prop("checked",true)
            $('#no').prop("checked",true)
        }
    }
</script>