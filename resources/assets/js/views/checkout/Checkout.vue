<template> 
    <div class="main-section">
        <form action="https://ogone.test.v-psp.com/ncol/test/orderstandard_utf8.asp"  method="post">    
            <!-- <form action=""  method="post">     -->
            <!-- <form action=""  method="post" @submit.prevent="buyALicence">     -->

            <input type="hidden" name="PSPID" v-model="pspid">

            <input type="hidden" name="ORDERID" v-model="orderId">

            <input type="hidden" name="AMOUNT" v-model="amount">

            <!--<input type="hidden" name="CURRENCY" value="EUR">-->
            <input type="hidden" name="CURRENCY" v-model="tournamentData.payment_currency"> 

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

            <input type="hidden" name="TP" value="">


            
            <input type="hidden" name="PMLIST" v-model="PMLIST">
            <input type="hidden" name="PMLISTTYPE" value="1">

            <input type="submit" id="paymentSubmit" ref="paymentSubmit" name="paymentSubmit" style="display:none">
        </form>

        <section class="buy-license-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-uppercase font-weight-bold mb-4">Confirmation</h3>
                        <p class="font-weight-bold mb-0">Please confirm your tournament purchase details as follows</p>
                        <div class="divider my-3"></div>
                        <div class="row">
                            <div class="col-sm-6 col-md-7 col-lg-7">
                                <p class="mb-0" v-if="!id">{{tournamentData.tournament_max_teams}}  team license for a {{tournamentData.dayDifference}} day tournament</p>

                                <p class="mb-0" v-if="!id && tournamentData.tournament_type == 'cup' && tournamentData.custom_tournament_format == 1">
                                    <span>Create custom formats</span>
                                </p>

								<p class="mb-0" v-if="id">
                                    <span v-if="tournamentData.teamDifference == 0">{{tournamentData.tournament_max_teams }} teams
                                    </span>
                                    <span v-if="tournamentData.teamDifference > 0">Additional {{tournamentData.teamDifference }} teams
                                    </span>
                                    <span v-if="tournamentData.teamDifference < 0">Reduce {{Math.abs(tournamentData.teamDifference)}} teams
                                    </span>
                                </p>

                                <p class="mb-0" v-if="id && tournamentData.tournament_type == 'cup' && tournamentData.custom_tournament_format == 1">
                                    <span>Tournament formats
                                    </span>
                                </p>
                                <p class="mb-0" v-if="id && tournamentData.transactionDifferenceAmountValue > 0">
                                    <span>Already paid amount</span>
                                </p>
                            </div>
                            <div class="col-sm-6 col-md-5 col-lg-5"  v-if="!id">
                                <p class="text-sm-right mb-0 mt-3 mt-sm-0">
                                    <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>
                                    <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>
                                    {{returnFormatedNumber(managePrice)}}</p>
                                <p class="text-sm-right mb-0 mt-3 mt-sm-0" v-if="tournamentData.tournament_type == 'cup' && tournamentData.custom_tournament_format == 1">
                                    <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>
                                    <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>
                                {{returnFormatedNumber(manageAdvancePrice)}}</p>
                            </div>

                            <div class="col-sm-6 col-md-5 col-lg-5"  v-if="id">
                                <p class="text-sm-right mb-0 mt-3 mt-sm-0">
                                    <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>
                                    <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>{{returnFormatedNumber(managePrice)}}
                                </p>

                                <p class="text-sm-right mb-0 mt-3 mt-sm-0" v-if="tournamentData.tournament_type == 'cup' && tournamentData.custom_tournament_format == 1">
                                    <span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>
                                    <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>{{returnFormatedNumber(manageAdvancePrice)}}
                                </p>

                                <p class="text-sm-right mb-0 mt-3 mt-sm-0" v-if="tournamentData.transactionDifferenceAmountValue > 0">
                                    -<span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>
                                    <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>{{returnFormatedNumber(manageDifferencePrice)}}
                                </p>

                            </div>
                        </div>

                        <div class="divider my-3 opacited"></div>

                        <p class="text-sm-right font-weight-bold"><span v-if="tournamentData.currency_type == 'GBP'">&#163;</span>   
                        <span v-if="tournamentData.currency_type == 'EURO'">&#128;</span>{{returnFormatedNumber(tournamentData.tournamentPricingValue/100)}}</p>
                        <button v-if="shaSignIn"  class="btn btn-success" v-on:click="redirectBuylicensePage()">EDIT YOUR LICENSE</button> 
                        <button v-if="shaSignIn"  class="btn btn-success" v-on:click="makePaymentButton()">Checkout</button>
                        <button v-if="!shaSignIn" class="btn btn-success" disabled="true">Get checkout</button>
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
    
    export default {
        components: {
            vueSlider,
            
        },
        data() {
            return {
                tournamentData: {

                },

                shaSignIn: "",
                orderId: "",
                pspid: "",
                amount: "",
                disabled: false,
                dayDifference: 1,
                currentCountry: "",
                countryCardList: [],
                countries: {},
                PMLIST: 'VISA;MasterCard',
				id:"",

            }
        },
        beforeRouteEnter(to, from, next) {

            next()
        },
        computed: {
            managePrice(){
                return this.tournamentData.tournamentLicenseBasicPriceDisplay
            },
            manageDifferencePrice(){
                if (this.tournamentData.payment_currency == 'GBP')
                {
                    return this.tournamentData.transactionDifferenceAmountValue*(parseFloat(this.tournamentData.gpbConvertValue));
                }
                return this.tournamentData.transactionDifferenceAmountValue;
            },
            manageAdvancePrice(){
                return this.tournamentData.tournamentLicenseAdvancePriceDisplay;
            }
        },
        methods: {
            generateHashKey(e) {
                this.tournamentData['PMLIST'] = this.PMLIST;
                this.tournamentData['PMLISTTYPE'] = 1;
                axios.post(Constant.apiBaseUrl + 'generateHashKey', this.tournamentData).then(response => {
                    if (response.data.success) {
                        this.shaSignIn = response.data.data.shaSignIn;
                        this.orderId = response.data.data.orderId;
                        this.pspid = response.data.data.pspid;
                        this.amount = response.data.data.tournamentPricingValue;
                        
                        let orderInfo = this.tournamentData;
                        orderInfo.shaSignIn = this.shaSignIn;
                        orderInfo.orderId = this.orderId;
                        orderInfo.pspid = this.pspid;
                        orderInfo.tournamentPricingValue = this.amount;

                        this.amount = parseInt(Math.round(this.amount));
                        Ls.set('orderInfo', JSON.stringify(orderInfo))
                        let self = this;
                        setTimeout(function () {
                            
                        }, 500)
                        self.disabled = false;
                    } else {
                        this.disabled = false;
                        toastr['error'](response.data.message, 'Error');
                    }
                }).catch(error => {
                    this.disabled = false;
                    console.log("error in buyALicence::", error);
                });
            },

            makePaymentButton() {
                this.$refs.paymentSubmit.click();
            },
            returnFormatedNumber(value){
                return Number(value).toFixed(2);  
            },
            setCoutryWiseCards() {
                this.countryList = [
                    {id: '37', name: 'NETHERLANDS', cardType: 'iDEAL'},
                    {id: '5', name: 'BELGIUM', cardType: 'KBC;CBC;Belfius;ING Homepay'},
                    {id: '19', name: 'GERMANY', cardType: 'SOFORT;Giropay'},
                    {id: '2', name: 'AUSTRIA', cardType: 'SOFORT'},
                    {id: '54', name: 'SWITZERLAND', cardType: 'SOFORT'},
                    {id: '18', name: 'FRANCE', cardType: 'CarteBancaire'},
                ];
                let usercountry = Ls.get('usercountry');
                if (usercountry != undefined && usercountry != "null" && usercountry != null) {
                    let idx = (this.countryList).findIndex(country => {
                        return country.id == usercountry
                    });
                    if (idx > -1) {
                        this.PMLIST = this.countryList[idx].cardType;
                    }
                    this.generateHashKey();
                } else {
                    this.generateHashKey();
                }

            },
            getCountries() {
                axios.get(Constant.apiBaseUrl + 'country/list').then(response => {
                    if (response.data.success) {
                        this.countries = response.data.data;
                        
                    }
                })
            },
            redirectBuylicensePage(){
                this.$router.push({ name: 'buylicense', query: { edityourlicense: 'yes', id:this.tournamentData.old_tournament_id}}); 
            },
        },
        beforeMount() {
            let tournamentDetails = Ls.get('tournamentDetails');
            if (typeof tournamentDetails != "undefined" && tournamentDetails != undefined && tournamentDetails != "null" && tournamentDetails != null) {
                
                this.tournamentData = JSON.parse(tournamentDetails); 
 
                let startDateArr = (this.tournamentData.tournament_start_date).split("/");
                let endDateArr = (this.tournamentData.tournament_end_date).split("/"); 
                let startDateFormat = startDateArr[2]+"/"+startDateArr[1]+"/"+startDateArr[0];
                let endDateFormat = endDateArr[2]+"/"+endDateArr[1]+"/"+endDateArr[0]; 
                let startDate = moment(startDateFormat);
                let endDate = moment(endDateFormat);
                
                this.dayDifference = endDate.diff(startDate, 'days'); 
				if(this.tournamentData.id){
                    this.id = this.tournamentData.id;
                }
            } else {
                this.$router.push({name: 'login'});
            }
        },
        mounted() {

            Ls.remove('tournamentDetails');

            this.setCoutryWiseCards();

        }
    }
</script>