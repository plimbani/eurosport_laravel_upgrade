<template>
	<div>
		<div class="container" id="">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="row">
						<div class="col-12">
							<h5>{{ $lang.add_template_modal_step4_header }}</h5>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<h6 class="font-weight-bold">My Custom Template &nbsp;<span class="small">(8 items)</span></h6>
						</div>
					</div>
					<div class="card mb-3">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">Round 1&nbsp;<span class="small">(8 items)</span></h6>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<h6 class="font-weight-bold">Group A</h6>
									<p>Teams play eachother once</p>
								</div>
								<div class="col-6">
									<h6 class="font-weight-bold">Group B</h6>
									<p>Teams play eachother once</p>
								</div>
							</div>
						</div>
					</div>
					<div class="card mb-3">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">Round 2&nbsp;<span class="small">(8 items)</span></h6>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<h6 class="font-weight-bold">Group C</h6>
									<p>Teams play eachother once</p>
								</div>
								<div class="col-6">
									<h6 class="font-weight-bold">Group D</h6>
									<p>Teams play eachother once</p>
								</div>
							</div>
						</div>
					</div>
					<div class="card mb-3">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">Round 3&nbsp;<span class="small">(8 items)</span></h6>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb-3">
									<h6 class="font-weight-bold">Group A</h6>
									<p>Teams play eachother once</p>
								</div>
								<div class="col-12">
									<h6 class="font-weight-bold">Group A</h6>
									<p>Teams play eachother once</p>
								</div>
							</div>
						</div>
					</div>					
					<div class="card mb-3">
						<div class="card-block">
							<div class="row align-items-center">
								<div class="col-12">
									<h6 class="font-weight-bold">Placings</h6>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p>Teams play eachother once</p>
								</div>
							</div>
						</div>
					</div>
					<form>
						<div class="form-group">
							<label for="remarks">Remarks</label>
							<input name="remarks" type="text" class="form-control" v-model="templateFormDetail.stepfour.remarks" placeholder="Remarks">
						</div>
						<div class="form-group row">
							<label class="col-12 form-control-label">Template font color</label>
							<div class="col-12">
								<div class="template-font-color-box pull-left mr-2" @click="setTemplateFontColor(color)" v-for="color in templateFontColors" :style="{'background-color': color}" :class="{ 'template-font-color-active' : templateFormDetail.stepfour.template_font_color == color }" ></div>
							</div>
						</div>					
						<div class="form-group row align-items-center mb-3">
							<div class="col-12">
								<button type="button" class="btn btn-primary" @click="back()">{{ $lang.add_template_modal_back_button }}</button>
								<button type="button" class="btn btn-primary" @click="saveTemplateDetail">{{ $lang.add_template_modal_save_button }}</button>
							</div>
				    	</div>
				    </form>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	import Template from '../../api/template.js'
	export default {
		props: ['templateFormDetail'],
        data() {
            return {
            	templateFontColors: [
            		'rgb(148,208,80)', 'rgb(146,208,80)', 'rgb(255,192,0)', 'rgb(217,149,148)'
            	],
            }
        },
        created() {
            this.$root.$on('updateTemplateData', this.updateTemplateData);
        },
        beforeCreate: function() {
            this.$root.$off('updateTemplateData');
        },
        methods: {
        	saveTemplateDetail() {
        		var templateData = {'templateFormDetail': this.templateFormDetail};
        		Template.saveTemplateDetail(templateData).then(
        			(response) => {

        			},
        			(error) => {

        			}
        		);
        	},
        	back() {
        		this.$emit('change-tab-index', 4, 3, 'stepfour', _.cloneDeep(this.templateFormDetail.stepfour));
        	},
        	setTemplateFontColor(color) {
        		this.templateFormDetail.stepfour.template_font_color = color;
        	}
        }
    }
</script>