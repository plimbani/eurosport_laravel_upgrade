<template>
	<div class="tab-content summary-content">
		<div class="row">
			<div class="col-md-12">
				<div class="pull-left col-md-6 padding0">
					<label class="pull-left">
						<img src="/assets/img/flag.png" width="30">
					</label>
					<label class="col-md-6">
						<h5>{{$lang.summary_cup}}</h5>
					</label>
					<div class="clearfix"></div>
					<span><strong>{{$lang.summary_location}}</strong> Cadiz (San Puerto), Jerez</span>
					<span><strong>{{$lang.summary_dates}}</strong> 09 - 10 Apr 2017</span>
				</div>
				<div class="pull-right col-md-6 padding0 text-right">
					<span><strong>{{$lang.summary_status}}</strong> Ready to publish</span>
					<button type="button" data-toggle="modal" data-target="#publish_modal" class="btn btn-primary col-md-4">{{$lang.summary_button_publish}}</button><br>
					<PublishTournament></PublishTournament>
					<button type="button" data-toggle="modal" data-target="#delete_tournament_modal" class="btn btn-danger col-md-4 mt-3">{{$lang.summary_button_delete}}</button>
					<DeleteTournament></DeleteTournament>
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="d-flex justify-content-between align-items-center text-center flex-wrap">	
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">21</div>
						<p>Teams</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">7</div>
						<p>Age categories</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">44</div>
						<p>Games</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
						<div class="card-title">12</div>
						<p>Pitches</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">	4</div>
						<p>Referees</p>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="m_card">
					<div class="card-content">
					<div class="card-title">	2</div>
						<p>Days</p>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="row">
			<div class="col-md-12">
				<span><strong>{{$lang.summary_age_groups}}</strong> G15, G17, U11, U13, U15, U17, U19</span>
				<span><strong>{{$lang.summary_participating_countries}}</strong> Germany, Spain</span>
				<span><strong>{{$lang.summary_euro_supporting_contact}}</strong> Chris Gartside</span>
			</div>
		</div>
		
	</div>

</template>

<script type="text/babel">
	
	import PublishTournament from './PublishTournament.vue'
	import DeleteTournament from './DeleteTournament.vue'

	export default {	
	    components: {
	        PublishTournament, DeleteTournament
	    }
	}
</script>