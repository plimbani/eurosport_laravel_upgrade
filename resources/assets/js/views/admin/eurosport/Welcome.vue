<template>
  <div class="main-content container-fluid" id="dashboardPage">
    <div class="row home-content">
      <div class="d-flex mb-4" :class="isResultAdmin ? 'col-sm-6' : 'col-sm-6'" v-if="!isInternalAdmin">
        <div class="card mb-0 w-100">
          <div class="card-header">
            <h5 class="text-center"
            v-if="(userDetails.role_slug != 'Results.administrator')"><strong>{{$lang.welcome_administration}}</strong></h5>
          </div>
          <div class="card-block text-center">
              <!-- <div class="form-group" v-if="(userDetails.role_name == 'Internal administrator') ">
                <ol class="col-sm-10 offset-sm-1">
                  <li class="text-left">Add your tournament details</li>
                  <li class="text-left">{{$lang.welcome_add_new_tournament_review}}</li>
                  <li class="text-left">{{$lang.welcome_add_new_tournament_publish}}!</li>
                </ol>
              </div> -->
              <!-- <button class="btn btn-primary col-sm-10 btn-theme" @click="addNewTournament()" v-if="(userDetails.role_name == 'Internal administrator') ">{{$lang.welcome_add_button_new_edition}} </button> -->

              <div class="form-group" v-if="(userDetails.role_name == 'Master administrator' || userDetails.role_name == 'Super administrator' || userDetails.role_slug == 'tournament.administrator')">
                <button class="btn btn-primary col-sm-10 btn-theme" @click="userList()">{{$lang.welcome_add_new_user}}</button>
              </div>
          </div>
        </div>
      </div>
      <div class="d-flex mb-4" :class="isInternalAdmin ? 'col-sm-6 offset-sm-3' : 'col-sm-6'" v-if="userDetails.role_slug != 'Results.administrator'">
        <div :class="{ 'card mb-0 w-100': true, 'is-disabled': currentLayout === 'commercialisation' }">
          <div class="card-header">
            <h5 class="text-center"><strong>{{$lang.welcome_manage_websites}}</strong></h5>
          </div>
          <div class="card-block text-center">
            <div class="form-group" v-if="(userDetails.role_name != 'Tournament administrator' && userDetails.role_slug != 'Results.administrator')">
              <button type="button" class="btn btn-primary col-sm-10" @click="addNewWebsite()">{{$lang.welcome_create_website}}</button>
            </div>
            <div class="form-group">
              <websiteDropDown></websiteDropDown>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import WebsiteDropDown from '../../../components/WebsiteDropDown.vue'
import Ls from '../../../services/ls'
import Tournament from '../../../api/tournament.js'

export default {
  data() {
    return {
      currentLayout: this.$store.state.Configuration.currentLayout,
    }
  },
  components : {
    WebsiteDropDown
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
    let websiteAdd  = {id:null, tournament_dates:null, tournament_location:null,tournament_name: null, 'currentPage':'Home'}
    this.$store.dispatch('SetWebsite', websiteAdd)
  },
  methods : {
    userList() {
      let currentNavigationData = {activeTab:'tournament_add', currentPage:
      'Users'}
      this.$store.dispatch('setActiveTab', currentNavigationData)

      let tournamentAdd  = {name:'', 'currentPage':'Users'}
      this.$store.dispatch('SetTournamentName', tournamentAdd)

      this.$router.push({ name: 'users_list' })
    },
    addNewWebsite() {
      let currentNavigationData = {activeTab:'website_add', currentPage:
      'Create Website'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
      this.$router.push({name: 'website_add'})
    }
  }
}
</script>
