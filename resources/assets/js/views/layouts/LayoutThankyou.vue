<template>
    <div class="template-container">
         <router-view></router-view>
    </div>
</template>

<script type="text/babel">

   

    import Layout from '../../helpers/layout'  
    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            
        },
        mounted() {
            Layout.set('layout-horizontal')
        }
    }
</script>