<template>
	<div>
		<div id="step1-template-setting">
            <div class="row justify-content-center">
                <div class="col-md-12">
            		<h5>{{ $lang.add_template_modal_step1_header }}</h5>
            		<div class="form-group" :class="{'has-error': errors.has('template_name') }">
            			<label>{{$lang.add_template_modal_template_name}}</label>
            			<input v-model="templateFormDetail.stepone.templateName" name="template_name" type="text" class="form-control" placeholder="My custom template" data-vv-as="template name" v-validate="'required'" :class="{'is-danger': errors.has('template_name') }">
                        <i v-show="errors.has('template_name')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('template_name')">{{ errors.first('template_name') }}</span>
            		</div>
                    <div class="form-group">
                        <label for="competition_type">Template type*</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <div class="c-input">
                                        <input class="euro-radio" type="radio" name="editor" value="advance" v-model="templateFormDetail.stepone.editor" id="radio_advance">
                                        <label for="radio_advance">Advanced</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <div class="c-input">
                                        <input class="euro-radio" type="radio" name="editor" value="festival" v-model="templateFormDetail.stepone.editor" id="radio_festival">
                                        <label for="radio_festival">Festival</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <div class="c-input">
                                        <input class="euro-radio" type="radio" name="editor" value="knockout" v-model="templateFormDetail.stepone.editor" id="radio_knockout">
                                        <label for="radio_knockout">Knockout</label>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>                   
            		<div class="form-group" :class="{'has-error': errors.has('no_of_teams') }">
            			<label v-if="templateFormDetail.stepone.editor == 'knockout'">{{$lang.add_template_modal_number_of_teams_knockout}}</label>
                        <label v-else>{{$lang.add_template_modal_number_of_teams}}</label>
                        <select class="form-control ls-select2" name="no_of_teams" v-model="templateFormDetail.stepone.no_of_teams" v-validate="'required'" :class="{'is-danger': errors.has('no_of_teams') }" data-vv-as="number of teams">
                            <option value="">Number of teams in group</option>
                            <option :value="team" v-for="team in teamsToDisplay">{{ team }}</option>
                        </select>
                        <i v-show="errors.has('no_of_teams')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('no_of_teams')">{{ errors.first('no_of_teams') }}</span>
            		</div>
                    <div v-if="templateFormDetail.stepone.editor == 'knockout'" class="form-group" :class="{'has-error': errors.has('no_of_groups') }">
                        <label>{{$lang.add_template_modal_number_of_groups}}</label>
                        <select class="form-control ls-select2" name="no_of_groups" v-model="templateFormDetail.stepone.no_of_groups" v-validate="'required'" :class="{'is-danger': errors.has('no_of_groups') }" data-vv-as="number of groups">
                            <option value="">Select number of groups</option>
                            <option :value="group" v-for="group in groupsToDisplay">{{ group }}</option>
                        </select>
                        <i v-show="errors.has('no_of_groups')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('no_of_groups')">{{ errors.first('no_of_groups') }}</span>
                    </div>
                    <div v-if="templateFormDetail.stepone.editor == 'knockout'" class="form-group" :class="{'has-error': errors.has('no_of_teams_in_round_two') }">
                        <label>{{$lang.add_template_modal_teams_in_round_two}}</label>
                        <select class="form-control ls-select2" name="no_of_teams_in_round_two" v-model="templateFormDetail.stepone.no_of_teams_in_round_two" v-validate="'required'" :class="{'is-danger': errors.has('no_of_teams_in_round_two') }" data-vv-as="number of groups">
                            <option value="">Select number of teams</option>
                            <option :value="team" v-for="team in teamsToDisplayInRoundTwo">{{ team }} teams</option>
                        </select>
                        <i v-show="errors.has('no_of_teams_in_round_two')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('no_of_teams_in_round_two')">{{ errors.first('no_of_teams_in_round_two') }}</span>
                    </div>
                    <form>
                        <div v-if="templateFormDetail.stepone.editor != 'knockout'" class="form-group" :class="{'has-error': errors.has('minimum_match') }">
                            <label for="remarks">Tournament minimum matches*</label>
                            <input name="minimum_match" type="text" class="form-control" v-model="templateFormDetail.stepone.minimum_match" placeholder="Minimum match" v-validate="'required|numeric'" :class="{'is-danger': errors.has('minimum_match') }" data-vv-as="minimum matches">
                            <i v-show="errors.has('minimum_match')" class="fa fa-warning"></i>
                            <span class="help is-danger" v-show="errors.has('minimum_match')">{{ errors.first('minimum_match') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input name="remarks" type="text" class="form-control" v-model="templateFormDetail.stepone.remarks" placeholder="Remarks">
                        </div>

                        <div class="form-group">
                            <label for="remarks">Competition format (round schedule)*</label>
                             <span class="ml-1 text-primary" data-toggle="popover" data-animation="false" data-placement="right" data-content="Add an entry for each round e.g. 4 x 4"><i class="fas fa-info-circle"></i></span>
                            <div v-for="(roundSchedule, index) in templateFormDetail.stepone.roundSchedules" class="row">
                                <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4">
                                    <div class="form-group" :class="{'has-error': errors.has('round_schedule'+index) }">
                                        <input :name="'round_schedule'+index" type="text" v-model="templateFormDetail.stepone.roundSchedules[index]" class="form-control" placeholder="Round schedule" :class="{'is-danger': errors.has('round_schedule'+index) }" v-validate="'required'" data-vv-as="round schedule">
                                        <div class="error-block">
                                            <i v-show="errors.has('round_schedule'+index)" class="fas fa-warning"></i>
                                            <span class="help is-danger" v-show="errors.has('round_schedule'+index)">{{ errors.first('round_schedule'+index) }}</span>
                                        </div>                                      
                                    </div>
                                </div>

                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-3 text-left text-sm-center" v-if="templateFormDetail.stepone.roundSchedules.length > 1">
                                    <div class="form-group">
                                        <p class="m-0"><a href="javascript:void(0)" class="text-primary" @click="removeRoundSchedule(index)"><i class="fas fa-trash text-danger"></i></a></p>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" @click="addNewRoundSchedule()"><i class="fa fa-plus"></i> Add round</button>
                            <!-- <a href="javascript:void(0)" class="text-primary" @click="addNewRoundSchedule()"><u>+ Add round</u></a> -->
                        </div>

                        <div class="form-group row">
                            <label class="col-12 form-control-label">Template key* <span class="ml-1 text-primary" data-toggle="popover" data-animation="false" data-placement="right" data-content="Template key: Green = preferred, Orange = second option, Red = last resort"><i class="fas fa-info-circle"></i></span></label>
                            
                            <div class="col-12">
                                <div class="template-font-color-box pull-left mr-2" @click="setTemplateFontColor(color)" v-for="color in templateFontColors" :style="{'background-color': color}" :class="{ 'template-font-color-active' : templateFormDetail.stepone.template_font_color == color }" ></div>
                                <input type="hidden" name="template_font_color" v-model="templateFormDetail.stepone.template_font_color" v-validate="'required'" :class="{'is-danger': errors.has('template_font_color') }" data-vv-as="template key">
                            </div>
                            <div class="col-12">
                                <i v-show="errors.has('template_font_color')" class="fa fa-warning"></i>
                                <span class="help is-danger" v-show="errors.has('template_font_color')">{{ errors.first('template_font_color') }}</span>
                            </div>
                        </div>
                    </form>
            		<div class="form-group">
            			<button type="button" class="btn btn-primary" @click="next()">{{$lang.add_template_modal_next_button}}</button>
            		</div>   
                </div>
            </div>         		
    	</div>
	</div>
</template>
<script type="text/javascript">
    import { ErrorBag } from 'vee-validate';
	export default {
        props: ['templateFormDetail'],
		data() {
		    return {
                templateFontColors: [
                    'rgb(146,208,80)', 'rgb(255,192,0)', 'rgb(217,149,148)'
                ],
		    }
		},
        created() {
        },
        components: {
        },
        inject: ['$validator'],
		mounted() {
            $("[data-toggle=popover]").popover({
                html : false,
                trigger: 'hover',
                content: function() {
                    var content = $(this).attr("data-popover-content");
                    return $(content).children(".popover-body").html();
                },
                title: function() {
                    var title = $(this).attr("data-popover-content");
                    return $(title).children(".popover-heading").html();
                }
            });
		},
        computed: {
            teamsToDisplay() {
                var start = this.templateFormDetail.stepone.editor == 'knockout' ? 8 : 2;
                var end = this.templateFormDetail.stepone.editor == 'knockout' ? 120 : 60;
                var totalTeams = [];
                for (var n = start; n <= end; n++) {
                    totalTeams.push(n);
                }

                return totalTeams;
            },
            groupsToDisplay() {
                var totalGroups = [];
                for (var n = 1; n <= 15; n++) {
                    totalGroups.push(n);
                }
                return totalGroups;
            },
            teamsToDisplayInRoundTwo() {
                var totalTeams = [];
                var result = 0;
                for(var n = 1; n < 7; n++) {
                    result = Math.pow(2, n);
                    if (result >= this.templateFormDetail.stepone.no_of_teams) {
                        break;
                    }
                    totalTeams.push(result);
                }
                return totalTeams;
            },
        },
		methods: {
            next() {
                this.$validator.validateAll().then((response) => {
                    if(response) {
            	       this.$emit('change-tab-index', 1, 2, 'stepone', _.cloneDeep(this.templateFormDetail.stepone));
                    }
                }).catch((errors) => {

                });
            },
            addNewRoundSchedule() {
                this.templateFormDetail.stepone.roundSchedules.push('');
            },
            removeRoundSchedule(index) {
                this.templateFormDetail.stepone.roundSchedules.splice(index, 1);
            },
            setTemplateFontColor(color) {
                this.templateFormDetail.stepone.template_font_color = color;
            },
		}
	}
</script>