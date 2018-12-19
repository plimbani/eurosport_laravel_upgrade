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
		                    <select class="form-control ls-select2" v-model="groupData.teams_play_each_other" @change="setMatches()">
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
		        		<div class="row">
		        			<div class="col-md-3">
				        		<strong>Match {{ teamIndex/2 + 1 }}</strong>
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
				        			{{ (groupData.type === 'round_robin' ? 'Team ' + (teamIndex + 1) : (teamIndex/2 === 0) ? 'Home' : 'Away' )  }}
				        		</label>
				        	</div>
				        	<div class="col-md-9">
				        		<div class="row">
				        			<div class="col-md-4">
				        				<div class="form-group mb-0">
					        				<select class="form-control ls-select2" v-model="team.position_type" @change="onPositionTypeChange(teamIndex)">
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
					        				<select class="form-control ls-select2 js-select-position" :id="'pos_'+(teamIndex+1)" @change="onAssignPosition(teamIndex+1)" v-model="team.position">
					                    		<option :value="position.value" v-for="position in getPositionsForSelection(teamIndex, team.group)">{{ position.name }}</option>
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
                if(this.groupData.type == 'placing_match' && this.groupData.no_of_teams % 2 != 0) {
                	toastr['error']('Placing match teams should be in even numbers.', 'Error');
                	this.groupData.no_of_teams = this.last_selected_teams;
                	return false;
                }
                this.last_selected_teams = this.groupData.no_of_teams;
                this.displayTeams();
        	},
        	displayTeams() {
        		var i;
        		var oldGroupTeamData = _.cloneDeep(this.groupData.teams);
				this.groupData.teams = [];
				let vm = this;
				for (i = 0; i < this.groupData.no_of_teams; i++) {
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
						vm.setMatches();	
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
		    },
		    onChangeGroupType() {
		    	let vm = this;
		    	this.$root.$emit('updateGroupCount');
		    	this.updateTeamPositions();
		    	Vue.nextTick()
					.then(function () {
						vm.setMatches();	
					});
		    },
		    getGroupsForSelection(teamIndex) {
		    	let team = this.groupData.teams[teamIndex];
        		let groupsForSelection = [];
        		let roundRobinIndex = 0;
        		let placingMatchIndex = 0;
        		let roundGroupCount = 0;
                let placingGroupCount = 0;
                let vm = this;

                if(this.divisionIndex !== -1 && this.roundIndex === 0) {
                	return groupsForSelection;
                }

        		_.forEach(this.templateFormDetail['steptwo'].rounds, function(round, roundIndex) {
					_.forEach(round.groups, function(group, groupIndex) {
						if(roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

						if(group.type === 'round_robin' && team.position_type === 'placed') {
							groupsForSelection[roundRobinIndex] = {'name': 'Group ' + String.fromCharCode(65 +roundGroupCount), 'value': '-1,' + roundIndex + ',' + groupIndex};
							roundGroupCount += 1;

							if(roundRobinIndex === 0 && team.group === '')
								vm.groupData.teams[teamIndex].group = groupsForSelection[roundRobinIndex].value;

							roundRobinIndex++;

							return true;
						}

						if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
							placingGroupCount += 1;
							groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (placingGroupCount), 'value': '-1,' + roundIndex + ',' + groupIndex};

							if(placingMatchIndex === 0 && (team.group === '' || typeof team.group === 'undefined')) {
								vm.groupData.teams[teamIndex].group = groupsForSelection[placingMatchIndex].value;
							}

							placingMatchIndex++;

							return true;
						}
					});
					if(roundIndex >= vm.roundIndex) return false;
				});

				_.forEach(this.templateFormDetail['steptwo'].divisions, function(division, divisionIndex) {
					_.forEach(division.rounds, function(round, roundIndex) {
						_.forEach(round.groups, function(group, groupIndex) {
							if(divisionIndex === vm.divisionIndex && roundIndex === vm.roundIndex && groupIndex >= vm.index) return false;

							if(group.type === 'round_robin' && team.position_type === 'placed') {
								groupsForSelection[roundRobinIndex] = {'name': 'Group ' + String.fromCharCode(65 +roundGroupCount), 'value': divisionIndex + ',' + roundIndex + ',' + groupIndex};
								roundGroupCount += 1;

								if(roundRobinIndex === 0 && team.group === '')
									vm.groupData.teams[teamIndex].group = groupsForSelection[roundRobinIndex].value;

								roundRobinIndex++;

								return true;
							}

							if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
								placingGroupCount += 1;
								groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (placingGroupCount), 'value': divisionIndex + ',' + roundIndex + ',' + groupIndex};

								if(placingMatchIndex === 0 && (team.group === '' || typeof team.group === 'undefined')) {
									vm.groupData.teams[teamIndex].group = groupsForSelection[placingMatchIndex].value;
								}

								placingMatchIndex++;

								return true;
							}
						});
						if(divisionIndex === vm.divisionIndex && roundIndex >= vm.roundIndex) return false;
					});
				});
				return groupsForSelection;
		    },
		    getPositionsForSelection(teamIndex, group) {
		    	let vm = this;
			    var positionsForSelection = [];

			    if(this.divisionIndex !== -1 && this.roundIndex === 0) {
			    	_.forEach(vm.templateFormDetail.steptwo.divisions[vm.divisionIndex].teams, function(team, teamIndex) {
			    		let position = team.position.split(',');
			    		let roundData = vm.templateFormDetail['steptwo'].rounds[position[0]];
			    		let groupData = vm.templateFormDetail['steptwo'].rounds[position[0]].groups[position[1]];
			    		if(groupData.type === 'round_robin') {
			    			positionsForSelection.push({'name': (teamIndex + 1) + ' (#' + (parseInt(position[2]) + 1) + vm.getRoundRobinGroupName(roundData, position[1]) + ')' , 'value': teamIndex});
			    		} else if(groupData.type === 'placing_match') {
			    			let name = '';
			    			name = (teamIndex + 1);
			    			name += ' (PM' + vm.getPlacingMatchGroupName(roundData, position[1]);
			    			if(team.position_type === 'winner') {
			    				name += ' WR';
			    			} else if(team.position_type === 'loser') {
			    				name += ' LR';
			    			}
			    			
			    			name += ' Match ' + (parseInt(position[2]) + 1) + ')';
			    			positionsForSelection.push({'name': name, 'value': teamIndex});
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
							positionsForSelection.push({'name': 'Match' + i, 'value': group + ',' + (i - 1)});
						}
					}

			    	return positionsForSelection;
		    	}
		    },
		    onPositionTypeChange(teamIndex) {
		    	this.groupData.teams[teamIndex].group = '';
		    	this.groupData.teams[teamIndex].position = '';
		    },
		    onGroupChange(teamIndex) {
		    	this.groupData.teams[teamIndex].position = '';
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
		    		_.forEach(this.groupData.teams, function(team, teamIndex) {
		    			vm.groupData.teams[teamIndex].position = teamIndex;
		    			vm.groupData.teams[teamIndex].position_type = 'team';
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
		    },
		    setMatches() {
		    	let vm = this;
        		this.groupData.matches = [];

        		let groupData = this.groupData;
    			let times = groupData.teams_play_each_other;
    			let noOfTeams = groupData.no_of_teams;
    			let totalTimes = this.teamsPlayedEachOther[times];
    			let groupName = null;

    			if(groupData.type === 'round_robin') {
		    		groupName = this.getRoundRobinGroupName(this.roundData, this.index);
		    	}

		    	let matchCount = 1;
		    	if(this.roundIndex === 0 && groupData.type === 'placing_match' && this.index === this.getFirstPlacingMatch()) {
		    		for(var i=1; i<=noOfTeams; i=i+2) {
		    			let home = i;
		    			let away = i + 1;
		    			let inBetween =  home + "-" + away;
						let matchNumber = "CAT.PM" + (this.roundIndex+1) + ".G" + matchCount + "." + home + "-" + away;
						let displayMatchNumber = "CAT." + (this.roundIndex+1) + "." + matchCount + ".@HOME-@AWAY";
						let displayHomeTeamPlaceholderName = home;
						let displayAwayTeamPlaceholderName = away;

						vm.groupData.matches.push({
							in_between: inBetween,
							match_number: matchNumber,
							display_match_number: displayMatchNumber,
							display_home_team_placeholder_name: displayHomeTeamPlaceholderName,
							display_away_team_placeholder_name: displayAwayTeamPlaceholderName,
						});
						matchCount++;
		    		}
		    	}

		    	if(this.roundIndex === 0 && groupData.type === 'placing_match' && this.index !== this.getFirstPlacingMatch()) {
		    		let teams = groupData.teams;
		    		let totalPlacingMatches = 0;
		    		let allPlacingMatches = [];

		    		_.forEach(_.cloneDeep(this.roundData.groups), function(o, index) {
						if(o.type === 'placing_match' && index < this.index) {
							totalPlacingMatches += o.matches.length;
							allPlacingMatches = _.merge(allPlacingMatches, o.matches);
						}
					});

		    		for(var i=0; i<noOfTeams; i=i+2) {
		    			let position1 = teams[i].position.split(',')[3];
		    			let position2 = teams[i+1].position.split(',')[3];

		    			let positionType1 = this.getPositionTypeCode(teams[i].position_type);
		    			let positionType2 = this.getPositionTypeCode(teams[i].position_type);

		    			let homePlaceholder = "CAT.PM" + (this.roundIndex + 1) + ".G" + parseInt(position1 + 1);
		    			let awayPlaceholder = "CAT.PM" + (this.roundIndex + 1) + ".G" + parseInt(position2 + 1);

		    			let homePlacingMatch = _.filter(allPlacingMatches, function(o, index) { return (o.match_number.indexOf(homePlaceholder)) !== -1; });
		    			let awayPlacingMatch = _.filter(allPlacingMatches, function(o, index) { return (o.match_number.indexOf(awayPlaceholder)) !== -1; });

		    			let home = homePlacingMatch.in_between('-', '_') + '_' + positionType1;
		    			let away = awayPlacingMatch.in_between('-', '_') + '_' + positionType2;
		    			let inBetween =  homePlaceholder + positionType1 + "-" + awayPlaceholder + positionType2;
						let matchNumber = "CAT.PM" + (this.roundIndex + 1) + ".G" + (totalPlacingMatches + matchCount) + "." + home + "-" + away;
						let displayMatchNumber = null;
						if(positionType1 === positionType2 && positionType1 === 'WR') {
							displayMatchNumber = "CAT." + (this.roundIndex+1) + "." + (totalPlacingMatches + matchCount) + ".wrs.(@HOME-@AWAY)";
						} else if(positionType1 === positionType2 && positionType1 === 'LR') {
							displayMatchNumber = "CAT." + (this.roundIndex+1) + "." + (totalPlacingMatches + matchCount) + ".lrs.(@HOME-@AWAY)";
						}

						let displayHomeTeamPlaceholderName = (this.roundIndex + 1) + "." + parseInt(position1 + 1);
						let displayAwayTeamPlaceholderName = (this.roundIndex + 1) + "." + parseInt(position2 + 1);

						vm.groupData.matches.push({
							in_between: inBetween,
							match_number: matchNumber,
							display_match_number: displayMatchNumber,
							display_home_team_placeholder_name: displayHomeTeamPlaceholderName,
							display_away_team_placeholder_name: displayAwayTeamPlaceholderName,
						});
						matchCount++;
		    		}
		    	}
    			
    			if(groupData.type == "round_robin") {
	    			for(var i=0; i<totalTimes; i++){
	    				for(var j=1; j<=noOfTeams; j++) {
	    					for(var k=(j+1); k<=noOfTeams; k++) {
	    						let inBetween = null;
	    						let matchNumber = null;
	    						let displayMatchNumber = null;
	    						let displayHomeTeamPlaceholderName = null;
	    						let displayAwayTeamPlaceholderName = null;
	    						if(this.divisionIndex === -1) {
	    							let team1 = groupData.teams[j-1];
		    						let team2 = groupData.teams[k-1];
	    							if(this.roundIndex == 0 && groupData.type == "round_robin") {
	    								let home = groupName + j;
	    								let away = groupName + k;
		    							inBetween = j + '-' + k;
		    							matchNumber = "CAT.RR" + (this.roundIndex+1) + ".0" + matchCount + "." + home + "-" + away;
		    							displayMatchNumber = "CAT." + (this.roundIndex+1) + "." + matchCount + ".@HOME-@AWAY";
		    							displayHomeTeamPlaceholderName = home;
		    							displayAwayTeamPlaceholderName = away;
		    						} else {
		    							let divisionRoundGroupPositionTeam1 = team1.position.split(',');
		    							let divisionRoundGroupPositionTeam2 = team2.position.split(',');
		    							let roundDataTeam1 = null;
		    							let roundDataTeam2 = null;
		    							if(divisionRoundGroupPositionTeam1[0] === '-1') {
		    								roundDataTeam1 = vm.templateFormDetail['steptwo'].rounds[divisionRoundGroupPositionTeam1[1]];
		    							} else {
		    								roundDataTeam1 = vm.templateFormDetail['steptwo'].divisions[divisionRoundGroupPositionTeam1[0]].rounds[divisionRoundGroupPositionTeam1[1]];
		    							}
		    							if(divisionRoundGroupPositionTeam2[0] === '-1') {
		    								roundDataTeam2 = vm.templateFormDetail['steptwo'].rounds[divisionRoundGroupPositionTeam2[1]];
		    							} else {
		    								roundDataTeam2 = vm.templateFormDetail['steptwo'].divisions[divisionRoundGroupPositionTeam2[0]].rounds[divisionRoundGroupPositionTeam2[1]];
		    							}
		    							let groupName1 = this.getRoundRobinGroupName(roundDataTeam1, parseInt(divisionRoundGroupPositionTeam1[2]));
		    							let groupName2 = this.getRoundRobinGroupName(roundDataTeam2, parseInt(divisionRoundGroupPositionTeam2[2]));
		    							inBetween = parseInt(divisionRoundGroupPositionTeam1[3] + 1) + groupName1 + '-' + parseInt(divisionRoundGroupPositionTeam2[3] + 1) + groupName2;
		    						}
	    						}
	    						matchCount++;
	    						vm.groupData.matches.push({
	    							in_between: inBetween,
	    							match_number: matchNumber,
	    							display_match_number: displayMatchNumber,
	    							display_home_team_placeholder_name: displayHomeTeamPlaceholderName,
	    							display_away_team_placeholder_name: displayAwayTeamPlaceholderName,
	    						});
	    					}
	    				}
	    			}
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
        }
    }
</script>