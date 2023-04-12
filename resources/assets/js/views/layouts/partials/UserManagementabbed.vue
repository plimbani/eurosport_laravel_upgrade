<template>
	<div class="row">
		<div class="col-sm-12">
			<div class="card main-card">
				<div class="card-block">
					<div class="row">
						<div class="col-lg-12">
							<div class="tabs tabs-primary user_tabs">
								<ul class="nav nav-tabs" role="tablist">
									<li @click="setCurrentView('adminUsers')" class="nav-item">
										<a :class="[currentView == 'adminUsers' ? 'active' : '']" class="nav-link" data-toggle="tab"
										href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.user_management_admin_user}}</div></a>
									</li>
									<li @click="setCurrentView('mobileUsers')" class="nav-item">
										<a :class="[currentView == 'mobileUsers' ? 'active' : '']" class="nav-link" data-toggle="tab"
										href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.user_management_mobileuser}}</div></a>
									</li>
								</ul>
								<UserList :currentView="currentView" :userList="userList" :isListGettingUpdate="isListGettingUpdate"></UserList>
							</div>
						</div>
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
      		},
      		isListGettingUpdate: false,
			currentView: 'adminUsers',
		}
	},
	components: {
		UserList
	},
	watch: {
		currentView(newVal, oldVal) {
			this.getSelectComponent();
		}
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
		getSelectComponent(userSearh='', userType='', currentPage=1, noOfRecords=20) {
			if (cancel !== undefined) {
				cancel();
			}
			this.isListGettingUpdate = true;
			$("body .js-loader").removeClass('d-none');

			let userData = {}

			if(userSearh != '') {
				userData.userData = userSearh;
			}

			if(userType != '') {
				userData.userType = userType;
			}

			if (this.currentView == 'mobileUsers') {
				userData.userType = 'mobile.user';
			}

			userData.currentPage = currentPage;

			userData.noOfRecords = noOfRecords;

			axios.post('api/users/getUsersByRegisterType', userData, {cancelToken: new CancelToken(function executor(c) 
				{
					cancel = c;
				})
			}).then((response) => {
				if('data' in response.data) {
					this.userList = response.data;
					this.userList.userCount = response.data.data.length;
				} else {
					this.userList = [];
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
		},
		setCurrentView(currentView) {
			this.currentView = currentView;
		},
	}
}
</script>
