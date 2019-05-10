<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-lg-12">
					<div class="tabs tabs-primary user_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab"
								href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.user_management_user}}</div></a>
							</li>
						</ul>
						<UserList :userList="userList" :isListGettingUpdate="isListGettingUpdate"></UserList>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
import _ from 'lodash';
import User from '../../../api/users.js'
import UserList from '../../admin/users/List.vue'
import axios from 'axios';
const CancelToken = axios.CancelToken;
let cancel;

export default {
	data() {
		return {
			'header' : 'header',
			'userList': {
				'userData': [],
				'userCount': 0,
				'listStatus': 1,
      			'emaildata':[]
      		},
      		isListGettingUpdate: false,
		}
	},
	components: {
		UserList
	},
	created() {
		this.getSelectComponent();
		this.$root.$on('setSearch', this.getSelectComponent);
	    this.$root.$on('clearSearch', this.clearSearch);
	},

	methods: {
	  clearSearch() {
	    this.getSelectComponent()
	},		
	getSelectComponent(userSearh='', userType='') {
		if (cancel !== undefined) {
	        cancel();
	    }
	    this.isListGettingUpdate = true;
	    $("body .js-loader").removeClass('d-none');


		let emaildata = []
		let userData = {}

		if(userSearh != '') {
			  userData.userData = userSearh;
		}

		if(userType != '') {
			  userData.userType = userType;
		}

	  axios.post('api/users/getUsersByRegisterType', userData, {cancelToken: new CancelToken(function executor(c) 
	        {
	            cancel = c;
	        })
	    }).then((response) => {
	        if('users' in response.data) {
	            // for(var val1 in response.data.users) {
	            //   for(var val2 in response.data.users[val1]) {
	            //     emaildata.push(response.data.users[val1]['email'])
	            //    //emaildata=response.data.users[val1]['email']
	            //   }
	            // }

	            // var unique = emaildata.filter(function(elem, index, self) {
	            //     return index == self.indexOf(elem);
	            // })

	            this.userList.emaildata = _.map(response.data.users, 'email');
	            this.userList.userData = response.data.users;
	            this.userList.userCount = response.data.users.length;
	          } else {
	          	this.userList.userData = [];
	          	this.userList.userCount = 0;
	          }
	          this.isListGettingUpdate = false;
	          $("body .js-loader").addClass('d-none');
		    }).catch((error) => {
		        if (axios.isCancel(error)) {
		        }
		        this.isListGettingUpdate = false;
		        $("body .js-loader").addClass('d-none');
		    });

      // User.getUsersByRegisterType(userData).then(
      //   (response)=> {

          
      //   },
      //   (error)=> {

      //   }
      // )

      // this.$router.push({name: 'users_list', params: { registerType: registerType }})
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
