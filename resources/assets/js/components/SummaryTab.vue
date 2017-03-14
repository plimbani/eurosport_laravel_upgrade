<template>
	<div class="tab-content summary-content">
		<div class="row">
			<div class="col-md-12">
				<div class="pull-left col-md-6 padding0">
					<label class="pull-left">
						<img src="/assets/img/flag.png" width="30">
					</label>
					<label class="col-md-6">
						<h5>XYZ Cup 2017</h5>
					</label>
					<div class="clearfix"></div>
					<span><strong>Locations:</strong> Cadiz (San Puerto), Jerez</span>
					<span><strong>Dates:</strong> 09 - 10 Apr 2017</span>
				</div>
				<div class="pull-right col-md-6 padding0 text-right">
					<span><strong>Status:</strong> Ready to publish</span>
					<button type="button" data-toggle="modal" data-target="#publish_modal" class="btn btn-outline-primary col-md-4">Publish</button><br>
					<PublishTournament></PublishTournament>
					<button type="button" data-toggle="modal" data-target="#delete_tournament_modal" class="btn btn-outline-danger col-md-4 mt-3">Delete</button>
					<DeleteTournament></DeleteTournament>
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="row">
			<div class="col-md-12 blog text-center">	
				<div class="col-md-2 bolg-div">
					<h5><strong>21</strong></h5>
					<span><strong>Teams</strong></span>
				</div>
				<div class="col-md-2 bolg-div">
					<h5><strong>7</strong></h5>
					<span><strong>Age categories</strong></span>
				</div>
				<div class="col-md-2 bolg-div">
					<h5><strong>44</strong></h5>
					<span><strong>Games</strong></span>
				</div>
				<div class="col-md-2 bolg-div">
					<h5><strong>12</strong></h5>
					<span><strong>Pitches</strong></span>
				</div>
				<div class="col-md-2 bolg-div">
					<h5><strong>4</strong></h5>
					<span><strong>Referees</strong></span>
				</div>
				<div class="col-md-2 bolg-div">
					<h5><strong>2</strong></h5>
					<span><strong>Days</strong></span>
				</div>
			</div>
		</div>
		<div class="clearfix mt-4"></div>
		<div class="row">
			<div class="col-md-12">
				<span><strong>Age groups:</strong> G15, G17, U11, U13, U15, U17, U19</span>
				<span><strong>Participating countries:</strong> Germany, Spain</span>
				<span><strong>Euro-Sportring contact:</strong> Chris Gartside</span>
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