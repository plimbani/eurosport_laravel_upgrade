
<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<div class="clearfix">
	  				<div class="pull-left">
		  				<div class="mt-4"><strong>Pitch Planner</strong></div>
		  			</div>
		  			<div class="pull-right">
		  				<form class="form-inline filter-category-form">
		  					<div class="form-group">
		  						<label for="nameInput" class="control-label"><strong>Filter by:</strong></label> 
		  						<label class="radio-inline control-label">
	  								<input type="radio" name="gender" value="team" checked="checked"> Team
	                            </label>
	                             <label class="radio-inline control-label">
	                            	<input type="radio" name="gender" value="country"> Location
	                            </label>
	                            <label class="radio-inline control-label">
	                            	<input type="radio" name="gender" value="age category"> Age Category
	                            </label>
	                            <select class="form-control ls-select2">
		                            <option value="">Select a team</option>
		                            <option value="">-------</option>
		                            <option value="">Etc...</option>
		                        </select>
		                        <label class="control-label">
		                        	<a href="#">Clear</a>
		                        </label>
		  					</div> 
		  					
		  				</form>
		  			</div>
	  			</div>

	  			<div class="mt-4">
	  				<pitch-planner-table></pitch-planner-table>	  					
	  			</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
	import GamesTab from '../../../components/GamesTab.vue'
	import RefereesTab from '../../../components/RefereesTab.vue'
	import PitchModal from '../../../components/PitchModal.vue'
	import PitchPlannerTable from '../../../components/PitchPlannerTable.vue'
	
	export default {
    data() {
       return {         
         'tournamentId': this.$store.state.Tournament.tournamentId
       }
    },
    mounted() {
    	this.$store.dispatch('SetPitches',this.tournamentId);
    	// Here we put validation check
    let tournamentId = this.$store.state.Tournament.tournamentId
    if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
      toastr['error']('Please Select Tournament', 'Error');
      this.$router.push({name: 'welcome'});
    } else {
      // Means Set Here
     let currentNavigationData = {activeTab:'pitch_planner', 
     currentPage: 'Pitch Planner'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
    }
    
    },	
    components: {
        GamesTab, RefereesTab, PitchModal, PitchPlannerTable
    }  
}
</script>