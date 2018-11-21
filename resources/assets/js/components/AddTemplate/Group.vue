<template>
	<div>
	    <div class="card mb-3">
	    	<div class="card-block">
		        <h6 class="font-weight-bold">{{ getGroupName }} <span class="pull-right"><a href="javascript:void(0)" @click="removeGroup()"><i class="jv-icon jv-dustbin"></i></a></span></h6>
		        <div class="form-group">
		            <div class="radio">
		                <label><input type="radio" checked="checked" value="round_robin" v-model="groupData.type" @change="onChangeGroupType()"> Round robin</label>
		                <label><input type="radio" value="placing_match" v-model="groupData.type" @change="onChangeGroupType()"> Placing match</label>
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
			        				<select class="form-control ls-select2" v-model="team.position_type">
				                    	<option value="placed">Placed</option>
				                    	<option value="winner">Winner</option>
				                    	<option value="loser">Loser</option>
				                    	<option value="team">Team</option>
				                    </select>
				                </div>
		        			</div>
		        			<div class="col-md-4">
		        				<div class="form-group mb-0">
			        				<select class="form-control ls-select2" v-model="team.group">
			                    		<option v-for="group in getGroupsForSelection(teamIndex)" :value="group.value">{{ group.name }}</option>
			                    	</select>
			                    </div>
		        			</div>
		        			<div class="col-md-4">
		        				<div class="form-group mb-0">
			        				<select class="form-control ls-select2 js-select-position" :id="'pos_'+(teamIndex+1)" @change="onAssignPosition(teamIndex+1)" v-model="team.position">
			                    		<option v-for="position in getPositionsForSelection(team.group)">{{ position }}</option>
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
    	props: ['index', 'roundIndex', 'groupData', 'roundData', 'templateFormDetail'],
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
        	this.displayTeams();
        },
        computed: {
		    getGroupName() {
		    	let vm = this;

		    	if(this.groupData.type === 'round_robin') {
		    		let currentRoundGroupCount =  _.filter(this.roundData.groups, function(o, index) { return (o.type === 'round_robin' && index < vm.index); }).length;
		    		return 'Group ' + String.fromCharCode(65 + this.roundData.startRoundGroupCount + currentRoundGroupCount);
		    	}

		    	if(this.groupData.type === 'placing_match') {
		    		let currentPlacingGroupCount =  _.filter(this.roundData.groups, function(o, index) { return (o.type === 'placing_match' && index <= vm.index); }).length;
		    		return 'PM ' + (this.roundData.startPlacingGroupCount + currentPlacingGroupCount);
		    	}
		    },
        },
        methods: {
        	removeGroup() {
        		this.$parent.removeGroup(this.index, this.roundIndex);
        		this.$root.$emit('updateGroupCount');
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
				// var positions = [];
				// var groupsArray = [];
				this.groupData.teams = [];
				for (i = 0; i < this.groupData.no_of_teams; i++) {
					// this.teamPositions.push(i + 1);
					// positions.push(this.getSuffixForPosition(i + 1));
				    this.groupData.teams.push({position_type: 'placed', group: '', position: ''});
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
		    onAssignPosition(e) {
		    },
		    onChangeGroupType() {
		    	this.$root.$emit('updateGroupCount');
		    },
		    getGroupsForSelection(teamIndex) {
		    	let team = this.groupData.teams[teamIndex];
        		let groupsForSelection = [];
        		let roundRobinIndex = 0;
        		let placingMatchIndex = 0;
        		let roundGroupCount = 0;
                let placingGroupCount = 0;
                let vm = this;
        		_.forEach(this.templateFormDetail['steptwo'].rounds, function(round, roundIndex) {
					_.forEach(round.groups, function(group, groupIndex) {
						if(roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

						if(group.type === 'round_robin' && team.position_type === 'placed') {
							groupsForSelection[roundRobinIndex] = {'name': 'Group ' + String.fromCharCode(65 +roundGroupCount), 'value': roundIndex + ',' + groupIndex};
							roundGroupCount += 1;

							if(roundRobinIndex === 0 && team.group === '')
								vm.groupData.teams[teamIndex].group = groupsForSelection[roundRobinIndex].value;

							roundRobinIndex++;

							return true;
						}

						if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
							placingGroupCount += 1;
							groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (placingGroupCount), 'value': roundIndex + ',' + groupIndex};

							if(placingMatchIndex === 0 && team.group === '')
								vm.groupData.teams[teamIndex].group = groupsForSelection[placingMatchIndex].value;

							placingMatchIndex++;

							return true;
						}
					});
					if(roundIndex >= vm.roundIndex) return false;
				});
				return groupsForSelection;
		    },
		    getPositionsForSelection(group) {
		    	if(group) {
			    	var currentGroup = group.split(',');
			    	var positionsForSelection = [];
			    	_.forEach(this.templateFormDetail['steptwo'].rounds[this.roundIndex - 1].groups, function(group, groupIndex) {
		    			if(groupIndex == currentGroup[1]) {
			    			_.forEach(group.teams, function(team, teamIndex) {
			    				positionsForSelection.push('Position '+ (teamIndex + 1));
			    			});
		    			}
			    	});

			    	return positionsForSelection;
		    	}
		    },
        }
    }
</script>