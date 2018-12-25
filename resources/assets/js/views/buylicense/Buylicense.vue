<template>
      <form action="" id="registerForm" method="post" @submit.prevent="buyALicence">
        
        <h2>Buy a Licence</h2>

        <div>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
        <!-- <router-link :to="{ name: 'bar', params: { id: 123 }}">bar</router-link> -->
        <div>
            <h3>NUMBER OF TEAMS COMPETING</h3>
            <vue-slider v-model="tournamentData.tournament_max_teams"></vue-slider>
            <!-- <vue-slider :min='2' :max='60' v-model="tournamentData.tournament_max_teams"></vue-slider> -->
                </div>
        <div>
            <h3>Name of your Tournament</h3>
            <input type="text" class="form-control form-control-danger" placeholder="Tournament Name" id = "tournament_name" name="tournament_name" v-model="tournamentData.tournament_name" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('tournament_name')">The tournament name field is required.</span>
        </div>

        <div>
            <h4>Start Date</h4>
           <!--  <date-picker placeholder="Start Date" lang="en" v-model="tournamentData.tournament_start_date" :first-day-of-week="1"></date-picker> -->
            <datepicker  @selected="selectStartDate" id="startDate" :value="tournamentData.tournament_start_date" :disabled-dates="startDisabledDates" :format="customFormatter" v-validate="{ rules: { required: true } }"></datepicker>
        </div>
        <div>
            <h4>End Date</h4>
            <!-- <date-picker placeholder="End Date" lang="en" v-model="tournamentData.tournament_end_date" :first-day-of-week="1"></date-picker> -->
            <datepicker @selected="selectEndDate" id="endDate" :value="tournamentData.tournament_end_date" :disabled-dates="endDisabledDates" :format="customFormatter" v-validate="{ rules: { required: true } }"></datepicker>
        </div>

        <button class="btn btn-login">Buy Your Licence</button>
      </form>  
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Constant from '../../services/constant';
    import vueSlider from 'vue-slider-component';
    import Datepicker from 'vuejs-datepicker';
    // console.log("register  page");
    export default {
        components: {
            vueSlider,
            Datepicker 
        },
        data() {
            return {
              tournamentData:{
                tournament_max_teams: 2,  
                tournament_name: "",
                // tournament_start_date:"12/25/2018",  
                // tournament_end_date:"12/25/2018", 
                tournament_start_date:new Date(),  
                tournament_end_date:new Date(), 
                total_amount:300, 
              },
              startDisabledDates:{
                to: new Date(Date.now() - 8640000),
              },
              endDisabledDates:{
                 to: new Date(Date.now() - 8640000),
                // from: new Date(Date.now() - 8640000),
                // from: new Date(this.tournamentData.tournament_start_date + 8640000),
              } 
            }
        },
        beforeRouteEnter(to, from, next) {
              if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
                next(vm => {
                    vm.tournamentData.tournament_max_teams = to.query.teams;
               })
            }
            next()
        },
        methods: {
            selectStartDate(date){
                this.tournamentData.tournament_start_date = date;
            },
            selectEndDate(date){
                this.tournamentData.tournament_end_date = date;
            },
            buyALicence(e){
                this.$validator.validateAll();
                if (!this.errors.any()) {
                    this.tournamentData.tournament_start_date = moment(this.tournamentData.tournament_start_date).format('MM/DD/YYYY')
                    this.tournamentData.tournament_end_date = moment(this.tournamentData.tournament_end_date).format('MM/DD/YYYY')
                    axios.post(Constant.apiBaseUrl+'buy-license', this.tournamentData).then(response =>  {
                         if (response.data.success) {
                            this.$router.push({'name':'welcome'})
                         }else{
                             toastr['error'](response.data.message, 'Error');
                         }
                     }).catch(error => {
                         console.log("error in register::",error);
                     });
                    // this.$router.push({'name':'welcome'}) 
                }
            },
            customFormatter(date) {
              return moment(date).format('MM/DD/YYYY');
            } 
        },
        mounted() {

            // $('#startDate').on('change', () => { 
            //     this.tournamentData.tournament_start_date = $('#startDate').val() 
            //     console.log("ff::",this.tournamentData.tournament_start_date)
            // }
            // )
        }
    }
</script>