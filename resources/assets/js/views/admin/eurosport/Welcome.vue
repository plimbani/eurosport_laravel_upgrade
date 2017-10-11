<template>
  <div class="main-content container" id="dashboardPage">
    <!-- <div class="row">
      <div class="col-md-12">
        <div class="alert alert-info alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Hi Chris, Welcome to Tournament Planner. Got a question? Check the <strong> Help </strong>page or <strong> contact us </strong>for assistance.

        </div>
      </div>
    </div> -->
    <div class="row home-content">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <h5 class="text-center"><strong>{{$lang.welcome_manage_tournament}}</strong></h5>
          </div>
          <div class="card-block text-center">
            <div class="form-group" v-if="(userDetails.role_name != 'Tournament administrator')">
              <button v-if="(userDetails.role_name != 'Tournament administrator')" type="button" class="btn btn-success col-sm-8" data-target="#tournament_details_modal" data-toggle="modal">{{$lang.welcome_add_button_tournament_details}}</button>
              <AddTournamentDetailsModal></AddTournamentDetailsModal>
            </div>
            <div class= "form-group" v-if="(userDetails.role_name != 'Tournament administrator' &&  userDetails.role_name != 'Internal administrator')">
              <button class="btn btn-primary col-sm-8 btn-theme"
              @click="addNewTournament()" v-if="(userDetails.role_name != 'Tournament administrator' &&  userDetails.role_name != 'Internal administrator')">
              {{$lang.welcome_add_button_new_edition}}</button>
            </div>
            <div class="form-group">
              <tournamentDropDown></tournamentDropDown>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h5 class="text-center"
          v-if="(userDetails.role_name != 'Tournament administrator' &&  userDetails.role_name != 'Internal administrator')"><strong>{{$lang.welcome_manage_user}}</strong></h5>
          <h5 class="text-center" v-if="(userDetails.role_name == 'Tournament administrator')"><strong>{{$lang.welcome_add_tournament_permission}}</strong></h5>
          <h5 class="text-center" v-if="(userDetails.role_name == 'Internal administrator')"><strong>{{$lang.welcome_add_tournament}}</strong></h5>
          <!-- <h5 class="text-center" v-else><strong>{{$lang.welcome_add_tournament}}</strong> -->
          </h5>
        </div>
        <div class="card-block text-center">
            <div class="form-group" v-if="(userDetails.role_name == 'Internal administrator' || userDetails.role_name == 'Tournament administrator') ">
              <ol class="col-sm-8 offset-sm-2">
                <li class="text-left">{{$lang.welcome_add_new_tournament_edition_details}}</li>
                <li class="text-left">{{$lang.welcome_add_new_tournament_review}}</li>
                <li class="text-left">{{$lang.welcome_add_new_tournament_publish}}!</li>
              </ol>
            </div>
            <button class="btn btn-primary col-sm-8 btn-theme" @click="addNewTournament()" v-if="(userDetails.role_name == 'Internal administrator') ">{{$lang.welcome_add_button_new_edition}} </button>
            <button class="btn btn-primary col-sm-8 btn-theme" @click="userList()" v-if="(userDetails.role_name == 'Master administrator' || userDetails.role_name == 'Super administrator')">{{$lang.welcome_add_new_user}}</button>
            <br>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>
<script>
import TournamentDropDown from '../../../components/TournamentDropDown.vue'
import AddTournamentDetailsModal  from  '../../../components/AddTournamentDetailsModal.vue'
import Ls from '../../../services/ls'
export default {
  components : {
    TournamentDropDown,
    AddTournamentDetailsModal
  },

computed: {
    userDetails: function() {
      return this.$store.state.Users.userDetails
    },
  },
  mounted() {

    let tournamentAdd  = {name:'', 'currentPage':'Home'}
    this.$store.dispatch('SetTournamentName', tournamentAdd)

    // Here we set Default Value For Tournament

    /*let userDetails = this.$store.state.Users.userDetails
        // this.userDetails = this.$store.state.Users.userDetails

    let that = this
     setTimeout(function(){
    //   // console.log(userDetails.length,'hh')
       if(userDetails.length == 0)
       {

         let email = Ls.get('email');

         // Now here we are call and fetch user details
         let userData = {'email':email}
         that.$store.dispatch('getUserDetails', userData);
          that.userDetails = that.$store.state.Users.userDetails
       }
        let tournamentAdd  = {name:'', 'currentPage':'Home'}

       that.$store.dispatch('SetTournamentName', tournamentAdd)
     },1000)


     */
  },
  methods : {
    addNewTournament() {
      // we have to Dispatch there is New Tournament
      let tournamentAdd  = {name:'Your Tournament',
      'currentPage':'TournamentAdd','tournamentStartDate': '','tournamentEndDate':'','tournamentDays': 0,'tournamentStatus': '','id': ''}
      let currentNavigationData = {activeTab:'tournament_add', currentPage:
      'Add Tournament'}
      this.$store.dispatch('setActiveTab', currentNavigationData)

      this.$store.dispatch('SetTournamentName', tournamentAdd)
      this.$router.push({name: 'tournament_add'})
    },
    userList() {
      let currentNavigationData = {activeTab:'tournament_add', currentPage:
      'Users'}
      this.$store.dispatch('setActiveTab', currentNavigationData)

      let tournamentAdd  = {name:'', 'currentPage':'Users'}
      this.$store.dispatch('SetTournamentName', tournamentAdd)

      this.$router.push({ name: 'users_list' })
    }
  }
}
</script>
