<template>    
    <footer class="site-footer d-flex justify-content-center">
        <div class="page-copyright align-self-center">
            <small>Copyright {{currentYear}} Euro-Sportring. Developed  by <a href="http://aecordigital.com" target="_blank"><font color="#fff" style="text-decoration: underline">aecor</font></a>.</small>
        </div>
    </footer>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                'footer' : 'footer',
                'currentYear': new Date().getFullYear()
            }
        },
        methods: {
            // changeFooterStyle() {
            //     alert('h')
                
            // }
        },
        mounted() {
            // this.changeFooterStyle();                        
        }
    }
</script>
