<template>
  <div class="card main-card">
    <div class="card-block pt-0">
      <div class="row">
        <div class="col-md-12">
          <p v-if="tournamentEndDateTimeDisplayMessage" class="result-administration-date">
            <small class="text-muted">Please note: You will no longer be able to enter results or edit your tournament after {{ getTournamentExpireDateValue | formatDate }} </small> 
          </p>  
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="tabs tabs-primary has-arrows">
            <ul class="nav nav-tabs edit-tournament-tab" role="tablist">
                <li class="nav-item" v-if="!isResultAdmin">
                    <a :class="[activePath == 'tournament_add' ? 'active' : '', 'nav-link','doc-filled']" data-toggle="tab"  href="#tournament_add" role="tab" @click="GetSelectComponent('tournament_add')">
                      <div class="wrapper-tab">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.tournament_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Add and edit tournament dates and specify venue and contact details">
                            <i class="fas fa-info-circle"></i>
                        </span>
                      </div>
                    </a> 
                </li>
                <li class="nav-item" v-if="!isResultAdmin">      
                    <a :class="[(activePath == 'competition_format' ? 'active' : ''), (competitionList.length > 0 ? 'doc-filled' : ''), 'nav-link']" data-toggle="tab" href="#competition_format" role="tab" @click="GetSelectComponent('competition_format')">
                      <div class="wrapper-tab">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.competation_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Create and edit age categories, competition structures, game duration details and set the rules">
                            <i class="fas fa-info-circle"></i>
                        </span>
                      </div>
                    </a>                    
                </li>

                <li class="nav-item" v-if="!isResultAdmin">                    
                    <a :class="[(activePath == 'pitch_capacity' ? 'active' : ''), (pitches !== undefined ? 'doc-filled' : ''), 'nav-link']" data-toggle="tab" href="#pitch_capacity" role="tab" @click="GetSelectComponent('pitch_capacity')">
                      <div class="wrapper-tab">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.pitch_capacity_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Add pitches, specify pitch size, type and confirm pitch availability">
                            <i class="fas fa-info-circle"></i>
                        </span>
                      </div>
                    </a>                    
                </li>

                <li class="nav-item" v-if="!isResultAdmin">                    
                    <a id="matchPlannerTab" :class="[(activePath == 'pitch_planner' ? 'active' : ''), (isMatchScheduled ? 'doc-filled' : ''), 'nav-link']" data-toggle="tab" href="#pitch_planner" role="tab" @click="GetSelectComponent('pitch_planner')">
                      <div class="wrapper-tab">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.pitch_planner_label}}
        
                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Plan the matches on the pitches. There are two ways to do this: 1. Drag and drop one match at a time on to the pitch. 2. Click on automatic planning. You can plan the matches by group or for a whole age category at the same time.">
                            <i class="fas fa-info-circle"></i>
                        </span>
                      </div>
                    </a>                    
                </li>

                <li class="nav-item" v-if="!isResultAdmin">
                    <a :class="[(activePath == 'teams_groups' ? 'active' : ''),(teamsCount.length > 0 ? 'doc-filled' : ''), 'nav-link']" data-toggle="tab" href="#teams_groups" role="tab"  @click="GetSelectComponent('teams_groups')">
                      <div class="wrapper-tab">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.teams_groups_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Upload your teams, allocate teams to age categories and edit team colours">
                            <i class="fas fa-info-circle"></i>
                        </span>
                      </div>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a id="administrationTab" :class="[(activePath == 'tournaments_summary_details' ? 'active' : ''), (isScoreUpdated ? 'doc-filled' : ''), 'nav-link']" data-toggle="tab" href="#home3" role="tab" @click="GetSelectComponent('tournaments_summary_details')">
                      <div class="wrapper-tab">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>
                    
                        {{$lang.summary_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Edit and view results, access reports and send messages to your tournament participants">
                            <i class="fas fa-info-circle"></i>
                        </span>
                      </div>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a :class="[(activePath == 'match_results' ? 'active' : ''), (isMatchScheduled ? 'doc-filled' : ''), 'nav-link']" data-toggle="tab" href="#match_results" role="tab"  @click="GetSelectComponent('match_results')">
                      <div class="wrapper-tab">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>
                          {{$lang.summary_label_schedule}}
                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Match results">
                            <i class="fas fa-info-circle"></i>
                        </span>
                      </div>
                    </a>
                </li>
            </ul>
          <router-view></router-view>
          <UnSavedMatchScoresInfoModal v-show="unChangedMatchScoresInfoModalOpen" :unChangedMatchScores="unChangedMatchScores"></UnSavedMatchScoresInfoModal>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
import _ from 'lodash'
import Tournament from '../../../api/tournament.js'
import UnSavedMatchScoresInfoModal from '../../../components/UnsavedMatchScoresInfo.vue'
var moment = require('moment-timezone');

export default {
  data() {
    return {
      'header' : 'header',
      'tournamentId' : this.$store.state.Tournament.tournamentId,
      displayTournamentEndDate:"",
      currentDateTime: moment().tz("Europe/London").format('DD/MM/YYYY HH:mm:ss'),
      currentDate: moment().tz("Europe/London").format('DD/MM/YYYY'),
      unChangedMatchScoresInfoModalOpen: false,
      unChangedMatchScores: [],
      getTournamentExpireDateValue:'',

    }
  },
  filters: {
    formatDate: function(date) {
      if(date != null ) {
        return moment(date).format("HH:mm Do MMM YYYY");
      } else {
        return  '-';
      }
    }
  },
  components: {
    UnSavedMatchScoresInfoModal
  },
  computed: {
    activePath() {
      return this.$store.state.activePath
    },
    tournamentEndDateTimeDisplayMessage() {
      //let displayTournamentEndDate = this.displayTournamentEndDate;
      //let expireTime = moment(displayTournamentEndDate).add(8, 'hours').format('DD/MM/YYYY HH:mm:ss');

      let expireTime = moment(this.getTournamentExpireDateValue).format('YYYY-MM-DD HH:mm:ss');
      let currentDateTime = moment().format('YYYY-MM-DD HH:mm:ss');

      let currentDate = moment().tz("Europe/London").format('YYYY-MM-DD');
      let tournamentStartDate = moment(this.$store.state.Tournament.tournamentStartDate,'DD/MM/YYYY').format('YYYY-MM-DD');

      //if(displayTournamentEndDate) {
        if(this.$store.state.Users.userDetails.role_slug == 'customer' && tournamentStartDate <= currentDate && expireTime >= currentDateTime) {
           return true;
        } else {
          return false;
        }
      //}
    },
    isScoreUpdated() {
      let isScoreUpdated = false;
      _.forEach(this.$store.state.Tournament.matches , function(o, index) {
        if(o.homeScore != null || o.AwayScore != null) {
          isScoreUpdated = true;
        }
      })
      return isScoreUpdated;
    },
    isMatchScheduled() {
      let isMatchScheduled = false;
      _.forEach(this.$store.state.Tournament.matches , function(o, index) {
          if(o.is_scheduled == 1) {
            isMatchScheduled = true;
          }
        })
      return isMatchScheduled;
    },
    teamsCount()
    {
      return this.$store.state.Tournament.teams
    },
    competitionList()
    {
      return this.$store.state.Tournament.competationList
    },
    pitches()
    {
      return this.$store.state.Pitch.pitches
    },
    isResultAdmin() {
      return this.$store.state.Users.userDetails.role_slug == 'Results.administrator';
    }
  },
  mounted() {
    this.updateTabStateData();
    this.$store.dispatch('ResetPitchPlannerFromEnlargeMode');
    this.editTournamentMessage();
    if(this.tournamentId == '' ) {
      //this.$router.push({name: 'welcome'})
      }
    // alert('hi')
    // here we call function which select the active class
    // 
    $("[data-toggle=popover]").popover({
        html : false,
        trigger: 'hover',
        content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
        },
        title: function() {
            var title = $(this).attr("data-popover-content");
            return $(title).children(".popover-heading").html();
        }
    });
  },
  methods: {
    GetSelectComponent(componentName) {
      // here we check for Tournament Add
       this.$router.push({name: componentName})
       this.updateTabStateData();
    },
    editTournamentMessage() {
      this.TournamentId = this.$store.state.Tournament.tournamentId
      let TournamentData = {'tournament_id': this.TournamentId}
      Tournament.editTournamentMessage(TournamentData).then(
          (response) => {
              this.displayTournamentEndDate = response.data
          },
          (error) => {
          }
      )
      this.updateTabStateData();
    },
    displayTournamentCompetationList () {
    // Only called if valid tournament id is Present
      if (!isNaN(this.$store.state.Tournament.tournamentId)) {
        // here we add data for
        let TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId}
        Tournament.getCompetationFormat(TournamentData).then(
        (response) => {
          this.competationList = response.data.data
          this.$store.dispatch('setCompetationList',this.competationList)
        },
        (error) => {
        }
        )
      }
    },
    updateTabStateData() {
      this.displayTournamentCompetationList();
      if ( this.$store.state.Users.userDetails.role_slug == 'customer')
      {
        this.getTournamentExpireDate();
      }
      if(this.$store.state.Tournament.tournamentId != 0 && this.$store.state.Tournament.tournamentId != '' && this.$store.state.Tournament.tournamentId != null) {
        this.$store.dispatch('SetTeams',this.$store.state.Tournament.tournamentId);
      }
      this.$store.dispatch('SetPitches',this.$store.state.Tournament.tournamentId);
      if(this.$store.state.Tournament.tournamentId != 0 && this.$store.state.Tournament.tournamentId != '' && this.$store.state.Tournament.tournamentId != null) {
        this.$store.dispatch('setMatches');
      }
    },
    setUnChangedMatchScoresModal(data) {
      this.unChangedMatchScores = data;
      this.unChangedMatchScoresInfoModalOpen = true;
      setTimeout(function() {
        $('#unSavedMatchScoresModal').modal('show');
      }, 500);
    },
    getTournamentExpireDate(){
        let TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId,'tournament_end_date': this.$store.state.Tournament.tournamentEndDate}
        Tournament.getTournamentExpireDate(TournamentData).then(
        (response) => {
          this.getTournamentExpireDateValue = response.data;
        },
        (error) => {
        }
        )
    }
  }
}
</script>
