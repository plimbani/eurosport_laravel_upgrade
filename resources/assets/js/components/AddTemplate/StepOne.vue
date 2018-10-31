<template>
	<div>
		<div id="step1-template-setting">
            <div class="row">
                <div class="col-md-7 col-lg-5">
            		<h5>{{ $lang.add_template_modal_step1_header }}</h5>
            		<div class="form-group" :class="{'has-error': errors.has('template_name') }">
            			<label>{{$lang.add_template_modal_template_name}}</label>
            			<input v-model="formValues.templateName" name="template_name" type="text" class="form-control" placeholder="My custom template" data-vv-as="template name" v-validate="'required'" :class="{'is-danger': errors.has('template_name') }">
                        <i v-show="errors.has('template_name')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('template_name')">{{ errors.first('template_name') }}</span>
            		</div>
            		<div class="form-group" :class="{'has-error': errors.has('teams') }">
            			<label>{{$lang.add_template_modal_number_of_teams}}</label>
                        <select class="form-control ls-select2" name="teams" id="teams" v-model="formValues.teams" v-validate="'required'" :class="{'is-danger': errors.has('teams') }">
                            <option value="">Number of teams</option>
                            <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                        </select>
                        <i v-show="errors.has('teams')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('teams')">{{ errors.first('teams') }}</span>
            		</div>
            		<div class="form-group">
            			<div class="radio">
            				<label><input type="radio" name="editor" value="advance_editor" v-model="formValues.editor"> Advance editor</label>
            				<label><input type="radio" name="editor" value="simple_editor" v-model="formValues.editor"> Simple editor</label>
                            <span class="info-editor text-primary" data-toggle="popover" data-animation="false" data-placement="right" :data-popover-content="'#divison_detail'"><i class="fa fa-info-circle"></i></span>
                            <div v-bind:id="'divison_detail'" style="display:none;">
                                <div class="popover-body">Editor description</div>
                            </div>
            			</div>
            		</div>
            		<div class="form-group">
            			<button type="button" class="btn btn-danger" @click="next()">{{$lang.add_template_modal_step1_button}}</button>
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
		    	formValues: {
                    templateName: '',
                    teams: '',
                    editor: 'advance_editor',
		    	},
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
		methods: {
            next() {
                this.$validator.validateAll().then((response) => {
                    if(response) {
            	       this.$emit('change-tab-index', 1, 2, 'stepone', this.formValues);
                    }
                }).catch((errors) => {

                });
            }
		}
	}
</script>