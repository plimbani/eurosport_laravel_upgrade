<template>
	<div>
		<div class="draggable--section" :class="childClassNames">
			<draggable v-model="age_categories" :options="{draggable:'.history-year-age-category-item', handle: '.history-year-age-category-handle'}" @end="onDragEnd()">
				<div class="history-year-age-category-item draggable--section-card" v-for="(category, index) in age_categories" :key="index">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
				  				{{ category.name }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteCategory(index)">
						        	<i class="fas fa-trash"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editCategory(category, index)">
						        	<i class="fas fa-pencil"></i>
						        </a>
						        <a class="text-primary history-year-age-category-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fas fa-bars"></i>
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
		props: ['ageCategories','childClassNames', 'yearIndex'],
		data() {
			return {
				age_categories: [],
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
			ageCategories: {
				handler(value){
					this.age_categories = _.cloneDeep(value);
				},
				deep: true,
			},
		},
		mounted() {
			// Get all age category
			this.age_categories = _.cloneDeep(this.ageCategories);
			this.$root.$on('getHistoryCategories', this.getHistoryCategories);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getHistoryCategories');
    },
		methods: {
			addCategory() {
				var formData = {
					id: '',
					name: '',
					teams: [],
				};
				this.categoryIndex = this.age_categories.length;
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
        this.$emit('setHistoryCategoryList', _.cloneDeep(this.age_categories), this.yearIndex);
      },
			initializeTeamModal(formData, additionalParams) {
				this.$emit('initializeTeamModal', formData, additionalParams);
			},
      deleteCategoryTeam(deleteIndex, categoryIndex) {
      	this.age_categories[categoryIndex]['teams'] = _.remove(this.age_categories[categoryIndex]['teams'], function(team, index) {
					return index != deleteIndex;
				});
				this.getHistoryCategories();
      },
      setCategoryTeamList(categoryTeamList, categoryIndex) {
      	this.age_categories[categoryIndex]['teams'] = categoryTeamList;
      	this.getHistoryCategories();
      },
		}
	}
</script>