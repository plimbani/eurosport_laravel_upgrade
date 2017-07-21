<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary user_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link" :class="{'active' : this.$route.params.registerType=='desktop'}" data-toggle="tab"
								href="#desktop" role="tab" @click="getSelectComponent('desktop')">{{$lang.user_management_desktopuser}}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" :class="{'active' : this.$route.params.registerType=='mobile'}" data-toggle="tab"
								href="#mobile" role="tab" @click="getSelectComponent('mobile')">{{$lang.user_management_mobileuser}}</a>
							</li>
						</ul>
						<router-view :userList="userList" :registerType="registerType"></router-view>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
import User from '../../../api/users.js'
export default {
	data() {
		return {
			'header' : 'header',
			'userList': {
				'userData': [],
				'userCount': 0,
				'registerType': '',
				'listStatus': 1,
      			'emaildata':[]
			}
		}
	},
	created() {
	  this.getSelectComponent(this.$route.params.registerType);
	  this.$root.$on('setSearch', this.getSelectComponent);
	},
	methods: {
	getSelectComponent(registerType, userData='') {
	  let emaildata = []
	  let user1Data = {}
	  this.registerType = registerType
	  if(userData != '') {
	  	  user1Data = {registerType:registerType, 'userData': userData}
	  } else {
	      user1Data = {registerType:registerType}
	  }
      User.getUsersByRegisterType(user1Data).then(
        (response)=> {
          if('users' in response.data) {
            for(var val1 in response.data.users) {
              for(var val2 in response.data.users[val1]) {
                emaildata.push(response.data.users[val1]['email'])
                //emaildata=response.data.users[val1]['email']
              }
            }

            var unique = emaildata.filter(function(elem, index, self) {
                return index == self.indexOf(elem);
            })

            this.userList.emaildata = unique

            this.userList.userData = response.data.users;
            this.userList.userCount = response.data.users.length;
          } else {
          this.userList.userData = [];
          this.userList.userCount = 0;
          }
        },
        (error)=> {

        }
      )

      this.$router.push({name: 'users_list', params: { registerType: registerType }})
			/*axios.get("/api/getUsersByRegisterType/"+registerType).then((response) => {

				if('users' in response.data) {

					this.userList.userData = response.data.users;
                	this.userList.userCount = response.data.users.length;
				} else {
					this.userList.userData = [];
                	this.userList.userCount = 0;
				}
            });
			this.$router.push({name: 'users_list', params: { registerType: registerType }})
      */
		}
	}
}
</script>
