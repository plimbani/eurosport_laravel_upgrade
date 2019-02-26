<template> 
    <div class="main-section"> 
        <section class="buy-license-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-6">
                        <h1 class="font-weight-bold">Buy a License</h1>
                        <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. </p>

                        <label>Number of teams competing</label>

                        <div class="row my-4 my-lg-5">
                            <div class="col-10 col-md-11 col-lg-12">
                                <vue-slider :min='2' :max='60' tooltip-dir='right' v-model="tournamentData.tournament_max_teams"></vue-slider>
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

                        <label>Name of your tournament</label>
                        <div class="form-group">
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

                                <div class="card-text">
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
                                <div class="row justify-content-end">
                                    <div class="col-md-7 col-lg-7 col-xl-6">
                                        <button v-if ="!disabled" class="btn btn-success btn-block"  v-on:click="buyALicence()">Buy your license</button>
                                        <button v-else="disabled" class="btn btn-success btn-block" disabled="true">Buy your license</button> 
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
                    tournament_start_date:new Date(),  
                    tournament_end_date:new Date(), 
                    total_amount:100, 
                    currency_type:"EURO",
                    payment_currency:"EUR"
                },
                
                shaSignIn:"", 
                orderId:"", 
                pspid:"", 
                amount:"",
                disabled:false,
                dayDifference:1,
                id:"",
                gpbConvertValue:1
                
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
                            // console
                            vm.getTournamentDetail();
                        }
                    }, 100); 
               })
            }
            next()
        },
        methods: {
           
            buyALicence(e){ 
                this.$validator.validateAll();
                if (this.tournamentData.tournament_name) {
                    // this.disabled = true;
                    // console.log("vvv::",document.getElementById('tournament_start_date').value)
                    this.tournamentData.tournament_start_date = document.getElementById('tournament_start_date').value;
                    this.tournamentData.tournament_end_date = document.getElementById('tournament_end_date').value;
                    
                    Ls.set("tournamentDetails",JSON.stringify(this.tournamentData)); 
                    
                    let token = Ls.get('auth.token')
                    if(typeof token != "undefined" && token != undefined && token != "null" && token != null){ 
                        this.$router.push({name: 'checkout'});
                    }else{
                        this.$router.push({name: 'login'});
                    } 
                } 
            },
            customFormatter(date) {
              return moment(date).format('MM/DD/YYYY');
            }, 

            findDifferenceBetweenDates(){ 
                // console.log("startDate::",startDate);
                let startDateFromId = document.getElementById('tournament_start_date').value;
                let endDateFromId = document.getElementById('tournament_end_date').value;

                // need to work on it for min and max date management
                // manage min and max Date
                $( "#tournament_start_date" ).datepicker("option","maxDate",endDateFromId);
                $( "#totournament_end_date" ).datepicker("option","minDate",startDateFromId);


                let startDateArr = startDateFromId.split("/");
                let endDateArr = endDateFromId.split("/"); 
                let startDate = moment([startDateArr[2], startDateArr[1], startDateArr[0]]);
                let endDate = moment([endDateArr[2], endDateArr[1], endDateArr[0]]);
                this.dayDifference = endDate.diff(startDate, 'days');
                // console.log("this.dayDifference::",this.dayDifference);
                
            },

            getTournamentDetail(){ 
                axios.get(Constant.apiBaseUrl+'get-tournament?tournamentId='+this.id, {}).then(response =>  {  
                        if (response.data.success) {  
                            var start_date = new Date(moment(response.data.data.start_date, 'DD/MM/YYYY').format('MM/DD/YYYY'));
                            var end_date = new Date(moment(response.data.data.end_date, 'DD/MM/YYYY').format('MM/DD/YYYY')); 
                            this.tournamentData['id'] = this.id;
                            this.tournamentData['tournament_name'] = response.data.data.name;
                            this.tournamentData['tournament_max_teams'] = response.data.data.maximum_teams;   
                            let startMonth = start_date.getMonth()+1;                         
                            let endMonth = end_date.getMonth()+1;                         
                            this.tournamentData['tournament_start_date'] = start_date.getDate()+'/'+startMonth + '/'+start_date.getFullYear();
                            this.tournamentData['tournament_end_date'] = end_date.getDate()+'/'+endMonth + '/'+end_date.getFullYear(); 
                            $('#tournament_start_date').datepicker('setDate', this.tournamentData['tournament_start_date'])
                             $('#tournament_end_date').datepicker('setDate', this.tournamentData['tournament_end_date'])  
                         }else{ 
                            toastr['error'](response.data.message, 'Error');
                         }
                 }).catch(error => {
                     
                 }); 
            },

            changeCurrencyType(event){
                // console.log(event.target.value);
                this.tournamentData.currency_type = event.target.value;
                if((this.tournamentData.currency_type).toLowerCase() == "gbp"){
                    this.tournamentData.payment_currency = this.tournamentData.currency_type;
                    this.tournamentData.total_amount = (this.tournamentData.total_amount)*this.gpbConvertValue;
                    // total_amount
                }else{
                    this.tournamentData.total_amount = this.tournamentData.total_amount/this.gpbConvertValue;
                    this.tournamentData.payment_currency = "EUR";
                }
            },

            getCurrencyValue(){ 
                axios.get(Constant.apiBaseUrl+'get-website-settings?type=currency').then(response =>  { 
                    if (response.data.success) { 
                        this.gpbConvertValue = response.data.data.gbp;
                        // console.log("this.gpbConvertValue::",this.gpbConvertValue);
                     }else{ 
                        toastr['error'](response.data.message, 'Error');
                     }
                }).catch(error => {
                     
                }); 
            },

            returnFormatedNumber(value){
                return Number(value).toFixed(2);  
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
                    // console.log("startDate");
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

        }
    }
</script>