<template>
	<div class="modal fade bg-modal-color refdel" id="add_new_template_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog add-newtemplate-modal">
        <div class="modal-content border-0 rounded-0">
            <div class="modal-header">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="modal-title" id="addNewTemplateModal">{{$lang.add_template_modal_header}}</h4>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="close" aria-label="Close" @click="closeModal()">
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
                <step-four v-if="currentStep === 4" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-four>
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
                templateFormDetail: _.cloneDeep(this.intialState()),
		    }
		},
        components: {
          StepOne, StepTwo, StepThree, StepFour
        },
        inject: ['$validator'],
        created() {
            this.$root.$on('clearFormFields', this.clearFormFields);
        },
        beforeCreate: function() {
            this.$root.$off('clearFormFields');
        },
		mounted() {
		},
		methods: {
            intialState() {
                return {
                    stepone: {
                        templateName: '',
                        no_of_teams: '',
                        editor: 'advance',
                    },
                    steptwo: {
                        rounds: [{
                            no_of_teams: '',
                            groups: [{
                                type: "round_robin",
                                no_of_teams: 2,
                                teams_play_each_other: "once",
                                teams: [{position_type: 'placed', group: '', position: ''}],
                                matches: [{in_between: '1-2'}],
                            }],
                            start_round_group_count: 0,
                            start_placing_group_count: 0,
                        }],
                        divisions: [],
                        round_group_count: 1,
                        placing_group_count: 0,
                        start_round_count: 0,
                        round_count: 1,
                    },
                    stepthree: {
                        placings: [{position_type: 'placed', group: '', position: ''}]
                    },
                    stepfour: {
                        remarks: '',
                        template_font_color: '',
                        roundSchedules: [''],
                        graphic_image: '',
                        image:'',
                        imagePath :''
                    }
                }
            },
            changeTabIndex(from, to, key, data) {
                this.templateFormDetail[key] = _.cloneDeep(data);
                this.currentStep = to;
                this.templateFormDetail.steptwo.rounds[0].no_of_teams = this.templateFormDetail.stepone.no_of_teams;
            },
            closeModal() {
                let vm = this;
                $('#add_new_template_modal').modal('hide');
                $('#add_new_template_modal').on('hidden.bs.modal', function () {
                    vm.$emit('addTemplateModalHidden');
                });
            },
            clearFormFields() {
                let vm = this;
                this.templateFormDetail = _.cloneDeep(this.intialState());
                this.changeTabIndex(this.currentStep, 1, 'stepone', this.templateFormDetail);
                this.errors.clear();
            }
		}
	}
</script>