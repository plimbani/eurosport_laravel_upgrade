<template>
    <div>Payment Success Page</div>
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

                }
            }
        },
        methods: {
            getPaymentDetails(){
                axios.get(Constant.apiBaseUrl+'payment/response', this.paymentObj).then(response =>  {
                        console.log("response::",response)
                        if (response.data.success) {
//                            // console.log("response.data::",response.data.payment_details);
                        
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
            let tempObj = this.$route.query;
            for(let key in tempObj){ 
                this.paymentObj[key] = tempObj[key];
            }  
            this.getPaymentDetails(); 
        }
    }
</script>