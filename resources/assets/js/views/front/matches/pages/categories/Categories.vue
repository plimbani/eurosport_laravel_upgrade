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
              <a href="#" data-toggle="modal" data-target="#commentmodal" class="text-primary" @click.prevent="showComment(category)"><i class="fa fa-info-circle" v-if="category.comments != null"></i></a>
						</td>
						<td>{{ category.total_teams }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="no-data h6 text-muted" v-if="categories.length == 0">{{ $t('matches.no_categories_found') }}</div>

		<!-- For category groups -->
		<div class="" v-if="showView == 'groups'">
      <a @click="changeTable()" href="javascript:void(0)" aria-expanded="true" class="btn btn-primary mb-2 text-white">
      <i aria-hidden="true" class="fa fa-angle-double-left"></i> Back to category list</a>
      <div class="table-responsive" v-if="groupsData.length > 0">
        <table class="table table-hover table-bordered mt-2">
          <thead class="no-border">
                <tr>
                    <th>{{ $t('matches.categories') }}</th>
                    <th>{{ $t('matches.type') }}</th>
                    <th>{{ $t('matches.teams') }}</th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="group in groupsData">
                <td>
                  <a class="pull-left text-left text-primary" href="javascript:void(0)" @click.prevent="showCompetitionDetail(group)"><u>{{ group.name }}</u> </a>
                </td>
                <td>{{ group.competation_type }}</td>
                <td class="text-center">{{ group.team_size }}</td>
              </tr>
            </tbody>
        </table>
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
      };
  	},
  	computed: {
  	},
   	components: {
      Competition,
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
				let tournamentData = {'ageGroupId': ageGroupId};
        this.currentCategoryId = ageGroupId;
		    CategoryList.getCategoryCompetitions(tournamentData).then(
	        (response) => {
	          this.groupsData = response.data.competitions;
	          this.showView = 'groups';
	        },
	        (error) => {
	        }
		    )
    	},
    	changeTable() {
    		this.showView = 'category';
    	},
      showCompetitionDetail(group) {
        this.showView = 'competition';
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
            }
          },
          (error) => {
          }
        )
      },
      showCompetitionViewFromCategory(id, competitionName, competitionType) {
        this.showView = 'competition';
        this.getSelectedCompetitionDetails(id, competitionName, competitionType);
      },
    }
	}
</script>