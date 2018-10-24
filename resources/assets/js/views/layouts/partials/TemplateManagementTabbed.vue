<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary template_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab">{{$lang.template_management_template}}</a>
							</li>
						</ul>
						<TemplateList :templateList="templateList"></TemplateList>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
import Template from '../../../api/template.js'
import TemplateList from '../../admin/templates/List.vue'
export default {
	data() {
		return {
			'header' : 'header',
			'templateList': {
      		}
		}
	},
	components: {
		TemplateList
	},
	created() {
	},
	mounted() {
		this.getTemplates();
	},
	methods: {
		getTemplates() {
			Template.getTemplates().then(
				(response)=> {
					console.log('response', response);
					this.templateList = response.data.data;
				},
		        (error)=> {

		        }
		    )
		}
	}
}
</script>
