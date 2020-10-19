<template>  
    <div>
        <div id="step2-template-setting">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h5 v-if="templateFormDetail.stepone.editor === 'knockout'">Step 2: Setup round 1</h5>
                    <h5 v-else>Step 2: Setup rounds</h5>
                    <div v-for="(round, roundIndex) in templateFormDetail.steptwo.rounds">
                        <round  :index="roundIndex" :divisionIndex="-1" :roundData="round" :templateFormDetail="templateFormDetail" :startGroupCount="getPreviousRoundGroupCount(roundIndex-1)" :isSeletedRoundTeamsAndGroupTeamsNotSame="!isSeletedRoundTeamsIsInUse(roundIndex)"></round>
                    </div>
                    <division v-for="(division, divisionIndex) in templateFormDetail.steptwo.divisions" :index="divisionIndex" :divisionData="division" :templateFormDetail="templateFormDetail"></division>
                    
                    <div class="form-group">
                        <button type="button" class="btn btn-success" @click="addNewRound()" :disabled="templateFormDetail.steptwo.divisions.length > 0"><small><i class="jv-icon jv-plus"></i></small> &nbsp;Add a round</button>
                        <button type="button" class="btn btn-success" @click="addNewDivision()" :disabled="templateFormDetail.steptwo.rounds.length === 0"><small><i class="jv-icon jv-plus"></i></small> &nbsp;Add a division</button>
                        <span class="info-editor text-primary" data-toggle="popover" data-animation="false" data-placement="right" :data-popover-content="'#editor_detail'"><i class="fa fa-info-circle"></i></span>
                        <div v-bind:id="'editor_detail'" style="display:none;">
                            <div class="popover-body">After a round you have the option to split the teams into seperate divisions. Teams in different divisions will not play again each other again.</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" @click="back()">{{ $lang.add_template_modal_back_button }}</button>
                        <button type="button" class="btn btn-primary" @click="next()" :disabled="isDisabled">{{ $lang.add_template_modal_next_button }}</button>
                    </div>
                </div>
            </div>                  
        </div>
    </div>
</template>
<script type="text/javascript">
    import Round from './Round.vue';
    import Division from './Division.vue';
    export default {
        props: ['templateFormDetail'],
        data() {
            return {
                teamsCheckError: [],
                teamsPlayEachOther: {
                    'once': 1,
                    'twice': 2,
                    'three_times': 3,
                    'four_times': 4,
                },
            }
        },
        components: {
            Round,
            Division,
        },
        mounted() {
        },
        created() {
            this.$root.$on('updateGroupCount', this.updateGroupCount);
            this.$root.$on('updateRoundCount', this.updateRoundCount);            
        },
        beforeCreate: function() {
            // Remove custom event listener 
            this.$root.$off('updateGroupCount');
            this.$root.$off('updateRoundCount');
        },
        computed: {
            isDisabled() {
                let vm = this;
                let rounds = this.templateFormDetail.steptwo.rounds;
                let isMatched = this.checkForTeamCount(rounds, -1);

                if(!isMatched) {
                    _.forEach(vm.templateFormDetail.steptwo.divisions, function(division, divisionIndex) {
                        isMatched = vm.checkForTeamCount(division.rounds, divisionIndex);
                        if(isMatched) {
                            return false;
                        }
                    });
                }

                return isMatched;
            }
        },
        methods: {
            addNewRound() {
                this.templateFormDetail.steptwo.rounds.push({no_of_teams: "", groups: [], start_round_group_count: this.templateFormDetail.steptwo.round_group_count, start_placing_group_count: this.templateFormDetail.steptwo.placing_group_count});
                this.updateRoundCount();
            },
            removeGroup(groupIndex, roundIndex) {
                this.templateFormDetail.steptwo.rounds[roundIndex].groups.splice(groupIndex, 1);
            },
            addNewDivision() {
                this.templateFormDetail.steptwo.divisions.push({no_of_teams: "", teams: [], rounds: [], start_round_count: this.templateFormDetail.steptwo.round_count});
            },
            next() {
                this.$emit('change-tab-index', 2, 3, 'steptwo', _.cloneDeep(this.templateFormDetail.steptwo));
            },
            getPreviousRoundGroupCount(previousRoundIndex) {
                return 0;
                // return this.templateFormDetail.steptwo.rounds[previousRoundIndex].groups.length;
            },
            updateGroupCount() {
                let startRoundGroupCount = 0;
                let startPlacingGroupCount = 0;
                let vm = this;
                _.forEach(vm.templateFormDetail.steptwo.rounds, function(round, index) {
                    vm.templateFormDetail.steptwo.rounds[index].start_round_group_count =  startRoundGroupCount;
                    vm.templateFormDetail.steptwo.rounds[index].start_placing_group_count =  startPlacingGroupCount;
                    startRoundGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'round_robin'; }).length;
                    startPlacingGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'placing_match'; }).length;
                });
                let groupMatchesCount = [];
                _.forEach(vm.templateFormDetail.steptwo.divisions, function(division, divisionIndex) {
                    _.forEach(division.rounds, function(round, index) {
                        if(!(index in groupMatchesCount)) {
                            groupMatchesCount[index] = 0;
                        }
                        vm.templateFormDetail.steptwo.divisions[divisionIndex].rounds[index].start_round_group_count = startRoundGroupCount;
                        vm.templateFormDetail.steptwo.divisions[divisionIndex].rounds[index].start_placing_group_count = startPlacingGroupCount;
                        startRoundGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'round_robin'; }).length;
                        startPlacingGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'placing_match'; }).length;
                        _.forEach(round.groups, function(group, groupIndex) {
                            if(group.type === 'round_robin') {
                                vm.templateFormDetail.steptwo.divisions[divisionIndex].rounds[index].groups[groupIndex].start_match_count = 0;
                                // groupMatchesCount[index] += (group.teams.length - 1) * (group.teams.length/2) * vm.teamsPlayEachOther[group.teams_play_each_other];
                            }
                            if(group.type === 'placing_match') {
                                vm.templateFormDetail.steptwo.divisions[divisionIndex].rounds[index].groups[groupIndex].start_match_count = groupMatchesCount[index];
                                groupMatchesCount[index] += group.teams.length/2;
                            }
                        });
                    });
                });
                this.templateFormDetail.steptwo.round_group_count = startRoundGroupCount;
                this.templateFormDetail.steptwo.placing_group_count = startPlacingGroupCount;
            },
            back() {
                this.$emit('change-tab-index', 2, 1, 'steptwo', _.cloneDeep(this.templateFormDetail.steptwo));
            },
            updateRoundCount() {
                let vm = this;
                let startRoundCount = 0;
                this.templateFormDetail.steptwo.start_round_count = startRoundCount;
                startRoundCount = this.templateFormDetail.steptwo.rounds.length;
                _.forEach(vm.templateFormDetail.steptwo.divisions, function(division, divisionIndex) {
                    vm.templateFormDetail.steptwo.divisions[divisionIndex].start_round_count = startRoundCount;
                    // startRoundCount +=  vm.templateFormDetail.steptwo.divisions[divisionIndex].rounds.length;
                });
                this.templateFormDetail.steptwo.round_count = startRoundCount;
            },
            isSeletedRoundTeamsIsInUse(roundIndex) {
                let isMatched = false;
                let totalGroupTeams = 0;
                let round = this.templateFormDetail.steptwo.rounds[roundIndex];
                let roundTeams = round.no_of_teams;
                let isPlacingMatchRecorded = false;
                _.forEach(round.groups, function(group) {
                    if(isPlacingMatchRecorded && roundIndex === 0 && group.type === 'placing_match') {
                        return true;
                    }
                    totalGroupTeams += Number(group.no_of_teams);
                    if(group.type === 'placing_match' && roundIndex === 0) {
                        isPlacingMatchRecorded = true;
                    }
                });
                if(roundTeams === totalGroupTeams) {
                    isMatched = true;
                }

                return isMatched;
            },
            checkForTeamCount(rounds, divisionIndex) {
                let isMatched = false;
                _.forEach(rounds, function(round, roundIndex) {
                    let totalGroupTeams = 0;
                    let isPlacingMatchRecorded = false;
                    _.forEach(round.groups, function(group) {
                        if(isPlacingMatchRecorded && roundIndex === 0 && divisionIndex === -1 && group.type === 'placing_match') {
                            return true;
                        }
                        totalGroupTeams += Number(group.no_of_teams);
                        if(group.type === 'placing_match' && roundIndex === 0 && divisionIndex === -1) {
                            isPlacingMatchRecorded = true;
                        }
                    });
                    if(round.no_of_teams !== totalGroupTeams) {
                        isMatched = true;
                        return false;
                    }
                });
                return isMatched;
            },
        },
    }
</script>