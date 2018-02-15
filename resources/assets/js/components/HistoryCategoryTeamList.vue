<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="categoryTeamList" :options="{draggable:'.history-year-age-category-team-item', handle: '.history-year-age-category-team-handle'}">
				<div class="history-year-age-category-team-item draggable--section-card" v-for="(categoryTeam, index) in categoryTeamList" :key="categoryTeam.id">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
				  				{{ categoryTeam.name }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteHistoryYearAgeCategoryTeam(index)">
						        	<i class="jv-icon jv-dustbin"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editHistoryYearAgeCategoryTeam(categoryTeam, index)">
						        	<i class="jv-icon jv-edit"></i>
						        </a>
						        <a class="text-primary history-year-age-category-team-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fa fa-bars"></i>
						        </a>
						    </div>
						</div>
					</div>
				</div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addCategoryTeam()">{{ $lang.add_a_team }}</button>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		props: ['teams', 'childClassNames', 'categoryIndex', 'yearIndex'],
		data() {
			return {
				categoryTeamList: [],
				categoryTeamIndex: -1,
				categoryTeamOperation: 'add',
			};
		},
		components: {
			draggable,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		watch: {
			teams: function(value) {
				this.categoryTeamList = _.cloneDeep(value);
			},
		},
		mounted() {
			// Get all age category
			this.categoryTeamList = this.teams;
			this.$root.$on('getCategoryTeamList', this.getCategoryTeamList);
		},
		methods: {
			addCategoryTeam() {
				var formData = {
					id: '',
					name: '',
				};
				
				this.categoryTeamIndex = this.categoryTeamList.length;
				this.categoryTeamOperation = 'add';

				var additionalParams = {
					categoryTeamIndex: this.categoryTeamIndex,
					categoryTeamOperation: this.categoryTeamOperation,
					yearIndex: this.yearIndex,
					categoryIndex: this.categoryIndex,
				};
				this.$emit('initializeTeamModal', formData, additionalParams);
			},

			editHistoryYearAgeCategoryTeam(historyYearAgeCategory, index) {
				var formData = {
					id: historyYearAgeCategory.id,
					name: historyYearAgeCategory.name,
				};
				this.categoryTeamIndex = this.categoryTeamList.length;
				this.categoryTeamOperation = 'edit';

				var additionalParams = {
					categoryTeamIndex: this.categoryTeamIndex,
					categoryTeamOperation: this.categoryTeamOperation,
					yearIndex: this.yearIndex,
					categoryIndex: this.categoryIndex,
				};
				this.$emit('initializeTeamModal', formData, additionalParams);
			},
			// updateHistoryYearAgeCategoryTeam(historyYearAgeCategoryTeamData) {
			// 	this.categoryTeamList[this.categoryTeamIndex].name = historyYearAgeCategoryTeamData.name;
			// 	this.getCategoryTeamList();
			// 	$('#history_year_age_category_team_modal_' + this.parentIndex).modal('hide');
			// },
			// deleteHistoryYearAgeCategoryTeam(deleteIndex) {
			// 	this.categoryTeamList = _.remove(this.categoryTeamList, function(stat, index) {
			// 	  return index != deleteIndex;
			// 	});
			// 	this.getCategoryTeamList();
			// },

			onDragEnd() {
				this.getCategoryTeamList();
			},
			getCategoryTeamList() {
        this.$emit('setHistoryAgeCategoryTeamList', _.cloneDeep(this.categoryTeamList), this.parentIndex);
      },
		}
	}
</script>