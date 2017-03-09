<template>
    <div>
        <div class="add_user_btn">
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addUserModal">Add New User</button>
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <input type="text" v-model="userlist">
                    </div>
                </div>
            </div>
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
                                        <option v-for="(role, id) in userRolesOptions" v-bind:value="id">
                                            {{ role }}
                                        </option>
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
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                userRolesOptions: []
            }
        },
        props: {
            userlist: String
        },
        methods: {
            getRoles() {
                Vue.axios.get("/api/roles-for-select").then((response) => {
                    this.userRolesOptions = response.data;
                });
            }
        }
    }
</script>