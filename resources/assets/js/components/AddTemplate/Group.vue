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
		                    <select :data-last-selected="last_selected_teams" class="form-control ls-select2" v-model="groupData.no_of_teams" @change="onTeamChange($event)">
		                    	<option v-for="n in 28" v-if="n >= 2" :value="n">{{ n }}</option>
		                    </select>
		                </div>
		            </div>

		            <div class="col-md-6" v-if="groupData.type == 'round_robin'">
		                <div class="form-group mb-0">
		                    <label>Teams play each other</label>
		                    <select class="form-control ls-select2" v-model="groupData.teams_play_each_other">
		                        <option value="once">Once</option>
		                        <option value="twice">Twice</option>
		                        <option value="three_times">Three times</option>
		                        <option value="four_times">Four times</option>
		                    </select>
		                </div>
		            </div>
		        </div>
		        <div class="row align-items-center mt-3" v-if="!(roundIndex === 0 && groupData.type === 'round_robin')" v-for="(match, matchIndex) in groupData.matches">
		        	<div class="col-md-12 mb-3" v-if="matchIndex % 2 === 0 && groupData.type === 'placing_match'">
		        		<div class="row">
		        			<div class="col-md-3">
				        		<strong>Match {{ matchIndex/2 + 1 }}</strong>
				        	</div>
				        	<div class="col-md-9">
				        		<input type="checkbox" /> Final
				        	</div>
		        		</div>
		        	</div>
		        	<div class="col-md-12">
		        		<div class="row">
				        	<div class="col-md-3">
				        		<label class="mb-0">
				        			{{ (groupData.type === 'round_robin' ? 'Team ' + (matchIndex + 1) : (matchIndex/2 === 0) ? 'Home' : 'Away' )  }}
				        		</label>
				        	</div>
				        	<div class="col-md-9">
				        		<div class="row">
				        			<div class="col-md-4">
				        				<div class="form-group mb-0">
					        				<select class="form-control ls-select2" v-model="match.position_type" @change="onPositionTypeChange(matchIndex)">
					        					<option :value="position.key" v-for="position in getPositionTypes()">{{ position.value }}</option>
						                    </select>
						                </div>
				        			</div>
				        			<div class="col-md-4">
				        				<div class="form-group mb-0">
					        				<select :disabled="(roundIndex === 0 && groupData.type === 'placing_match' && index === getFirstPlacingMatch())" class="form-control ls-select2" v-model="match.group" @change="onGroupChange(matchIndex)">
					                    		<option v-for="group in getGroupsForSelection(matchIndex)" :value="group.value">{{ group.name }}</option>
					                    	</select>
					                    </div>
				        			</div>
				        			<div class="col-md-4">
				        				<div class="form-group mb-0">
					        				<select class="form-control ls-select2 js-select-position" :id="'pos_'+(matchIndex+1)" @change="onAssignPosition(matchIndex+1)" v-model="match.position">
					                    		<option :value="position.value" v-for="position in getPositionsForSelection(matchIndex, match.group)">{{ position.name }}</option>
					                    	</select>
					                    </div>
				        			</div>
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
		    		return 'Group ' + String.fromCharCode(65 + this.roundData.start_round_group_count + currentRoundGroupCount);
		    	}

		    	if(this.groupData.type === 'placing_match') {
		    		let currentPlacingGroupCount =  _.filter(this.roundData.groups, function(o, index) { return (o.type === 'placing_match' && index <= vm.index); }).length;
		    		return 'PM ' + (this.roundData.start_placing_group_count + currentPlacingGroupCount);
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
        		var oldGroupTeamData = _.cloneDeep(this.groupData.matches);
				this.groupData.matches = [];
				for (i = 0; i < this.groupData.no_of_teams; i++) {
					if(_.has(oldGroupTeamData, i)) {
						this.groupData.matches.push({position_type: oldGroupTeamData[i].position_type, group: oldGroupTeamData[i].group, position: oldGroupTeamData[i].position, teams: ''});
						continue;
					}
					if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) {
						this.groupData.matches.push({position_type: 'team', group: '', position: i, teams: ''});
						continue;
					}

					if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index !== this.getFirstPlacingMatch()) {
						this.groupData.matches.push({position_type: 'winner', group: '', position: '', teams: ''});
						continue;
					}
				    this.groupData.matches.push({position_type: 'placed', group: '', position: '', teams: ''});
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
		    	this.updateTeamPositions();
		    },
		    getGroupsForSelection(matchIndex) {
		    	let match = this.groupData.matches[matchIndex];
        		let groupsForSelection = [];
        		let roundRobinIndex = 0;
        		let placingMatchIndex = 0;
        		let roundGroupCount = 0;
                let placingGroupCount = 0;
                let vm = this;
        		_.forEach(this.templateFormDetail['steptwo'].rounds, function(round, roundIndex) {
					_.forEach(round.groups, function(group, groupIndex) {
						if(roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

						if(group.type === 'round_robin' && match.position_type === 'placed') {
							groupsForSelection[roundRobinIndex] = {'name': 'Group ' + String.fromCharCode(65 +roundGroupCount), 'value': roundIndex + ',' + groupIndex};
							roundGroupCount += 1;

							if(roundRobinIndex === 0 && match.group === '')
								vm.groupData.matches[matchIndex].group = groupsForSelection[roundRobinIndex].value;

							roundRobinIndex++;

							return true;
						}

						if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], match.position_type) > -1) {
							placingGroupCount += 1;
							groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (placingGroupCount), 'value': roundIndex + ',' + groupIndex};

							if(placingMatchIndex === 0 && (match.group === '' || typeof match.group === 'undefined')) {
								vm.groupData.matches[matchIndex].group = groupsForSelection[placingMatchIndex].value;
							}

							placingMatchIndex++;

							return true;
						}
					});
					if(roundIndex >= vm.roundIndex) return false;
				});
				return groupsForSelection;
		    },
		    getPositionsForSelection(matchIndex, group) {
		    	let vm = this;
			    var positionsForSelection = [];

			    if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) {
			    	_.forEach(this.groupData.matches, function(match, matchIndex) {
		    			positionsForSelection.push({'name': vm.getSuffixForPosition(matchIndex + 1), 'value': matchIndex});
		    		});
		    		return positionsForSelection;
			    }

		    	let match = this.groupData.matches[matchIndex];
		    	if(group) {
			    	var currentRoundGroup = group.split(',');
			    	var matches = this.templateFormDetail['steptwo'].rounds[currentRoundGroup[0]].groups[currentRoundGroup[1]].matches;			    	
			    	var numberOfTeams = this.templateFormDetail['steptwo'].rounds[currentRoundGroup[0]].groups[currentRoundGroup[1]].no_of_teams;
			    	var groupType = this.templateFormDetail['steptwo'].rounds[currentRoundGroup[0]].groups[currentRoundGroup[1]].type;
			    	
				    // for round robin
			    	if(groupType === 'round_robin' && match.position_type === 'placed') {
				    	if(this.groupData.matches[matchIndex].position === '' || typeof this.groupData.matches[matchIndex].position === 'undefined') {
				    		this.groupData.matches[matchIndex].position = group + ',0';
				    	}
				    	_.forEach(matches, function(match, index) {
		    				positionsForSelection.push({'name': vm.getSuffixForPosition(index + 1), 'value': group + ',' + index});
		    			});
		    		}

			    	// for placing
					if(groupType === 'placing_match' && _.indexOf(['winner', 'loser'], match.position_type) > -1) {
						let matches = numberOfTeams / 2;
						this.groupData.matches[matchIndex].position = group + ',0';
						for (var i = 1; i <= matches; i++) {
							positionsForSelection.push({'name': 'Match' + i, 'value': group + ',' + (i - 1)});
						}
					}

			    	return positionsForSelection;
		    	}
		    },
		    onPositionTypeChange(matchIndex) {
		    	this.groupData.matches[matchIndex].group = '';
		    	this.groupData.matches[matchIndex].position = '';
		    },
		    onGroupChange(matchIndex) {
		    	this.groupData.matches[matchIndex].position = '';
		    },
		    getPositionTypes() {
				let positionTypes = [];

				if(!(this.roundIndex === 0 && this.groupData.type === 'placing_match')) {
					positionTypes.push({'key': 'placed', 'value': 'Placed'});
				}

				if(!(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch())) {
					positionTypes.push({'key': 'winner', 'value': 'Winner'});
					positionTypes.push({'key': 'loser', 'value': 'Loser'});
				}

				if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) {
					positionTypes.push({'key': 'team', 'value': 'Team'});
				}
				return positionTypes;
		    },
		    updateTeamPositions() {
		    	let vm = this;
		    	if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) {
		    		_.forEach(this.groupData.matches, function(match, matchIndex) {
		    			vm.groupData.matches[matchIndex].position = matchIndex;
		    			vm.groupData.matches[matchIndex].position_type = 'team';
		    		});
		    	}
		    	if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index !== this.getFirstPlacingMatch()) {
		    		_.forEach(this.groupData.teams, function(team, teamIndex) {
		    			vm.groupData.teams[teamIndex].position_type = 'winner';
		    		});
				}
		    },
		    getFirstPlacingMatch() {
		    	let index = _.findIndex(this.roundData.groups, {'type': 'placing_match'});
		    	return index;
		    }
        }
    }
</script>