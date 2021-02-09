<template>
  <div class="main-content container-fluid" id="dashboardPage">
    <div class="row home-content">
      <div class="d-flex mb-4" :class="isInternalAdmin ? 'col-sm-6 offset-sm-3' : 'col-sm-6'">
        <div class="card mb-0 w-100">
          <div class="card-header">
            <h5 class="text-center"><strong>{{$lang.welcome_manage_tournament}}</strong></h5>
          </div>
          <div class="card-block text-center">
            <div class="form-group" v-if="(userDetails.role_name != 'Tournament administrator' && userDetails.role_slug != 'Results.administrator')">
              <button type="button" class="btn btn-success col-sm-10" data-target="#tournament_details_modal" data-toggle="modal">{{$lang.welcome_add_button_tournament_details}}</button>
              <AddTournamentDetailsModal></AddTournamentDetailsModal>
            </div>
            <div class= "form-group" v-if="(userDetails.role_name != 'Tournament administrator' && userDetails.role_slug != 'Results.administrator')">
              <button class="btn btn-primary col-sm-10 btn-theme" @click="addNewTournament()">{{$lang.welcome_add_button_new_edition}}</button>
            </div>
             <div class="form-group" v-if="( userDetails.role_name == 'Internal administrator' || userDetails.role_slug == 'tournament.administrator' || userDetails.role_name == 'Master administrator' || userDetails.role_name == 'Super administrator')">
              <button class="btn btn-primary col-sm-10 btn-theme" @click="duplicateTournament()">
              {{$lang.welcome_create_duplicate_tournament}}</button>
            </div>
            <div class="form-group">
              <tournamentDropDown></tournamentDropDown>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex mb-4" :class="isResultAdmin ? 'col-sm-6' : 'col-sm-6'" v-if="!isInternalAdmin">
        <div class="card mb-0 w-100">
          <div class="card-header">
            <h5 class="text-center"
            v-if="(userDetails.role_slug != 'Results.administrator')"><strong>{{$lang.welcome_administration}}</strong></h5>
            <h5 class="text-center" v-if="(userDetails.role_slug == 'Results.administrator')"><strong>{{$lang.welcome_add_tournament_permission}}</strong></h5>
            </h5>
          </div>
          <div class="card-block text-center">
              <!-- <div class="form-group" v-if="(userDetails.role_name == 'Internal administrator') ">
                <ol class="col-sm-10 offset-sm-1">
                  <li class="text-left">Add your tournament details</li>
                  <li class="text-left">{{$lang.welcome_add_new_tournament_review}}</li>
                  <li class="text-left">{{$lang.welcome_add_new_tournament_publish}}!</li>
                </ol>
              </div> -->
              <div class="form-group" v-if="userDetails.role_slug == 'Results.administrator'">
                <ol class="col-sm-10 offset-sm-1">
                  <li class="text-left">{{$lang.welcome_view_tournaments_assigned_to_you}}</li>
                  <li class="text-left">{{$lang.welcome_add_details_about_matches}}</li>
                </ol>
              </div>
              <!-- <button class="btn btn-primary col-sm-10 btn-theme" @click="addNewTournament()" v-if="(userDetails.role_name == 'Internal administrator') ">{{$lang.welcome_add_button_new_edition}} </button> -->

              <div class="form-group" v-if="(userDetails.role_name == 'Master administrator' || userDetails.role_name == 'Super administrator' || userDetails.role_slug == 'tournament.administrator')">
                <button class="btn btn-primary col-sm-10 btn-theme" @click="userList()">{{$lang.welcome_add_new_user}}</button>
              </div>

              <div class="form-group" v-if="(userDetails.role_slug == 'Super.administrator')">
                <button class="btn btn-primary col-sm-10 btn-theme" @click="templateList()">{{$lang.welcome_manage_templates}}</button>
              </div>
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
import Tournament from '../../../api/tournament.js'

export default {
  data() {
    return {
      currentLayout: this.$store.state.Configuration.currentLayout,
    }
  },
  components : {
    TournamentDropDown,
    AddTournamentDetailsModal
  },

computed: {
    userDetails: function() {
      return this.$store.state.Users.userDetails
    },
    isResultAdmin() {
      return this.$store.state.Users.userDetails.role_slug == 'Results.administrator';
    },
    isTournamentAdmin() {
      return this.$store.state.Users.userDetails.role_slug == 'tournament.administrator';
    },
    isInternalAdmin() {
      return this.$store.state.Users.userDetails.role_slug == 'Internal.administrator';
    }
  },
  mounted() {
    let tournamentAdd  = {name:'', 'currentPage':'Home'}
    this.$store.dispatch('SetTournamentName', tournamentAdd)
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

      this.$store.dispatch('setCompetationList','');
      this.$store.dispatch('SetTeams','');
      this.$store.dispatch('SetPitches','');
      this.$store.dispatch('setMatches','');
    },
    userList() {
      let currentNavigationData = {activeTab:'tournament_add', currentPage:
      'Users'}
      this.$store.dispatch('setActiveTab', currentNavigationData)

      let tournamentAdd  = {name:'', 'currentPage':'Users'}
      this.$store.dispatch('SetTournamentName', tournamentAdd)

      this.$router.push({ name: 'users_list' })
    },
    templateList() {
      let currentNavigationData = {activeTab:'tournament_add', currentPage: 'Templates'};
      this.$store.dispatch('setActiveTab', currentNavigationData);
      let tournamentAdd  = {name:'', 'currentPage':'Templates'}
      this.$store.dispatch('SetTournamentName', tournamentAdd)
      this.$router.push({ name: 'templates_list' });
    },
    manageTemplate() {
      this.templateList();
    },
    duplicateTournament() {
      let currentNavigationData = {currentPage:'Tournaments'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
      this.$router.push({ name: 'duplicate_tournament_copy' })
    }
  }
}
</script>
