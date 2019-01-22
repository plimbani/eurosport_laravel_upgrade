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
                    <button class="btn btn-success" @click="printReceipt()">Print receipt</button>
                    <!-- <button class="btn btn-success" @click="createPDF()">Print receipt</button> -->
                    <!-- <a href="javascript:void(0)">Print receipt</a> -->
                </div>
            </div>
             <div class="row justify-content-between">
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
            </div>
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
                axios.post(Constant.apiBaseUrl+'payment/response', apiParams).then(response =>  {
                        if (response.data.success) {
                            console.log("response.data::",response.data.data)
                            this.paymentObj.amount = response.data.data.amount;
                            this.paymentObj.currency = response.data.data.currency;
                            let payment_response = JSON.parse(response.data.data.payment_response);
                            this.paymentObj.orderid = payment_response.orderID;
                            this.tournament_id = response.data.data.tournament_id;
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
                    // let url = Constant.apiBaseUrl+'generate/receipt?tournament_id=154';
                    let url = Constant.apiBaseUrl+'generate/receipt?tournament_id='+this.tournament_id;
                 
                    let params = {}
                    
                    axios.post(url, params).then(response =>  {
                        if (response.data.success) {
                            console.log("receipt::",response.data.data)
                            const url = window.URL.createObjectURL(new Blob(["link of file"]));
                            const link = document.createElement('a');
                            link.href = url;
                            link.setAttribute('download', 'receipt.pdf'); 
                            document.body.appendChild(link);
                            link.click(); 
                         }else{
                             toastr['error'](response.data.message, 'Error');
                         }
                     }).catch(error => {
                         console.log("error in buyALicence::",error);
                     });
                }
                
                // this.$nextTick(() => {
                //     window.print();
                // });
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
                // console.log('Payment ',this.paymentObj)
                this.getPaymentDetails(); 
            }else{
                // console.log("else");
            }
        }
    }
</script>