<template>
    <section class="confirmation-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-uppercase mb-0">Access Your Tournament</h6>
                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. If you have an immediate enquiry please call </p>

                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <button class="btn btn-success btn-block">Access Your Tournament</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script type="text/babel">
    // console.log("register  page");
    export default {
        data() {
            return {
                
            }
        },
        methods: {
            redirectTournamentDetail(e){
               console.log("redirectTournamentDetail");
               // this.$router.push({'name':'buylicense'}) 
            } 
        },
    }
</script>