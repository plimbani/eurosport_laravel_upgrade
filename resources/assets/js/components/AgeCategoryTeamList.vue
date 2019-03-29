<template>
	<div>
		<div class="draggable--section" :class="childClassNames">
			<draggable v-model="ageCategoryTeams" :options="{draggable:'.age-category-team-item', handle: '.age-category-team-handle'}" @end="onDragEnd()">
		  	<div class="age-category-team-item draggable--section-card" v-for="(ageCategoryTeam, index) in ageCategoryTeams" :key="index">
		  		<div class="draggable--section-card-header">
		  			<div class="draggable--section-card-header-panel">
		  				<div>
			  				{{ ageCategoryTeam.name }} ({{ ageCategoryTeam.country.country_code }}) 	
			  				<span :class="'flag-icon flag-icon-'+ageCategoryTeam.country.country_flag"> 	
			  				</span>
			  			</div>

			  			<div class="draggable--section-card-header-icons">
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="deleteAgeCategoryTeam(index)">
					        	<i class="fas fa-trash"></i>
					        </a>
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="editAgeCategoryTeam(ageCategoryTeam, index)">
					        	<i class="fas fa-pencil"></i>
					        </a>
					        <a class="text-primary age-category-team-handle draggable-handle" href="javascript:void(0)">
					        	<i class="fas fa-bars"></i>
					        </a>
					    </div>
		  			</div>
		  			<!-- Add child tags like draggable--section-child-1 -->
		  		</div>
		    </div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addAgeCategoryTeam()">{{ $lang.add_team }}</button>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		props: ['childClassNames', 'teams', 'parentIndex'],
		data() {
			return {
				ageCategoryTeams: [],
				currentAgeCategoryTeamIndex: -1,
				currentAgeCategoryTeamOperation: 'add',
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
					this.ageCategoryTeams = _.cloneDeep(value);
				},
				deep: true,
			},
		},
		mounted() {
			// Get all age category team
			this.ageCategoryTeams = this.teams;
			this.$root.$on('getAgeCategoryTeams', this.getAgeCategoryTeams);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getAgeCategoryTeams');
    },
		methods: {
			addAgeCategoryTeam() {
				var formData = {
					id: '',
					name: '',
					country: '',
				};
				this.currentAgeCategoryTeamIndex = this.teams.length;
				this.currentAgeCategoryTeamOperation = 'add';
				var additionalParams = {
					currentAgeCategoryTeamOperation: this.currentAgeCategoryTeamOperation,
					currentAgeCategoryTeamIndex: this.currentAgeCategoryTeamIndex,
					parentIndex: this.parentIndex,
				};
				this.$emit('initializeTeamModal', formData, additionalParams);
			},
			editAgeCategoryTeam(ageCategoryTeam, index) {
				var formData = {
					id: ageCategoryTeam.id,
					name: ageCategoryTeam.name,
					country: ageCategoryTeam.country
				};
				this.currentAgeCategoryTeamIndex = index;
				this.currentAgeCategoryTeamOperation = 'edit';
				var additionalParams = {
					currentAgeCategoryTeamOperation: this.currentAgeCategoryTeamOperation,
					currentAgeCategoryTeamIndex: this.currentAgeCategoryTeamIndex,
					parentIndex: this.parentIndex,
				};
				this.$emit('initializeTeamModal', formData, additionalParams);
			},
			deleteAgeCategoryTeam(deleteIndex) {
				this.$emit('deleteAgeCategoryTeam', deleteIndex, this.parentIndex);
			},
			onDragEnd() {
				this.getAgeCategoryTeams();
			},
			getAgeCategoryTeams() {
        this.$emit('setAgeCategoryTeams', _.cloneDeep(this.ageCategoryTeams), this.parentIndex);
      },
		},
	}
</script>