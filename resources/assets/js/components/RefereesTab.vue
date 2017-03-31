<template>
	<div class="tab-content planner_list_content">
		<div class="row">
			<div class="col-md-12">
				<div class="text-center">
					<button type="button" data-toggle="modal" data-target="#refreesModal" class="btn btn-primary mb-3">Add Referee</button>
				</div>
				<AddRefereesModel></AddRefereesModel>
				<div class="raferee_list">
					<div class="raferee_details">
						<img src="/assets/img/user_icon.png" width="40">
						<p>Paul Jones</p>
					</div>
					<div class="raferee_details">
						<img src="/assets/img/user_icon.png" width="40">
						<p>John Smith</p>
					</div>
					<div class="raferee_details">
						<img src="/assets/img/user_icon.png" width="40">
						<p>Jane Dodd</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
	import AddRefereesModel from './AddRefereesModel.vue'

	export default { 
		components: {
		    AddRefereesModel
		}
	}
</script>