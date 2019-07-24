<template>
    <div class="main-section">
        <section class="buy-license-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">Confirmation</h1>
                        <p>Thank you for purchase. Your order number is {{paymentObj.orderID}}</p>
                    </div>
                    <div class="col-md-12">
                        <button v-if="tournament_id" class="btn btn-success" @click="printReceipt()">Print receipt</button>
                         <button v-if="!tournament_id" class="btn btn-success" disabled="true">Print receipt</button>
                        
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-12">
                        <h3 class="mb-0 text-uppercase font-weight-bold">Receipt</h3>

                        <div class="divider my-3"></div>

                        <div class="row">
                            <div class="col-sm-6 col-md-7 col-lg-7">
                                <p v-if="!paymentFlag" class="mb-0" id="reeiptDetails">{{tournament.tournament_max_teams}} team license for a {{tournament.dayDifference}} day tournament</p>

                                <p v-if="paymentFlag" class="mb-0" id="reeiptDetails">
                                    <span v-if="tournament.teamDifference == 0">{{tournament.tournament_max_teams }} teams
                                    </span>
                                    <span v-if="tournament.teamDifference > 0">Additional {{tournament.teamDifference }} teams
                                    </span>
                                    <span v-if="tournament.teamDifference < 0">Reduce {{Math.abs(tournament.teamDifference)}} teams
                                    </span>
                                </p>

                                <p v-if="paymentFlag" class="mb-0" id="reeiptDetails && tournament.tournament_type == 'cup' && tournament.custom_tournament_format == 1">
                                    <span>Tournament formats
                                    </span>
                                </p>

                                <p v-if="paymentFlag" class="mb-0" id="reeiptDetails && tournament.transactionDifferenceAmountValue > 0">
                                    <span>Paid amount
                                    </span>
                                </p>

                            </div>
                            <div class="col-sm-6 col-md-5 col-lg-5" v-if="!paymentFlag">
                                <p class="text-sm-right mb-0 mt-3 mt-sm-0"> {{paymentObj.currency == 'EUR' ? '€' : '£' }}{{paymentObj.amount}}</p>
                            </div>

                            <div class="col-sm-6 col-md-5 col-lg-5" v-if="paymentFlag">
                                <p class="text-sm-right mb-0 mt-3 mt-sm-0"> {{paymentObj.currency == 'EUR' ? '€' : '£' }}{{returnFormatedNumber(managePrice)}}</p>
                                <p class="text-sm-right mb-0 mt-3 mt-sm-0"> {{paymentObj.currency == 'EUR' ? '€' : '£' }}{{returnFormatedNumber(manageAdvancePrice)}}</p>
                                <p class="text-sm-right mb-0 mt-3 mt-sm-0"> {{paymentObj.currency == 'EUR' ? '€' : '£' }}{{returnFormatedNumber(manageDifferencePrice)}}</p>
                            </div>
                        </div>

                        <div class="divider my-3 opacited"></div>
                        <p class="text-sm-right font-weight-bold">{{paymentObj.currency == 'EUR' ? '€' : '£' }} {{ returnFormatedNumber(paymentObj.amount) }}</p>
                        <p class="py-3">You may now proceed to your dashboard and begin adding your tournament details.</p>
                        <button v-if="tournament_id" class="btn btn-primary" v-on:click="redirectToDashboardPage()">Get started</button>
                        <button v-if="!tournament_id" class="btn btn-primary" disabled="true">Get started</button>
                        
                    </div>
                </div>

                
            </div>
        </section>
    </div>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'
    import Commercialisation from '../../api/commercialisation.js'
    
    export default {
        data() {
            return {
                tournament_id:"",
                paymentFlag:false,
                totaldays:"",
                daysign : "+",
                paymentObj:{

                },
                tournament:{},
                userDetail:this.$store.state.Users.userDetails,
            }
        },
        computed: {
            managePrice(){
                return this.tournament.tournamentLicenseBasicPriceDisplay
            },
            manageDifferencePrice(){
                if (this.tournament.payment_currency == 'GBP')
                {
                    return this.tournament.transactionDifferenceAmountValue*(parseFloat(this.tournament.gpbConvertValue));
                }
                return this.tournament.transactionDifferenceAmountValue;
            },
            manageAdvancePrice(){
                return this.tournament.tournamentLicenseAdvancePriceDisplay;
            }
        },
        methods: {
            getPaymentDetails(){
                let apiParams = {
                    tournament:this.tournament,
                    paymentResponse:this.paymentObj
                } 
                var url = "payment/response";
                
                if(typeof this.tournament.id != "undefined" && this.tournament.id != undefined && !this.tournament.is_renew){                    
                    url = "manage-tournament";

                    let startDateArr = (this.tournament.tournament_start_date).split("/");
                    let endDateArr = (this.tournament.tournament_end_date).split("/");
                    let startDateFormat = startDateArr[2]+"/"+startDateArr[1]+"/"+startDateArr[0];
                    let endDateFormat = endDateArr[2]+"/"+endDateArr[1]+"/"+endDateArr[0];

                    let startDate = moment(startDateFormat);
                    let endDate = moment(endDateFormat).add('days',1);

                    this.totaldays = endDate.diff(startDate, 'days'); 
                    if(this.tournament.dayDifference < 0) {
                        this.daysign = "";
                    }
                    this.paymentFlag =  true;
                }
                
                axios.post(Constant.apiBaseUrl+url, apiParams).then(response =>  {
                        if (response.data.success) {
                            
                            if(url == "payment/response"){
                                this.paymentObj.amount = response.data.data.amount;
                                this.paymentObj.currency = response.data.data.currency;
                                this.tournament_id = response.data.data.tournament_id;
                                let payment_response = JSON.parse(response.data.data.payment_response);
                                
                                this.paymentObj.orderid = payment_response.orderID;
                            }else{
                                this.tournament_id = response.data.data.tournament.id;
                                this.paymentObj.orderid = response.data.data.paymentResponse.orderID;
                                this.paymentObj.amount = response.data.data.paymentResponse.amount;
                                this.paymentObj.currency = response.data.data.paymentResponse.currency;
                            }
                            
                            
                         }else{
                             toastr['error'](response.data.message, 'Error');
                         }
                 }).catch(error => {
                     console.log("error in buyALicence::",error);
                 });
            },

            printReceipt() {
                let tournamentData = {'tournament_id':this.tournament_id, 'user_name':this.userDetail.name};
                Commercialisation.getSignedUrlForBuyLicensePrint(tournamentData).then(
                (response) => {
                    window.location.href = response.data;
                })                
            },


            redirectToDashboardPage(){
                if(this.tournament_id != ""){
                    this.$router.push({name: 'dashboard'});
                }
            }, 
            getUserDetails(emailData){
                UserApi.getUserDetails(emailData).then(
                  (response)=> {
                    this.userData = response.data.data;
                    Ls.set('userData',JSON.stringify(this.userData[0]))  
                    let UserData  = JSON.parse(Ls.get('userData'))
                    this.$store.dispatch('getUserDetails', UserData);
                  },
                  (error)=> {
                  }
                );
            },           
            returnFormatedNumber(value){
                return Number(value).toFixed(2);  
            },
        },
        beforeMount(){  
            let tournament = Ls.get('orderInfo'); 
            if(tournament != null && tournament != "null"){
                this.tournament = JSON.parse(tournament);
                
                this.tournament.total_amount = this.tournament.total_amount/100;   
                let tempObj = this.$route.query;
                Ls.remove('orderInfo');
                for(let key in tempObj){ 
                    this.paymentObj[key] = tempObj[key];
                }  
                
                this.getPaymentDetails(); 
            }else{
                
            }
        }
    }
</script>