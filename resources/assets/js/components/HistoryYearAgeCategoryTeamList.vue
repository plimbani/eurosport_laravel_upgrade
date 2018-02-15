<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="historyAgeCategoryTeamsList" :options="{draggable:'.history-year-age-category-team-item', handle: '.history-year-age-category-team-handle'}">
				<div class="history-year-age-category-team-item draggable--section-card" v-for="(historyYearsAgeCategoryTeam, index) in historyAgeCategoryTeamsList" :key="historyYearsAgeCategoryTeam.id">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
				  				{{ historyYearsAgeCategoryTeam.name }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteHistoryYearAgeCategoryTeam(index)">
						        	<i class="jv-icon jv-dustbin"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editHistoryYearAgeCategoryTeam(historyYearsAgeCategoryTeam, index)">
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
		<button type="button" class="btn btn-primary" @click="addHistoryYearAgeCategoryTeam()">{{ $lang.add_a_team }}</button>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		props: ['historyAgeCategoryTeamsList', 'childClassNames', 'parentIndex'],
		data() {
			return {
				historyYearsAgeCategoryTeamList: [],
				currentHistoryYearAgeCategoryTeamIndex: -1,
				currentHistoryYearAgeCategoryTeamOperation: 'add',
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
		methods: {
			addHistoryYearAgeCategoryTeam() {
				var formData = {
					id: '',
					name: '',
				};
				this.currentHistoryYearAgeCategoryTeamIndex = this.historyYearsAgeCategoryTeamList.length;
				this.currentHistoryYearAgeCategoryTeamOperation = 'add';

				var additionalParams = {
					currentHistoryYearAgeCategoryTeamIndex: this.currentHistoryYearAgeCategoryTeamIndex,
					currentHistoryYearAgeCategoryTeamOperation: this.currentHistoryYearAgeCategoryTeamOperation,
					parentIndex: this.parentIndex,
				};
				this.$emit('initializeHistoryAgeCategoryTeamModal', formData, additionalParams);
			},

			storeHistoryYearAgeCategoryTeam(historyYearAgeCategoryTeamData) {
				this.historyYearsAgeCategoryTeamList.push({ id: '', name: historyYearAgeCategoryTeamData.name });
				this.getHistoryAgeCategoryTeamList();
				$('#history_year_age_category_team_modal_' + this.parentIndex).modal('hide');
			},
			editHistoryYearAgeCategoryTeam(historyYearAgeCategory, index) {
				var formData = {
					id: historyYearAgeCategory.id,
					name: historyYearAgeCategory.name,
				};
				this.currentHistoryYearAgeCategoryTeamIndex = index;
				this.currentHistoryYearAgeCategoryTeamOperation = 'edit';
				this.initializeAgeCategoryTeamModel(formData);
			},
			updateHistoryYearAgeCategoryTeam(historyYearAgeCategoryTeamData) {
				this.historyYearsAgeCategoryTeamList[this.currentHistoryYearAgeCategoryTeamIndex].name = historyYearAgeCategoryTeamData.name;
				this.getHistoryAgeCategoryTeamList();
				$('#history_year_age_category_team_modal_' + this.parentIndex).modal('hide');
			},
			deleteHistoryYearAgeCategoryTeam(deleteIndex) {
				this.historyYearsAgeCategoryTeamList = _.remove(this.historyYearsAgeCategoryTeamList, function(stat, index) {
				  return index != deleteIndex;
				});
				this.getHistoryAgeCategoryTeamList();
			},
			initializeAgeCategoryTeamModel(formData) {
				var vm = this;
				this.$root.$emit('setHistoryYearAgeCategoryTeamData', formData);
				$('#history_year_age_category_team_modal_' + this.parentIndex).modal('show');
			},
			onDragEnd() {
				this.getHistoryAgeCategoryTeamList();
			},
			getHistoryAgeCategoryTeamList() {
        this.$emit('setHistoryAgeCategoryTeamList', _.cloneDeep(this.historyYearsAgeCategoryTeamList), this.parentIndex);
      },
		}
	}
</script>