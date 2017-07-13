<template>
	<div>
	<div class="form-group">
	   <a @click="setCurrentTabView(currentTabView)" data-toggle="tab" href="javascript:void(0)"
  role="tab" aria-expanded="true"
  class="btn btn-primary">
  <i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to {{setCurrentMsg}}</a>
	</div>
		<div class="row">
			<div class="col-md-12 mb-3">
				<div class="row">
					<div class="col-md-12">
						<h6>{{otherData.TeamName}}</h6>
					</div>
				</div>
        <br>
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary btn-md js-pitch-team-bt matches" :class="[teamView == 'matchList']"> <a @click="setTeamView('matchList')">Matches</a></button>
						<button  class="btn btn-secondary btn-md js-pitch-team-bt teamstanding" :class="[teamView == 'teamStanding']" > <a @click="setTeamView('teamStanding')">Team Standing</a></button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						 <component :is="teamView"
             :matchData="matchData"></component>
					</div>
				</div>
			<!--<matchList :matchData="matchData"></matchList>-->
			</div>
		</div>
	</div>
</template>
<script type="text/babel">
import MatchList from './MatchList.vue'
import TeamStanding from './TeamStanding.vue'
export default {
	props: ['matchData','otherData'],
	data() {
		return {
			teamView: 'matchList'
		}
	},
	mounted() {

		$(document).ready(function() {
	 		 $(document).on('click', '.js-pitch-team-bt', function(e){
                $(".js-pitch-team-bt").removeClass('btn-primary').addClass('btn-secondary');
                $(this).removeClass('btn-secondary').addClass('btn-primary');
            });
        });
	},
	components: {
		MatchList,TeamStanding
	},
  computed: {

        currentTabView() {
          return this.$store.state.setCurrentView
        },
        setCurrentMsg() {
          let msg = ''
          if(this.$store.state.setCurrentView == 'drawsListing') {
            msg = 'draw List'
          }
          if(this.$store.state.setCurrentView == 'teamListing') {
            msg = 'team List'
          }
          if(this.$store.state.setCurrentView == 'matchListing') {
            msg = 'match List'
          }
          return msg
        }
  },
	methods: {
		setTeamView(view) {
			this.teamView = view
		},
		setCurrentView(currentScheduleView,currentView) {
          this.currentView = currentView
          this.$store.dispatch('setCurrentScheduleView',currentScheduleView)
          this.$root.$emit('changeDrawListComp')
   	    },
        setCurrentTabView(setCurrentTabView) {
          if(setCurrentTabView == 'drawsListing')
          {
            this.$store.dispatch('setCurrentScheduleView','drawList')
            this.$root.$emit('changeDrawListComp')
          }
           if(setCurrentTabView == 'teamListing')
          {
            this.$store.dispatch('setCurrentScheduleView','teamList')
            this.$root.$emit('changeComp')
          }
          if(setCurrentTabView == 'matchListing')
          {
            this.$store.dispatch('setCurrentScheduleView','matchList')
            this.$root.$emit('changeComp')
          }
         // alert(setCurrentTabView)
          // this.currentView = currentView
        //  this.$store.dispatch('setCurrentScheduleView','drawList')
        //  this.$root.$emit('changeDrawListComp')
        },
	}

}
</script>
