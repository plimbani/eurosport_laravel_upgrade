<template>
	<div class="card">
		<div class="card-block">
			<div class="row">
				<!-- <button type="button" class="btn btn-outline-secondary" @click="GetSelectComponent('add_user')">Add User</button> -->
				<div class="col-lg-10 offset-1">
					<div class="tabs tabs-primary user_tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-to	ggle="tab" 
								href="#home2" role="tab" @click="GetSelectComponent('desktop_users')">Desktop users</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" 
								href="#home2" role="tab" @click="GetSelectComponent('mobile_users')">Mobile users</a>
							</li>							
						</ul>
						<div class="add_user_btn">
							<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addUserModal">Add New User</button>
						</div>
						<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
		                    <div class="modal-dialog" role="document">
		                        <div class="modal-content">
		                            <div class="modal-header">
		                                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
		                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                                    <span aria-hidden="true">×</span>
		                                </button>
		                            </div>
		                            <div class="modal-body">
		                                <form>
		                                	<div class="form-group row">
		                                		<label class="col-sm-5 form-control-label">Name</label>
					                            <div class="col-sm-6">
					                                <input type="text" class="form-control" placeholder="Your name">
					                            </div>
		                                	</div>
		                                	<div class="form-group row">
		                                		<label class="col-sm-5 form-control-label">Surname</label>
		                                		<div class="col-sm-6">
					                            	<input type="text" class="form-control" placeholder="Your surname">
					                            </div>
		                                	</div>
		                                	<div class="form-group row">
		                                		<label class="col-sm-5 form-control-label">Email address</label>
		                                		<div class="col-sm-6">
					                            	<input type="email" class="form-control" placeholder="Your email id">
					                            </div>
		                                	</div>
		                                	<div class="form-group row">
		                                		<label class="col-sm-5 form-control-label">Organisation</label>
		                                		<div class="col-sm-6">
					                            	<input type="text" class="form-control" placeholder="Your Organisation">
					                            </div>
		                                	</div>
		                                	<div class="form-group row">
		                                		<label class="col-sm-5 form-control-label">User type</label>
					                            <div class="col-sm-6">
					                                <select class="form-control ls-select2">
					                                	<option value="">Master</option>
					                                	<option value="">Tournament administrator</option>
					                                	<option value="">Other</option>
					                                </select>
					                            </div>
		                                	</div>
		                                </form>
		                            </div>
		                            <div class="modal-footer">
		                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		                                <button type="button" class="btn btn-primary">Save</button>
		                            </div>
		                        </div>
		                    </div>
		                </div>
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
			'header' : 'header'
		}
	},
	methods: {
		GetSelectComponent(componentName) {			
			this.$router.push({name: componentName})
		}
	}
}
</script>