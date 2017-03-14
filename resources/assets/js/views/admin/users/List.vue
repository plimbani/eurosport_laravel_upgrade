<template>
    <div>
        <div class="add_user_btn">
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#user_form_modal" @click="addUser()">Add New User</button>
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Add, edit and remove users of Euro-Sportring Tournament Planner.</p>
                        </div>
                        <div class="col-md-12">
                            <table class="table add-category-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Email address</th>
                                        <th>Organisation</th>
                                        <th>User type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="" v-for="user in userList.userData">
                                        <td>{{ user.person_detail.first_name }}</td>
                                        <td>{{ user.person_detail.last_name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.organisation }}</td>
                                        <td>{{ user.roles[0].name }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#user_form_modal" @click="editUser(user.id)"><i class="fa fa-edit"></i></a>
                                            &nbsp;
                                            <a href="javascript:void(0)" data-confirm-msg="Are you sure you would like to delete this user record?" data-toggle="modal" data-target="#delete_modal" @click="prepareDeleteResource(user.id)"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="userList.userCount == 0" class="col-md-12">
                            <h6 class="block text-center">No record found</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="user_form_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form @submit.prevent="validateBeforeSubmit">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ userModalTitle }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">Name</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.name" v-validate="'required|alpha'" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" placeholder="Your name">
                                    <i v-show="errors.has('name')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">Surname</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.surname" v-validate="'required|alpha'" :class="{'is-danger': errors.has('surname') }" name="surname" type="text" class="form-control" placeholder="Your surname">
                                    <i v-show="errors.has('surname')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('surname')">{{ errors.first('surname') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">Email address</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.emailAddress" v-validate="'required|email'" :class="{'is-danger': errors.has('email_address') }" name="email_address" type="email" class="form-control" placeholder="Your email address">
                                    <i v-show="errors.has('email_address')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('email_address')">The email address field is required.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">Organisation</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.organisation" v-validate="'required'" :class="{'is-danger': errors.has('organisation') }" name="organisation" type="text" class="form-control" placeholder="Your organisation">
                                    <i v-show="errors.has('organisation')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('organisation')">{{ errors.first('organisation') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">User type</label>
                                <div class="col-sm-6">
                                    <select v-validate="'required'":class="{'is-danger': errors.has('organisation') }" class="form-control ls-select2" name="user_type" v-model="formValues.userType">
                                        <option value="">Select</option>
                                        <option v-for="(role, id) in userRolesOptions" v-bind:value="id">
                                            {{ role }}
                                        </option>
                                    </select>
                                    <span class="help is-danger" v-show="errors.has('user_type')">The user type field is required.</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
    </div>
</template>

<script type="text/babel">
    import DeleteModal from '../../../components/DeleteModal.vue'

    export default {
        components: {
            DeleteModal
        },
        data() {
            return {
                formValues: this.initialState(),
                userRolesOptions: [],
                userModalTitle: 'Add User',
                deleteConfirmMsg: 'Are you sure you would like to delete this user record?',
                deleteAction: ''
            }
        },
        created() {
            this.getRoles();
        },
        props: {
            userList: Object
        },
        methods: {
            initialState() {
                return {
                    id: '',
                    name: '',
                    surname: '',
                    emailAddress: '',
                    organisation: '',
                    userType: ''
                }
            },
            getRoles() {
                axios.get("/api/roles-for-select").then((response) => {
                    this.userRolesOptions = response.data;
                });
            },
            addUser() {
                this.$data.formValues = this.initialState();
                this.userModalTitle="Add User";
            },
            editUser(id) {
                this.userModalTitle="Edit User";
                axios.get("/api/user/edit/"+id).then((response) => {
                    this.$data.formValues = response.data;
                });
            },
            prepareDeleteResource(id) {
                this.deleteAction="/api/user/delete/"+id;
            },
            updateUserList() {
                axios.get("/api/getUsersByRegisterType/"+this.$route.params.registerType).then((response) => {
                    if('users' in response.data) {
                        this.userList.userData = response.data.users;
                        this.userList.userCount = response.data.users.length;
                    } else {
                        this.userList.userData = [];
                        this.userList.userCount = 0;
                    }
                });
            },
            validateBeforeSubmit(){
                this.$validator.validateAll().then(() => {
                    if(this.$data.formValues.id=="") {
                        axios.post("/api/user/create", this.formValues).then((response) => {
                            toastr.success('User has been added succesfully.', 'Add User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                            this.$data.formValues = this.initialState();
                            this.updateUserList();
                        });
                    } else {
                        axios.post("/api/user/update/"+this.formValues.id, this.formValues).then((response) => {
                            toastr.success('User has been updated succesfully.', 'Update User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                            this.$data.formValues = this.initialState();
                            this.updateUserList();
                        });
                    }
                }).catch(() => { });
            },
            deleteConfirmed() {
                axios.post(this.deleteAction).then((response) => {
                    $("#delete_modal").modal("hide");
                    toastr.success('User has been deleted succesfully.', 'Delete User', {timeOut: 5000});
                    this.updateUserList();
                });
            }
        }
    }
</script>