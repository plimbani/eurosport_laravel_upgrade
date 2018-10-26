<template>
	<div>
		<div id="step1-template-setting">
            <div class="row">
                <div class="col-md-4">
            		<h5>{{ $lang.add_template_modal_step1_header }}</h5>
            		<div class="form-group">
            			<label>{{$lang.add_template_modal_template_name}}</label>
            			<input v-model="formValues.templateName" name="template_name" type="text" class="form-control" placeholder="My custom template">
            		</div>
            		<div class="form-group">
            			<label>{{$lang.add_template_modal_number_of_teams}}</label>
                        <select class="form-control ls-select2" name="teams" id="teams">
                            <option value="">Number of teams</option>
                            <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                        </select>
            		</div>
            		<div class="form-group">
            			<div class="radio">
            				<label><input type="radio" name="editor"> Advance editor</label>
            				<label><input type="radio" name="editor"> Simple editor</label>
            				<span class="info-editor"><i class="fa fa-info-circle"></i></span>
            			</div>
            		</div>
            		<div class="form-group">
            			<button type="button" class="btn btn-danger" @click="next()">{{$lang.add_template_modal_step1_button}}</button>
            		</div>   
                </div>
            </div>         		
    	</div>
    	<new-template-step-two></new-template-step-two>
	</div>
</template>
<script type="text/javascript">
    import NewTemplateStepTwo from './NewTemplateStepTwo.vue'
	export default {
		data() {
		    return {
		    	formValues: {
		    	},
		    }
		},
        components: {
          NewTemplateStepTwo
        },
		mounted() {
		},
		methods: {
            next() {
            	this.$emit('change-tab-index', 1, 2);
            }
		}
	}
</script>