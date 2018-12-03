<template>
    <div class="card mb-3">
        <div class="card-block">
            <h6 class="font-weight-bold">{{ getRoundName }} <span :class="{'pull-right': true, 'is-disabled': (index === 0 && divisionIndex === -1)}"><a href="javascript:void(0)" @click="removeRound(index)"><i class="jv-icon jv-dustbin"></i></a></span></h6>
            <div class="form-group">
                <label>Number of teams in round</label>
                <select class="form-control ls-select2" v-model="roundData.no_of_teams" :disabled="index === 0 && divisionIndex === -1" @change="onTeamChange()">
                    <option value="">Number of teams</option>
                    <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                </select>
            </div>
            
            <!-- new group component -->
            <group v-for="(group, groupIndex) in roundData.groups" :index="groupIndex" :roundIndex="index" :roundData="roundData" :groupData="group" :templateFormDetail="templateFormDetail"></group>

            <div class="form-group mb-0">
                <button type="button" class="btn btn-primary" @click="addNewGroup(index)" :disabled="disabledNewGroupButton(roundData.no_of_teams, index)"><small><i class="jv-icon jv-plus"></i></small> Add a group</button>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Group from './Group.vue';
    export default {
        props: ['roundData', 'templateFormDetail', 'index', 'divisionIndex'],
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
        },
        methods: {
            addNewGroup(index) {
                this.roundData.groups.push({type: "round_robin", no_of_teams: "2", teams_play_each_other: "once", teams: []});
                this.$root.$emit('updateGroupCount');
            },
            removeRound(index) {
                this.templateFormDetail.steptwo.rounds.splice(index, 1);
                this.$root.$emit('updateGroupCount');
                this.$root.$emit('updateRoundCount');
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
                $(groupTeams).each(function( index, element ) {
                    totalGroupTeams += parseInt(element.no_of_teams);
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
        }
    }
</script>