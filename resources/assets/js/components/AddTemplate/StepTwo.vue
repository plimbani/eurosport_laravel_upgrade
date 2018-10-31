<template>
    <div>
        <div id="step2-template-setting">
            <div class="row">
                <div class="col-md-4">
                    <h5>Step 2 : Setup rounds</h5>
                    <div class="rounds bordered-box" v-for="(round, index) in templateFormDetail.steptwo.rounds">
                        <h6 class="font-weight-bold">Round {{index + 1}} <span class="pull-right"><a href="javascript:void(0)" @click="removeRound(index)"><i class="fa fa-trash"></i></a></span></h6>
                        <div class="form-group">
                            <label>Number of teams in round</label>
                            <select class="form-control ls-select2" v-model="round.no_of_teams" name="teams" id="teams" disabled="disabled">
                                <option value="">Number of teams</option>
                                <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                            </select>
                        </div>
                        
                        <!-- add new group component -->
                        <group v-for="(group, index) in groups" :index="index" :groups="groups"></group>

                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-default" @click="addNewGroup()">Add a group</button>
                        </div>
                    </div>                    
                    
                    <div class="form-group">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" @click="addNewRound">Add a round</button>
                            <button type="button" class="btn btn-default">Add a divison</button>
                        </div>
                        <span class="info-editor text-primary" data-toggle="popover" data-animation="false" data-placement="right" :data-popover-content="'#editor_detail'"><i class="fa fa-info-circle"></i></span>
                        <div v-bind:id="'editor_detail'" style="display:none;">
                            <div class="popover-body">Division description</div>
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
        data() {
            return {
                templateFormDetail: {
                    stepone: {
                        templateName: '',
                        teams: '',
                        editor: '',
                    },
                    steptwo: {
                        rounds: [{
                            no_of_teams: ''
                        }],
                    },
                },
                groups: [{
                    group_type: "",
                    no_of_teams_in_groups: "",
                    teams_play_eachother: ""
                }],
            }
        },
        components: {
            Group
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
                this.templateFormDetail.steptwo.rounds.push({no_of_teams: ""});
            },
            addNewGroup() {
                this.groups.push({group_type: "", no_of_teams_in_groups: "", teams_play_eachother: ""});
            },
            removeRound(index) {
                this.templateFormDetail.steptwo.rounds.splice(index, 1);
            },
            updateTemplateData(data) {
                this.templateFormDetail = data;
            },
        }
    }
</script>