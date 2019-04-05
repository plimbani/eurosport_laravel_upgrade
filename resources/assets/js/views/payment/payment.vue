<template>
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
                            <p class="mb-0" id="reeiptDetails">{{tournament.tournament_max_teams}} Team licence for a {{tournament.dayDifference}} day</p>
                        </div>
                        <div class="col-sm-6 col-md-5 col-lg-5">
                            <p class="text-sm-right mb-0 mt-3 mt-sm-0">{{paymentObj.amount}} {{paymentObj.currency}}</p>
                        </div>
                    </div>

                    <div class="divider my-3 opacited"></div>

                    <p class="text-sm-right font-weight-bold">{{paymentObj.amount}} {{paymentObj.currency}}</p>

                    <p class="py-3">You may now proceed to your dashboard and begin adding your tournament details.</p>
                    <button v-if="tournament_id" class="btn btn-success" v-on:click="redirectToDashboardPage()">Get started</button>
                    <button v-if="!tournament_id" class="btn btn-success" disabled="true">Get started</button>
                    
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
                tournament:{}
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
                }
                
                axios.post(Constant.apiBaseUrl+url, apiParams).then(response =>  {
                        if (response.data.success) {
                            
                            if(url == "payment/response"){
                                this.paymentObj.amount = response.data.data.amount;
                                this.paymentObj.currency = response.data.data.currency;
                                this.tournament_id = response.data.data.id;
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

            printReceipt(){

                
                if(this.tournament_id != ""){                    
                    let url = Constant.apiBaseUrl+'generate/receipt?tournament_id='+this.tournament_id;
                    window.open(url,'_blank');
                }
                
                
            },
            redirectToDashboardPage(){
                if(this.tournament_id != ""){
                    this.$router.push({name: 'dashboard'});
                }
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