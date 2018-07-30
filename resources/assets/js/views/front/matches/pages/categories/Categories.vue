<template>
	<div>
		<div class="table-responsive" v-if="showTable == 'category'">
			<table id="categoriesTable" class="table table-hover table-bordered mt-4">
				<thead>
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
						</td>
						<td>{{ category.total_teams }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="no-data h6 text-muted" v-if="categories.length == 0">{{ $t('matches.no_categories_found') }}</div>

		<!-- For category groups -->
		<div class="" v-if="showTable == 'groups'">
      <a @click="changeTable()" href="javascript:void(0)" aria-expanded="true" class="btn btn-primary mb-2">
      <i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to category list</a>
      <table class="table table-hover table-bordered" v-if="groupsData.length > 0">
        <thead>
              <tr>
                  <th class="text-center">{{ $t('matches.categories') }}</th>
                  <th class="text-center">{{ $t('matches.type') }}</th>
                  <th class="text-center">{{ $t('matches.teams') }}</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="group in groupsData">
              <td>
                <a class="pull-left text-left text-primary" href="#" @click.prevent="showCompetitionData(group)"><u>{{ group.name }}</u> </a>
              </td>
              <td>{{ group.competation_type }}</td>
              <td class="text-center">{{ group.team_size }}</td>
            </tr>
          </tbody>
      </table>
    </div>

	</div>
</template>

<script type="text/babel">
  import CategoryList from '../../../../../api/frontend/categorylist.js';

	export default {
		data() {
      return {
      	categories: [],
      	groupsData: [],
      	showTable: 'category',
      	tournamentData: tournamentData,
      };
  	},
  	computed: {
  	},
   	components: {
		},
		mounted() {			
			this.getAllCategoriesData();
		},
    created() {
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
		    CategoryList.getCategoryCompetitions(tournamentData).then(
	        (response) => {
	          this.groupsData = response.data.competitions;
	          this.showTable = 'groups';
	        },
	        (error) => {
	        }
		    )
    	},
    	changeTable() {
    		this.showTable = 'category';
    	},
      showCompetitionData(group) {
        // var id = group.id;
        // var competitionName = group.name;
        // var competitionType = group.competation_type;
        // this.$root.$emit('showCompetitionData', id, competitionName, competitionType);
      }
    }
	}
</script>