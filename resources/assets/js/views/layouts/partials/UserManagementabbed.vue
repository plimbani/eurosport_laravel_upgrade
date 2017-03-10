<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-10 offset-1">
					<div class="tabs tabs-primary user_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link" :class="{'active' : this.$route.params.registerType=='desktop'}" data-toggle="tab" 
								href="#desktop" role="tab" @click="getSelectComponent('desktop')">Desktop users</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" :class="{'active' : this.$route.params.registerType=='mobile'}" data-toggle="tab" 
								href="#mobile" role="tab" @click="getSelectComponent('mobile')">Mobile users</a>
							</li>							
						</ul>
						<router-view :userList="userList"></router-view>
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
			'userList': {
				'userData': [],
				'userCount': 0
			}
		}
	},
	created() {
		this.getSelectComponent(this.$route.params.registerType);
	},
	methods: {
		getSelectComponent(registerType) {
			axios.get("/api/getUsersByRegisterType/"+registerType).then((response) => {
				if('users' in response.data) {
					this.userList.userData = response.data.users;
                	this.userList.userCount = response.data.users.length;
				} else {
					this.userList.userData = [];
                	this.userList.userCount = 0;
				}
            });
			this.$router.push({name: 'users_list', params: { registerType: registerType }})
		}
	}
}
</script>