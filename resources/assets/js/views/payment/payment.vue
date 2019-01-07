<template>
    <section class="buy-license-section section-padding">
        <div>Payment Success Page</div>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-12">
                    <h1 class="font-weight-bold">Confirmation</h1>
                    <p>Thank you for purchase. Your order number is {{paymentObj.orderID}}</p>
                </div>
                <div class="col-md-12">
                    <a href="javascript:void(0)">Print receipt</a>
                </div>
            </div>
             <div class="row justify-content-between">
                <div class="col-md-12">
                    Receipt
                </div>
                <hr>
                <div class="col-md-12">
                    32 Teams licence for a 4 day tournament price is {{paymentObj.amount}} {{paymentObj.currency}}
                </div>
            </div>

            <div class="row justify-content-between">
                <button class="btn btn-success">Get Started</button>
            </div>
        </div>
    </section>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'

    // console.log("register  page");
    export default {
        data() {
            return {
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
                axios.post(Constant.apiBaseUrl+'payment/response', apiParams).then(response =>  {
                        if (response.data.success) {
                            this.paymentObj.amount = response.data.data.amount;
                            this.paymentObj.currency = response.data.data.currency;
                            let payment_response = JSON.parse(response.data.data.payment_response);
                            this.paymentObj.orderid = payment_response.orderID;
                           console.log("response.data::",this.paymentObj);
                            
                        // this.$router.push({'name':'welcome'})
                     }else{
                         toastr['error'](response.data.message, 'Error');
                     }
                 }).catch(error => {
                     console.log("error in buyALicence::",error);
                 });
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
                // console.log('Payment ',this.paymentObj)
                this.getPaymentDetails(); 
            }else{
                // console.log("else");
            }
        }
    }
</script>