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
						<history-year-age-category-team-list :parentIndex="index" 
						:teams="historyYearsAgeCategory.teams" 
						@setHistoryAgeCategoryTeamList="setHistoryAgeCategoryTeamList" 
						@initializeHistoryAgeCategoryTeamModal="initializeHistoryAgeCategoryTeamModal"></history-year-age-category-team-list>
					</div>
				</div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addHistoryYearAgeCategory()">{{ $lang.add_age_category }}</button>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import HistoryYearAgeCategoryTeamList from './HistoryYearAgeCategoryTeamList.vue';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		props: ['historyAgeCategoryList','childClassNames', 'parentIndex'],
		data() {
			return {
				historyYearsAgeCategoryList: [],
				currentHistoryYearAgeCategoryIndex: -1,
				currentHistoryYearAgeCategoryOperation: 'add',
			};
		},
		components: {
			draggable,
			HistoryYearAgeCategoryTeamList,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		watch: {
			historyAgeCategoryList: function(value) {
				this.historyYearsAgeCategoryList = _.cloneDeep(value);
			},
		},
		mounted() {
			// Get all age category
			this.historyYearsAgeCategoryList = _.cloneDeep(this.historyAgeCategoryList);
			this.$root.$on('getHistoryAgeCategories', this.getHistoryAgeCategories);
		},
		methods: {
			addHistoryYearAgeCategory() {
				var formData = {
					id: '',
					name: '',
					teams: [],
				};
				this.currentHistoryYearAgeCategoryIndex = this.historyYearsAgeCategoryList.length;
				this.currentHistoryYearAgeCategoryOperation = 'add';
				var additionalParams = {
					currentHistoryYearAgeCategoryOperation: this.currentHistoryYearAgeCategoryOperation,
					currentHistoryYearAgeCategoryIndex: this.currentHistoryYearAgeCategoryIndex,
					parentIndex: this.parentIndex,
				};
				this.$emit('initializeHistoryAgeCategoryModal', formData, additionalParams);
			},

			editHistoryYearAgeCategory(historyYearAgeCategory, index) {
				var formData = {
					id: historyYearAgeCategory.id,
					name: historyYearAgeCategory.name,
					teams: historyYearAgeCategory.teams,
				};
				this.currentHistoryYearAgeCategoryIndex = index;
				this.currentHistoryYearAgeCategoryOperation = 'edit';
				var additionalParams = {
					currentHistoryYearAgeCategoryOperation: this.currentHistoryYearAgeCategoryOperation,
					currentHistoryYearAgeCategoryIndex: this.currentHistoryYearAgeCategoryIndex,
					parentIndex: this.parentIndex,
				};
				this.$emit('initializeHistoryAgeCategoryModal', formData, additionalParams);
			},

			deleteHistoryYearAgeCategory(deleteIndex) {
				this.$emit('deleteHistoryYearAgeCategory', deleteIndex, this.parentIndex);
			},

			setHistoryAgeCategoryTeamList(teams, index) {
				this.historyYearsAgeCategoryList[index].teams = teams
				this.$emit('setHistoryAgeCategoryList', _.cloneDeep(this.historyYearsAgeCategoryList), this.parentIndex);
			},

			onDragEnd() {
				this.getHistoryAgeCategories();
			},
			getHistoryAgeCategories() {
        this.$emit('setHistoryAgeCategoryList', _.cloneDeep(this.historyYearsAgeCategoryList), this.parentIndex);
      },

			// -----------------

			initializeHistoryAgeCategoryTeamModal(formData, additionalParams) {
				this.$emit('initializeHistoryAgeCategoryTeamModal', formData, additionalParams);
			}			
		}
	}
</script>