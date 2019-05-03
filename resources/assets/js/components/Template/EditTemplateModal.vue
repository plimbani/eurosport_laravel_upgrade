<template>
	<div class="modal fade bg-modal-color refdel" id="edit_template_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog add-newtemplate-modal">
        <div class="modal-content border-0 rounded-0">
            <div class="modal-header">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="modal-title" id="editTemplateModal">{{$lang.add_template_modal_header}}</h4>
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
                <step-four v-show="currentStep === 4" :templateFormDetail="templateFormDetail" :editedTemplateId="editedTemplateId" @change-tab-index="changeTabIndex" :templateGraphicImage="templateGraphicImage"></step-four>
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
        props: ['editTemplateDetail'],
		data() {
		    return {
                currentStep: 1,
                templateFormDetail: JSON.parse(this.editTemplateDetail.template_form_detail),
                editedTemplateId: this.editTemplateDetail.id,
                templateGraphicImage: this.editTemplateDetail.graphic_image,
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
                this.templateFormDetail.steptwo.rounds[0].no_of_teams = this.templateFormDetail.stepone.no_of_teams;
            },
            closeModal() {
                $('#edit_template_modal').modal('hide');
                // this.changeTabIndex(this.currentStep, 1, 'stepone', this.templateFormDetail);
                return;
            }
		}
	}
</script>