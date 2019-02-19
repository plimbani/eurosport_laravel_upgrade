<template> 
    <div class="main-section">
        <form action="https://ogone.test.v-psp.com/ncol/test/orderstandard_utf8.asp"  method="post">    
        <!-- <form action=""  method="post">     -->
        <!-- <form action=""  method="post" @submit.prevent="buyALicence">     -->

            <input type="hidden" name="PSPID" v-model="pspid">

            <input type="hidden" name="ORDERID" v-model="orderId">

            <input type="hidden" name="AMOUNT" v-model="amount">

            <input type="hidden" name="CURRENCY" v-model="tournamentData.currency_type">

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

            <input type="hidden" name="PMLIST" value="VISA;MasterCard"> <input
            type="hidden" name="PMLISTTYPE" value="1">

            <input type="submit" id="paymentSubmit" ref="paymentSubmit" name="paymentSubmit" style="display:none">
        </form>  

        <section class="buy-license-section section-padding">
            Please Wait While we redirect your to Payment page
            <div class="card-text">
                <div class="row">
                    <div class="col-sm-6 col-md-7 col-lg-7">
                        <p class="mb-0">{{tournamentData.tournament_max_teams}} team license for a {{dayDifference}} day(s) tournament</p>
                    </div>
                    <div class="col-sm-6 col-md-5 col-lg-5">
                        <p class="text-sm-right mb-0 mt-3 mt-sm-0">Â£100.00</p>
                    </div>
                </div>

                <div class="divider my-3 opacited"></div>

                <p class="text-sm-right font-weight-bold">Â£100.00</p>

                <div class="row justify-content-between">
                    <button class="btn btn-success" v-on:click="makePaymentButton()">Checkout</button>
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
                 
                },
               
                shaSignIn:"", 
                orderId:"", 
                pspid:"", 
                amount:"",
                disabled:false,
                dayDifference:1
            }
        },
        beforeRouteEnter(to, from, next) { 
               
            next()
        },
        methods: { 
            generateHashKey(e){  
                this.tournamentData['PMLIST'] = 'VISA;MasterCard';
                this.tournamentData['PMLISTTYPE'] = 1;
                axios.post(Constant.apiBaseUrl+'generateHashKey', this.tournamentData).then(response =>  {  
                        if (response.data.success) { 
                            this.shaSignIn = response.data.data.shaSignIn;
                            this.orderId = response.data.data.orderId;
                            this.pspid = response.data.data.pspid;
                            this.amount = response.data.data.total_amount; 

                            let orderInfo = this.tournamentData;
                            orderInfo.shaSignIn = this.shaSignIn;
                            orderInfo.orderId = this.orderId;
                            orderInfo.pspid = this.pspid;
                            orderInfo.total_amount = this.amount;
                            Ls.set('orderInfo',JSON.stringify(orderInfo))
                            let self = this;
                            setTimeout(function(){ 
                                // self.$refs.paymentSubmit.click();
                            },500) 
                            self.disabled = false;
                         }else{
                            this.disabled = false;
                            toastr['error'](response.data.message, 'Error');
                         }
                 }).catch(error => {
                    this.disabled = false;
                     console.log("error in buyALicence::",error);
                 });  
            },
            
            makePaymentButton(){
                 this.$refs.paymentSubmit.click();
            }

        },
        beforeMount(){  
            let tournamentDetails = Ls.get('tournamentDetails')
            if(typeof tournamentDetails != "undefined" && tournamentDetails != undefined && tournamentDetails != "null" && tournamentDetails != null){
                // console.log("tournamentDetails::",tournamentDetails);
                this.tournamentData = JSON.parse(tournamentDetails);
                
            }else{
                this.$router.push({name: 'login'});
            }
        },
        mounted () { 
            Ls.remove('tournamentDetails');
            this.generateHashKey();
        }
    }
</script>