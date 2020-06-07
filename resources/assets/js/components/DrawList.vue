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
              <a href="#" @click="viewTemplateGraphic(competation.id, competation.tournament_template_id)"class="btn btn-outline-primary btn-sm ml-2 float-right">View schedule</a>
            </td>
            <td class="text-center">{{ competation.total_teams }}</td>
          </tr>
          <displaygraphic :sectionGraphicImage="'DrawList'" :categoryId="categoryId" :tournamentTemplateId="tournamentTemplateId" :tournamentId="$store.state.Tournament.tournamentId"></displaygraphic>
        </tbody>
      </table>
      <span v-else>No information available</span>
    </div>
    <!-- after click -->
    <div class="" v-if="currentView == 'drawList'">
      <a @click="changeTable()" data-toggle="tab" href="javascript:void(0)"
      role="tab" aria-expanded="true"
      class="btn btn-primary mb-2">
      <i aria-hidden="true" class="fas fa-angle-double-left"></i>Back to category list</a>
      <div v-for="(drawData,index) in groupsFilter">
        <h6 class="mt-2">
          <strong>{{ index }}</strong>
        </h6>
        <table class="table table-hover table-bordered" v-if="drawData.length > 0">
          <thead>
              <tr>
                  <th>{{$lang.summary_schedule_draws_categories}}</th>
                  <th class="text-center" style="width:200px">{{$lang.summary_schedule_type}}</th>
                  <th class="text-center" style="width:100px">{{$lang.summary_schedule_team}}</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="draw in drawData">
                <td>
                  <a class="pull-left text-left text-primary" @click.prevent="changeGroup(draw)" href=""><u>{{ draw.display_name }}</u> </a>
                  <a v-if="(isUserDataExist && !isResultAdmin)" href="#" @click="openEditCompetitionNameModal(draw)" class="pull-right text-primary"><i class="fas fa-pencil"></i></a>
                </td>
                <td class="text-center">{{ draw.competation_type }}</td>
                <td class="text-center">{{ draw.team_size }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="row">
        <div v-for="(divData,index) in divFilter" class="col-md-6">
          <h6 class="mt-2 round-title">
            <strong><a class="text-center" href="javascript:void(0)" @click="openEditCategoryDivisionNameModal(index)">{{ index | getDivName}}<i v-if="isUserDataExist" class="fas fa-pencil ml-2"></i></a></strong>
          </h6>
          <div v-for="(draw1,index1) in divData">
            <h6 class="mt-4 mb-3 nested-rounds">
              <strong>{{ index1 }}</strong>
            </h6>

            <table class="table table-hover table-bordered">
              <thead>
                  <tr>
                      <th>{{$lang.summary_schedule_draws_categories}}</th>
                      <th class="text-center" style="width:200px">{{$lang.summary_schedule_type}}</th>
                      <th class="text-center" style="width:100px">{{$lang.summary_schedule_team}}</th>
                  </tr>
              </thead>
              <tbody>
                <tr  v-for="draw in draw1"> <!--  -->
                    <td>
                      <a class="pull-left text-left text-primary" @click.prevent="changeGroup(draw)" href=""><u>{{ draw.display_name }}</u> </a>
                      <a v-if="isUserDataExist" href="#" @click="openEditCompetitionNameModal(draw)" class="pull-right text-primary"><i class="fas fa-pencil"></i></a>
                    </td>
                    <td class="text-center">{{ draw.competation_type }}</td>
                    <td class="text-center">{{ draw.team_size }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal" id="commentmodal" tabindex="-1" role="dialog" aria-labelledby="commentmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="competationmodalLabel">Info for teams</h5>
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
    <div class="modal" id="editCompetitionNameModal" tabindex="-1" role="dialog" aria-labelledby="commentmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="competationmodalLabel">Edit Name</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <div class="col-sm-4 form-control-label">Name</div>
                  <div class="col-sm-8">
                    <input type="text" name="competition_display_name" v-model="competitionData.display_name" class="form-control">
                  </div>
                </div>
              </div>
            </div>    
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="closeModal()">{{$lang.pitch_modal_cancel}}</button>
            <button type="button" class="btn btn-primary" @click="updateCompetitionName()">{{$lang.pitch_modal_save}}</button>
          </div>
         </div>
      </div>
    </div> 
    

    <div class="modal" id="editDivisionNameModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title">Rename</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-4 form-control-label">Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="name" v-validate="'required'" :class="{'is-danger': errors.has('name') }" v-model="divisionName" class="form-control">
                    <i v-show="errors.has('name')" class="fa fa-warning"></i>
                  <span class="help is-danger" v-show="errors.has('name')">{{ 
                    errors.first('name') }}<br>
                  </span>
                  </span>
                  </div>
                </div>
              </div>
            </div>    
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="updateCategoryDivisionName()">Save</button>
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
import displaygraphic from './DisplayGraphicalStructure.vue'
import _ from 'lodash'

export default {
  data() {
    return {
      competationList:[],
      showTable: 'category',
      groupsData:[],
      ageCatgeoryComments: '',
      competitionData: {},
      divisionName:'',
      divisionId:'',
      groupsFilter: {},
      divFilter: {},
      categoryId: null,
      tournamentTemplateId: null,
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
		TeamDetails, DrawDetails, displaygraphic
	},
  computed: {
    currentView() {
      return this.$store.state.currentScheduleViewAgeCategory
    },
    currentAgeCategoryId() {
      return this.$store.state.currentAgeCategoryId
    },
    isUserDataExist() {
      return this.$store.state.isAdmin;
    },
    isResultAdmin() {
      return this.$store.state.Users.userDetails.role_slug == 'Results.administrator';
    },    
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

      let tournamentData = {'ageGroupId': ageGroupId,'fromDrawList':1}
      Tournament.getCategoryCompetitions(tournamentData).then(
        (response) => {

          let filterData = response.data.competitions.round_robin;
          let filter = _.groupBy(filterData, 'competation_round_no');
          this.groupsFilter = _.groupBy(filterData, 'competation_round_no');
          this.groupsData = response.data.competitions.round_robin;
          this.showTable = "groups"

          this.divFilter = response.data.competitions.division;
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
    openEditCompetitionNameModal(drawData) {
      this.competitionData = _.clone(drawData, true);
      // this.competitionData.display_name = drawData.display_name;
      $('#editCompetitionNameModal').modal('show');
    },
    openEditCategoryDivisionNameModal(divData) {
      let divName =  _.clone(divData, true);

      this.divisionName = divName.split("|")[1];
      this.divisionId = divName.split("|")[0];

      $('#editDivisionNameModal').modal('show');
    },

    updateCompetitionName() {
      var data = {'competitionData': this.competitionData};
      Tournament.updateCompetitionDisplayName(data).then(
        (response) => {
          $('#editCompetitionNameModal').modal('hide');
          toastr.success(response.data.options.message, 'Competition Details', {timeOut: 5000});
          this.showGroups(this.currentAgeCategoryId);
        },
        (error) => {
        }
      )
    },
    updateCategoryDivisionName() {
      this.$validator.validateAll().then((response) => {
        if (!this.errors.any()) {
          let tournamentId = this.$store.state.Tournament.tournamentId
          let ageCategoryId = this.$store.state.currentAgeCategoryId
          let TournamentData = {'tournament_id':tournamentId, 'currentAgeCategoryId':ageCategoryId,'divisionId':this.divisionId,'categoryDivisionName': this.divisionName}
          Tournament.updateCategoryDivisionName(TournamentData).then(
            (response) => {
              $('#editDivisionNameModal').modal('hide');
              toastr.success('Division name has been update successfully.', 'Division Name', {timeOut: 5000});
              this.showGroups(this.currentAgeCategoryId);
            },
            (error) => {
            }
          )
        }
       }).catch(() => {
          // toastr['error']('Invalid Credentials', 'Error')
       });
    },
    closeModal() {
      $('#editCompetitionNameModal').modal('hide');
    },
    viewTemplateGraphic : function(ageCategoryId, templateId){
      this.categoryId = ageCategoryId;
      this.tournamentTemplateId = templateId;
      this.$root.$emit('getTemplateGraphic', ageCategoryId, templateId);
      $('#displayGraphicImage').modal('show');
    }
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
    },
    getDivName: function (value) {
      if (!value) return ''
      return value.split("|")[1];
    },
    getDivId: function (value) {
      if (!value) return ''
      return value.split("|")[0];
    }
  },
}
</script>

