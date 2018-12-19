<template>
      <form action="" id="registerForm" method="post" @submit.prevent="buyALicence">
        
        <h2>Buy a Licence</h2>

        <div>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
        <!-- <router-link :to="{ name: 'bar', params: { id: 123 }}">bar</router-link> -->
        <div>
            <h3>NUMBER OF TEAMS COMPETING</h3>
            <vue-slider v-model="tournamentData.teams"></vue-slider>
        </div>
        <div>
            <h3>Name of your Tournament</h3>
            <input type="text" class="form-control form-control-danger" placeholder="Tournament Name" id = "tournament_name" name="tournament_name" v-model="tournamentData.tournament_name" v-validate="{ rules: { required: true } }">
            <span class="help is-danger" v-show="errors.has('tournament_name')">The tournament name field is required.</span>
        </div>

        <div>
            <h4>Start Date</h4>
           <!--  <date-picker placeholder="Start Date" lang="en" v-model="tournamentData.start_date" :first-day-of-week="1"></date-picker> -->
            <datepicker :value="tournamentData.start_date" :disabled-dates="startDisabledDates"></datepicker>
        </div>
        <div>
            <h4>End Date</h4>
            <!-- <date-picker placeholder="End Date" lang="en" v-model="tournamentData.end_date" :first-day-of-week="1"></date-picker> -->
            <datepicker :value="tournamentData.end_date" :disabled-dates="endDisabledDates"></datepicker>
        </div>

        <button class="btn btn-login">Buy Your Licence</button>
      </form>  
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import vueSlider from 'vue-slider-component';
    // import DatePicker from 'vue2-datepicker'
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
                teams: 0,  
                tournament_name: "",
                start_date:"",  
                end_date:"", 
              },
              startDisabledDates:{
                to: new Date(Date.now() - 8640000),
              },
              endDisabledDates:{
                 to: new Date(Date.now() - 8640000),
                // from: new Date(Date.now() - 8640000),
                // from: new Date(this.tournamentData.start_date + 8640000),
              } 
            }
        },
        beforeRouteEnter(to, from, next) {
              if(Object.keys(to.query).length !== 0) { //if the url has query (?query)
                next(vm => {
                    // console.log("to.query.teams:",to.query.teams);
                    vm.tournamentData.teams = to.query.teams
               })
            }
            next()
        },
        methods: {
            buyALicence(e){
                this.$validator.validateAll();
                if (!this.errors.any()) {
                    console.log("buyALicence::",this.tournamentData);
                    // this.$router.push({'name':'welcome'}) 
                }
            } 
        },
    }
</script>