<template>
	<div>
		<div class="form-group">
	       	<button @click="backToTeamList()" class="btn btn-primary">
		        <i aria-hidden="true" class="fas fa-angle-double-left"></i> Back to team list
		    </button>
		</div>
		<div class="row">
			<div class="col-md-12 mb-3">
				<div class="row">
					<div class="col-md-12">
						<label class="mt-3"><h6 class="font-weight-bold mr-3">{{ currentSelectedTeamName }}</h6></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<matches :matches="teamMatches" :competitionDetail="'{}'" :currentView="currentView" :fromView="'Teams'" :isDivExist="isDivExist" :isDivExistData="isDivExistData"></matches>
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
		props: ['teamMatches','currentView', 'currentSelectedTeamName', 'fromView'],
		data() {
			return {
				teamView: 'matchList',
				isDivExist: false,
				isDivExistData: [],
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
