<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="ageCategoryTeams" :options="{draggable:'.age-category-team-item', handle: '.age-category-team-handle'}">
		  	<div class="age-category-team-item draggable--section-card" :class="classNames" v-for="(ageCategoryTeam, index) in ageCategoryTeams" :key="ageCategoryTeam.id">
		  		<div class="draggable--section-card-header">
		  			<div class="draggable--section-card-header-panel">
		  				<div>
			  				{{ ageCategoryTeam.name }} ({{ ageCategoryTeam.country }})
			  			</div>
			  			<div class="draggable--section-card-header-icons">
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="deleteAgeCategoryTeam(index)">
					        	<i class="jv-icon jv-dustbin"></i>
					        </a>
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="editAgeCategoryTeam(ageCategoryTeam, index)">
					        	<i class="jv-icon jv-edit"></i>
					        </a>
					        <a class="text-primary age-category-team-handle draggable-handle" href="javascript:void(0)">
					        	<i class="fa fa-bars"></i>
					        </a>
					    </div>
		  			</div>
		  			<!-- Add child tags like draggable--section-child-1 -->
		  		</div>
		    </div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addAgeCategoryTeam()">{{ $lang.add_team }}</button>
		<age-category-team-modal :currentAgeCategoryTeamOperation="currentAgeCategoryTeamOperation" @storeAgeCategoryTeam="storeAgeCategoryTeam" @updateAgeCategoryTeam="updateAgeCategoryTeam"></age-category-team-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import AgeCategoryTeamModal  from  './AgeCategoryTeamModal.vue';
	import _ from 'lodash';

	export default {
		props: ['classNames'],
		data() {
			return {
				ageCategoryTeams: [],
				currentAgeCategoryTeamIndex: -1,
				currentAgeCategoryTeamOperation: 'add',
			};
		},
		components: {
			draggable,
			AgeCategoryTeamModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			// Get all age category team
			this.getAllAgeCategoryTeams();
			this.$root.$on('getAgeCategoryTeams', this.getAgeCategoryTeams);
		},
		methods: {
			getAllAgeCategoryTeams() {
				Website.getAgeCategoryTeams(this.getWebsite).then(
	        (response) => {
	          this.ageCategoryTeams = response.data.data;
	        },
	        (error) => {
	        }
	      );
			},
			addAgeCategoryTeam() {
				var formData = {
					id: '',
					name: '',
					country: '',
				};
				this.currentAgeCategoryTeamIndex = this.ageCategoryTeams.length;
				this.currentAgeCategoryTeamOperation = 'add';
				this.initializeModel(formData);
			},
			storeAgeCategoryTeam(ageCategoryTeamData) {
				this.ageCategoryTeams.push({ id: '', name: ageCategoryTeamData.name, country: ageCategoryTeamData.country });
				$('#age_category_team_modal').modal('hide');
			},
			editAgeCategoryTeam(ageCategoryTeam, index) {
				var formData = {
					id: ageCategoryTeam.id,
					name: ageCategoryTeam.name,
					country: ageCategoryTeam.country
				};
				this.currentAgeCategoryTeamIndex = index;
				this.currentAgeCategoryTeamOperation = 'edit';
				this.initializeModel(formData);
			},
			updateAgeCategoryTeam(ageCategoryTeamData) {
				this.ageCategoryTeams[this.currentAgeCategoryTeamIndex].name = ageCategoryTeamData.name;
				this.ageCategoryTeams[this.currentAgeCategoryTeamIndex].country = ageCategoryTeamData.country;
				$('#age_category_team_modal').modal('hide');
			},
			deleteAgeCategoryTeam(deleteIndex) {
				this.ageCategoryTeams = _.remove(this.ageCategoryTeams, function(team, index) {
				  return index != deleteIndex;
				});
			},
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setAgeCategoryTeamData', formData);
				$('#age_category_team_modal').modal('show');
			},
			getAgeCategoryTeams() {
        this.$emit('setAgeCategoryTeams', this.ageCategoryTeams);
      },
		},
	}
</script>