<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary template_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.template_management_template}}</div></a>
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
			'templateList': {
				'templateData': [],
				'templateCount': 0,
      		}
		}
	},
	components: {
		TemplateList
	},
	created() {
		this.$root.$on('setSearch', this.getTemplates);
		this.$root.$on('clearSearch', this.clearSearch);
		this.getTemplates();
		this.$root.$on('getTemplates', this.getTemplates);
	},
	beforeCreate: function() {
	    this.$root.$off('getTemplates');
	},	
	mounted() {
	},
	methods: {
		getTemplates(teamSearch='', createdBySearch='') {
			let templateData = {};
			if(teamSearch != '') {
	  	  		templateData.teamSearch = teamSearch;
  			}
			if(createdBySearch != '') {
	  	  		templateData.createdBySearch = createdBySearch;
  			}

  			$("body .js-loader").removeClass('d-none');
			Template.getTemplates(templateData).then(
				(response)=> {
					$("body .js-loader").addClass('d-none');
					this.templateList.templateData = response.data.data;
					this.templateList.templateCount = response.data.data.length;
				},
		        (error)=> {
		        }
		    )
		},
		clearSearch() {
			this.getTemplates();
		}
	}
}
</script>
