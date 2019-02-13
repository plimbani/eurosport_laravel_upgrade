<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary user_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.duplicate_tournament}}</div></a>
							</li>
						</ul>
						<DuplicateTournamentList></DuplicateTournamentList>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
import DuplicateTournamentList from '../../admin/tournaments/DuplicateTournamentList.vue'
export default {
	data() {
		return {
			'header' : 'header',
		}
	},
	components: {
		DuplicateTournamentList
	},
	methods: {
	  
	}
}
</script>
