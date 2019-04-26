<template>  
    <div>
        <div class="container" id="step2-template-setting">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h5>{{ $lang.add_template_modal_step2_header }}</h5>
                    <div v-for="(round, roundIndex) in templateFormDetail.steptwo.rounds">
                        <round  :index="roundIndex" :divisionIndex="-1" :roundData="round" :templateFormDetail="templateFormDetail" :startGroupCount="getPreviousRoundGroupCount(roundIndex-1)"></round>

                        <p class="text-danger" v-if="!isSeletedRoundTeamsIsInUse(roundIndex)">Round teams and group teams count should match.</p>
                    </div>
                    <division v-for="(division, divisionIndex) in templateFormDetail.steptwo.divisions" :index="divisionIndex" :divisionData="division" :templateFormDetail="templateFormDetail"></division>
                    
                    <div class="form-group">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" @click="addNewRound()" :disabled="templateFormDetail.steptwo.divisions.length > 0"><small><i class="jv-icon jv-plus"></i></small> &nbsp;Add a round</button>
                            <button type="button" class="btn btn-success" @click="addNewDivision()" :disabled="templateFormDetail.steptwo.rounds.length === 0"><small><i class="jv-icon jv-plus"></i></small> &nbsp;Add a divison</button>
                        </div>
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
                let status;
                _.find(this.teamsCheckError, function(o) {
                    if(o == false) {                        
                        return true;
                    } else {
                        return false;
                    }
                });
            }
        },
        methods: {
            addNewRound() {
                this.templateFormDetail.steptwo.rounds.push({no_of_teams: "", groups: [], startRoundGroupCount: this.templateFormDetail.steptwo.round_group_count, startPlacingGroupCount: this.templateFormDetail.steptwo.placing_group_count});
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
                _.forEach(vm.templateFormDetail.steptwo.divisions, function(division, divisionIndex) {
                    _.forEach(division.rounds, function(round, index) {
                        vm.templateFormDetail.steptwo.divisions[divisionIndex].rounds[index].start_round_group_count = startRoundGroupCount;
                        vm.templateFormDetail.steptwo.divisions[divisionIndex].rounds[index].start_placing_group_count = startPlacingGroupCount;
                        startRoundGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'round_robin'; }).length;
                        startPlacingGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'placing_match'; }).length;
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
                _.forEach(round.groups, function(groupValue) {
                    totalGroupTeams += Number(groupValue.no_of_teams);
                    if(roundTeams === totalGroupTeams) {
                        isMatched = true;
                    }
                });

                this.teamsCheckError[roundIndex] = isMatched;
                return isMatched;
            }
        },
    }
</script>