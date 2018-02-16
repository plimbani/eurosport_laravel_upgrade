<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="categoriesList" :options="{draggable:'.history-year-age-category-item', handle: '.history-year-age-category-handle'}">
				<div class="history-year-age-category-item draggable--section-card" v-for="(category, index) in categoriesList" :key="index">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
				  				{{ category.name }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteCategory(index)">
						        	<i class="jv-icon jv-dustbin"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editCategory(category, index)">
						        	<i class="jv-icon jv-edit"></i>
						        </a>
						        <a class="text-primary history-year-age-category-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fa fa-bars"></i>
						        </a>
						    </div>
						</div>
						<history-category-team-list :categoryIndex="index" :yearIndex="yearIndex" 
						:childClassNames="'draggable--section-child-2'" :teams="category.teams" 
						@setCategoryTeamList="setCategoryTeamList" 
						@deleteCategoryTeam="deleteCategoryTeam" @initializeTeamModal="initializeTeamModal"></history-category-team-list>
					</div>
				</div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addCategory()">{{ $lang.add_age_category }}</button>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import HistoryCategoryTeamList from './HistoryCategoryTeamList.vue';
	import _ from 'lodash';

	export default {
		props: ['categoryList','childClassNames', 'yearIndex'],
		data() {
			return {
				categoriesList: [],
				categoryIndex: -1,
				categoryOperation: 'add',
			};
		},
		components: {
			draggable,
			HistoryCategoryTeamList,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		watch: {
			categoryList: {
				handler(value){
					this.categoriesList = _.cloneDeep(value);
				},
				deep: true,
			},
		},
		mounted() {
			// Get all age category
			this.categoriesList = _.cloneDeep(this.categoryList);
			this.$root.$on('getHistoryCategories', this.getHistoryCategories);
		},
		methods: {
			addCategory() {
				var formData = {
					id: '',
					name: '',
					teams: [],
				};
				this.categoryIndex = this.categoriesList.length;
				this.categoryOperation = 'add';
				var additionalParams = {
					categoryOperation: this.categoryOperation,
					categoryIndex: this.categoryIndex,
					yearIndex: this.yearIndex,
				};
				this.$emit('initializeCategoryModal', formData, additionalParams);
			},
			editCategory(categoryData, index) {
				var formData = {
					id: categoryData.id,
					name: categoryData.name,
					teams: categoryData.teams,
				};
				this.categoryIndex = index;
				this.categoryOperation = 'edit';
				var additionalParams = {
					categoryOperation: this.categoryOperation,
					categoryIndex: this.categoryIndex,
					yearIndex: this.yearIndex,
				};
				this.$emit('initializeCategoryModal', formData, additionalParams);
			},
			deleteCategory(deleteIndex) {
				this.$emit('deleteHistoryCategory', deleteIndex, this.yearIndex);
			},
			onDragEnd() {
				this.getHistoryCategories();
			},
			getHistoryCategories() {
        this.$emit('setCategoryLists', _.cloneDeep(this.categoriesList), this.yearIndex);
      },
			initializeTeamModal(formData, additionalParams) {
				this.$emit('initializeTeamModal', formData, additionalParams);
			},
      deleteCategoryTeam(deleteIndex, categoryIndex) {
      	this.categoriesList[categoryIndex]['teams'] = _.remove(this.categoriesList[categoryIndex]['teams'], function(team, index) {
					return index != deleteIndex;
				});
				this.getHistoryCategories();
      },
      setCategoryTeamList(categoryTeamList, categoryIndex) {
      	this.categoriesList[categoryIndex]['teams'] = categoryTeamList;
      	this.getHistoryCategories();
      },
		}
	}
</script>