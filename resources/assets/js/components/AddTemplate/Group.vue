<template>
	<div>
	    <div class="card mb-3">
	    	<div class="card-block">
		        <h6 class="font-weight-bold">Group {{ index + 1}} <span class="pull-right"><a href="javascript:void(0)" @click="removeGroup()"><i class="jv-icon jv-dustbin"></i></a></span></h6>
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

		        <div class="row align-items-center mt-3" v-if="roundIndex > 0" v-for="(team, teamIndex) in groupData.teams">
		        	<div class="col-md-3">
		        		<label class="mb-0">Team {{ teamIndex+1 }}</label>
		        	</div>
		        	<div class="col-md-9">
		        		<div class="row">
		        			<div class="col-md-4">
		        				<div class="form-group mb-0">
			        				<select class="form-control ls-select2" name="placing">
				                    	<option value="placed">Placed</option>
				                    	<option value="winner">Winner</option>
				                    	<option value="loser">Loser</option>
				                    	<option value="team">Team</option>
				                    </select>
				                </div>
		        			</div>
		        			<div class="col-md-4">
		        				<div class="form-group mb-0">
			        				<select class="form-control ls-select2" name="relevant_group">
			                    		<option value="group">Group</option>
			                    		<option value="pm">PM</option>
			                    	</select>
			                    </div>
		        			</div>
		        			<div class="col-md-4">
		        				<div class="form-group mb-0">
			        				<select class="form-control ls-select2 js-select-position" name="rank" :id="'pos_'+(teamIndex+1)" @change="onAssignPosition(teamIndex+1)">
			                    		<option v-for="position in team.position" v-bind:value="(teamIndex+1)" v-bind:data-id="'pos_'+(teamIndex+1)">{{ position }}</option>
			                    	</select>
			                    </div>
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
        	var vm = this
        	$('.js-select-position').change(function() {
        		let vl = $(this).val();
        		console.log('vl', vl);
        	});
        },
        created() {
        	this.groupData.teams = '';
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
		      	if(d>=11 && d<=13) return d +'th';
		      	switch (d % 10) {
		            case 1:  return d +"st";
		            case 2:  return d +"nd";
		            case 3:  return d +"rd";
		            default: return d +"th";
		        }
		    },
        }
    }
</script>