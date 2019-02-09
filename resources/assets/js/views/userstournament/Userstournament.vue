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
                usersTourmanents:{},
                code:""
            }
        },
        beforeRouteEnter(to, from, next) { 
              if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
                next(vm => {    
                    setTimeout(function(){   
                        if(typeof to.query.code != "undefined"){
                            vm.code = to.query.code;
                            // console
                            vm.getTournamentListOfUser();
                        }
                    }, 100); 
               })
            }
            next()
        },
        methods: {
            getTournamentListOfUser(){
               
            } 
        },
        beforeMount(){  
            // this.getTournamentListOfUser();
        }
    }
</script>