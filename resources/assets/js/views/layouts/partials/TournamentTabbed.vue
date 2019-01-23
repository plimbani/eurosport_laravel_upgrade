<template>
  <div class="card">
    <div class="card-block">
      <div class="row">
        <div class="col-lg-12">
          <div class="tabs tabs-primary">
            <ul class="nav nav-tabs edit-tournament-tab" role="tablist">
                <li class="nav-item">
                    <a :class="[activePath == 'tournament_add' ? 'active' : '', 'nav-link','doc-filled']" data-toggle="tab"  href="#tournament_add" role="tab" @click="GetSelectComponent('tournament_add')">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.tournament_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Add and edit tournament dates and specify location and contact details">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </a> 
                </li>

                <li class="nav-item">                    
                    <a :class="[(activePath == 'competation_format' ? 'active' : '', 'nav-link'), (competitionList.length > 0 ? 'doc-filled' : '')]" data-toggle="tab" href="#competation_format" role="tab" @click="GetSelectComponent('competation_format')">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.competation_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Create and edit age categories, competition structures, game duration details and set the rules">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </a>                    
                </li>

                <li class="nav-item">                    
                    <a :class="[(activePath == 'pitch_capacity' ? 'active' : '', 'nav-link'), (pitches !== undefined ? 'doc-filled' : '')]" data-toggle="tab" href="#pitch_capacity" role="tab" @click="GetSelectComponent('pitch_capacity')">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.pitch_capacity_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Add pitches, specify pitch size, type and confirm pitch availability">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </a>                    
                </li>

                <li class="nav-item">                    
                    <a :class="[(activePath == 'teams_groups' ? 'active' : '', 'nav-link'),(teamsCount.length > 0 ? 'doc-filled' : '')]" data-toggle="tab" href="#teams_groups" role="tab"  @click="GetSelectComponent('teams_groups')">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.teams_groups_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Upload your teams, allocate teams to age categories and edit team colours">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </a>                    
                </li>

                <li class="nav-item">                    
                    <a :class="[(activePath == 'pitch_planner' ? 'active' : '', 'nav-link'), (isMatchScheduled ? 'doc-filled' : '')]" data-toggle="tab" href="#pitch_planner" role="tab" @click="GetSelectComponent('pitch_planner')">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>

                        {{$lang.pitch_planner_label}}
        
                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="top" data-content="Manually or automatically schedule games and allocate referees">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a :class="[(activePath == 'tournaments_summary_details' ? 'active' : '', 'nav-link'), (isScoreUpdated ? 'doc-filled' : '')]" data-toggle="tab" href="#home3" role="tab" @click="GetSelectComponent('tournaments_summary_details')">
                        <span class="icon-football-block"><i class="fas fa-futbol"></i></span>
                    
                        {{$lang.summary_label}}

                        <span class="text-tooltip" data-toggle="popover" data-animation="false" data-placement="left" data-content="Edit and view results, access reports and send messages to your tournament participants">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </a>                    
                </li>
            </ul>
          <router-view></router-view>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
import _ from 'lodash'
import Tournament from '../../../api/tournament.js'
export default {
  data() {
    return {
      'header' : 'header',
      'tournamentId' : this.$store.state.Tournament.tournamentId,
    }
  },
  computed: {
    activePath() {
      return this.$store.state.activePath
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
    }
  },
  mounted() {
    this.updateTabStateData();
    this.$store.dispatch('ResetPitchPlannerFromEnlargeMode');
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
      if(componentName != 'competation_format' || componentName != 'pitch_planner' ||  componentName != 'tournament_add' ) {
      setTimeout( function(){
       // alert('called')
       // alert($(document).height())
       // alert($(window).height())
        if ($(document).height() > $(window).height()) {
                    $('.site-footer').removeClass('sticky');
                } else {
                   $('.site-footer').addClass('sticky');
                }

      },2000 )
      }
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
      this.$store.dispatch('SetTeams',this.$store.state.Tournament.tournamentId);
      this.$store.dispatch('SetPitches',this.$store.state.Tournament.tournamentId);
      this.$store.dispatch('setMatches');
    }
  },
}
</script>
