\<template>
    <div class="template-container">
        <site-header></site-header>
          <div class="main-content" id="dashboardPage">
            <breadCrum></breadCrum>
            <tournamentTabbed>              
            </tournamentTabbed>                 
          </div>             
        <!--<router-view></router-view>-->
        <div class="horizontal_line"></div>
        <site-footer></site-footer>
    </div>
</template>

<script type="text/babel">

    import SiteHeader from './partials/SiteHeader.vue'
    import SiteFooter from './partials/SiteFooter.vue'
    import SiteHeaderBottom from './partials/SiteHeaderBottom.vue'

    import Layout from '../../helpers/layout'
    import BreadCrum from '../../components/BreadCrum.vue'
    import TournamentTabbed from './partials/TournamentTabbed.vue'

    export default {
        data() {
            return {
                'header' : 'header'
            }
        },
        components : {
            SiteHeader , SiteHeaderBottom , SiteFooter, BreadCrum, TournamentTabbed
        },
        mounted() {
        return axios.get('/api/matches').then(response =>  {
            console.log(response)
            
            }).catch(error => {
                if (error.response.status == 401) {
                    toastr['error']('Invalid Credentials', 'Error');
                } else {
                    // Something happened in setting up the request that triggered an Error
                    console.log('Error', error.message);
                }
            });
            // // alert('hello')
            //     return axios.get('/api/matches').then(response =>  {
            //         console.log(response);
            //     }).catch(error => {
            //         console.log('Error', error.message);
            //     });
            // Layout.set('layout-horizontal')
        }
    }
</script>