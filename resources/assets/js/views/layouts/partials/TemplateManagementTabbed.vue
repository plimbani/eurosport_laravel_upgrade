<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary template_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item" v-if="$route.name == 'templates_list'">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.template_management_template}}</div></a>
							</li>
							<li class="nav-item" v-if="$route.name == 'add_new_template'">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab"><div class="wrapper-tab">Add New Template</div></a>
							</li>
							<li class="nav-item" v-if="$route.name == 'edit_template'">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab"><div class="wrapper-tab">Edit Template</div></a>
							</li>
						</ul>
						<router-view></router-view>
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
			
		}
	},
	components: {
		TemplateList
	}
}
</script>
