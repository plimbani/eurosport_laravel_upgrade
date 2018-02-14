<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="historyYearsAgeCategoryTeamList" :options="{draggable:'.history-year-age-category-team-item', handle: '.history-year-age-category-team-handle'}">
				<div class="history-year-age-category-team-item draggable--section-card" v-for="(historyYearsAgeCategoryTeam, index) in historyYearsAgeCategoryTeamList" :key="historyYearsAgeCategoryTeam.id">
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
		<history-year-age-category-team-modal :currentHistoryYearAgeCategoryTeamOperation="currentHistoryYearAgeCategoryTeamOperation" @storeHistoryYearAgeCategoryTeam="storeHistoryYearAgeCategoryTeam" @updateHistoryYearAgeCategoryTeam="updateHistoryYearAgeCategoryTeam" :modalIndex="categoryIndex" ></history-year-age-category-team-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import HistoryYearAgeCategoryTeamModal from './HistoryYearAgeCategoryTeamModal.vue';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		props: ['teamList', 'childClassNames', 'categoryIndex'],
		data() {
			return {
				historyYearsAgeCategoryTeamList: [],
				currentHistoryYearAgeCategoryTeamIndex: -1,
				currentHistoryYearAgeCategoryTeamOperation: 'add',
			};
		},
		components: {
			draggable,
			HistoryYearAgeCategoryTeamModal,
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
				this.initializeAgeCategoryTeamModel(formData);
			},
			storeHistoryYearAgeCategoryTeam(historyYearAgeCategoryTeamData) {
				this.historyYearsAgeCategoryTeamList.push({ id: '', name: historyYearAgeCategoryTeamData.name });
				$('#history_year_age_category_team_modal_' + this.categoryIndex).modal('hide');
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
				this.historyYearsAgeCategoryTeamList[this.currentHistoryYearIndex].name = historyYearAgeCategoryTeamData.name;
				$('#history_year_age_category_team_modal_' + this.categoryIndex).modal('hide');
			},
			deleteHistoryYearAgeCategoryTeam(deleteIndex) {
				this.historyYearsAgeCategoryTeamList = _.remove(this.historyYearsAgeCategoryTeamList, function(stat, index) {
				  return index != deleteIndex;
				});
			},
			initializeAgeCategoryTeamModel(formData) {
				var vm = this;
				this.$root.$emit('setHistoryYearAgeCategoryTeamData', formData);
				$('#history_year_age_category_team_modal_' + this.categoryIndex).modal('show');
			},
		}
	}
</script>