<template>
  <div class="">
    <!-- categories -->
    <div class="" v-if="currentView == 'ageCategoryList'">
      <table class="table table-hover table-bordered" v-if="competationList.length > 0">
        <thead>
          <tr>
            <th class="text-center">{{$lang.summary_schedule_draws_categories}}</th>            
            <th class="text-center">{{$lang.summary_schedule_team}}</th>          
          </tr>
        </thead>
        <tbody>
          <tr v-for="competation in competationList">
            <td>
              <a class="text-primary" href="" @click.prevent="showGroups(competation.id)"><u>{{ competation.group_name }} ({{ competation.category_age }})</u></a>
              <a href="#" data-toggle="modal" data-target="#commentmodal" class="text-primary" @click.prevent="showComment(competation)"><i class="fa fa-info-circle" v-if="competation.comments != null"></i></a>
            </td>
            <td class="text-center">{{ competation.total_teams }}</td>
          </tr>
        </tbody>
      </table>
      <span v-else>No information available</span>
    </div>
    <!-- after click -->
    <div class="" v-if="currentView == 'drawList'">
      <a @click="changeTable()" data-toggle="tab" href="javascript:void(0)"
      role="tab" aria-expanded="true"
      class="btn btn-primary mb-2">
      <i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to category list</a>
      <table class="table table-hover table-bordered" v-if="groupsData.length > 0">
        <thead>
              <tr>
                  <th class="text-center">{{$lang.summary_schedule_draws_categories}}</th>
                  <th class="text-center">{{$lang.summary_schedule_type}}</th>
                  <th class="text-center">{{$lang.summary_schedule_team}}</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="drawData in groupsData">
              <td>
                <a class="pull-left text-left text-primary" @click.prevent="changeGroup(drawData)" href=""><u>{{ drawData.name }}</u> </a>
              </td>
              <td>{{ drawData.competation_type }}</td>
              <td class="text-center">{{ drawData.team_size }}</td>
            </tr>
          </tbody>
      </table>
    </div>
    <div class="modal" id="commentmodal" tabindex="-1" role="dialog" aria-labelledby="commentmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="competationmodalLabel">{{$lang.competation_modal_comments}}</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                {{ ageCatgeoryComments }}
              </div>
            </div>    
          </div>
         </div>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'
import DrawDetails from './DrawDetails.vue'

export default {
  data() {
    return {
      competationList:[],
      showTable: 'category',
      groupsData:[],
      ageCatgeoryComments: ''
    }
  },
  mounted() {
    this.getCategoryCompetitions();
    if(this.currentAgeCategoryId != 0){
      this.showGroups(this.currentAgeCategoryId);
    }

  },
	// props:['matchData'],
	components: {
		TeamDetails, DrawDetails
	},
  computed: {
    currentView() {
      return this.$store.state.currentScheduleViewAgeCategory
    },
    currentAgeCategoryId() {
      return this.$store.state.currentAgeCategoryId
    }
  },
	methods: {
		/*changeTeam(Id, Name) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			this.$root.$emit('changeDrawListComp',Id,Name);
			//this.$emit('changeComp', Id);
		},*/
		changeGroup(data) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			let Id = data.id
			let Name = data.name
      let CompetationType = data.actual_competition_type
			this.$root.$emit('changeDrawListComp',Id, Name,CompetationType);
			//this.$emit('changeComp',Id);
		},
    getCategoryCompetitions() {
      let tournamentId = this.$store.state.Tournament.tournamentId
      let TournamentData = {'tournament_id':tournamentId}
      Tournament.getCompetationFormat(TournamentData).then(
        (response)=> {
          this.competationList = response.data.data
        },
        (error) => {
          alert('Error in getting category competitions')
        }
      )
    },
    showGroups(ageGroupId) {
      this.$store.dispatch('setCurrentScheduleViewAgeCategory','drawList')
      this.$store.dispatch('setcurrentAgeCategoryId',ageGroupId)


      let tournamentData = {'ageGroupId': ageGroupId}
      Tournament.getCategoryCompetitions(tournamentData).then(
        (response) => {
          this.groupsData = response.data.competitions
          this.showTable = "groups"
        },
        (error) => {
        }
      )
    },
    changeTable() {
      this.$store.dispatch('setCurrentScheduleViewAgeCategory','ageCategoryList')
      this.showTable = "category"
    },
    showComment(competition) {
      this.ageCatgeoryComments = competition.comments;
    },
	},
	filters: {
    formatGroup:function (value,round) {
      if(round == 'Round Robin') {
         return value
      }
      if(!isNaN(value.slice(-1))) {
         return value.substring(0,value.length-1)
      } else {
         return value
      }
    }
  },
}
</script>
