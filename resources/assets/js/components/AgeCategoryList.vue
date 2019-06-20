<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="ageCategories.length" v-model="ageCategories" :options="{draggable:'.age-category-item', handle: '.age-category-handle'}">
			  	<div class="age-category-item draggable--section-card" v-for="(ageCategory, index) in ageCategories" :key="index">
			  		<div class="draggable--section-card-header">
			  			<div class="draggable--section-card-header-panel">
			  				<div>
				  				{{ ageCategory.name }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteAgeCategory(index)">
						        	<i class="fas fa-trash"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editAgeCategory(ageCategory, index)">
						        	<i class="fas fa-pencil"></i>
						        </a>
						        <a class="text-primary age-category-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fas fa-bars"></i>
						        </a>
						    </div>
			  			</div>
			  			<!-- Add child tags like draggable--section-child-1 -->
							<age-category-team-list :parentIndex="index" :childClassNames="'draggable--section-child-1'" :teams="ageCategory.teams" @setAgeCategoryTeams="setAgeCategoryTeams" @deleteAgeCategoryTeam="deleteAgeCategoryTeam" @initializeTeamModal="initializeTeamModal"></age-category-team-list>
			  		</div>
			    </div>
			</draggable>
			<p v-else class="text-muted">{{ $lang.no_age_categories_found }}</p>
		</div>
		<button type="button" class="btn btn-primary" @click="addAgeCategory()">{{ $lang.add_category }}</button>
		<age-category-modal :currentAgeCategoryOperation="currentAgeCategoryOperation" @storeAgeCategory="storeAgeCategory" @updateAgeCategory="updateAgeCategory"></age-category-modal>
		<age-category-team-modal :currentAgeCategoryTeamOperation="teamModalData.currentAgeCategoryTeamOperation" @storeAgeCategoryTeam="storeAgeCategoryTeam" @updateAgeCategoryTeam="updateAgeCategoryTeam" :modalIndex="teamModalData.parentIndex" :countries="countries"></age-category-team-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import AgeCategoryModal  from  './AgeCategoryModal.vue';
	import AgeCategoryTeamList  from  './AgeCategoryTeamList.vue';
	import AgeCategoryTeamModal  from  './AgeCategoryTeamModal.vue';
	import _ from 'lodash';

	export default {
		props: ['countries'],
		data() {
			return {
				ageCategories: [],
				currentAgeCategoryIndex: -1,
				currentAgeCategoryOperation: 'add',
				teamModalData: {
					currentAgeCategoryTeamOperation: 'add',
					currentAgeCategoryTeamIndex: -1,
					parentIndex: -1,
				}
			};
		},
		components: {
			draggable,
			AgeCategoryModal,
			AgeCategoryTeamList,
			AgeCategoryTeamModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			// Get all age categories
			this.getAllAgeCategories();
			this.$root.$on('getAgeCategories', this.getAgeCategories);
			this.$root.$on('importAgeCategories', this.importAgeCategories);
			this.teamModalData.countries = _.cloneDeep(this.countries);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getAgeCategories');
      this.$root.$off('importAgeCategories');
    },
		methods: {
			getAllAgeCategories() {
				Website.getAgeCategories(this.getWebsite).then(
	        (response) => {
	          this.ageCategories = response.data.data;
	        },
	        (error) => {
	        }
	      );
			},
			addAgeCategory() {
				var formData = {
					id: '',
					name: '',
					teams: [],
				};
				this.currentAgeCategoryIndex = this.ageCategories.length;
				this.currentAgeCategoryOperation = 'add';
				this.initializeModal(formData);
			},
			storeAgeCategory(ageCategoryData) {
				this.ageCategories.push({ id: '', name: ageCategoryData.name, teams: [] });
				$('#age_category_modal').modal('hide');
			},
			editAgeCategory(ageCategory, index) {
				var formData = {
					id: ageCategory.id,
					name: ageCategory.name,
					teams: ageCategory.teams,
				};
				this.currentAgeCategoryIndex = index;
				this.currentAgeCategoryOperation = 'edit';
				this.initializeModal(formData);
			},
			updateAgeCategory(ageCategoryData) {
				this.ageCategories[this.currentAgeCategoryIndex].name = ageCategoryData.name;
				this.ageCategories[this.currentAgeCategoryIndex].teams = ageCategoryData.teams;
				$('#age_category_modal').modal('hide');
			},
			deleteAgeCategory(deleteIndex) {
				this.ageCategories = _.remove(this.ageCategories, function(stat, index) {
				  return index != deleteIndex;
				});
			},
			initializeModal(formData) {
				var vm = this;
				this.$root.$emit('setAgeCategoryData', formData);
				$('#age_category_modal').modal('show');
			},
			getAgeCategories() {
				this.$emit('setAgeCategories', this.ageCategories);
			},
			importAgeCategories(ageCategories) {
				this.ageCategories = ageCategories;
			},
			setAgeCategoryTeams(ageCategoryTeams, index) {
				this.ageCategories[index].teams = ageCategoryTeams;
			},
			initializeTeamModal(formData, additionalParams) {
				this.teamModalData.currentAgeCategoryTeamOperation = additionalParams.currentAgeCategoryTeamOperation;
				this.teamModalData.currentAgeCategoryTeamIndex = additionalParams.currentAgeCategoryTeamIndex;
				this.teamModalData.parentIndex = additionalParams.parentIndex;
				this.$root.$emit('setAgeCategoryTeamData', formData);
				$('#age_category_team_modal').modal('show');
			},
			storeAgeCategoryTeam(ageCategoryTeamData) {
				var ageCategoryIndex = this.teamModalData.parentIndex;
				var currentAgeCategoryTeamIndex = this.teamModalData.currentAgeCategoryTeamIndex;
				this.ageCategories[ageCategoryIndex]['teams'].push({ id: '', name: ageCategoryTeamData.name, country: ageCategoryTeamData.country });
				$('#age_category_team_modal').modal('hide');
			},
			updateAgeCategoryTeam(ageCategoryTeamData) {
				var ageCategoryIndex = this.teamModalData.parentIndex;
				var currentAgeCategoryTeamIndex = this.teamModalData.currentAgeCategoryTeamIndex;
				this.ageCategories[ageCategoryIndex]['teams'][currentAgeCategoryTeamIndex].name = ageCategoryTeamData.name;
				this.ageCategories[ageCategoryIndex]['teams'][currentAgeCategoryTeamIndex].country = ageCategoryTeamData.country;
				$('#age_category_team_modal').modal('hide');
			},
			deleteAgeCategoryTeam(deleteIndex, ageCategoryIndex) {
				this.ageCategories[ageCategoryIndex]['teams'] = _.remove(this.ageCategories[ageCategoryIndex]['teams'], function(team, index) {
					return index != deleteIndex;
				});
			},
		},
	}
</script>