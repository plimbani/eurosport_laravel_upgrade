<template>
	<div>
		<div class="form-group">
	       	<button @click="backToTeamList()" class="btn btn-primary">
		        <i aria-hidden="true" class="fa fa-angle-double-left"></i> Back to team list
		    </button>
		</div>
		<div class="row">
			<div class="col-md-12 mb-3">
				<div class="row">
					<div class="col-md-12">
						<label class="mt-3"><h6 class="mr-3 mb-0">{{ currentSelectedTeamName }}</h6></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<matches :tournamentData="tournamentData" :matches="teamMatches" :competitionDetail="'{}'" :currentView="currentView" :fromView="'Teams'"></matches>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/babel">
	import MatchList from '../../../../../../api/frontend/matchlist.js';
  	import Matches from './../../list/components/Matches.vue';

	export default {
		props: ['teamMatches','currentView', 'currentSelectedTeamName', 'fromView', 'tournamentData'],
		data() {
			return {
				teamView: 'matchList'
			}
		},
		mounted() {
			
		},
		components: {
			Matches,
		},
	  	computed: {
	  	},
		methods: {
	        backToTeamList() {
	        	this.$root.$emit('showTeamsList');
	        },

		}

	}
</script>
