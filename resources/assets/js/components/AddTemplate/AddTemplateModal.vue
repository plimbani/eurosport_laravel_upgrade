<template>
	<div class="modal fade bg-modal-color refdel" id="add_new_template_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog add-newtemplate-modal">
        <div class="modal-content border-0 rounded-0">
            <div class="modal-header">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="modal-title" id="addNewTemplateModal">{{$lang.add_template_modal_header}}</h4>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <!-- Step 1 -->
                <step-one v-show="currentStep === 1" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-one>

                <!-- Step 2 -->
                <step-two v-show="currentStep === 2" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-two>

                <!-- Step 3 -->
                <step-three v-show="currentStep === 3" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-three>
                
                <!-- Step 4 -->
                <step-four v-show="currentStep === 4" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-four>
            </div>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    import _ from 'lodash';
    import StepOne from './StepOne.vue'
    import StepTwo from './StepTwo.vue'
    import StepThree from './StepThree.vue'
    import StepFour from './StepFour.vue'

	export default {
		data() {
		    return {
                currentStep: 1,
                templateFormDetail: {
                    stepone: {
                        templateName: '',
                        teams: '',
                        editor: 'advance_editor',
                    },
                    steptwo: {
                        rounds: [{
                            no_of_teams: '',
                            groups: [{
                                type: "round_robin",
                                no_of_teams: "2",
                                teams_play_each_other: "once",
                                teams: [{groups: [], position_type: 'placed', position: []}]
                            }],
                            startRoundGroupCount: 0,
                            startPlacingGroupCount: 0,
                        }],
                        divisions: [],
                        round_group_count: 1,
                        placing_group_count: 0,
                    },
                    stepthree: {
                        placings: [{groups: [], position_type: 'placed', position: []}],
                    },
                }
		    }
		},
        components: {
          StepOne, StepTwo, StepThree, StepFour
        },
		mounted() {
		},
		methods: {
            changeTabIndex(from, to, key, data) {
                this.templateFormDetail[key] = _.cloneDeep(data);
                this.currentStep = to;
                this.templateFormDetail.steptwo.rounds[0].no_of_teams = this.templateFormDetail.stepone.teams;
                this.$root.$emit('updateTemplateData', _.cloneDeep(this.templateFormDetail));
            }
		}
	}
</script>