<template>
    <section class="confirmation-section section-padding">
        <div class="tournament-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <img src='/images/dummy.png' class="img-fluid tournament-image">
                            </div>
                            <div class="col-sm-8 col-md-9">
                                 Users Tournaments
                            </div>
                        </div>
                    </div>
                    
                </div>
              
            </div>
        </div>
    </section>
</template>
<script type="text/babel">
    // console.log("register  page");
    import Constant from '../../services/constant'
    export default {
        data() {
            return {
                usersTourmanents:[],
                customer_id:3, // currently static
            }
        },
        beforeRouteEnter(to, from, next) { 
            //   if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
            //     next(vm => {    
            //         setTimeout(function(){   
            //             if(typeof to.query.code != "undefined"){
            //                 vm.code = to.query.code;
            //                 // console
            //                 vm.getTournamentListOfUser();
            //             }
            //         }, 100); 
            //    })
            // }
            next()
        },
        methods: {
            getTournamentListOfUser(){
                let params = {
                    customer_id:this.customer_id, // currently static
                }
                // console.log("params::",params)
                axios.post(Constant.apiBaseUrl+'customer-tournament',params).then(response =>  { 
                    // this.disabled = false;
                     // console.log("response::",response.data.data);
                     this.usersTourmanents = response.data.data;
                }).catch(error => {
                    this.disabled = false;
                     console.log("error in getTournamentListOfUser::",error);
                });  
            } 
        },
        beforeMount(){  
            this.getTournamentListOfUser();
        }
    }
</script>