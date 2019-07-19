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
						<TemplateList :templateList="templateList" :isListGettingUpdate="isListGettingUpdate"></TemplateList>
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
      		},
      		isListGettingUpdate: false,
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
		getTemplates(teamSearch='', createdBySearch='', currentPage=1, noOfRecords=20) {
			let templateData = {};

			this.isListGettingUpdate = true;

			templateData.currentPage = currentPage;

		    templateData.noOfRecords = noOfRecords;

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
					if(response.data) {
						this.templateList.templateData = response.data.data;
						this.templateList.templateCount = response.data.data.data.length;
					} else {
						this.templateList.templateData = [];
	          			this.templateList.templateCount = 0;
					}	
					this.isListGettingUpdate = false;	
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
