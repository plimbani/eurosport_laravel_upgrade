<template>
    <div>
        <div id="step2-template-setting">
            <div class="row">
                <div class="col-md-7 col-lg-5">
                    <h5>Step 2 : Setup rounds</h5>
                    <div class="rounds bordered-box" v-for="(round, roundIndex) in templateFormDetail.steptwo.rounds">
                        <h6 class="font-weight-bold">Round {{ roundIndex + 1 }} <span :class="{'pull-right': true, 'is-disabled': roundIndex == 0}"><a href="javascript:void(0)" @click="removeRound(roundIndex)"><i class="fa fa-trash"></i></a></span></h6>
                        <div class="form-group">
                            <label>Number of teams in round</label>
                            <select class="form-control ls-select2" v-model="round.no_of_teams" name="teams" id="teams" :disabled="roundIndex == 0">
                                <option value="">Number of teams</option>
                                <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                            </select>
                        </div>
                        
                        <!-- add new group component -->
                        <group v-for="(group, groupIndex) in round.groups" :index="groupIndex" :roundIndex="roundIndex" :data="group"></group>

                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-default" @click="addNewGroup(roundIndex)" :disabled="disabled(round.no_of_teams, roundIndex)">Add a group</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" @click="addNewRound()">Add a round</button>
                            <button type="button" class="btn btn-default" @click="addNewDivision()" :disabled="templateFormDetail.steptwo.rounds.length === 0">Add a divison</button>
                        </div>
                        <span class="info-editor text-primary" data-toggle="popover" data-animation="false" data-placement="right" :data-popover-content="'#editor_detail'"><i class="fa fa-info-circle"></i></span>
                        <div v-bind:id="'editor_detail'" style="display:none;">
                            <div class="popover-body">After a round you have the option to split the teams into seperate divisions. Teams in different divisions will not play again each other again.</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" @click="next()">{{$lang.add_template_modal_step1_button}}</button>
                    </div>
                </div>
            </div>                  
        </div>
    </div>
</template>
<script type="text/javascript">
    import Group from './Group.vue'
    export default {
        props: ['templateFormDetail'],
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
            this.$root.$on('updateTemplateData', this.updateTemplateData);
        },
        beforeCreate: function() {
            // Remove custom event listener 
            this.$root.$off('updateTemplateData');
        },
        methods: {
            addNewRound() {
                this.templateFormDetail.steptwo.rounds.push({no_of_teams: "", groups: []});
            },
            addNewGroup(index) {
                this.templateFormDetail.steptwo.rounds[index].groups.push({type: "round_robin", no_of_teams: "2", teams_play_each_other: "once"});
            },
            removeRound(index) {
                this.templateFormDetail.steptwo.rounds.splice(index, 1);
            },
            updateTemplateData(data) {
                // this.templateFormDetail = data;
            },
            removeGroup(groupIndex, roundIndex) {
                this.templateFormDetail.steptwo.rounds[roundIndex].groups.splice(groupIndex, 1);
            },
            addNewDivision() {
                this.templateFormDetail.steptwo.divisions.push({no_of_teams: "", teams: [], rounds: []});
            },
            disabled(teamsInRound, roundIndex) {
                let groupTeams = this.templateFormDetail.steptwo.rounds[roundIndex].groups;
                let totalGroupTeams = 0;

                $(groupTeams).each(function( index, element ) {
                    totalGroupTeams += parseInt(element.no_of_teams);
                });

                if(totalGroupTeams >= teamsInRound) {
                    toastr['error']('Group teams should not be greater than total round teams', 'Error');
                    return true;
                }
                return false;
            },
        }
    }
</script>