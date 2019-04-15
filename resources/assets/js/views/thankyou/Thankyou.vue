<template>
    <div class="main-section">
      <form action="" id="registerForm" method="post" @submit.prevent="redirectToPage">
        <section class="confirmation-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-uppercase mb-0">Registration Complete</h6>
                        <h1 class="font-weight-bold">Thank You</h1>
                        <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. If you have an immediate enquiry please call   <a href="tel:+44(0)1234 567 890">+44(0)1234 567 890</a></p>

                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                <button class="btn btn-success btn-block">Buy a License</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
      </form>  
    </div>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    
    export default {
        data() {
            return {
                
            }
        },
        methods: {
            redirectToPage(e){
               
               this.$router.push({'name':'buylicense'}) 
            } 
        },
    }
</script>