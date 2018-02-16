<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="historyYears" :options="{draggable:'.history-year-item', handle: '.history-year-handle'}">
				<div class="history-year-item draggable--section-card" v-for="(historyYear, index) in historyYears" :key="index">
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
						<history-category-list :yearIndex="index" :childClassNames="'draggable--section-child-1'" :categoryList="historyYear.categoryList" 
						@setHistoryCategoryList="setHistoryCategoryList" @deleteHistoryCategory="deleteHistoryCategory" @initializeCategoryModal="initializeCategoryModal"
						@initializeTeamModal="initializeTeamModal"></history-category-list>
					</div>
				</div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addHistoryYear()">{{ $lang.add_year }}</button>

		<history-year-modal :currentYearOperation="currentYearOperation" @storeHistoryYear="storeHistoryYear" @updateHistoryYear="updateHistoryYear"></history-year-modal>

		<history-category-modal :categoryOperation="categoryModal.categoryOperation" 
		@storeCategory="storeCategory" @updateCategory="updateCategory" 
		:yearIndex="categoryModal.yearIndex"></history-category-modal>

		<history-category-team-modal :categoryTeamOperation="categoryTeamModal.categoryTeamOperation" @storeCategoryTeam="storeCategoryTeam" 
		@updateCategoryTeam="updateCategoryTeam" :yearIndex="categoryTeamModal.yearIndex" 
		:categoryIndex="categoryTeamModal.categoryIndex"></history-category-team-modal>

	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import HistoryYearModal from './HistoryYearModal.vue';
	import HistoryCategoryList from './HistoryCategoryList.vue';
	import HistoryCategoryModal from './HistoryCategoryModal.vue';
	import HistoryCategoryTeamModal from './HistoryCategoryTeamModal.vue';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		data() {
			return {
				historyYears: [],
				currentYearIndex: -1,
				currentYearOperation: 'add',
				categoryModal: {
					categoryIndex: -1,
					categoryOperation: 'add',
					yearIndex: -1,
				},
				categoryTeamModal: {
					categoryTeamIndex: -1,
					categoryTeamOperation: 'add',
					yearIndex: -1,
					categoryIndex: -1,
				}
			};
		},
		components: {
			draggable,
			HistoryYearModal,
			HistoryCategoryModal,
			HistoryCategoryList,
			HistoryCategoryTeamModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			this.$root.$on('getHistoryYears', this.getHistoryYears);
		},
		methods: {
			addHistoryYear() {
				var formData = {
					id: '',
					year: '',
					categoryList: [],
				};
				this.currentYearIndex = this.historyYears.length;
				this.currentYearOperation = 'add';
				this.initializeModel(formData);
			},
			storeHistoryYear(historyYearData) {
				this.historyYears.push({ id: '', year: historyYearData.year, categoryList: [] });
				$('#history_year_modal').modal('hide');
			},
			editHistoryYear(historyYear, index) {
				var formData = {
					id: historyYear.id,
					year: historyYear.year,
					categoryList: historyYear.categoryList,
				};
				this.currentYearIndex = index;
				this.currentYearOperation = 'edit';
				this.initializeModel(formData);
			},
			updateHistoryYear(historyYearData) {
				this.historyYears[this.currentYearIndex].year = historyYearData.year;
				this.historyYears[this.currentYearIndex].categoryList = historyYearData.categoryList;
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
			setHistoryCategoryList(categories, index) {
				this.historyYears[index].categoryList = categories;
			},
			getHistoryYears() {
				this.$emit('setHistoryData', this.historyYears);
			},
			// category list related functions
			initializeCategoryModal(formData, additionalParams) {
				this.categoryModal.categoryOperation = additionalParams.categoryOperation;
				this.categoryModal.categoryIndex = additionalParams.categoryIndex;
				this.categoryModal.yearIndex = additionalParams.yearIndex;
				this.$root.$emit('setCategoryData', formData);
				$('#history_category_modal').modal('show');
			},
			storeCategory(categoryData) {
				var yearIndex = this.categoryModal.yearIndex;
				this.historyYears[yearIndex]['categoryList'].push({ id: '', name: categoryData.name, teams: [] });
				$('#history_category_modal').modal('hide');
			},
			updateCategory(categoryData) {
				var yearIndex = this.categoryModal.yearIndex;
				var categoryIndex = this.categoryModal.categoryIndex;
				this.historyYears[yearIndex]['categoryList'][categoryIndex].name = categoryData.name;
				$('#history_category_modal').modal('hide');
			},
			setCategoryLists(categoryList, index) {
				this.historyYears[index].categoryList = categoryList;
			},
			deleteHistoryCategory(deleteIndex, yearIndex) {
				this.historyYears[yearIndex]['categoryList'] = _.remove(this.historyYears[yearIndex]['categoryList'], function(ageCategory, index) {
					return index != deleteIndex;
				});
			},
			// Category Team related functions
			initializeTeamModal(formData, additionalParams) {
				this.categoryTeamModal.categoryTeamOperation = additionalParams.categoryTeamOperation;
				this.categoryTeamModal.categoryIndex = additionalParams.categoryIndex;
				this.categoryTeamModal.yearIndex = additionalParams.yearIndex;
				this.categoryTeamModal.categoryTeamIndex = additionalParams.categoryTeamIndex;
				this.$root.$emit('setCategoryTeamData', formData);
				$('#category_team_modal').modal('show');
			},
			storeCategoryTeam(categoryTeamData) {
				var yearIndex = this.categoryTeamModal.yearIndex;
				var categoryIndex = this.categoryTeamModal.categoryIndex;

				this.historyYears[yearIndex]['categoryList'][categoryIndex]['teams'].push({ id: '', name: categoryTeamData.name});
				$('#category_team_modal').modal('hide');
			},
			updateCategoryTeam(categoryTeamData) {
				var yearIndex = this.categoryTeamModal.yearIndex;
				var categoryIndex = this.categoryTeamModal.categoryIndex;
				var categoryTeamIndex = this.categoryTeamModal.categoryTeamIndex;

				this.historyYears[yearIndex]['categoryList'][categoryIndex]['teams'][categoryTeamIndex]['name'] = categoryTeamData.name;
				$('#category_team_modal').modal('hide');
			},
		}
	}
</script>