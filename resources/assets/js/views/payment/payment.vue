<template>
    <section class="buy-license-section section-padding">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-12">
                    <h1 class="font-weight-bold">Confirmation</h1>
                    <p>Thank you for purchase. Your order number is {{paymentObj.orderID}}</p>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-success" @click="printReceipt()">Print receipt</button>
                    <!-- <button class="btn btn-success" @click="createPDF()">Print receipt</button> -->
                    <!-- <a href="javascript:void(0)">Print receipt</a> -->
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <h3 class="mb-0 text-uppercase font-weight-bold">Receipt</h3>

                    <div class="divider my-3"></div>

                    <div class="row">
                        <div class="col-sm-6 col-md-7 col-lg-7">
                            <p class="mb-0" id="reeiptDetails">{{tournament.tournament_max_teams}} Teams licence for a {{tournament.dayDifference}} day tournament price is {{paymentObj.amount}} {{paymentObj.currency}}</p>
                        </div>
                        <div class="col-sm-6 col-md-5 col-lg-5">
                            <p class="text-sm-right mb-0 mt-3 mt-sm-0">£100.00</p>
                        </div>
                    </div>

                    <div class="divider my-3 opacited"></div>

                    <p class="text-sm-right font-weight-bold">£100.00</p>

                    <p class="py-3">You may now proceed to your dashboard and begin adding your tournament details.</p>
                    <button class="btn btn-success" v-on:click="redirectToDashboardPage()">Get Started</button>
                </div>
            </div>

            <!-- <div class="row justify-content-between">
                <div class="col-md-12">
                    Receipt
                </div>
                <hr>
                <div class="col-md-12" id="reeiptDetails">
                    {{tournament.tournament_max_teams}} Teams licence for a 4 day tournament price is {{paymentObj.amount}} {{paymentObj.currency}}
                </div>
            </div>

            <div class="row justify-content-between">
                <button class="btn btn-success">Get Started</button>
            </div> -->
        </div>
    </section>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'
    import jsPDF from 'jspdf' 

    // console.log("register  page");
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
            createPDF () {
                let pdfName = 'receipt'; 
                var doc = new jsPDF();
                let content = $("#reeiptDetails").text();
                doc.text(content, 10, 10);
                doc.save(pdfName + '.pdf');
            },
            getPaymentDetails(){
                let apiParams = {
                    tournament:this.tournament,
                    paymentResponse:this.paymentObj
                } 
                var url = "payment/response";
                // console.log("this.tournament.id::",this.tournament.id);
                if(typeof this.tournament.id != "undefined" && this.tournament.id != undefined && !this.tournament.is_renew){
                    // console.log("inside");
                    url = "manage-tournament";
                }
                // console.log("after last")
                axios.post(Constant.apiBaseUrl+url, apiParams).then(response =>  {
                        if (response.data.success) {
                            // console.log("response.data::",response.data.data)
                            if(url == "payment/response"){
                                this.paymentObj.amount = response.data.data.amount;
                                this.paymentObj.currency = response.data.data.currency;
                                this.tournament_id = response.data.data.tournament_id;
                                let payment_response = JSON.parse(response.data.data.payment_response);
                                // let payment_response = response.data.data.paymentResponse;
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

                // ORDER-5c45fa8daa010-1548089997
                if(this.tournament_id != ""){                    
                    let url = Constant.apiBaseUrl+'generate/receipt?tournament_id='+this.tournament_id;
                    window.open(url,'_blank');
                }
                
                // this.$nextTick(() => {
                //     window.print();
                // });
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
                // console.log("this.tournament:",this.tournament);
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