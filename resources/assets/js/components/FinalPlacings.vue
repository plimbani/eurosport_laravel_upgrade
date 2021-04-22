<template>
  <div class="">
    <!-- categories -->
    <div class="" v-if="currentView == 'finalPlacings'">
      <table class="table table-hover table-bordered" v-if="competationList.length > 0">
        <thead>
          <tr>
            <th>{{$lang.summary_schedule_draws_age_category}}</th>
            <th>{{$lang.summary_schedule_draws_categories_name}}</th>
            <th class="text-center">{{$lang.summary_schedule_draws_age_placings}}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="competation in competationList">
            <td>{{ competation.category_age }}</td>
            <td>{{ competation.group_name }}</td>
            <td class="text-center">
              <a @click="getAgeCategoryDetail(competation.id)" class="text-primary" href="#" data-toggle="modal" data-target="#viewPlacingsModal"> 
                <u>{{ $lang.summary_schedule_view_placings }}</u>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
      <span v-else>No information available</span>
    </div>

    <!-- view final placings modal -->
    <div class="modal" id="viewPlacingsModal" tabindex="-1" role="dialog" aria-labelledby="viewPlacingsLabel" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="competationmodalLabel">{{$lang.competation_modal_placings}}</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body modal-fixed-height">
              <div class="form-group row mb-0" v-for="position in positionData">
                <div class="col-sm-4 form-control-label font-weight-bold border">Placing {{ position.pos }}</div>
                <div class="col-sm-4 form-control-label d-flex"> 
                  <span :class="'flag-icon flag-icon-' + position.team_flag"></span>
                  <span class="ml-1">{{ position.team_name }}</span>
                </div>
                <div class="col-sm-4 form-control-label text-right">
                    <a @click="deleteFinalPlacingTeam(position.position_id)" class="text-primary" href="javascript:void(0)"><i class="fas fa-trash text-danger"></i></a>
                </div>
              </div>   
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
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
      ageCatgeoryComments: '',
      positionData: [],
      positionId: '',
      ageCategoryId: '',
    }
  },
  mounted() {    
    this.$store.dispatch('setCurrentScheduleView','finalPlacings')
    this.getCategoryCompetitions();
    if(this.currentAgeCategoryId != 0){
      this.showGroups(this.currentAgeCategoryId);
    }
  },
  components: {
    TeamDetails, DrawDetails
  },
  computed: {
    currentView() {
      return this.$store.state.currentScheduleView
    },
    currentAgeCategoryId() {
      return this.$store.state.currentAgeCategoryId
    }
  },
  methods: {
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
      this.$store.dispatch('setCurrentScheduleViewAgeCategory','finalPlacings')
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
    getAgeCategoryDetail(ageCategoryId) {
      this.ageCategoryId = ageCategoryId
      let ageCategoryData = {'ageCategoryId': ageCategoryId}
      Tournament.getPlacingsData(ageCategoryData).then(
        (response) => {
          this.positionData = response.data.data
        },
        (error) => {

        }
      )
    },
    closeModal() {
        $('#viewPlacingsModal').modal('hide')
        return false
    },
    deleteFinalPlacingTeam(positionId) {
      let placingData = {'positionId': positionId, 'ageCategoryId': this.ageCategoryId}
      Tournament.deleteFinalPlacingTeam(placingData).then(
        (response) => {
          this.getAgeCategoryDetail(this.ageCategoryId)
          toastr.success('Placing has been deleted successfully.', 'Delete placing', {timeOut: 5000});
        },
        (error) => {

        }
      )
    }
  },
  filters: {
  },
}
</script>