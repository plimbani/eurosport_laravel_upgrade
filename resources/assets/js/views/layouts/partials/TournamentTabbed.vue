<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">

								<a :class="[activePath == 'tournament_add' ? 'active' : '', 'nav-link']" data-toggle="tab"  href="#tournament_add" role="tab" @click="GetSelectComponent('tournament_add')">Tournament Details</a>

							</li>					  		 
							<li class="nav-item">
								<a :class="[activePath == 'competation_format' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#competation_format" role="tab" @click="GetSelectComponent('competation_format')">Competation Formats</a>
							</li>
							<li class="nav-item">
								<a :class="[activePath == 'pitch_capacity' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#pitch_capacity" role="tab" @click="GetSelectComponent('pitch_capacity')">Pitch Capacity</a>
							</li>
							<li class="nav-item">
								<a :class="[activePath == 'pitch_planner' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#pitch_planner" role="tab" @click="GetSelectComponent('pitch_planner')">Pitch Planner</a>
							</li>
							<li class="nav-item">
								<a :class="[activePath == 'teams_groups' ? 'active' : '', 'nav-link']" data-toggle="tab" 
								href="#teams_groups" role="tab"  @click="GetSelectComponent('teams_groups')">Teams And Groups</a>
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
			'activePath' :  this.$store.state.activePath
		}
	},
	mounted() {
		// here we call function which select the active class
	},
	methods: {
		GetSelectComponent(componentName) {
			// here we check for Tournament Add			  
			this.$router.push({name: componentName})
			setTimeout( function(){
				if ($(document).height() > $(window).height()) {
                    $('.site-footer').removeClass('sticky');    
                } else {
                   $('.site-footer').addClass('sticky');
                }
			},2000 )            
		}
	},
	computed: {

	}
}
</script>