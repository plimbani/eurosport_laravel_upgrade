<template>
	<div>
		<div class="container" id="step1-template-setting">
            <div class="row justify-content-center">
                <div class="col-md-8">
            		<h5>{{ $lang.add_template_modal_step1_header }}</h5>
            		<div class="form-group" :class="{'has-error': errors.has('template_name') }">
            			<label>{{$lang.add_template_modal_template_name}}</label>
            			<input v-model="templateFormDetail.stepone.templateName" name="template_name" type="text" class="form-control" placeholder="My custom template" data-vv-as="template name" v-validate="'required'" :class="{'is-danger': errors.has('template_name') }">
                        <i v-show="errors.has('template_name')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('template_name')">{{ errors.first('template_name') }}</span>
            		</div>
                    <div class="form-group">
                        <label for="competition_type">Editor type</label>
                        <span class="ml-1 text-primary" data-toggle="popover" data-animation="false" data-placement="right" :data-popover-content="'#divison_detail'"><i class="fa fa-info-circle"></i></span>
                        <div v-bind:id="'divison_detail'" style="display:none;">
                            <div class="popover-body">Editor description</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <div class="c-input">
                                        <input class="euro-radio" type="radio" name="editor" value="advance" v-model="templateFormDetail.stepone.editor" id="radio_advance">
                                        <label for="radio_advance">Advance</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <div class="c-input">
                                        <input class="euro-radio" type="radio" name="editor" value="simple" v-model="templateFormDetail.stepone.editor" id="radio_simple">
                                        <label for="radio_simple">Simple</label>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="form-group" v-if="templateFormDetail.stepone.editor == 'simple'">
                        <label for="competition_type">Competition type</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <div class="c-input">
                                        <input class="euro-radio" type="radio" name="competition_type" value="league" v-model="templateFormDetail.stepone.competition_type" id="radio_league">
                                        <label for="radio_league">League</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <div class="c-input">
                                        <input class="euro-radio" type="radio" name="competition_type" value="knockout" v-model="templateFormDetail.stepone.competition_type" id="radio_knockout">
                                        <label for="radio_knockout">Knockout</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
            		<div class="form-group" :class="{'has-error': errors.has('no_of_teams') }">
            			<label>{{$lang.add_template_modal_number_of_teams}}</label>
                        <select class="form-control ls-select2" name="no_of_teams" v-model="templateFormDetail.stepone.no_of_teams" v-validate="'required'" :class="{'is-danger': errors.has('no_of_teams') }">
                            <option value="">Number of teams</option>
                            <option :value="team" v-for="team in teamsToDisplay">{{ team }}</option>
                        </select>
                        <i v-show="errors.has('no_of_teams')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('no_of_teams')">{{ errors.first('no_of_teams') }}</span>
            		</div>
                    <div class="form-group" :class="{'has-error': errors.has('group_size') }" v-if="templateFormDetail.stepone.editor == 'simple' && templateFormDetail.stepone.competition_type == 'knockout'">
                        <label>{{$lang.add_template_modal_group_size}}</label>
                        <select class="form-control ls-select2" name="group_size" v-model="templateFormDetail.stepone.group_size" v-validate="'required'" :class="{'is-danger': errors.has('group_size') }">
                            <option value="">Group size</option>
                            <option :value="sizeIndex" v-for="(size, sizeIndex) in getAllGroupSize"> {{ size }}</option>
                        </select>
                        <i v-show="errors.has('group_size')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('group_size')">{{ errors.first('group_size') }}</span>
                    </div>
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
		    }
		},
        components: {
        },
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
                var totalTeams = [];
                if(this.templateFormDetail.stepone.editor == 'advance') {
                    for (var n = 4; n <= 28; n++) {
                        totalTeams.push(n);
                    }
                }
                if(this.templateFormDetail.stepone.editor == 'simple' && this.templateFormDetail.stepone.competition_type == 'knockout') {
                    for (var n = 8; n <= 60; n++) {
                        if(n % 4 == 0 || n % 5 == 0 || n % 6 == 0) {
                            totalTeams.push(n);
                        }
                    }
                }
                if(this.templateFormDetail.stepone.editor == 'simple' && this.templateFormDetail.stepone.competition_type == 'league') {
                    for (var n = 2; n <= 28; n++) {
                        if(n % 2 == 0) {
                            totalTeams.push(n);
                        }
                    }
                }

                return totalTeams;
            },
            getAllGroupSize() {
                let groupSize = {};
                let noOfTeams = this.templateFormDetail.stepone.no_of_teams;

                let preDefinedTeams = ['4', '5', '6'];
                preDefinedTeams.forEach(function (value, key) {
                    if(noOfTeams && noOfTeams % value == 0) {
                        groupSize[value] = value+ ' teams per group';
                    }
                });

                return groupSize;
            }
        },
		methods: {
            next() {
                this.$validator.validateAll().then((response) => {
                    if(response) {
            	       this.$emit('change-tab-index', 1, 2, 'stepone', _.cloneDeep(this.templateFormDetail.stepone));
                    }
                }).catch((errors) => {

                });
            }
		}
	}
</script>