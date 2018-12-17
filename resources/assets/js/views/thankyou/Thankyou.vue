<template>
      <form action="" id="registerForm" method="post" @submit.prevent="redirectToPage">
        <div>Registration Complete</div>
        <h2>Thank you</h2>

        <div>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
        <!-- <router-link :to="{ name: 'bar', params: { id: 123 }}">bar</router-link> -->
        <button class="btn btn-login">Buy A Licence</button>
      </form>  
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    // console.log("register  page");
    export default {
        data() {
            return {
                
            }
        },
        methods: {
            redirectToPage(e){
               // console.log("redirectToPage");
               this.$router.push({'name':'welcome'}) 
            } 
        },
    }
</script>