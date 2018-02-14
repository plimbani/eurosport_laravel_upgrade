<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="historyYearsAgeCategoryList" :options="{draggable:'.history-year-age-category-item', handle: '.history-year-age-category-handle'}">
				<div class="history-year-age-category-item draggable--section-card" v-for="(historyYearsAgeCategory, index) in historyYearsAgeCategoryList" :key="historyYearsAgeCategory.id">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
				  				{{ historyYearsAgeCategory.name }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteHistoryYearAgeCategory(index)">
						        	<i class="jv-icon jv-dustbin"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editHistoryYearAgeCategory(historyYearsAgeCategory, index)">
						        	<i class="jv-icon jv-edit"></i>
						        </a>
						        <a class="text-primary history-year-age-category-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fa fa-bars"></i>
						        </a>
						    </div>
						</div>
						<history-year-age-category-team-list :categoryIndex="index" :childClassNames="'draggable--section-child-1'" :ageCategoryList="historyYearsAgeCategoryList.ageCategoryList" @setHistoryAgeCategoryTeamList="setHistoryAgeCategoryTeamList"></history-year-age-category-team-list>
					</div>
				</div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addHistoryYearAgeCategory()">{{ $lang.add_age_category }}</button>
		<history-year-age-category-modal :currentHistoryYearAgeCategoryOperation="currentHistoryYearAgeCategoryOperation" @storeHistoryYearAgeCategory="storeHistoryYearAgeCategory" @updateHistoryYearAgeCategory="updateHistoryYearAgeCategory" :modalIndex="parentIndex" ></history-year-age-category-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import HistoryYearAgeCategoryModal from './HistoryYearAgeCategoryModal.vue';
	import HistoryYearAgeCategoryTeamList from './HistoryYearAgeCategoryTeamList.vue';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		props: ['ageCategoryList', 'childClassNames', 'parentIndex'],
		data() {
			return {
				historyYearsAgeCategoryList: [],
				currentHistoryYearAgeCategoryIndex: -1,
				currentHistoryYearAgeCategoryOperation: 'add',
			};
		},
		components: {
			draggable,
			HistoryYearAgeCategoryModal,
			HistoryYearAgeCategoryTeamList,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		methods: {
			addHistoryYearAgeCategory() {
				alert(222);
				var formData = {
					id: '',
					name: '',
				};
				this.currentHistoryYearAgeCategoryIndex = this.historyYearsAgeCategoryList.length;
				this.currentHistoryYearAgeCategoryOperation = 'add';
				this.initializeAgeCategoryModel(formData);
			},
			storeHistoryYearAgeCategory(historyYearAgeCategoryData) {
				this.historyYearsAgeCategoryList.push({ id: '', name: historyYearAgeCategoryData.name });
				$('#history_year_age_category_modal_' + this.parentIndex).modal('hide');
			},
			editHistoryYearAgeCategory(historyYearAgeCategory, index) {
				var formData = {
					id: historyYearAgeCategory.id,
					name: historyYearAgeCategory.name,
				};
				this.currentHistoryYearAgeCategoryIndex = index;
				this.currentHistoryYearAgeCategoryOperation = 'edit';
				this.initializeAgeCategoryModel(formData);
			},
			updateHistoryYearAgeCategory(historyYearAgeCategoryData) {
				this.historyYearsAgeCategoryList[this.currentHistoryYearAgeCategoryIndex].name = historyYearAgeCategoryData.name;
				$('#history_year_age_category_modal_' + this.parentIndex).modal('hide');
			},
			deleteHistoryYearAgeCategory(deleteIndex) {
				this.historyYearsAgeCategoryList = _.remove(this.historyYearsAgeCategoryList, function(stat, index) {
				  return index != deleteIndex;
				});
			},
			initializeAgeCategoryModel(formData) {
				var vm = this;
				console.log('this.parentIndex', this.parentIndex);
				this.$root.$emit('setHistoryYearAgeCategoryData', formData);
				$('#history_year_age_category_modal_' + this.parentIndex).modal('show');
			},
			setHistoryAgeCategoryTeamList(teamList, index) {
				this.historyYearsAgeCategoryList[index].teamList = teamList;
			},
		}
	}
</script>