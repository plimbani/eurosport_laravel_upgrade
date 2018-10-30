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
                <new-template-step-one v-show="currentStep === 1" @change-tab-index="changeTabIndex"></new-template-step-one>

                <!-- Step 2 -->
                <new-template-step-two v-show="currentStep === 2" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></new-template-step-two>
            </div>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    import NewTemplateStepOne from './NewTemplateStepOne.vue'
    import NewTemplateStepTwo from './NewTemplateStepTwo.vue'

	export default {
		data() {
		    return {
                currentStep: 1,
                templateFormDetail: {}
		    }
		},
        components: {
          NewTemplateStepOne, NewTemplateStepTwo
        },
		mounted() {
		},
		methods: {
            changeTabIndex(from, to, data) {
                this.templateFormDetail = data;
                this.currentStep = to;
            }
		}
	}
</script>