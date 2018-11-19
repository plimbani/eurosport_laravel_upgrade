<template>  
    <div>
        <div id="step2-template-setting">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-7">
                    <h5>Step 2 : Setup rounds</h5>
                    <round v-for="(round, roundIndex) in templateFormDetail.steptwo.rounds" :index="roundIndex" :divisionIndex="-1" :roundData="round" :templateFormDetail="templateFormDetail" :startGroupCount="getPreviousRoundGroupCount(roundIndex-1)"></round>

                    <div class="rounds bordered-box" v-for="(division, divisionIndex) in templateFormDetail.steptwo.divisions">
                        <h6 class="font-weight-bold">Division {{ divisionIndex + 1 }} <span class="pull-right"><a href="javascript:void(0)" @click="removeDivision(divisionIndex)"><i class="jv-icon jv-dustbin"></i></a></span></h6>
                        <div class="form-group">
                            <label>Number of teams in division</label>
                            <select class="form-control ls-select2" v-model="division.no_of_teams">
                                <option value="">Number of teams</option>
                                <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                            </select>
                        </div>
                        
                        <!-- add new round component -->
                        <round v-for="(round, roundIndex) in division.rounds" :index="roundIndex" :divisionIndex="divisionIndex" :roundData="round" :templateFormDetail="templateFormDetail"></round>

                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-success" @click="addNewDivisionRound(divisionIndex)"><small><i class="jv-icon jv-plus"></i></small> &nbsp;Add a round</button>
                        </div>
                    </div>
                    
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
                        <button type="button" class="btn btn-primary" @click="next()">{{ $lang.add_template_modal_step1_button }}</button>
                    </div>
                </div>
            </div>                  
        </div>
    </div>
</template>
<script type="text/javascript">
    import Round from './Round.vue';
    export default {
        props: ['templateFormDetail'],
        data() {
            return {
            }
        },
        components: {
            Round,
        },
        mounted() {
        },
        created() {
            this.$root.$on('updateTemplateData', this.updateTemplateData);
            this.$root.$on('updateGroupCount', this.updateGroupCount);
        },
        beforeCreate: function() {
            // Remove custom event listener 
            this.$root.$off('updateTemplateData');
            this.$root.$off('updateGroupCount');
        },
        computed: {

        },
        methods: {
            addNewRound() {
                this.templateFormDetail.steptwo.rounds.push({no_of_teams: "", groups: [], startRoundGroupCount: this.templateFormDetail.round_group_count, startPlacingGroupCount: this.templateFormDetail.placing_group_count});
            },
            addNewDivisionRound(index) {
                this.templateFormDetail.steptwo.divisions[index].rounds.push({no_of_teams: "", groups: []});
            },
            updateTemplateData(data) {
                // this.templateFormDetail = data;
            },
            removeGroup(groupIndex, roundIndex) {
                this.templateFormDetail.steptwo.rounds[roundIndex].groups.splice(groupIndex, 1);
            },
            removeDivision(index) {
                this.templateFormDetail.steptwo.divisions.splice(index, 1);
            },
            addNewDivision() {
                this.templateFormDetail.steptwo.divisions.push({no_of_teams: "", teams: [], rounds: []});
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
                    vm.templateFormDetail.steptwo.rounds[index].startRoundGroupCount =  startRoundGroupCount;
                    vm.templateFormDetail.steptwo.rounds[index].startPlacingGroupCount =  startPlacingGroupCount;
                    startRoundGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'round_robin'; }).length;
                    startPlacingGroupCount +=  _.filter(round.groups, function(o) { return o.type === 'placing_match'; }).length;
                });
                this.templateFormDetail.round_group_count = startRoundGroupCount;
                this.templateFormDetail.placing_group_count = startPlacingGroupCount; 
            },
        }
    }
</script>