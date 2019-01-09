<template> 
    <div class="main-section">
        <form action="https://ogone.test.v-psp.com/ncol/test/orderstandard_utf8.asp"  method="post">    
        <!-- <form action=""  method="post">     -->
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
        </form>  

        <section class="buy-license-section section-padding">
             Checkout page
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
                    // axios.post(Constant.apiBaseUrl+'generateHashKey', this.tournamentData).then(response =>  {  
                    //         if (response.data.success) { 
                    //             this.shaSignIn = response.data.data.shaSignIn;
                    //             this.orderId = response.data.data.orderId;
                    //             this.pspid = response.data.data.pspid;
                    //             this.amount = response.data.data.total_amount; 

                    //             let orderInfo = this.tournamentData;
                    //             orderInfo.shaSignIn = this.shaSignIn;
                    //             orderInfo.orderId = this.orderId;
                    //             orderInfo.pspid = this.pspid;
                    //             orderInfo.total_amount = this.amount;
                    //             Ls.set('orderInfo',JSON.stringify(orderInfo))
                    //             // let self = this;
                    //             // setTimeout(function(){ 
                    //             //     self.$refs.paymentSubmit.click();
                    //             //     self.disabled = false;
                    //             // },500) 
                    //          }else{
                    //             this.disabled = false;
                    //             toastr['error'](response.data.message, 'Error');
                    //          }
                    //  }).catch(error => {
                    //     this.disabled = false;
                    //      console.log("error in buyALicence::",error);
                    //  }); 
                 
            },
             
        },
        beforeMount(){  
        },
        mounted () {
            

        }
    }
</script>