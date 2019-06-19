<template>
    <div>
        <div class="alert alert-success margin-top-15 my-3" v-if="queryParams == 'reset password'">
            <i class="fas fa-check text-success mr-2"></i>Your password has been updated.
        </div>
        <div class="alert alert-success margin-top-15 my-3 d-inline-flex align-items-start" v-else>
            <i class="fas fa-check text-success mr-2"></i>
            <p class="mb-1">Your account registration is complete. You can now login to the mobile app.</p>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        data() 
        {
            return {
                queryParams: this.$route.query.reset   
            }
        }
    }
</script>