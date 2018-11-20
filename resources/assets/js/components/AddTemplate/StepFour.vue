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
					<div class="row align-items-center mb-3">
						<div class="col-12">
							<button type="button" class="btn btn-primary" @click="saveTemplateDetail">Save</button>
						</div>
			    	</div>
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
        	}
        }
    }
</script>