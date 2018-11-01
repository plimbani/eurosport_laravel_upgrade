<template>
    <div class="rounds bordered-box">
        <h6 class="font-weight-bold">Round {{ index + 1 }} <span :class="{'pull-right': true, 'is-disabled': (index === 0 && divisionIndex === -1)}"><a href="javascript:void(0)" @click="removeRound(index)"><i class="fa fa-trash"></i></a></span></h6>
        <div class="form-group">
            <label>Number of teams in round</label>
            <select class="form-control ls-select2" v-model="roundData.no_of_teams" :disabled="index === 0 && divisionIndex === -1">
                <option value="">Number of teams</option>
                <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
            </select>
        </div>
        
        <!-- add new group component -->
        <group v-for="(group, groupIndex) in roundData.groups" :index="groupIndex" :roundIndex="index" :data="group"></group>

        <div class="form-group mb-0">
            <button type="button" class="btn btn-default" @click="addNewGroup(index)" :disabled="disabledNewGroupButton(roundData.no_of_teams, index)">Add a group</button>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Group from './Group.vue';
    export default {
        props: ['roundData', 'templateFormDetail', 'index', 'divisionIndex'],
        data() {
            return {
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
        methods: {
            addNewGroup(index) {
                this.roundData.groups.push({type: "round_robin", no_of_teams: "2", teams_play_each_other: "once"});
            },
            removeRound(index) {
                this.templateFormDetail.steptwo.rounds.splice(index, 1);
            },
            removeGroup(groupIndex, roundIndex) {
                this.roundData.groups.splice(groupIndex, 1);
            },
            disabledTeamSelection(roundIndex) {
                let groupTotalTeams = this.getGroupTotalTeams(roundIndex);
                if(groupTotalTeams > this.roundData.no_of_teams) {
                    toastr['error']('Group teams should not be greater than round teams.', 'Error');
                    return true;
                }
                return false;
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
            }
        }
    }
</script>