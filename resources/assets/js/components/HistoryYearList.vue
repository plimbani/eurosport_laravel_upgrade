<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="historyYears" :options="{draggable:'.history-year-item', handle: '.history-year-handle'}">
				<div class="history-year-item draggable--section-card" v-for="(historyYear, index) in historyYears" :key="historyYear.id">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
				  				{{ historyYear.year }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteHistoryYear(index)">
						        	<i class="jv-icon jv-dustbin"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editHistoryYear(historyYear, index)">
						        	<i class="jv-icon jv-edit"></i>
						        </a>
						        <a class="text-primary history-year-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fa fa-bars"></i>
						        </a>
						    </div>
						</div>
						<history-year-age-category-list :parentIndex="index" :childClassNames="'draggable--section-child-1'" 
						:historyAgeCategoryList="historyYear.ageCategoryList" 
						@setHistoryAgeCategoryList="setHistoryAgeCategoryList" 
						@deleteHistoryYearAgeCategory="deleteHistoryYearAgeCategory" 
						@initializeHistoryAgeCategoryModal="initializeHistoryAgeCategoryModal" 
						@initializeHistoryAgeCategoryTeamModal="initializeHistoryAgeCategoryTeamModal"></history-year-age-category-list>
					</div>
				</div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addHistoryYear()">{{ $lang.add_year }}</button>
		<history-year-modal :currentHistoryYearOperation="currentHistoryYearOperation" @storeHistoryYear="storeHistoryYear" @updateHistoryYear="updateHistoryYear"></history-year-modal>

		<history-year-age-category-modal 
			:currentHistoryYearAgeCategoryOperation="historyAgeCategoryModalData.currentHistoryYearAgeCategoryOperation" 
			@storeHistoryYearAgeCategory="storeHistoryYearAgeCategory" 
			@updateHistoryYearAgeCategory="updateHistoryYearAgeCategory" 
			:modalIndex="historyAgeCategoryModalData.parentIndex" >
		</history-year-age-category-modal>

		<history-year-age-category-team-modal :currentHistoryYearAgeCategoryTeamOperation="historyAgeCategoryTeamModalData.currentHistoryYearAgeCategoryTeamOperation" 
		@storeHistoryYearAgeCategoryTeam="storeHistoryYearAgeCategoryTeam" 
		@updateHistoryYearAgeCategoryTeam="updateHistoryYearAgeCategoryTeam" 
		:modalIndex="historyAgeCategoryTeamModalData.parentIndex" ></history-year-age-category-team-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import HistoryYearModal from './HistoryYearModal.vue';
	import HistoryYearAgeCategoryList from './HistoryYearAgeCategoryList.vue';
	import HistoryYearAgeCategoryModal from './HistoryYearAgeCategoryModal.vue';
	import HistoryYearAgeCategoryTeamModal from './HistoryYearAgeCategoryTeamModal.vue';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		data() {
			return {
				historyYears: [],
				currentHistoryYearIndex: -1,
				currentHistoryYearOperation: 'add',
				historyAgeCategoryModalData: {
					currentHistoryYearAgeCategoryIndex: -1,
					currentHistoryYearAgeCategoryOperation: 'add',
					parentIndex: -1,
				},
				historyAgeCategoryTeamModalData: {
					currentHistoryYearAgeCategoryTeamIndex: -1,
					currentHistoryYearAgeCategoryTeamOperation: 'add',
					parentIndex: -1,
				}
			};
		},
		components: {
			draggable,
			HistoryYearModal,
			HistoryYearAgeCategoryList,
			HistoryYearAgeCategoryModal,
			HistoryYearAgeCategoryTeamModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		methods: {
			addHistoryYear() {
				var formData = {
					id: '',
					year: '',
					ageCategoryList: [],
				};
				this.currentHistoryYearIndex = this.historyYears.length;
				this.currentHistoryYearOperation = 'add';
				this.initializeModel(formData);
			},
			storeHistoryYear(historyYearData) {
				this.historyYears.push({ id: '', year: historyYearData.year, ageCategoryList: [] });
				$('#history_year_modal').modal('hide');
			},
			editHistoryYear(historyYear, index) {
				var formData = {
					id: historyYear.id,
					year: historyYear.year,
					ageCategoryList: historyYear.ageCategoryList,
				};
				this.currentHistoryYearIndex = index;
				this.currentHistoryYearOperation = 'edit';
				this.initializeModel(formData);
			},
			updateHistoryYear(historyYearData) {
				this.historyYears[this.currentHistoryYearIndex].year = historyYearData.year;
				this.historyYears[this.currentHistoryYearIndex].ageCategoryList = historyYearData.ageCategoryList;
				$('#history_year_modal').modal('hide');
			},
			deleteHistoryYear(deleteIndex) {
				this.historyYears = _.remove(this.historyYears, function(value, index) {
				  return index != deleteIndex;
				});
			},
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setHistoryYearData', formData);
				$('#history_year_modal').modal('show');
			},
			setHistoryAgeCategoryList(ageCategoryList, index) {
				this.historyYears[index].ageCategoryList = ageCategoryList;
			},


			// --------------

			initializeHistoryAgeCategoryModal(formData, additionalParams) {
				this.historyAgeCategoryModalData.currentHistoryYearAgeCategoryOperation = additionalParams.currentHistoryYearAgeCategoryOperation;
				this.historyAgeCategoryModalData.currentHistoryYearAgeCategoryIndex = additionalParams.currentHistoryYearAgeCategoryIndex;
				this.historyAgeCategoryModalData.parentIndex = additionalParams.parentIndex;
				this.$root.$emit('setHistoryYearAgeCategoryData', formData);
				$('#history_year_age_category_modal').modal('show');
			},

			storeHistoryYearAgeCategory(historyYearAgeCategoryData) {
				var ageCategoryIndex = this.historyAgeCategoryModalData.parentIndex;
				var currentAgeCategoryIndex = this.historyAgeCategoryModalData.currentHistoryYearAgeCategoryIndex;

				this.historyYears[ageCategoryIndex]['ageCategoryList'].push({ id: '', name: historyYearAgeCategoryData.name, teams: [] });
				$('#history_year_age_category_modal').modal('hide');

			},

			updateHistoryYearAgeCategory(historyYearAgeCategoryData) {
				var ageCategoryIndex = this.historyAgeCategoryModalData.parentIndex;
				var currentHistoryYearAgeCategoryIndex = this.historyAgeCategoryModalData.currentHistoryYearAgeCategoryIndex;
				this.historyYears[ageCategoryIndex]['ageCategoryList'][currentHistoryYearAgeCategoryIndex].name = historyYearAgeCategoryData.name;
				this.historyYears[ageCategoryIndex]['ageCategoryList'][currentHistoryYearAgeCategoryIndex].teams = historyYearAgeCategoryData.teams;
				$('#history_year_age_category_modal').modal('hide');

			},

			deleteHistoryYearAgeCategory(deleteIndex, historyYearIndex) {
				this.historyYears[historyYearIndex]['ageCategoryList'] = _.remove(this.historyYears[historyYearIndex]['ageCategoryList'], function(value, index) {
					return index != deleteIndex;
				});
			},

			// ---------------------

			initializeHistoryAgeCategoryTeamModal(formData, additionalParams, categoryIndex) {
				this.historyAgeCategoryTeamModalData.currentHistoryYearAgeCategoryTeamOperation = additionalParams.currentHistoryYearAgeCategoryTeamOperation;
				this.historyAgeCategoryTeamModalData.currentHistoryYearAgeCategoryTeamIndex = additionalParams.currentHistoryYearAgeCategoryTeamIndex;
				this.historyAgeCategoryTeamModalData.parentIndex = additionalParams.parentIndex;
				this.$root.$emit('setHistoryYearAgeCategoryTeamData', formData);
				$('#history_year_age_category_team_modal').modal('show');
			},

			storeHistoryYearAgeCategoryTeam(historyYearAgeCategoryTeamData) {
				var yearIndex = this.currentHistoryYearIndex;
				var ageCategoryIndex = this.historyAgeCategoryModalData.currentHistoryYearAgeCategoryIndex;

				this.historyYears[yearIndex]['ageCategoryList'][ageCategoryIndex]['teams'].push({ id: '', name: historyYearAgeCategoryTeamData.name});
				$('#history_year_age_category_team_modal').modal('hide');
			},

			updateHistoryYearAgeCategoryTeam() {

			},

		}
	}
</script>