<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary template_tabs">
						<ul class="nav nav-tabs" role="tablist" v-if="$route.name == 'templates_list'">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.template_management_template}}</div></a>
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
