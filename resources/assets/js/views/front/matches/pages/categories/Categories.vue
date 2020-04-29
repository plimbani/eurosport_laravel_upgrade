<template>
	<div>
		<div class="table-responsive" v-if="showView == 'category'">
			<table id="categoriesTable" class="table table-hover table-bordered">
				<thead class="no-border">
					<tr>
						<th>{{ $t('matches.categories') }}</th>
						<th>{{ $t('matches.teams') }}</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="category in categories">
						<td>
							<a class="text-primary" href="#" @click.prevent="showCategoryGroups(category.id)">
								<u>{{ category.group_name }} ({{ category.category_age }})</u>
							</a>
              <a href="#" data-toggle="modal" data-target="#commentmodal" class="text-primary" @click.prevent="showComment(category)"><i class="fas fa-info-circle" v-if="category.comments != null"></i></a>
              <a href="#" @click="viewTemplateGraphic(category.id, category.tournament_template_id)" class="btn btn-outline-primary btn-sm ml-2 float-right text-primary">{{ $t('matches.view_schedule') }}</a>
						</td>
						<td>{{ category.total_teams }}</td>
					</tr>
          <displaygraphic :sectionGraphicImage="'DrawList'"></displaygraphic>
				</tbody>
			</table>
		</div>
		<div class="no-data h6 text-muted" v-if="categories.length == 0">{{ $t('matches.no_categories_found') }}</div>

		<!-- For category groups -->
		<div class="" v-if="showView == 'groups'">
      <button @click="changeTable()" href="javascript:void(0)" aria-expanded="true" class="btn btn-primary mb-2 text-white">
      <i aria-hidden="true" class="fas fa-angle-double-left"></i> {{ $t('matches.back_to_category_list') }}</button>
      <div v-for="(drawData,index) in groupsFilter">
        <h6 class="mt-2">
          <strong>{{ index }}</strong>
        </h6>
        <table class="table table-hover table-bordered mt-2" v-if="drawData.length > 0">
          <thead class="no-border">
              <tr>
                <th>{{ $t('matches.categories') }}</th>
                <th>{{ $t('matches.type') }}</th>
                <th>{{ $t('matches.teams') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="group in drawData">
                <td>
                  <a class="pull-left text-left text-primary" href="javascript:void(0)" @click.prevent="showCompetitionDetail(group)"><u>{{ group.name }}</u> </a>
                </td>
                <td>{{ group.competation_type }}</td>
                <td class="text-center">{{ group.team_size }}</td>
              </tr>
            </tbody>
        </table>
      </div>

      <div class="row">
        <div v-for="(divData,index) in divFilter" class="col-md-6">
          <h6 class="mt-2">
            <strong>{{ index | getDivName}}</strong>
          </h6>
          <div v-for="(draw1,index1) in divData">
            <h6 class="mt-2">
              <strong>{{ index1 }}</strong>
            </h6>

            <table class="table table-hover table-bordered mt-2">
              <thead>
                  <tr>
                      <th>{{$t('matches.categories')}}</th>
                      <th class="text-center" style="width:200px">{{$t('matches.type')}}</th>
                      <th class="text-center" style="width:100px">{{ $t('matches.teams')}}</th>
                  </tr>
              </thead>
              <tbody>
                <tr  v-for="draw in draw1"> <!--  -->
                    <td>
                      <a class="pull-left text-left text-primary" @click.prevent="showCompetitionDetail(draw)" href=""><u>{{ draw.display_name }}</u> </a>
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
  
    <!-- Competition detail page -->
    <competition v-if="showView == 'competition'" :matches="matches" :competitionDetail="competitionDetail" :currentView="'Competition'" :fromView="'Categories'" :categoryId="currentCategoryId"></competition>
    
    <!-- Category comment modal -->
    <div class="modal" id="commentmodal" tabindex="-1" role="dialog" aria-labelledby="commentmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title font-weight-bold" id="competationmodalLabel">Info for teams</h5>
             <button type="button" class="close" data-dismiss="modal" :aria-label="$t('tournament.close')">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                {{ ageCategoryComments }}
              </div>
            </div>    
          </div>
         </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">
  import CategoryList from '../../../../../api/frontend/categorylist.js';
  import MatchList from '../../../../../api/frontend/matchlist.js';
  import Competition from './../list/components/Competition.vue';
  import displaygraphic from '../../../../../components/DisplayGraphicalStructure.vue';

	export default {
		data() {
      return {
      	categories: [],
      	groupsData: [],
      	showView: 'category',
      	tournamentData: tournamentData,
        ageCategoryComments: '',
        matches: [],
        competitionDetail: {
          id: '',
          name: '',
          type: '',
        },
        currentCategoryId: '',
        divData: [],
        templateGraphicImageName: '',
        templateGraphicImagePath: '',
        divisionName:'',
        divisionId:'',
        groupsFilter: {},
        divFilter: {},
      };
  	},
  	computed: {
  	},
   	components: {
      Competition,displaygraphic
		},
		mounted() {			
			this.getAllCategoriesData();
		},
    created() {
      this.$root.$on('showCategoryGroups', this.showCategoryGroups);
      this.$root.$on('showCompetitionViewFromCategory', this.showCompetitionViewFromCategory);
    },
    beforeCreate() {
      // Remove custom event listener
      this.$root.$off('showCategoryGroups');
      this.$root.$off('showCompetitionViewFromCategory');
    },
    methods: {
    	getAllCategoriesData() {
    	  let data = {'tournament_id': tournamentData.id};
        CategoryList.getAllCategoriesData(data).then(
        (response)=> {
        	this.categories = response.data.data;
        },
        (error)=> {}
        )
    	},
    	showCategoryGroups(ageGroupId) {
				let tournamentData = {'ageGroupId': ageGroupId,'fromDrawList':1};
        this.currentCategoryId = ageGroupId;
		    CategoryList.getCategoryCompetitions(tournamentData).then(
	        (response) => {

            let filterData = response.data.competitions.round_robin;
            let filter = _.groupBy(filterData, 'competation_round_no');
            this.groupsFilter = _.groupBy(filterData, 'competation_round_no');
            this.groupsData = response.data.competitions.round_robin;
            this.showView = "groups"

            this.divFilter = response.data.competitions.division;
	        },
	        (error) => {
	        }
		    )
    	},
    	changeTable() {
    		this.showView = 'category';
    	},
      showCompetitionDetail(group) {
        // this.showView = 'competition';
        var id = group.id;
        var competitionName = group.name;
        var competitionType = group.actual_competition_type;
        this.getSelectedCompetitionDetails(id, competitionName, competitionType);
      },
      showComment(category) {
        this.ageCategoryComments = category.comments;
      },
      getSelectedCompetitionDetails(competitionId, competitionName, competitionType) {
        var tournamentId = tournamentData.id;
        var data = {'tournamentId': tournamentId, 'competitionId': competitionId};
        var vm = this;

        this.competitionDetail.name = competitionName;
        this.competitionDetail.id = competitionId;
        this.competitionDetail.type = competitionType;

        MatchList.getFixtures(data).then(
          (response)=> {
            if(response.data.status_code == 200) {
              vm.matches = response.data.data;
              vm.$root.$emit('setMatchesForMatchList', _.cloneDeep(response.data.data));
              vm.showView = 'competition';
            }
          },
          (error) => {
          }
        )
      },
      showCompetitionViewFromCategory(id, competitionName, competitionType) {
        // this.showView = 'competition';
        this.getSelectedCompetitionDetails(id, competitionName, competitionType);
      },
      viewTemplateGraphic : function(ageCategoryId, templateId){
        this.$root.$emit('getTemplateGraphic', ageCategoryId, templateId);
        $('#displayGraphicImage').modal('show');
      }
    },
    filters: {
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