<template>
	<div>
	    <div class="bordered-box">
	        <h6 class="font-weight-bold">Group {{ index + 1}} <span class="pull-right"><a href="javascript:void(0)" @click="removeGroup()"><i class="fa fa-trash"></i></a></span></h6>
	        <div class="form-group">
	            <div class="radio">
	                <label><input type="radio" checked="checked" value="round_robin" v-model="data.type"> Round robin</label>
	                <label><input type="radio" value="placing_match" v-model="data.type"> Placing match</label>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-md-6">
	                <div class="form-group mb-0">
	                    <label>Number of teams in group</label>
	                    <select class="form-control ls-select2" name="no_of_teams" id="no_of_teams" v-model="data.no_of_teams" @change="onTeamChange()">
	                    	<option v-for="n in 28" v-if="n >=2" :value="n">{{ n }}</option>
	                    </select>
	                </div>
	            </div>

	            <div class="col-md-6">
	                <div class="form-group mb-0">
	                    <label>Teams play each other</label>
	                    <select class="form-control ls-select2" name="teams_play_each_other" id="teams_play_each_other" v-model="data.teams_play_each_other">
	                        <option value="once">Once</option>
	                        <option value="twice">Twice</option>
	                        <option value="three_times">Three times</option>
	                        <option value="four_times">Four times</option>
	                    </select>
	                </div>
	            </div>
	        </div>

	        <div class="row align-items-center mt-3" v-show="roundIndex > 0">
	        	<div class="col-md-3">
	        		<label>Team 1</label>
	        	</div>
	        	<div class="col-md-9">
	        		<div class="row">
	        			<div class="col-md-4">
	        				<div class="form-group">
		        				<select class="form-control ls-select2" name="relevant-group" id="relevant-group">
		                    		<option>Group A</option>
		                    	</select>
		                    </div>
	        			</div>	        			
	        			<div class="col-md-4">
	        				<div class="form-group">
		        				<select class="form-control ls-select2" name="placing" id="placing">
			                    	<option>Placed</option>
			                    </select>
			                </div>
	        			</div>
	        			<div class="col-md-4">
	        				<div class="form-group">
		        				<select class="form-control ls-select2" name="rank" id="rank">
		                    		<option>1st</option>
		                    	</select>
		                    </div>
	        			</div>
	        		</div>
	        	</div>
	        </div>
	    </div>
    </div>
</template>
<script type="text/javascript">
    export default {
    	props: ['index', 'roundIndex', 'data', 'roundData'],
        data() {
            return {

            }
        },
        components: {
        },
        mounted() {
        },
        methods: {
        	removeGroup() {
        		this.$parent.removeGroup(this.index, this.roundIndex);
        	},
        	onTeamChange() {
 				let groupTotalTeams = this.$parent.getGroupTotalTeams(this.roundIndex);
                if(groupTotalTeams >= this.roundData.no_of_teams) {
                    toastr['error']('Group teams should not be greater than round teams.', 'Error');
                    return true;
                }
                return false;        		
        	}
        }
    }
</script>