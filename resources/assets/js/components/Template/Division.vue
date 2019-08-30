<template> 
    <div class="card mb-3">
        <div class="card-block">
            <h6 class="font-weight-bold">Division {{ index + 1 }} <span class="pull-right"><a href="javascript:void(0)" @click="removeDivision(index)"><i class="fas fa-trash text-danger"></i></a></span></h6>
            <div class="form-group">
                <label>Number of teams in division</label>
                <select class="form-control ls-select2" v-model="divisionData.no_of_teams" @change="onTeamChange(index)">
                    <option value="">Number of teams</option>
                    <option v-for="n in templateFormDetail.stepone.no_of_teams" v-if="n >= 2" :value="n">{{ n }}</option>
                </select>
            </div>

            <div class="row align-items-center mt-3 mb-3" v-for="(team, teamIndex) in divisionData.teams">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="mb-0">
                                {{ 'Team ' + (teamIndex + 1) }}
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
                                        <select class="form-control ls-select2" v-model="team.group" @change="onGroupChange(teamIndex)">
                                            <option v-for="group in getGroupsForSelection(teamIndex)" :value="group.value">{{ group.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <select class="form-control ls-select2 js-select-position" :id="'pos_'+(teamIndex+1)" v-model="team.position">
                                            <option :value="position.value" v-for="position in getPositionsForSelection(teamIndex, team.group)">{{ position.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- add new round component -->
            <round v-for="(round, roundIndex) in divisionData.rounds" :index="roundIndex" :divisionIndex="index" :roundData="round" :templateFormDetail="templateFormDetail" :isSeletedRoundTeamsAndGroupTeamsNotSame="!isSeletedRoundTeamsIsInUse(roundIndex)"></round>

            <div class="form-group mb-0">
                <button :disabled="divisionData.no_of_teams === 0" type="button" class="btn btn-success" @click="addNewDivisionRound(index)"><small><i class="jv-icon jv-plus"></i></small> &nbsp;Add a round</button>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    import Round from './Round.vue';

    export default {
        props: ['index', 'divisionData', 'templateFormDetail'],
        data() {
            return {
                last_selected_teams: this.divisionData.no_of_teams,
            }
        },
        components: {
            Round,
        },
        mounted() {
        },
        created() {
            
        },
        beforeCreate: function() {
            
        },
        computed: {
        },
        methods: {
            removeDivision(index) {
                this.templateFormDetail.steptwo.divisions.splice(index, 1);
                this.$root.$emit('updateGroupCount');
                this.$root.$emit('updateRoundCount');
                this.$root.$emit('updatePositions');
            },
            onTeamChange(divisionIndex) {
                let divisionTeams = this.divisionData.no_of_teams;
                if(divisionTeams > this.templateFormDetail.stepone.no_of_teams) {
                    toastr['error']('Division team count get exceeds.', 'Error');
                    this.divisionData.no_of_teams = this.last_selected_teams;
                    return false;
                }
                this.last_selected_teams = this.divisionData.no_of_teams;
                if(this.templateFormDetail.steptwo.divisions[divisionIndex].rounds.length > 0) {
                    this.templateFormDetail.steptwo.divisions[divisionIndex].rounds[0].no_of_teams = this.divisionData.no_of_teams;
                }
                this.displayTeams();
            },
            displayTeams() {
                var i;
                var oldDivisionTeamData = _.cloneDeep(this.divisionData.teams);
                this.divisionData.teams = [];
                for (i = 0; i < this.divisionData.no_of_teams; i++) {
                    if(_.has(oldDivisionTeamData, i)) {
                        this.divisionData.teams.push({position_type: oldDivisionTeamData[i].position_type, group: oldDivisionTeamData[i].group, position: oldDivisionTeamData[i].position});
                        continue;
                    }
                    
                    this.divisionData.teams.push({position_type: 'placed', group: '', position: ''});
                }
            },
            onPositionTypeChange(teamIndex) {
                this.divisionData.teams[teamIndex].group = '';
                this.divisionData.teams[teamIndex].position = '';
            },
            getPositionTypes() {
                let positionTypes = [];

                positionTypes.push({'key': 'placed', 'value': 'Placed'});
                positionTypes.push({'key': 'winner', 'value': 'Winner'});
                positionTypes.push({'key': 'loser', 'value': 'Loser'});

                return positionTypes;
            },
            onGroupChange(teamIndex) {
                this.divisionData.teams[teamIndex].position = '';
            },
            getGroupsForSelection(teamIndex) {
                let team = this.divisionData.teams[teamIndex];
                let groupsForSelection = [];
                let roundRobinIndex = 0;
                let placingMatchIndex = 0;
                let roundGroupCount = 0;
                let placingGroupCount = 0;
                let vm = this;
                _.forEach(this.templateFormDetail['steptwo'].rounds, function(round, roundIndex) {
                    _.forEach(round.groups, function(group, groupIndex) {
                        if(group.type === 'round_robin' && team.position_type === 'placed') {
                            groupsForSelection[roundRobinIndex] = {'name': 'Group ' + String.fromCharCode(65 + roundGroupCount), 'value': '-1,' + roundIndex + ',' + groupIndex};
                            roundGroupCount += 1;

                            if(roundRobinIndex === 0 && (team.group === '' || typeof team.group === 'undefined'))
                                vm.divisionData.teams[teamIndex].group = groupsForSelection[roundRobinIndex].value;

                            roundRobinIndex++;

                            return true;
                        }

                        if(group.type === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
                            placingGroupCount += 1;
                            groupsForSelection[placingMatchIndex] = {'name': 'PM ' + (placingGroupCount), 'value': '-1,' + roundIndex + ',' + groupIndex};

                            if(placingMatchIndex === 0 && (team.group === '' || typeof team.group === 'undefined')) {
                                vm.divisionData.teams[teamIndex].group = groupsForSelection[placingMatchIndex].value;
                            }

                            placingMatchIndex++;

                            return true;
                        }
                    });
                });
                return groupsForSelection;
            },
            getPositionsForSelection(teamIndex, group) {
                let vm = this;
                var positionsForSelection = [];

                let team = this.divisionData.teams[teamIndex];
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
                            if(this.divisionData.teams[teamIndex].position === '' || typeof this.divisionData.teams[teamIndex].position === 'undefined') {
                                this.divisionData.teams[teamIndex].position = group + ',0';
                            }
                            _.forEach(teams, function(groupTeam, index) {
                                positionsForSelection.push({'name': vm.getSuffixForPosition(index + 1), 'value': group + ',' + index});
                            });
                        }

                        // for placing
                        if(groupType === 'placing_match' && _.indexOf(['winner', 'loser'], team.position_type) > -1) {
                            let matches = numberOfTeams / 2;
                            if(this.divisionData.teams[teamIndex].position === '' || typeof this.divisionData.teams[teamIndex].position === 'undefined') {
                                this.divisionData.teams[teamIndex].position = group + ',0';
                            }
                            for (var i = 1; i <= matches; i++) {
                                positionsForSelection.push({'name': 'Match' + i, 'value': group + ',' + (i - 1)});
                            }
                        }
                    }

                    return positionsForSelection;
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
            addNewDivisionRound(index) {
                this.templateFormDetail.steptwo.divisions[index].rounds.push({no_of_teams: "", groups: [], start_round_group_count: this.templateFormDetail.steptwo.round_group_count, start_placing_group_count: this.templateFormDetail.steptwo.placing_group_count});
                if(this.templateFormDetail.steptwo.divisions[index].rounds.length === 1) {
                    this.templateFormDetail.steptwo.divisions[index].rounds[0].no_of_teams = this.divisionData.no_of_teams;
                }
                this.$root.$emit('updateRoundCount');
            },
            isSeletedRoundTeamsIsInUse(roundIndex) {
                let isMatched = false;
                let totalGroupTeams = 0;
                let round = this.templateFormDetail.steptwo.divisions[this.index].rounds[roundIndex];
                let roundTeams = round.no_of_teams;
                _.forEach(round.groups, function(groupValue) {
                    totalGroupTeams += Number(groupValue.no_of_teams);
                });
                if(roundTeams === totalGroupTeams) {
                    isMatched = true;
                }

                return isMatched;
            },
        },

    };
</script>