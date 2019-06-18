<template> 
    <div class="main-section"> 
        <section class="buy-license-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-6">
                        <h1 class="font-weight-bold" v-if="!id">Buy a license</h1>
                        <h1 class="font-weight-bold" v-if="id">Update License for {{tournamentData.tournament_name}}<span v-if="tournamentData.access_code">(#{{tournamentData.access_code}})</span></h1>
                        <p class="mb-5" v-if="!id">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. </p>
                        <p class="mb-5" v-if="id">You can add more teams and extend the duration of your tournament. </p>
                        <label> What kind of tournament are you organising?</label>
                        
                        <div class="tournament-details">
                            <div class="form-group">
                                <label class="radio-inline control-label d-inline-flex align-items-center mr-3">
                                    <div class="checkbox checked">
                                        <div class="c-input">
                                          <input type="radio" id="cup" name="tournament_type" value="cup" class="euro-radio mr-2"  v-model="tournamentData.tournament_type" @click="tournamentOrganising()" @change="tournammentPricingData()"> 
                                          <label for="cup">Cup</label>
                                        </div>
                                    </div>
                                </label>

                                <label class="radio-inline control-label d-inline-flex align-items-center">
                                    <div class="checkbox">
                                        <div class="c-input">
                                          <input type="radio" id="league" name="tournament_type" value="league" class="euro-radio mr-2 " v-model="tournamentData.tournament_type" @change="tournammentPricingData()">
                                          <label for="league">League</label>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div v-if="tournamentData.tournament_type != 'league'" class="tournament_formats">
                            <label>Do you want to create custom tournament formats?</label>
                                <div class="form-group">
                                    <label class="radio-inline control-label d-inline-flex align-items-center mr-3">
                                        <div class="checkbox checked">
                                            <div class="c-input">
                                              <input type="radio" id="no" name="custom_tournament_format" 
                                              value="0" class="euro-radio mr-2"  v-model="tournamentData.custom_tournament_format" @change="tournammentPricingData()">
                                              <label for="no">No <span></span>
                                              <span v-if="tournamentData.currency_type == 'GBP'">&#163; INCLUDED</span> 
                                              <span v-if="tournamentData.currency_type == 'EURO'">&#128; INCLUDED</span></label>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="radio-inline control-label d-inline-flex align-items-center">
                                        <div class="checkbox">
                                            <div class="c-input">
                                              <input type="radio" id="yes" name="custom_tournament_format" value="1" class="euro-radio mr-2"  v-model="tournamentData.custom_tournament_format" 
                                              @change="tournammentPricingData()">
                                              <label for="yes">Yes 
                                                <span v-if="tournamentData.currency_type == 'GBP'">&#163; {{returnFormatedNumber(tournamentData.tournamentLicenseAdvancePriceDisplay)}}</span>   
                                                <span v-if="tournamentData.currency_type == 'EURO'">&#128; {{returnFormatedNumber(tournamentData.tournamentLicenseAdvancePriceDisplay)}}</span>
                                            </label>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <label>Number of teams competing</label>

                        <div class="row">
                            <div class="col-12">
                                <vue-slider @callback='changeTeams' :min='2' :max='tournamentData.tournamentTeamNumbers' tooltip-dir='right' v-model="tournamentData.tournament_max_teams" class="tournament_teams mb-4" @change="tournammentPricingData(1)" ref="tournamentTeamSlider"></vue-slider>
                            </div>
                        </div>

                        <label>When will the tournament run?</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                   <input type="text" class="form-control ls-datepicker" id="tournament_start_date" v-model="tournamentData.tournament_start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    
                                    <input type="text" class="form-control ls-datepicker" id="tournament_end_date" v-model="tournamentData.tournament_end_date">
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
                                            <p class="mb-0">{{tournamentData.tournament_max_teams}} team license for a {{dayDifference}} day tournament</p>
                                        </div>
                                        <div class="col-sm-6 col-md-5 col-lg-5">
                                            <p class="text-sm-right mb-0 mt-3 mt-sm-0">
                                             <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                             <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>   
                                            {{returnFormatedNumber(tournamentData.tournamentPricingValue)}}</p>

                                        </div>
                                    </div>

                                    <div class="divider my-3 opacited"></div>

                                    <p class="text-sm-right font-weight-bold">
                                        <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                        <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>  
                                        {{returnFormatedNumber(tournamentData.tournamentPricingValue)}}</p>
                                </div>
                                <div class="card-text" v-if="id">
                                    <div class="row" v-if="!buyLicenseIsDataNotUpdated">
                                        <div class="col-sm-6 col-md-7 col-lg-7">
                                            <p class="mb-0" v-if="new_added_teams > 0">Additional {{new_added_teams}} teams</p> 
                                            <p class="mb-0" v-if="newDaysAdded > 0">Additional {{newDaysAdded}} days</p>
                                            <p class="mb-0" v-if="user_old_selected_format">Tournament type</p>
                                            <p class="mb-0" v-if="user_old_selected_type">Tournament formats</p>
                                        </div>
                                        <div class="col-sm-6 col-md-5 col-lg-5">
                                            <p class="text-sm-right mb-0 mt-3 mt-sm-0" >
                                             <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                             <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>

                                            {{returnFormatedNumber(tournamentData.tournamentPricingValue)}}</p>
                                        </div>
                                    </div>
                                    <div class="row" v-if="buyLicenseIsDataNotUpdated">
                                        <div class="col-sm-6 col-md-7 col-lg-7">
                                            <p class="mb-0">No change</p>
                                        </div>
                                    </div>

                                    <div class="divider my-3 opacited"></div>

                                    <p class="text-sm-right font-weight-bold" v-if="newDaysAdded > 0 || new_added_teams > 0">
                                        <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                                        <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>  
                                        {{returnFormatedNumber(tournamentData.tournamentPricingValue)}}</p>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-md-7 col-lg-7 col-xl-6">
                                        <button v-if ="!disabled && !id" class="btn btn-success btn-block"  v-on:click="buyALicence()">
                                            <span v-if ="!tournamentData.is_renew">Buy your license</span>
                                            <span v-if ="tournamentData.is_renew">Renew your licence</span> 
                                        </button>      
                                        <button v-if="disabled && !id" class="btn btn-success btn-block" disabled="true">Buy your license</button>
                                         <button v-if ="!disabled && id && buyLicenseIsDataNotUpdated" class="btn btn-success btn-block" v-on:click="updateALicence()">
                                         Confirm Details </button>
                                         <button v-if ="!disabled && id && !buyLicenseIsDataNotUpdated" class="btn btn-success btn-block"  v-on:click="buyALicence()">
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
                    tournamentPricingValue: 0,
                    transactionDifferenceAmountValue: 0,
                    tournamentLicenseAdvancePriceDisplay: 0,
                    maximumCupTeamSize:0,
                    maximumLeagueTeamSize:0,
                    tournamentTeamNumbers:0,

                },
                
                shaSignIn:"", 
                orderId:"", 
                pspid:"", 
                amount:"",
                disabled:false,
                dayDifference:2,
                oldDaysDifference:1,
                newDaysAdded:0,
                id:"",
                gpbConvertValue:1,
                tournament_old_teams:2,
                new_added_teams:0,
                tournamentPricingBand: '',
                edityourlicense: false,
                user_old_selected_type:'',
                user_old_selected_format:'',
                
            }
        },
        beforeRouteEnter(to, from, next) { 
              if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
                next(vm => {  
                    
                    setTimeout(function(){ 
                        vm.tournamentData.tournament_max_teams = to.query.teams;
                        vm.tournamentData.tournamentTeamNumbers =  to.query.teams;
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

        computed: {
            buyLicenseIsDataNotUpdated(){
                if(this.newDaysAdded <= 0 && this.new_added_teams <= 0 && this.user_old_selected_format == this.tournamentData.custom_tournament_format){
                    return true
                } else {
                    return false
                }
            }
        },
        
        methods: {
            changeTeams(){ 
                this.tournammentPricingData();
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
						this.tournamentData.teamDifference =  this.new_added_teams;
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

                startDateFormat = startDateFormat;
                endDateFormat = endDateFormat;

                let startDate = moment(startDateFormat);
                let endDate = moment(endDateFormat).add('days',1);

                this.dayDifference = endDate.diff(startDate, 'days'); 
                this.changeDays();
                
            },

                
            getTournamentDetail(){
                axios.get(Constant.apiBaseUrl+'get-tournament?tournamentId='+this.id, {}).then(response =>  {
                    if (response.data.success) {
                        var start_date = new Date(moment(response.data.data.tournament.start_date, 'DD/MM/YYYY').format('MM/DD/YYYY'));
                        var end_date = new Date(moment(response.data.data.tournament.end_date, 'DD/MM/YYYY').format('MM/DD/YYYY')); 
                        
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
                        this.tournamentData['old_tournament_id'] = response.data.data.tournament.id;
                        this.tournamentData['tournament_name'] = response.data.data.tournament.name;
                        this.tournamentData['tournament_max_teams'] = response.data.data.tournament.maximum_teams;   
                        this.tournament_old_teams = response.data.data.tournament.maximum_teams;   
                        this.tournamentData['access_code'] = response.data.data.tournament.access_code;
                        this.tournamentData['custom_tournament_format'] = response.data.data.tournament.custom_tournament_format;
                        this.tournamentData['tournament_type'] = response.data.data.tournament.tournament_type;
                        this.user_old_selected_type = response.data.data.tournament.tournament_type;
                        this.user_old_selected_format = response.data.data.tournament.custom_tournament_format; 

                        // transaction histories amount difference calculation 
                        let transactionAmount = [];
                        let tournamentPricing = _.filter(response.data.data.get_sorted_transaction_histories, function(historyAmount)
                        {
                            transactionAmount.push(historyAmount.amount); 
                        });
                        let transactionDifferenceAmountValue = _.sumBy(transactionAmount, function(historyAmount) { 
                            return historyAmount; 
                        }); 
                        this.tournamentData.transactionDifferenceAmountValue = transactionDifferenceAmountValue
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
                    this.tournamentData.tournamentPricingValue = (this.tournamentData.tournamentPricingValue)*this.gpbConvertValue;
                }else{
                    this.tournamentData.tournamentPricingValue = this.tournamentData.tournamentPricingValue/this.gpbConvertValue;
                    this.tournamentData.payment_currency = "EUR";
                }
                this.tournammentPricingData();
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
                    this.tournamentPricingBand = response.data.data;
                })
                
            },
            tournammentPricingData(value) {
                let tournamentOrganising = this.tournamentData.tournament_type
                let tournamentCustomFormats = this.tournamentData.custom_tournament_format
                let tournamentMaxTeams = this.tournamentData.tournament_max_teams
                let vm = this;
                let tournamentLicensePricingArray = [];
                //Custom tournament format display value 

                let maxCupTeamSize = _.maxBy(this.tournamentPricingBand.cup.bands,'max_teams');
                vm.tournamentData.maximumCupTeamSize = maxCupTeamSize.max_teams;
                vm.tournamentData.tournamentTeamNumbers = maxCupTeamSize.max_teams;

                let minLeagueSize = _.maxBy(this.tournamentPricingBand.league.bands,'max_teams');
                vm.tournamentData.maximumLeagueTeamSize = minLeagueSize.max_teams;
                vm.tournamentData.tournamentTeamNumbers = minLeagueSize.max_teams;

                let smallLeagueOrCupValue = '';

                if(maxCupTeamSize.max_teams > minLeagueSize.max_teams) { 
                    vm.tournamentData.tournamentTeamNumbers = maxCupTeamSize.max_teams; 
                    smallLeagueOrCupValue = 'league';
                } else {
                    vm.tournamentData.tournamentTeamNumbers = minLeagueSize.max_teams;
                    smallLeagueOrCupValue = 'cup';
                }

                if(value == 1) {
                    if(smallLeagueOrCupValue == 'cup' && this.tournamentData.tournament_max_teams <= maximumCupTeamSize.max_teams) {
                        $('#cup').prop("checked",true);
                        $('.tournament_formats').show();
                    } else if(smallLeagueOrCupValue == 'cup' && this.tournamentData.tournament_max_teams >= maximumCupTeamSize.max_teams) {
                        $('#league').prop("checked",true);
                        $('.tournament_formats').show();
                    } else if(smallLeagueOrCupValue == 'league' && this.tournamentData.tournament_max_teams <= minLeagueSize.max_teams) {
                        tournamentOrganising = 'league';
                        $('#league').prop("checked",true)
                        $('.tournament_formats').hide();
                    } else if(smallLeagueOrCupValue == 'league' && this.tournamentData.tournament_max_teams >= minLeagueSize.max_teams) {
                        tournamentOrganising = 'cup';
                        $('#cup').prop("checked",true);
                        $('.tournament_formats').show();
                    }
                }

                let tournamentPricing = _.filter(this.tournamentPricingBand.cup.bands, function(band) {
                     if(tournamentMaxTeams >= band.min_teams && tournamentMaxTeams <= band.max_teams) {
                        vm.tournamentData.tournamentLicenseAdvancePriceDisplay = band.advanced_price;
                    }
                });

                // Custom format value (Yes) change in GBP currency  
                if(this.tournamentData.currency_type == "GBP"){
                    this.tournamentData.payment_currency = this.tournamentData.currency_type;
                    this.tournamentData.tournamentLicenseAdvancePriceDisplay = (this.tournamentData.tournamentLicenseAdvancePriceDisplay)*this.gpbConvertValue;
                }

                if(tournamentOrganising == 'cup' && tournamentCustomFormats == 0 && tournamentMaxTeams) {
                    let tournamentPricing = _.filter(this.tournamentPricingBand.cup.bands, function(band) {
                        if(tournamentMaxTeams >= band.min_teams && tournamentMaxTeams <= band.max_teams) {
                            tournamentLicensePricingArray.push(band.price);  
                        }
                    });

                    let tournamentPricingRecord = _.head(tournamentLicensePricingArray);
                    vm.tournamentData.tournamentPricingValue = tournamentPricingRecord - this.tournamentData.transactionDifferenceAmountValue;

                    if(this.tournamentData.currency_type == "GBP") {
                        vm.tournamentData.payment_currency = vm.tournamentData.currency_type;
                        vm.tournamentData.tournamentPricingValue = (vm.tournamentData.tournamentPricingValue)*vm.gpbConvertValue;
                    }
                    vm.tournamentData.tournamentTeamNumbers = maxCupTeamSize.max_teams; 
                }   
                if(tournamentOrganising == 'cup' && tournamentCustomFormats == 1 && tournamentMaxTeams) {
                    let tournamentPricing = _.filter(this.tournamentPricingBand.cup.bands, function(band) {
                        if(tournamentMaxTeams >= band.min_teams && tournamentMaxTeams <= band.max_teams) {
                            tournamentLicensePricingArray.push(band.price + band.advanced_price);
                        }
                    });
                    let tournamentPricingRecord = _.head(tournamentLicensePricingArray);
                        vm.tournamentData.tournamentPricingValue = tournamentPricingRecord - this.tournamentData.transactionDifferenceAmountValue;

                    if(this.tournamentData.currency_type == "GBP") {
                        vm.tournamentData.payment_currency = vm.tournamentData.currency_type;
                        vm.tournamentData.tournamentPricingValue = (vm.tournamentData.tournamentPricingValue)*vm.gpbConvertValue;
                    } 
                    vm.tournamentData.tournamentTeamNumbers = maxCupTeamSize.max_teams;                   
                } 

                if(tournamentOrganising == 'league' && tournamentMaxTeams) {
                    let tournamentPricing = _.filter(this.tournamentPricingBand.league.bands, function(band) {
                        if(tournamentMaxTeams >= band.min_teams && tournamentMaxTeams <= band.max_teams) {
                            tournamentLicensePricingArray.push(band.price);
                        }
                    });
                    let tournamentPricingRecord = _.head(tournamentLicensePricingArray);
                        vm.tournamentData.tournamentPricingValue = tournamentPricingRecord - this.tournamentData.transactionDifferenceAmountValue;

                    if(this.tournamentData.currency_type == "GBP") {
                        vm.tournamentData.payment_currency = vm.tournamentData.currency_type;
                        vm.tournamentData.tournamentPricingValue = (vm.tournamentData.tournamentPricingValue)*vm.gpbConvertValue;
                    }
                    vm.tournamentData.tournamentTeamNumbers = minLeagueSize.max_teams;
                }
                if(isNaN(vm.tournamentData.tournamentPricingValue) || vm.tournamentData.tournamentPricingValue < 0){
                    vm.tournamentData.tournamentPricingValue  = 0;
                }
            },
            tournamentEditYourLicense() {
                let editTournamentLicense;
                if(this.$route.query.edityourlicense == 'yes') {
                    editTournamentLicense = JSON.parse(Ls.get("orderInfo")); 
                    this.tournamentData.tournament_start_date = editTournamentLicense.tournament_start_date;
                    this.tournamentData.tournament_end_date = editTournamentLicense.tournament_end_date;
                    this.tournamentData.tournament_max_teams = editTournamentLicense.tournament_max_teams;
                    this.tournamentData.tournament_name = editTournamentLicense.tournament_name;
                    this.tournamentData.total_amount = editTournamentLicense.total_amount;
                    this.tournamentData.access_code = editTournamentLicense.access_code;
                    this.tournamentData.currency_type = editTournamentLicense.currency_type;
                    this.tournamentData.payment_currency = editTournamentLicense.payment_currency;
                    this.tournamentData.is_renew = editTournamentLicense.is_renew;
                    this.tournamentData.tournament_type = editTournamentLicense.tournament_type;
                    this.tournamentData.custom_tournament_format = editTournamentLicense.custom_tournament_format
                    this.user_old_selected_format = editTournamentLicense.custom_tournament_format;
                    this.user_old_selected_type = editTournamentLicense.tournament_type;
                }
            }
        },
        mounted() {
            var vm = this;

            vm.tournamentData.tournament_start_date = moment(vm.tournamentData.tournament_start_date).format('DD/MM/YYYY');
            vm.tournamentData.tournament_end_date = moment(vm.tournamentData.tournament_end_date).add(1,'days').format('DD/MM/YYYY');


            $('#tournament_start_date').datepicker({
                autoclose: true,
                startDate: '-0m',
                onSelect: function( selectedDate ) {
                    $( "#totournament_end_date" ).datepicker( "option", "minDate", selectedDate);
                }
            });
            $('#tournament_end_date').datepicker({
                autoclose: true,
                startDate: '-0m',
                onSelect: function( selectedDate ) {
                    $( "#tournament_start_date" ).datepicker( "option", "maxDate", selectedDate);
                }
            });
            $('#tournament_start_date').datepicker('setDate', moment().format('DD/MM/YYYY'))
            $('#tournament_end_date').datepicker('setDate', moment().add(1,'days').format('DD/MM/YYYY')) 
           
            $("#tournament_start_date").on("change",function (event, value){
               vm.findDifferenceBetweenDates();
               vm.tournamentData.tournament_start_date = event.target.value; 
            })

            $("#tournament_end_date").on("change",function (event, value){ 
               vm.findDifferenceBetweenDates(); 
               vm.tournamentData.tournament_end_date = event.target.value;
            });
            this.getTournamentPricing();
            this.getCurrencyValue();
            setTimeout(function(){
                vm.setOldDays()
                if(this.id){
                    vm.getTournamentDetail()
                }
                vm.tournamentEditYourLicense()
                vm.tournammentPricingData()
            },1500) 
            
            if(this.$route.query.edityourlicense != 'yes'){
                $('#cup').prop("checked",true)
                $('#no').prop("checked",true)
            }
        }
    }
</script>