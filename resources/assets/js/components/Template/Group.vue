<template>
	<div>
	    <div class="card mb-3">
	    	<div class="card-block">
		        <h6 class="font-weight-bold">{{ getGroupName }} <span class="pull-right" v-if="templateFormDetail.stepone.editor != 'knockout'"><a href="javascript:void(0)" @click="removeGroup()"><i class="fas fa-trash text-danger"></i></a></span></h6>
		        <div class="form-group">
		        	<div class="radio" v-if="templateFormDetail.stepone.editor != 'knockout'">
	                    <div class="c-input">
	                        <input :name="'group_type' + divisionIndex + roundIndex + index" type="radio" :id="'round_robin' + divisionIndex + roundIndex + index" class="euro-radio" checked="checked" value="round_robin" v-model="groupData.type" @change="onChangeGroupType()">
	                        <label :for="'round_robin' + divisionIndex + roundIndex + index" class="d-inline-flex mr-5">Round robin</label>
	                        <input :name="'group_type' + divisionIndex + roundIndex + index" type="radio" :id="'placing_match' + divisionIndex + roundIndex + index" class="euro-radio" value="placing_match" v-model="groupData.type" @change="onChangeGroupType()">
	                        <label :for="'placing_match' + divisionIndex + roundIndex + index" class="d-inline-flex">Placing match</label>
	                    </div>
	                </div>

		            <!-- <div class="radio">
		                <label><input type="radio" checked="checked" value="round_robin" v-model="groupData.type" @change="onChangeGroupType()"> Round robin</label>
		                <label><input type="radio" value="placing_match" v-model="groupData.type" @change="onChangeGroupType()"> Placing match</label>
		            </div> -->
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		                <div class="form-group mb-0">
		                    <label>Number of teams</label>
		                    <select :data-last-selected="last_selected_teams" class="form-control ls-select2" v-model="groupData.no_of_teams" @change="onTeamChange($event)">
		                    	<option v-for="n in groupTeamsToDisplay" v-if="n >= 2" :value="n">{{ n }}</option>
		                    </select>
		                </div>
		            </div>

		            <div class="col-md-6" v-if="groupData.type == 'round_robin' && templateFormDetail.stepone.editor != 'knockout'">
		                <div class="form-group mb-0">
		                    <label>Teams play each other</label>
		                    <select class="form-control ls-select2" v-model="groupData.teams_play_each_other" @change="setMatches('teams_play_each_other_change')">
		                        <option value="once">Once</option>
		                        <option value="twice">Twice</option>
		                        <option value="three_times">Three times</option>
		                        <option value="four_times">Four times</option>
		                    </select>
		                </div>
		            </div>
		        </div>
		        <div class="row align-items-center mt-3" v-if="!(roundIndex === 0 && groupData.type === 'round_robin' && divisionIndex === -1)" v-for="(team, teamIndex) in groupData.teams">
		        	<div class="col-md-12 mb-3" v-if="teamIndex % 2 === 0 && groupData.type === 'placing_match'">
		        		<div class="row align-items-center">
		        			<div class="col-md-3">
				        		<strong>Match {{ teamIndex/2 + 1 }}</strong>
				        	</div>
				        	<div class="col-md-9" v-if="showHideIsFinal((teamIndex/2))">
				        		<div class="checkbox">
                                  	<div class="c-input">
                                    	<input type="checkbox" :id="'match_final' + divisionIndex + roundIndex + index + teamIndex" class="euro-checkbox" v-model="groupData.matches[teamIndex/2].is_final" />
                                    	<label :for="'match_final' + divisionIndex + roundIndex + index + teamIndex">Final </label>
                                  	</div>
                                </div>

				        		<!-- <input type="checkbox" v-model="groupData.matches[teamIndex/2].is_final" /> Final -->
				        	</div>
		        		</div>
		        	</div>
		        	<div class="col-md-12">
		        		<div class="row align-items-center">
				        	<div class="col-md-3">
				        		<label class="mb-0">
				        			{{ (groupData.type === 'round_robin' ? 'Team ' + (teamIndex + 1) : ((teamIndex % 2 === 0) ? 'Team 1' : 'Team 2') )  }}
				        		</label>
				        	</div>
				        	<div class="col-md-9">
				        		<div class="row">
				        			<div class="col-md-4">
				        				<div class="form-group mb-0">
					        				<select :class="getPositionTypeClassNames(teamIndex)" v-model="team.position_type" @change="onPositionTypeChange(teamIndex)">
					        					<option :value="position.key" v-for="position in getPositionTypes()">{{ position.value }}</option>
						                    </select>
						                </div>
				        			</div>
				        			<div class="col-md-4">
				        				<div class="form-group mb-0">
					        				<select :disabled="( (roundIndex === 0 && groupData.type === 'placing_match' && index === getFirstPlacingMatch()) || (roundIndex === 0 && divisionIndex !== -1) ) " class="form-control ls-select2" v-model="team.group" @change="onGroupChange(teamIndex)">
					                    		<option v-for="group in getGroupsForSelection(teamIndex)" :value="group.value">{{ group.name }}</option>
					                    	</select>
					                    </div>
				        			</div>
				        			<div class="col-md-4">
				        				<div class="form-group mb-0">
					        				<select :data-team-index="teamIndex" :class="getPositionClassNames()" @change="onAssignPosition(teamIndex+1)" v-model="team.position">
					                    		<option :value="position.value" v-for="position in getPositionsForSelection(teamIndex, team.group, team.position, team.position_type)">{{ position.name }}</option>
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
    	props: ['index', 'roundIndex', 'divisionIndex', 'groupData', 'roundData', 'templateFormDetail'],
        data() {
            return {
            	last_selected_teams: this.groupData.no_of_teams,
            	teamsPlayedEachOther: {
        			'once': '1',
        			'twice': '2',
        			'three_times': '3',
        			'four_times': '4'
        		}
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
		    	return this.getGroupNameByRoundAndGroupIndex(this.divisionIndex, this.roundIndex, this.index);
		    },
		    groupTeamsToDisplay() {
		    	let teams = [];
		    	let roundTeams = this.roundData.no_of_teams;
		    	let roundGroups = this.roundData.groups;
		    	let totalTeams = this.getRoundTillNowTotalTeams(false);
		    	let currentGroupSize = 0;

		    	currentGroupSize = roundTeams - totalTeams;

	    		for (var i = 2; i <= currentGroupSize; i++) {
	    			if(this.groupData.type == 'placing_match') {
		    			if(i % 2 == 0) {
		    				teams.push(i);
		    			}
		    		} else {
		    			teams.push(i);
		    		}
	    		}
		    	return teams;
		    }
        },
        methods: {
        	getRoundTillNowTotalTeams(countTillCurrentGroup) {
        		let roundGroups = this.roundData.groups;
        		let totalTeams = 0;
        		let countTillGroup = countTillCurrentGroup === true ? (parseInt(this.index) + 1) : this.index;

		    	for(let i=0; i<countTillGroup; i++) {
		    		totalTeams += roundGroups[i].no_of_teams;
		    	}

		    	return totalTeams;
        	},
        	removeGroup() {
        		this.$parent.removeGroup(this.index, this.roundIndex);
        		this.$root.$emit('updateGroupCount');
        		this.$root.$emit('updatePositions');
        	},
        	onTeamChange() {
        		console.log('onTeamChange');
        		return false;
 				let groupTotalTeams = this.$parent.getGroupTotalTeams(this.roundIndex);
 				let roundTeams = this.roundData.no_of_teams;
 				let totalTeams = this.getRoundTillNowTotalTeams(true);
                if(this.roundIndex === 0 && groupTotalTeams > this.roundData.no_of_teams) {
                    toastr['error']('The number of teams selected exceeds total teams in the round.', 'Error');
                    this.groupData.no_of_teams = this.last_selected_teams;
                    return false;
                }
                if(this.groupData.type == 'placing_match' && this.groupData.no_of_teams % 2 != 0) {
                	toastr['error']('Placing match teams should be in even numbers.', 'Error');
                	this.groupData.no_of_teams = this.last_selected_teams;
                	return false;
                }
                if((roundTeams - totalTeams) === 1 || (roundTeams - totalTeams) < 0) {
                	toastr['error']('The selected number of teams is invalid.', 'Error');
                	this.groupData.no_of_teams = this.last_selected_teams;
                	return false;
                }
                this.last_selected_teams = this.groupData.no_of_teams;
                this.displayTeams();
                this.$root.$emit('updateGroupCount');
                this.$root.$emit('updatePositions');
        	},
        	displayTeams() {
        		let i;
        		let oldGroupMatchesData = _.cloneDeep(this.groupData.matches);
        		let oldGroupTeamData = _.cloneDeep(this.groupData.teams);
				this.groupData.teams = [];
				this.groupData.matches = [];
				let vm = this;
				for (i = 0; i < this.groupData.no_of_teams; i++) {
					if(_.has(oldGroupMatchesData, i)) {
						vm.groupData.matches.push(oldGroupMatchesData[i]);
					} else if(vm.groupData.type === 'placing_match' && (i%2) === 0) {
						vm.groupData.matches.push({is_final: false});
					}
					if(_.has(oldGroupTeamData, i)) {
						this.groupData.teams.push({position_type: oldGroupTeamData[i].position_type, group: oldGroupTeamData[i].group, position: oldGroupTeamData[i].position});
						continue;
					}
					if(this.roundIndex === 0 && ((this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) || this.divisionIndex !== -1)) {
						this.groupData.teams.push({	position_type: 'team', group: '', position: i});
						continue;
					}
					if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index !== this.getFirstPlacingMatch()) {
						this.groupData.teams.push({position_type: 'winner', group: '', position: ''});
						continue;
					}
				    this.groupData.teams.push({position_type: 'placed', group: '', position: ''});
				}

				Vue.nextTick()
					.then(function () {
						vm.setMatches('no_of_team_change');	
					});
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
		    	let vm = this;
		    	Vue.nextTick()
					.then(function () {
						vm.setMatches('assign_position');
					});
		    },
		    onChangeGroupType() {
		    	let vm = this;
		    	if(this.groupData.type == 'placing_match' && this.groupData.no_of_teams % 2 != 0) {
		    		this.groupData.no_of_teams = this.groupData.no_of_teams - 1;
		    	}
		    	this.$root.$emit('updateGroupCount');
		    	this.$root.$emit('updatePositions');
		    	this.updateTeamPositions();
		    	Vue.nextTick()
					.then(function () {
						vm.setMatches('group_type_change');	
					});
		    },
		    getGroupsForSelection(teamIndex) {
		    	let team = this.groupData.teams[teamIndex];
        		let groupsForSelection = [];
        		let roundRobinIndex = 0;
        		let placingMatchIndex = 0;
                let vm = this;

                if(this.divisionIndex !== -1 && this.roundIndex === 0) {
                	return groupsForSelection;
                }

                if(this.divisionIndex === -1){
	        		_.forEach(this.templateFormDetail['steptwo'].rounds, function(round, roundIndex) {
						_.forEach(round.groups, function(group, groupIndex) {
							if(roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

							let roundData = vm.templateFormDetail['steptwo'].rounds[roundIndex];
							if(group.type === 'round_robin' && team.position_type === 'placed') {
								groupsForSelection[roundRobinIndex] = {'name': 'Group ' + vm.getRoundRobinGroupName(roundData, groupIndex), 'value': '-1,' + roundIndex + ',' + groupIndex};

								if(roundRobinIndex === 0 && (team.group === '' || typeof team.group === 'undefined'))
									vm.groupData.teams[teamIndex].group = groupsForSelection[roundRobinIndex].value;

								roundRobinIndex++;

								return true;
							}

							if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
								groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (vm.getPlacingMatchGroupName(roundData, groupIndex)), 'value': '-1,' + roundIndex + ',' + groupIndex};

								if(placingMatchIndex === 0 && (team.group === '' || typeof team.group === 'undefined')) {
									vm.groupData.teams[teamIndex].group = groupsForSelection[placingMatchIndex].value;
								}

								placingMatchIndex++;

								return true;
							}
						});
						if(roundIndex >= vm.roundIndex) return false;
					});
				}

				if(this.divisionIndex !== -1 && this.roundIndex > 0) {
					_.forEach(this.templateFormDetail['steptwo'].divisions, function(division, divisionIndex) {
						if(divisionIndex === vm.divisionIndex) return false;
					});

					let division = this.templateFormDetail['steptwo'].divisions[this.divisionIndex];
					_.forEach(division.rounds, function(round, roundIndex) {
						_.forEach(round.groups, function(group, groupIndex) {
							if(roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

							let roundData = vm.templateFormDetail['steptwo'].divisions[vm.divisionIndex].rounds[roundIndex];
							if(group.type === 'round_robin' && team.position_type === 'placed') {
								groupsForSelection[roundRobinIndex] = {'name': 'Group ' + vm.getRoundRobinGroupName(roundData, groupIndex), 'value': vm.divisionIndex + ',' + roundIndex + ',' + groupIndex};

								if(roundRobinIndex === 0 && team.group === '')
									vm.groupData.teams[teamIndex].group = groupsForSelection[roundRobinIndex].value;

								roundRobinIndex++;

								return true;
							}

							if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
								groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (vm.getPlacingMatchGroupName(roundData, groupIndex)), 'value': vm.divisionIndex + ',' + roundIndex + ',' + groupIndex};

								if(placingMatchIndex === 0 && (team.group === '' || typeof team.group === 'undefined')) {
									vm.groupData.teams[teamIndex].group = groupsForSelection[placingMatchIndex].value;
								}

								placingMatchIndex++;

								return true;
							}
						});
						if(roundIndex >= vm.roundIndex) return false;
					});
				}
				return groupsForSelection;
		    },
		    getPositionsForSelection(teamIndex, group, selectedPosition, selectedPositionType) {
		    	let vm = this;
			    var positionsForSelection = [];

			    if(this.divisionIndex !== -1 && this.roundIndex === 0) {
			    	_.forEach(vm.templateFormDetail.steptwo.divisions[vm.divisionIndex].teams, function(team, teamIndex) {
			    		let position = team.position.split(',');
			    		let roundData = vm.templateFormDetail['steptwo'].rounds[position[1]];
			    		let groupData = roundData.groups[position[2]];

			    		if(groupData) {
				    		if(groupData.type === 'round_robin') {
				    			positionsForSelection.push({'name': (teamIndex + 1) + ' (#' + (parseInt(position[3]) + 1) + vm.getRoundRobinGroupName(roundData, position[2]) + ')' , 'value': teamIndex});
				    		} else if(groupData.type === 'placing_match') {
				    			let name = '';
				    			name = (teamIndex + 1);
				    			name += ' (PM' + vm.getPlacingMatchGroupName(roundData, position[2]);
				    			if(team.position_type === 'winner') {
				    				name += ' WR';
				    			} else if(team.position_type === 'loser') {
				    				name += ' LR';
				    			}
				    			
				    			name += ' Match ' + (parseInt(position[3]) + 1) + ')';
				    			positionsForSelection.push({'name': name, 'value': teamIndex});
				    		}
				    	}
		    		});
		    		return positionsForSelection;
			    }

			    if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) {
			    	_.forEach(this.groupData.teams, function(team, teamIndex) {
			    		positionsForSelection.push({'name': vm.getSuffixForPosition(teamIndex + 1), 'value': teamIndex});
		    		});
		    		return positionsForSelection;
			    }

		    	let team = this.groupData.teams[teamIndex];
		    	if(group) {
			    	var currentDivisionRoundGroup = group.split(',');
			    	var groupData = null;
			    	if(currentDivisionRoundGroup[0] === '-1') {
			    		groupData = this.templateFormDetail['steptwo'].rounds[currentDivisionRoundGroup[1]].groups[currentDivisionRoundGroup[2]];
			    	} else {
			    		groupData = this.templateFormDetail['steptwo'].divisions[currentDivisionRoundGroup[0]].rounds[currentDivisionRoundGroup[1]].groups[currentDivisionRoundGroup[2]];
			    	}

			    	if(groupData) {
				    	var teams = groupData.teams;
				    	var numberOfTeams = groupData.no_of_teams;
				    	var groupType = groupData.type;
				    	
					    // for round robin
				    	if(groupType === 'round_robin' && team.position_type === 'placed') {
					    	if(this.groupData.teams[teamIndex].position === '' || typeof this.groupData.teams[teamIndex].position === 'undefined') {
					    		this.groupData.teams[teamIndex].position = group + ',0';
					    	}
					    	_.forEach(teams, function(team, index) {
			    				positionsForSelection.push({'name': vm.getSuffixForPosition(index + 1), 'value': group + ',' + index});
			    			});
			    		}

				    	// for placing
						if(groupType === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
							let matches = numberOfTeams / 2;
							if(this.groupData.teams[teamIndex].position === '' || typeof this.groupData.teams[teamIndex].position === 'undefined') {
								this.groupData.teams[teamIndex].position = group + ',0';
							}
							for (var i = 1; i <= matches; i++) {
								positionsForSelection.push({'name': 'Match ' + i, 'value': group + ',' + (i - 1)});
							}
						}
					}

			    	return positionsForSelection;
		    	}
		    },
		    onPositionTypeChange(teamIndex) {
		    	let vm = this;
		    	this.groupData.teams[teamIndex].group = '';
		    	this.groupData.teams[teamIndex].position = '';
		    	Vue.nextTick()
					.then(function () {
						vm.setMatches('position_type_change');
					});
		    },
		    onGroupChange(teamIndex) {
		    	let vm = this;
		    	this.groupData.teams[teamIndex].position = '';
		    	Vue.nextTick()
					.then(function () {
						vm.setMatches('group_change');
					});
		    },
		    getPositionTypes() {
				let positionTypes = [];

				if(this.roundIndex === 0 && ((this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) || this.divisionIndex !== -1)) {
					positionTypes.push({'key': 'team', 'value': 'Team'});
				}

				if(!(this.roundIndex === 0 && (this.groupData.type === 'placing_match' || this.divisionIndex !== -1))) {
					positionTypes.push({'key': 'placed', 'value': 'Placed'});
				}

				if(!(this.roundIndex === 0 && ((this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch())  || this.divisionIndex !== -1))) {
					positionTypes.push({'key': 'winner', 'value': 'Winner'});
					positionTypes.push({'key': 'loser', 'value': 'Loser'});
				}

				return positionTypes;
		    },
		    updateTeamPositions() {
		    	let vm = this;
		    	if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) {
		    		this.groupData.matches = [];
		    		_.forEach(this.groupData.teams, function(team, teamIndex) {
		    			vm.groupData.teams[teamIndex].position = teamIndex;
		    			vm.groupData.teams[teamIndex].position_type = 'team';
		    			if(teamIndex % 2 === 0) {
		    				vm.groupData.matches.push({is_final: false});
		    			}
		    		});
		    	}
		    	if(this.roundIndex === 0 && this.groupData.type === 'placing_match' && this.index !== this.getFirstPlacingMatch()) {
		    		this.groupData.matches = [];
		    		_.forEach(this.groupData.teams, function(team, teamIndex) {
		    			vm.groupData.teams[teamIndex].position_type = 'winner';
		    			if(teamIndex % 2 === 0) {
		    				vm.groupData.matches.push({is_final: false});
		    			}
		    		});
				}
		    },
		    getFirstPlacingMatch() {
		    	let index = _.findIndex(this.roundData.groups, {'type': 'placing_match'});
		    	return index;
		    },
		    setMatches(change) {
		    	let vm = this;

        		let groupData = this.groupData;
    			let times = groupData.teams_play_each_other;
    			let noOfTeams = groupData.no_of_teams;
    			let totalTimes = this.teamsPlayedEachOther[times];
    			let groupName = null;

    			if(groupData.type === 'round_robin') {
		    		groupName = this.getRoundRobinGroupName(this.roundData, this.index);
		    	}

		    	let matchCount = 1;

		    	var oldGroupMatchesData = _.cloneDeep(this.groupData.matches);

		    	this.groupData.matches = [];
		  		
    			// if(groupData.type == "round_robin" && this.roundIndex != 0) {
	    		// 	for(var i=0; i<totalTimes; i++){
	    		// 		for(var j=1; j<=noOfTeams; j++) {
	    		// 			for(var k=(j+1); k<=noOfTeams; k++) {
	    		// 				let inBetween = null;
	    		// 				let matchNumber = null;
	    		// 				let displayMatchNumber = null;
	    		// 				let displayHomeTeamPlaceholderName = null;
	    		// 				let displayAwayTeamPlaceholderName = null;
	    		// 				if(this.divisionIndex === -1) {
	    		// 					let team1 = groupData.teams[j-1];
		    	// 					let team2 = groupData.teams[k-1];
	    		// 					/*if(this.roundIndex == 0 && groupData.type == "round_robin") {
	    		// 						let home = groupName + j;
	    		// 						let away = groupName + k;
		    	// 						inBetween = j + '-' + k;
		    	// 						matchNumber = "CAT.RR" + (this.roundIndex+1) + ".0" + matchCount + "." + home + "-" + away;
		    	// 						displayMatchNumber = "CAT." + (this.roundIndex+1) + "." + matchCount + ".@HOME-@AWAY";
		    	// 						displayHomeTeamPlaceholderName = home;
		    	// 						displayAwayTeamPlaceholderName = away;
		    	// 					} else {*/
		    	// 						let divisionRoundGroupPositionTeam1 = team1.position.split(',');
		    	// 						let divisionRoundGroupPositionTeam2 = team2.position.split(',');
		    	// 						let roundDataTeam1 = null;
		    	// 						let roundDataTeam2 = null;

		    	// 						if(team1.position) {
		    	// 							if(divisionRoundGroupPositionTeam1[0] === '-1') {
			    // 								roundDataTeam1 = vm.templateFormDetail['steptwo'].rounds[divisionRoundGroupPositionTeam1[1]];
			    // 							} else {
			    // 								roundDataTeam1 = vm.templateFormDetail['steptwo'].divisions[divisionRoundGroupPositionTeam1[0]].rounds[divisionRoundGroupPositionTeam1[1]];
			    // 							}
		    	// 						}
		    							
		    	// 						if(team2.position) {
			    // 							if(divisionRoundGroupPositionTeam2[0] === '-1') {
			    // 								roundDataTeam2 = vm.templateFormDetail['steptwo'].rounds[divisionRoundGroupPositionTeam2[1]];
			    // 							} else {
			    // 								roundDataTeam2 = vm.templateFormDetail['steptwo'].divisions[divisionRoundGroupPositionTeam2[0]].rounds[divisionRoundGroupPositionTeam2[1]];
			    // 							}
			    // 						}

			    // 						if(roundDataTeam1 && roundDataTeam2) {
			    // 							let groupName1 = this.getRoundRobinGroupName(roundDataTeam1, parseInt(divisionRoundGroupPositionTeam1[2]));
			    // 							let groupName2 = this.getRoundRobinGroupName(roundDataTeam2, parseInt(divisionRoundGroupPositionTeam2[2]));
			    // 							inBetween = parseInt(divisionRoundGroupPositionTeam1[3] + 1) + groupName1 + '-' + parseInt(divisionRoundGroupPositionTeam2[3] + 1) + groupName2;
			    // 						}
		    	// 					//}
	    		// 				}
	    		// 				matchCount++;
	    		// 				vm.groupData.matches.push({
	    		// 					in_between: inBetween,
	    		// 					match_number: matchNumber,
	    		// 					display_match_number: displayMatchNumber,
	    		// 					display_home_team_placeholder_name: displayHomeTeamPlaceholderName,
	    		// 					display_away_team_placeholder_name: displayAwayTeamPlaceholderName,
	    		// 				});
	    		// 			}
	    		// 		}
	    		// 	}
	    		// }
	    		if(groupData.type == "placing_match") {
	    			_.forEach(groupData.teams, function(team, teamIndex) {
	    				if(teamIndex % 2 === 0) {
		    				let isFinal = (typeof oldGroupMatchesData[teamIndex/2] !== 'undefined' && typeof oldGroupMatchesData[teamIndex/2]['is_final'] !== 'undefined') ? oldGroupMatchesData[teamIndex/2]['is_final'] : false;
		    				vm.groupData.matches.push({is_final: isFinal});
			    		}
		    		});
	    		}
		    },
		    getGroupNameByRoundAndGroupIndex(divisionIndex, roundIndex, groupIndex) {
		    	let vm = this;
		    	let roundData = null;
		    	if(divisionIndex === -1) {
		    		roundData = this.templateFormDetail['steptwo'].rounds[roundIndex];
		    	} else {
		    		roundData = this.templateFormDetail['steptwo'].divisions[divisionIndex].rounds[roundIndex];
		    	}
		    	let groupData = roundData.groups[groupIndex];

		    	if(groupData.type === 'round_robin') {
		    		return 'Group ' + this.getRoundRobinGroupName(roundData, groupIndex);
		    	}

		    	if(groupData.type === 'placing_match') {
		    		return 'PM ' + this.getPlacingMatchGroupName(roundData, groupIndex);
		    	}
		    },
		    getRoundRobinGroupName(roundData, groupIndex) {
		    	let currentRoundGroupCount =  _.filter(roundData.groups, function(o, index) { return (o.type === 'round_robin' && index < groupIndex); }).length;
		    	return String.fromCharCode(65 + roundData.start_round_group_count + currentRoundGroupCount);
		    },
		    getPlacingMatchGroupName(roundData, groupIndex) {
		    	let currentPlacingGroupCount =  _.filter(roundData.groups, function(o, index) { return (o.type === 'placing_match' && index <= groupIndex); }).length;
		    	return (roundData.start_placing_group_count + currentPlacingGroupCount);
		    },
		    getPositionTypeCode(positionType) {
		    	if(positionType === 'winner') {
		    		return 'WR';
		    	} else {
		    		return 'LR';
		    	}
		    },
		    onFinalChange() {

		    },
		    showHideIsFinal(index) {
		    	if(index in this.groupData.matches) {
		    		return true;
		    	}
		    	return false;
		    },
		    getPositionClassNames() {
		    	return "form-control ls-select2 js-select-position js-select-position-" + this.divisionIndex + this.roundIndex + this.index;
		    },
		    getPositionTypeClassNames(teamIndex) {
		    	return 'form-control ls-select2 js-select-position-type-' + this.divisionIndex + this.roundIndex + this.index + this.teamIndex;
		    },
        }
    }
</script>