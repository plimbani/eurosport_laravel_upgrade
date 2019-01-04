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
                        <label for="competition_type">Template type</label>
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
                                        <input class="euro-radio" type="radio" name="editor" value="festival" v-model="templateFormDetail.stepone.editor" id="radio_festival">
                                        <label for="radio_festival">Festival</label>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>                   
            		<div class="form-group" :class="{'has-error': errors.has('no_of_teams') }">
            			<label>{{$lang.add_template_modal_number_of_teams}}</label>
                        <select class="form-control ls-select2" name="no_of_teams" v-model="templateFormDetail.stepone.no_of_teams" v-validate="'required'" :class="{'is-danger': errors.has('no_of_teams') }" data-vv-as="number of teams">
                            <option value="">Number of teams</option>
                            <option :value="team" v-for="team in teamsToDisplay">{{ team }}</option>
                        </select>
                        <i v-show="errors.has('no_of_teams')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('no_of_teams')">{{ errors.first('no_of_teams') }}</span>
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
                for (var n = 2; n <= 60; n++) {
                    totalTeams.push(n);
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
            }
		}
	}
</script>