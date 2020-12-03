<template>
    <div class="tab-content">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-lg-12" v-if="showEditForm">
                        <step-one v-show="currentStep === 1" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-one>

                        <!-- Step 2 -->
                        <step-two v-show="currentStep === 2" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-two>

                        <!-- Step 3 -->
                        <step-three v-show="currentStep === 3" :templateFormDetail="templateFormDetail" @change-tab-index="changeTabIndex"></step-three>
                
                        <!-- Step 4 -->
                        <step-four v-show="currentStep === 4" :templateFormDetail="templateFormDetail" :editedTemplateId="editedTemplateId" @change-tab-index="changeTabIndex"></step-four>
                    </div>
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
    import Template from '../../api/template.js'

	export default {
		data() {
		    return {
                currentStep: 1,
                templateFormDetail: '',
                editedTemplateId: '',
                editTournamentDetail:'',
                showEditForm:false,
		    }
		},
        created() {
            this.$root.$on('clearFormFields', this.clearFormFields);
        },
        beforeCreate: function() {
            this.$root.$off('clearFormFields');
        },
        components: {
            StepOne, StepTwo, StepThree, StepFour
        },
        mounted() {
            this.editTemplate();
        },
		methods: {
            changeTabIndex(from, to, key, data) {
                window.scrollTo(0,0);
                this.templateFormDetail[key] = _.cloneDeep(data);
                this.currentStep = to;
                this.templateFormDetail.steptwo.rounds[0].no_of_teams = this.templateFormDetail.stepone.no_of_teams;
            },
            clearFormFields() {
                this.errors.clear();
            },
            editTemplate() {
            this.editedTemplateId = this.$route.params.id
                Template.editTemplate(this.editedTemplateId).then(
                  (response)=> {
                    this.editTournamentDetail = response.data.data;
                    this.templateFormDetail =  _.cloneDeep(JSON.parse(this.editTournamentDetail.template_form_detail));
                    this.templateFormDetail.stepone.old_no_of_groups = this.templateFormDetail.stepone.no_of_groups;
                    this.showEditForm = true;
                    if(response.data.isTemplateInUse === true) {
                        this.templateFormDetail.stepone.templateName = '';
                    }
                  },
                  (error)=> {
                  }
                )
            },

		}
	}
</script>