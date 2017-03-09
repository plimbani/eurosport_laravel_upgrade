<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-9 offset-2">
					<div class="tabs tabs-primary">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a :class="[activePath == 'tournament_add' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#home2" role="tab" @click="GetSelectComponent('tournament_add')">Tournament Details</a>
							</li>					  		 
							<li class="nav-item">
								<a :class="[activePath == 'competation_format' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#home2" role="tab" @click="GetSelectComponent('competation_format')">Competation Formats</a>
							</li>
							<li class="nav-item">
								<a :class="[activePath == 'pitch_capacity' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#home2" role="tab" @click="GetSelectComponent('pitch_capacity')">Pitch Capacity</a>
							</li>
							<li class="nav-item">
								<a :class="[activePath == 'pitch_planner' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#home2" role="tab" @click="GetSelectComponent('pitch_planner')">Pitch Planner</a>
							</li>
							<li class="nav-item">
								<a :class="[activePath == 'teams_groups' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#home1" role="tab"  @click="GetSelectComponent('teams_groups')">Teams And Groups</a>
							</li>
							<li class="nav-item">
								<a :class="[activePath == 'tournaments_summary_details' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#home3" role="tab" @click="GetSelectComponent('tournaments_summary_details')">Summary</a>
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
export default {
	data() {
		return {
			'header' : 'header',
			'activePath' :  'tournament_add'
		}
	},
	mounted () {
		let path = window.location.pathname;
        var currentPath = path.substring(path.lastIndexOf('/') + 1);
        this.activePath = currentPath
	},
	methods: {
		GetSelectComponent(componentName) {		
		 this.activePath = componentName	
			this.$router.push({name: componentName})
		}
	}
}
</script>