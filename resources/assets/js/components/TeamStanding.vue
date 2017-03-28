<template>
<div class="col-md-6 row mt-4">

<table class="table match_overview">
	<thead>
		<th></th>
		<th></th>
		<th>Points</th>
		<th>Played</th>
		<th>Won</th>
		<th>Draws</th>
		<th>Lost</th>
		<th>Goals For</th>
		<th>Goals Against</th>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td class="text-center">
				<a href="" @click.prevent="teamDetails('')">
					 <img src="/assets/img/flag.png" width="20"> CS Pays Vert 1
					</span>
				</a>
			</td>
			<td>6</td>
			<td>2</td>
			<td>2</td>
			<td>0</td>
			<td>0</td>
			<td>3</td>
			<td>0</td>
		</tr>
	</tbody>
</table>
</div>
</template>
<script type="text/babel">

export default {
	data() {
		return {
			drawsData:[],
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		alert('called TeamStaing')		
	},
	methods: {
		
	}
}
</script>