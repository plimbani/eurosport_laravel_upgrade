<template>
    <section class="buy-license-section section-padding">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-12">
                    <h1 class="font-weight-bold">Transaction - {{status_message}}</h1>
                    <p>Your transaction is in status as "{{status_message}}". So we are not able to create tournament.</p>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-success" v-on:click="redirectToDashboardPage()">Get started</button>
                </div>
            </div>

            
        </div>
    </section>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'
    
    export default {
        data() {
            return {
                tournament_id:"",
                paymentObj:{

                },
                tournament:{},
                status_message:"cancelled",
            }
        },
        methods: {
            getPaymentDetails(){
                let apiParams = {
                    tournament:this.tournament,
                    paymentResponse:this.paymentObj
                } 
                var url = "payment/response";
                this.status_message = this.paymentObj.STATUS_MESSAGE;
                
                if(typeof this.tournament.id != "undefined" && this.tournament.id != undefined && !this.tournament.is_renew){
                    
                    url = "manage-tournament";
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
                     console.log("error in buyLicence::",error);
                 });
            },
            redirectToDashboardPage(){
                this.$router.push({name: 'dashboard'});
            }
             
            
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