<template>
	<div>
	    <div class="bordered-box">
	        <h6 class="font-weight-bold">Group {{ index + 1}} <span class="pull-right"><a href="javascript:void(0)" @click="removeGroup()"><i class="fa fa-trash"></i></a></span></h6>
	        <div class="form-group">
	            <div class="radio">
	                <label><input type="radio" checked="checked" value="round_robin" v-model="groupData.type"> Round robin</label>
	                <label><input type="radio" value="placing_match" v-model="groupData.type"> Placing match</label>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-md-6">
	                <div class="form-group mb-0">
	                    <label>Number of teams in group</label>
	                    <select :data-last-selected="last_selected_teams" class="form-control ls-select2" name="no_of_teams" id="no_of_teams" v-model="groupData.no_of_teams" @change="onTeamChange($event)">
	                    	<option v-for="n in 28" v-if="n >= 2" :value="n">{{ n }}</option>
	                    </select>
	                </div>
	            </div>

	            <div class="col-md-6">
	                <div class="form-group mb-0">
	                    <label>Teams play each other</label>
	                    <select class="form-control ls-select2" name="teams_play_each_other" id="teams_play_each_other" v-model="groupData.teams_play_each_other">
	                        <option value="once">Once</option>
	                        <option value="twice">Twice</option>
	                        <option value="three_times">Three times</option>
	                        <option value="four_times">Four times</option>
	                    </select>
	                </div>
	            </div>
	        </div>

	        <div class="row align-items-center mt-3" v-show="roundIndex > 0" v-for="(team, teamIndex) in groupData.teams">
	        	<div class="col-md-3">
	        		<label>Team {{ teamIndex+1 }}</label>
	        	</div>
	        	<div class="col-md-9">
	        		<div class="row">
	        			<div class="col-md-4">
	        				<div class="form-group">
		        				<select class="form-control ls-select2" name="relevant-group" id="relevant-group">
		                    		<option>Group</option>
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
		                    		<option v-for="position in team.position">{{ position }}</option>
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
	import _ from 'lodash';
    export default {
    	props: ['index', 'roundIndex', 'groupData', 'roundData'],
        data() {
            return {
            	last_selected_teams: this.groupData.no_of_teams,
            }
        },
        components: {
        },
        mounted() {
        },
        created() {
        	this.groupData.teams = [];
        	this.displayTeams();
        },
        methods: {
        	removeGroup() {
        		this.$parent.removeGroup(this.index, this.roundIndex);
        	},
        	onTeamChange() {
 				let groupTotalTeams = this.$parent.getGroupTotalTeams(this.roundIndex);
                if(groupTotalTeams > this.roundData.no_of_teams) {
                    toastr['error']('Round team count get exceeds.', 'Error');
                    this.groupData.no_of_teams = this.last_selected_teams;
                    return false;
                }
                this.last_selected_teams = this.groupData.no_of_teams;
                this.displayTeams();
        	},
        	displayTeams() {
        		var i;
				var positions = [];
				var groupsArray = [];
				this.groupData.teams = [];				
				for (i = 0; i < this.groupData.no_of_teams; i++) {
					positions.push(this.getSuffixForPosition(i + 1));
				    this.groupData.teams.push({groups: this.roundData.groups, position_type: 'placed', position: positions});
				}
        	},
			getSuffixForPosition(d) {
		      	if(d>3 && d<21) return d +'th';
		      	switch (d % 10) {
		            case 1:  return d +"st";
		            case 2:  return d +"nd";
		            case 3:  return d +"rd";
		            default: return d +"th";
		        }
		    }
        }
    }
</script>