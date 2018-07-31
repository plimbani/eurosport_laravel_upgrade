<template>
	<div>
	<div class="form-group">
	   <a @click="backToTeamList()" data-toggle="tab" href="javascript:void(0)"
	   role="tab" aria-expanded="true" class="btn btn-primary">
       <i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to team list</a>
	</div>
		<div class="row">
			<div class="col-md-12 mb-3">
				<div class="row">
					<div class="col-md-12">
						<label class="mt-3"><h6 class="mr-3 mb-0">{{otherData.TeamName}}</h6></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<component :is="teamView"  :matchData1="matchData"></component>
					</div>
				</div>
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
		this.currentView = 'matchList'

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
            msg = 'category list'
          }
          if(this.$store.state.setCurrentView == 'teamListing') {
            msg = 'team list'
          }
          if(this.$store.state.setCurrentView == 'matchListing') {
            msg = 'match list'
          }
          return msg
        }
  	},
	methods: {
		onChangeDrawDetails(){
			 this.$store.dispatch('setCurrentScheduleView','teamDetails')
		},
		setTeamView(view) {
			this.teamView = view
		},
		setCurrentView(currentScheduleView,currentView) {
			// alert('hi');
          this.currentView = currentView
          this.$store.dispatch('setCurrentScheduleView','matchList')
          this.$root.$emit('changeDrawListComp')
   	    },
        backToTeamList() {
        	
        },
   	    setCurrentViewTeam(currentView) {
		  if(currentView != this.currentView)
		  {
			let currentScheduleView = this.$store.state.currentScheduleView

			if(currentScheduleView == 'matchList') {
				this.currentView = 'teamStanding'
				this.$store.dispatch('setCurrentScheduleView','matchList')
			}

			this.currentView = currentView
		  }
			// Here we again
		}

	}

}
</script>
