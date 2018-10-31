<template>
	<div class="modal fade bg-modal-color refdel" id="add_new_template_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog add-newtemplate-modal">
        <div class="modal-content border-0 rounded-0">
            <div class="modal-header">
                <h4 class="modal-title" id="addNewTemplateModal">{{$lang.add_template_modal_header}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Step 1 -->
                <step-one v-show="currentStep === 1" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-one>

                <!-- Step 2 -->
                <step-two v-show="currentStep === 2" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-two>
            </div>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    import _ from 'lodash';
    import StepOne from './StepOne.vue'
    import StepTwo from './StepTwo.vue'

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
                        }],
                        divisions: [{}],
                    },
                    stepthree: {
                        placings: [],
                    },
                }
		    }
		},
        components: {
          StepOne, StepTwo
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