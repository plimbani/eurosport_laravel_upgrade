<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="ageCategories" :options="{draggable:'.age-category-item', handle: '.age-category-handle'}">
			  	<div class="age-category-item draggable--section-card" v-for="(ageCategory, index) in ageCategories" :key="ageCategory.id">
			  		<div class="draggable--section-card-header">
			  			<div class="draggable--section-card-header-panel">
			  				<div>
				  				{{ ageCategory.name }}
				  			</div>
				  			<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteAgeCategory(index)">
						        	<i class="jv-icon jv-dustbin"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editAgeCategory(ageCategory, index)">
						        	<i class="jv-icon jv-edit"></i>
						        </a>
						        <a class="text-primary age-category-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fa fa-bars"></i>
						        </a>
						    </div>
			  			</div>
			  			<!-- Add child tags like draggable--section-child-1 -->
							<age-category-team-list :parentIndex="index" :childClassNames="'draggable--section-child-1'" :teams="ageCategory.teams" @setAgeCategoryTeams="setAgeCategoryTeams"></age-category-team-list>
			  		</div>
			    </div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addAgeCategory()">{{ $lang.add_category }}</button>
		<age-category-modal :currentAgeCategoryOperation="currentAgeCategoryOperation" @storeAgeCategory="storeAgeCategory" @updateAgeCategory="updateAgeCategory"></age-category-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import AgeCategoryModal  from  './AgeCategoryModal.vue';
	import AgeCategoryTeamList  from  './AgeCategoryTeamList.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				ageCategories: [],
				currentAgeCategoryIndex: -1,
				currentAgeCategoryOperation: 'add',
			};
		},
		components: {
			draggable,
			AgeCategoryModal,
			AgeCategoryTeamList,
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
				this.initializeModel(formData);
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
				this.initializeModel(formData);
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
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setAgeCategoryData', formData);
				$('#age_category_modal').modal('show');
			},
			getAgeCategories() {
				this.$emit('setAgeCategories', this.ageCategories);
			},
			setAgeCategoryTeams(ageCategoryTeams, index) {
				this.ageCategories[index].teams = ageCategoryTeams;
			},
		},
	}
</script>