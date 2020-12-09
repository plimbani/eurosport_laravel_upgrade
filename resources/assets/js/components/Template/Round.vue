<template>
    <div class="card mb-3">
        <div class="card-block">
            <h6 class="font-weight-bold">{{ getRoundName }} <span v-if="!(index === 0 && divisionIndex === -1)" :class="{'pull-right': true}"><a href="javascript:void(0)" @click="removeRound(index)"><i class="fas fa-trash text-danger"></i></a></span></h6>
            <div class="form-group">
                <label>Number of teams</label>
                <select class="form-control ls-select2" v-model="roundData.no_of_teams" :disabled="isRoundDisabled" @change="onTeamChange()">
                    <option value="">Number of teams</option>
                    <option v-for="n in templateFormDetail.stepone.no_of_teams" v-if="n >= 2" :value="n">{{ n }}</option>
                </select>
                <p class="text-danger" v-if="isSeletedRoundTeamsAndGroupTeamsNotSame">Round teams and group teams count should match.</p>
            </div>
            <div class="form-group" v-if="templateFormDetail.stepone.editor == 'knockout' && isGroupShowAndDisabled">
                <label>Number of groups</label>
                <select class="form-control ls-select2" v-model="templateFormDetail.stepone.no_of_groups" :disabled="isGroupShowAndDisabled">
                    <option value="">Number of groups</option>
                    <option :value="group" v-for="group in groupsToDisplay()">{{ group }}</option>
                </select>
            </div>
            
            <!-- new group component -->
            <group v-for="(group, groupIndex) in roundData.groups" :index="groupIndex" :roundIndex="index" :divisionIndex="divisionIndex" :roundData="roundData" :groupData="group" :templateFormDetail="templateFormDetail"></group>

            <div class="form-group mb-0" v-if="templateFormDetail.stepone.editor != 'knockout'">
                <button type="button" class="btn btn-primary" @click="addNewGroup(index)" :disabled="disabledNewGroupButton(roundData.no_of_teams, index)"><small><i class="jv-icon jv-plus"></i></small> Add a group</button>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import _ from 'lodash';
    import Group from './Group.vue';
    export default {
        props: ['roundData', 'templateFormDetail', 'index', 'divisionIndex', 'isSeletedRoundTeamsAndGroupTeamsNotSame'],
        data() {
            return {
                last_selected_teams: this.roundData.no_of_teams,
            }
        },
        components: {
            Group,
        },
        mounted() {
        },
        created() {
        },
        beforeCreate: function() {
        },
        computed: {
            getRoundName() {
                let vm = this;

                if(this.divisionIndex === -1) {
                    return 'Round ' + (this.templateFormDetail.steptwo.start_round_count + this.index + 1);
                }

                return 'Round ' + (this.templateFormDetail.steptwo.divisions[this.divisionIndex].start_round_count + this.index + 1);
            },
            isRoundDisabled() {
                return (this.index === 0);
            },
            isGroupShowAndDisabled() {
                return (this.index === 0);
            },
        },
        methods: {
            addNewGroup(index) {
                this.roundData.groups.push({type: "round_robin", no_of_teams: "2", teams_play_each_other: "once", teams: [], matches: []});
                this.$root.$emit('updateGroupCount');
            },
            removeRound(index) {
                if(this.divisionIndex === -1) {
                    this.templateFormDetail.steptwo.rounds.splice(index, 1);
                } else {
                    this.templateFormDetail.steptwo.divisions[this.divisionIndex].rounds.splice(index, 1);
                }
                this.$root.$emit('updateGroupCount');
                this.$root.$emit('updateRoundCount');
                this.$root.$emit('updatePositions');
            },
            removeGroup(groupIndex, roundIndex) {
                this.roundData.groups.splice(groupIndex, 1);
            },
            disabledNewGroupButton(teamsInRound, roundIndex) {
                var groupTotalTeams = this.getGroupTotalTeams(roundIndex);
                if(groupTotalTeams >= teamsInRound) {
                    return true;
                }
                return false;
            },
            getGroupTotalTeams(roundIndex) {
                let groupTeams = this.roundData.groups;
                let totalGroupTeams = 0;
                let placingMatchIndex = _.findIndex(this.roundData.groups, {'type': 'placing_match'});
                $(groupTeams).each(function( index, group ) {
                    if(roundIndex === 0 && group.type === 'placing_match' && index !== placingMatchIndex) {
                        return true;
                    }
                    totalGroupTeams += parseInt(group.no_of_teams);
                });

                return totalGroupTeams;
            },
            onTeamChange() {
                if(this.roundData.no_of_teams > this.templateFormDetail.steptwo.rounds[0].no_of_teams) {
                    toastr['error']('Round team count get exceeds.', 'Error');
                    this.roundData.no_of_teams = this.last_selected_teams;
                    return false;
                }
                this.last_selected_teams = this.roundData.no_of_teams;
            },
            groupsToDisplay() {
                var totalGroups = [];
                for (var n = 1; n <= Math.floor((this.templateFormDetail.stepone.no_of_teams/3)); n++) {
                    totalGroups.push(n);
                }
                return totalGroups;
            },
        }
    }
</script>