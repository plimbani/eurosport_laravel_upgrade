<template>
    <div class="main-section">
        <section class="buy-license-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-12">
                        <h1 class="font-weight-bold">Transaction - {{status_message}}</h1>
                        <p>{{ predefined_payment_status_messages[status_message] }}</p>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-success" v-on:click="redirectToDashboardPage()">Go to dashboard</button>
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
    
    export default {
        data() {
            return {
                tournament_id:"",
                paymentObj:{

                },
                tournament:{},
                status_message:"cancelled",
                predefined_payment_status_messages: [],
            }
        },
        mounted() {
            this.getPaymentStatusMessages();
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
            },
            getPaymentStatusMessages() {
                var url = "get-payment-status-messages";
                axios.get(Constant.apiBaseUrl+url).then(response =>  {
                    this.predefined_payment_status_messages = response.data;
                }).catch(error => {
                });
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
        },
    }
</script>