<template>
	<div class="tab-content">
		<div class="card" :class="{ 'border-0' : isPitchPlannerInEnlargeMode }">
			<div class="card-block pb-0">
          <div class="row align-items-center justify-content-start">
            <div class="col-5 align-self-center">
              <h6 class="m-0 fieldset-title" v-if="isPitchPlannerInEnlargeMode == 0"><strong>{{$lang.pitch_planner_label}}</strong>
                <span class="match-planner-view">
                  (<a href="javascript:void(0)" class="horizontal js-horizontal-view" :class="{ 'active-view': isHorizontal }"  @click="setView('timelineDay')">{{$lang.pitch_planner_horizontal}}</a> /
                   <a href="javascript:void(0)" class="vertical" :class="{ 'active-view': isVertical }"  @click="setView('agendaDay')">{{$lang.pitch_planner_vertical}}</a>)
                 </span>
              </h6>
            </div>
            <div class="col-7 align-self-center">
              <pitchPlannerFilter :section="section"></pitchPlannerFilter>
            </div>
            <div>
              <label class="col-md-12 align-self-center pitchCapacityNotice" v-if="!PitchAvailable"> {{$lang.pitch_planner_text}}</label>
            </div>
          </div>
	  			<div class="mt-4" >
	  				<pitch-planner-table :scheduleMatchesArray="scheduleMatchesArray" :isMatchScheduleInEdit="isMatchScheduleInEdit" @changeMatchScheduleStatus="changeMatchScheduleStatus" @saveScheduleMatchResult="saveScheduleMatchResult" @clearScheduleMatchesArray="clearScheduleMatchesArray" @clearAllScheduleMatchesArray="clearAllScheduleMatchesArray"></pitch-planner-table>
            <UnsavedMatchPlannerModel :scheduleMatchesArray="scheduleMatchesArray" @movetoNextRoute="movetoNextRoute"></UnsavedMatchPlannerModel>
	  			</div>
			</div>
		</div>
    <div class="row">
      <div class="col-md-12">
        <div class="pull-right">
            <button class="btn btn-primary" :class="{'is-disabled': allScheduleMatches.length == 0 }" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">
var moment = require('moment');
	import PitchModal from '../../../components/PitchModal.vue';
	import PitchPlannerTable from '../../../components/PitchPlannerTable.vue';
  import PitchPlannerFilter from '../../../components/PitchPlannerFilter.vue';
  import UnsavedMatchPlannerModel from './../../../components/UnsavedMatchPlannerModel.vue';

	export default {
    props: ['showEnlargedPitchPlanner'],
    data() {
       return {
          'tournamentId': this.$store.state.Tournament.tournamentId,
          'section':'pitchPlanner',
          'isVertical': true,
          'isHorizontal': false,
          'scheduleMatchesArray': [],
          'allScheduleMatches': [],
          'isMatchScheduleInEdit': false,
          'movetoNextRouteName': null,
       }
    },
    beforeRouteLeave(to, from, next) {
      if (this.movetoNextRouteName === null && this.isMatchScheduleInEdit === true && this.scheduleMatchesArray.length > 0) {
        this.movetoNextRouteName = to;
        $('#unSavedMatchPlannerModal').modal('show');
      }
      else{
        if(this.showEnlargedPitchPlanner) {
          this.$emit('hideEnlargedPitchPlannerStatus');
        }
        next();
      }
    },
    mounted() {
      let vm = this
    	this.$store.dispatch('SetPitches',this.tournamentId);
    	// Here we put validation check
      let tournamentId = this.$store.state.Tournament.tournamentId
      if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
        toastr['error']('Please Select Tournament', 'Error');
        this.$router.push({name: 'welcome'});
      } else {
        // Means Set Here
       let currentNavigationData = {
        activeTab:'pitch_planner',
        currentPage: 'Match Planner'
      }
        this.$store.dispatch('setActiveTab', currentNavigationData)
      }
    },
    computed: {
      PitchAvailable: function() {
        let pitches = this.$store.state.Pitch.pitches;
        if(pitches != undefined && (pitches.length > 0)) {
          return true;
        }
        return false;
      },
      isPitchPlannerInEnlargeMode() {
        return this.$store.state.Pitch.isPitchPlannerInEnlargeMode
      },
      isPrintPitchPlanner() {
        return this.$store.state.Pitch.isPrintPitchPlanner
      }
    },
    methods: {
      setFilter() {
      },
      setView(view) {
        if(view == 'timelineDay') {
          this.isVertical = false;
          this.isHorizontal = true;
        }
        if(view == 'agendaDay') {
          this.isHorizontal = false;
          this.isVertical = true;
        }
        this.$root.$emit('setView', view);
      },
      changeMatchScheduleStatus(status) {
        this.isMatchScheduleInEdit = status;
      },
      saveScheduleMatchResult(matchData) {
        let matchIndex = _.findIndex(this.scheduleMatchesArray, function(o) { return o.matchId == matchData.matchId; });
        if(matchIndex === -1) {
            this.scheduleMatchesArray.push(matchData);
            this.allScheduleMatches.push(matchData);
        } else {
            this.scheduleMatchesArray[matchIndex] = matchData;
            this.allScheduleMatches[matchIndex] = matchData;
        }
      },
      clearScheduleMatchesArray() {
        this.scheduleMatchesArray = [];
      },
      clearAllScheduleMatchesArray() {
        this.allScheduleMatches = [];
      },
      movetoNextRoute() {
        if(this.movetoNextRouteName) {
          if(this.showEnlargedPitchPlanner) {
            this.$emit('hideEnlargedPitchPlannerStatus');
          }
          $('#unChangedMatchFixtureModal').modal('hide');
          this.$router.push({ name: this.movetoNextRouteName.name, params: this.movetoNextRouteName.params, query: this.movetoNextRouteName.query });
        }
      },
      next() {
        let currentNavigationData = {activeTab:'teams_groups', currentPage: 'Teams and groups'}
        this.$store.dispatch('setActiveTab', currentNavigationData)
        this.$router.push({name:'teams_groups'})
      },
    },
    components: {
        PitchModal, PitchPlannerTable, PitchPlannerFilter, UnsavedMatchPlannerModel
    }
}
</script>
