<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<!-- <button type="button" class="btn btn-outline-secondary" @click="GetSelectComponent('add_user')">Add User</button> -->
				<div class="col-lg-10 offset-1">
					<div class="tabs tabs-primary user_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" 
								href="#desktop" role="tab" @click="GetSelectComponent('desktop')">Desktop users</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" 
								href="#mobile" role="tab" @click="GetSelectComponent('mobile')">Mobile users</a>
							</li>							
						</ul>
						<router-view :userlist="userList"></router-view>
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
			'userList': '{"a" : "test"}'
		}
	},
	methods: {
		GetSelectComponent(registerType) {
			this.$router.push({name: 'users_list', params: { registerType: registerType }})
		}
	}
}
</script>