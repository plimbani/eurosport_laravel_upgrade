<template>
    <div class="tab-content">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-lg-12">
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
            let vm = this;
		},
		methods: {
            intialState() {
                return {
                    stepone: {
                        templateName: '',
                        no_of_teams: '',
                        no_of_groups: '',
                        old_no_of_groups: '',
                        no_of_teams_in_round_two: '',
                        editor: 'advance',
                        remarks: '',
                        template_font_color: '',
                        roundSchedules: [''],
                        minimum_match: '',
                    },
                    steptwo: {
                        rounds: [{
                            no_of_teams: '',
                            groups: [{
                                type: "round_robin",
                                no_of_teams: 2,
                                teams_play_each_other: "once",
                                teams: [{position_type: 'placed', group: '', position: ''}],
                                matches: [],
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
                        placings: []
                    },
                    stepfour: {
                        round_two_knockout_teams: {}
                    }
                }
            },
            changeTabIndex(from, to, key, data) {
                window.scrollTo(0,0);
                this.templateFormDetail[key] = _.cloneDeep(data);
                this.currentStep = to;
                this.templateFormDetail.steptwo.rounds[0].no_of_teams = this.templateFormDetail.stepone.no_of_teams;
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