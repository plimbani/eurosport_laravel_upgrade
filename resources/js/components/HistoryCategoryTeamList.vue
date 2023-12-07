<template>
	<div>
		<div class="draggable--section" :class="childClassNames">
			<draggable v-model="categoryTeamList" :options="{draggable:'.history-year-age-category-team-item', handle: '.history-year-age-category-team-handle'}" @end="onDragEnd()">
				<div class="history-year-age-category-team-item draggable--section-card" v-for="(categoryTeam, index) in categoryTeamList" :key="index">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
				  				{{ categoryTeam.name }} ({{ categoryTeam.country.country_code }}) 
				  				<span :class="'flag-icon flag-icon-'+categoryTeam.country.country_flag"> 	
			  				</span>
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteCategoryTeam(index)">
						        	<i class="fas fa-trash"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editCategoryTeam(categoryTeam, index)">
						        	<i class="fas fa-pencil"></i>
						        </a>
						        <a class="text-primary history-year-age-category-team-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fas fa-bars"></i>
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
			teams: {
				handler(value){
					this.categoryTeamList = _.cloneDeep(value);
				},
				deep: true,
			},
		},
		mounted() {
			// Get all age category
			this.categoryTeamList = this.teams;
			this.$root.$on('getCategoryTeamList', this.getCategoryTeamList);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getCategoryTeamList');
    },
		methods: {
			addCategoryTeam() {
				var formData = {
					id: '',
					name: '',
					country: '',
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
			editCategoryTeam(categoryTeam, index) {
				var formData = {
					id: categoryTeam.id,
					name: categoryTeam.name,
					country: categoryTeam.country,
				};
				this.categoryTeamIndex = index;
				this.categoryTeamOperation = 'edit';

				var additionalParams = {
					categoryTeamIndex: this.categoryTeamIndex,
					categoryTeamOperation: this.categoryTeamOperation,
					yearIndex: this.yearIndex,
					categoryIndex: this.categoryIndex,
				};
				this.$emit('initializeTeamModal', formData, additionalParams);
			},
			onDragEnd() {
				this.getCategoryTeamList();
			},
			getCategoryTeamList() {
        this.$emit('setCategoryTeamList', _.cloneDeep(this.categoryTeamList), this.categoryIndex);
      },
      deleteCategoryTeam(deleteIndex) {
				this.$emit('deleteCategoryTeam', deleteIndex, this.categoryIndex);
			},
		}
	}
</script>