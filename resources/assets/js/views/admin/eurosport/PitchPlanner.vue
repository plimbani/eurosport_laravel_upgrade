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
	  				<pitch-planner-table></pitch-planner-table>
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

	export default {
    data() {
       return {
         'tournamentId': this.$store.state.Tournament.tournamentId,
         'section':'pitchPlanner',
         'isVertical': true,
         'isHorizontal': false,
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
      }
    },
    components: {
        PitchModal, PitchPlannerTable, PitchPlannerFilter
    }
}
</script>
