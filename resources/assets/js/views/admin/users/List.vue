<template>
    <div>
        <div class="add_user_btn">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#user_form_modal" @click="addUser()">{{$lang.user_management_add_new_user}}</button>
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12">
                            <p>{{$lang.user_management_sentence}}</p>
                        </div>
                        <div class="col-md-12">
                            <table class="table add-category-table">
                                <thead>
                                    <tr>
                                        <th>{{$lang.user_desktop_name}}</th>
                                        <th>{{$lang.user_desktop_surname}}</th>
                                        <th>{{$lang.user_desktop_email}}</th>
                                        <th>{{$lang.user_desktop_organisation}}</th>
                                        <th>{{$lang.user_desktop_usertype}}</th>
                                        <th>{{$lang.user_desktop_status}}</th>
                                        <th>{{$lang.user_desktop_action}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr class="" v-for="user in userList.userData">
                                        <td>{{ user.person_detail.first_name }}</td>
                                        <td>{{ user.person_detail.last_name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.organisation }}</td>
                                        <td v-if="(user.roles).length>0">{{ user.roles[0].name }}</td>
                                        <td v-else></td>
                                        <td v-if="user.is_verified == 1">Accepted</td>

                                        <td class="text-left" v-else>
                                        <a href="#"  @click="resendModalOpen(user.email)"><u>Re-send</u></a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#user_form_modal" @click="editUser(user.id)"><i class="jv-icon jv-edit"></i></a>
                                            &nbsp;
                                            <a href="javascript:void(0)" data-confirm-msg="Are you sure you would like to delete this user record?" data-toggle="modal" data-target="#delete_modal" @click="prepareDeleteResource(user.id)"><i class="jv-icon jv-dustbin"></i></a>
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
                    <form name="frmUser" id="frmUser" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ userModalTitle }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row" :class="{'has-error': errors.has('name') }">
                                <label class="col-sm-5 form-control-label">{{$lang.user_management_add_name}}</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.name" v-validate="'required'"
                                    :class="{'is-danger': errors.has('name') }"
                                    name="name" type="text"
                                    class="form-control" placeholder="Enter first name">
                                    <i v-show="errors.has('name')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('name')">{{$lang.user_management_add_name_required}}
                                    </span>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">{{$lang.user_management_add_surname}}</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.surname" v-validate="'required|alpha'" :class="{'is-danger': errors.has('surname') }" name="surname" type="text" class="form-control" placeholder="Enter second name">
                                    <i v-show="errors.has('surname')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('surname')">{{$lang.user_management_add_surname_required}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">{{$lang.user_management_email}}</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.emailAddress" v-validate="'required|email'" :class="{'is-danger': errors.has('email_address') }" name="email_address" type="email" class="form-control" placeholder="Enter email address">
                                    <i v-show="errors.has('email_address')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('email_address')">{{$lang.user_management_email_required}}</span>
                                </div>
                            </div>

                            <!-- <div class="form-group row" v-if="formValues.id === ''">
                                <label class="col-sm-5 form-control-label">{{$lang.user_management_password}}</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.password" v-validate="'required'" :class="{'is-danger': errors.has('pass') }" name="pass" type="password" class="form-control" placeholder="Enter password">
                                    <i v-show="errors.has('pass')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('pass')">{</span>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-md-5 control-label">{{$lang.user_management_image}}</label>
                                <div class="col-sm-6">
                                      <div v-if="!image">
                                          <button type="button" id="profile_image_file">Choose file</button>
                                          <input type="file" name="userImg" id="userImg" style="display:none;" @change="onFileChange">
                                          <p class="help-block">Maximum size of 1 MB.<br/>
                                          Image dimensions 100 x 100.</p>
                                      </div>
                                       <div v-else>
                                              <img :src="image" width="40px" height="50px"/>
                                              <button @click="removeImage">Remove image</button>
                                      </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">{{$lang.user_management_organisation}}</label>
                                <div class="col-sm-6">
                                    <input v-model="formValues.organisation" v-validate="'required'" :class="{'is-danger': errors.has('organisation') }" name="organisation" type="text" class="form-control" placeholder="Enter organisation name">
                                    <i v-show="errors.has('organisation')" class="fa fa-warning"></i>
                                    <span class="help is-danger" v-show="errors.has('organisation')">{{$lang.user_management_organisation_required}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">{{$lang.user_management_user_type}}</label>
                                <div class="col-sm-6">
                                    <select v-validate="'required'":class="{'is-danger': errors.has('user_type') }" class="form-control ls-select2" name="user_type" v-model="formValues.userType">
                                        <option value="">Select</option>
                                        <option v-for="(role, id) in userRolesOptions" v-bind:value="id">
                                            {{ role }}
                                        </option>
                                    </select>
                                    <span class="help is-danger" v-show="errors.has('user_type')">{{$lang.user_management_user_type_required}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_user_cancle}}</button>
                            <button type="button" class="btn btn-primary" @click="validateBeforeSubmit1()">{{$lang.user_management_user_save}}</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <resend-modal :resendConfirm="resendConfirm" @confirmed="resendConfirmed()"></resend-modal>
    </div>
</template>
<script type="text/babel">
    import DeleteModal from '../../../components/DeleteModal.vue'
    import ResendModal from '../../../components/Resendmail.vue'

    export default {
        components: {
            DeleteModal,
            ResendModal
        },
        data() {
            return {
                formValues: {id: '',
                    name: '',
                    surname: '',
                    emailAddress: '',
                    organisation: '',
                    userType: '',
                    user_image: '',
                    resendEmail: ''
                },
                userRolesOptions: [],
                userModalTitle: 'Add User',
                deleteConfirmMsg: 'Are you sure you would like to delete this user record?',
                resendConfirm: 'Are you sure you would like to send this user another invite?',

                deleteAction: '',
                image: ''
            }
        },
        created() {
            this.getRoles();
        },
        props: {
            userList: Object
        },
        mounted(){
            $('#profile_image_file').click(function(){
                $('#userImg').trigger('click')
            })
        },
        methods: {
            initialState() {
                this.$data.formValues.id = '',
                this.$data.formValues.name =  '',
                this.formValues.surname= '',
                this.formValues.emailAddress= '',
                this.formValues.organisation= '',
                this.formValues.userType= '',
                this.formValues.user_image= '',
                this.formValues.resendEmail= ''
        },
            getRoles() {
                axios.get("/api/roles-for-select").then((response) => {
                    this.userRolesOptions = response.data;
                });
            },
            resendConfirmed() {
                let emailData = this.resendEmail

                axios.post("/api/user/resendEmail",{'email':emailData}).then((response) => {
                    $("#resend_modal").modal("hide");
                     toastr.success('Mail has been send successfully.', 'Mail sent', {timeOut: 5000});

                });
            },
            addUser() {
                //this.initialState();
                this.$data.formValues.name = ''
                this.$data.formValues.id = ''
                this.$data.formValues.emailAddress = ''
                this.$data.formValues.image = ''
                this.$data.formValues.organisation = ''
                this.$data.formValues.surname = ''
                this.$data.formValues.userType = ''
                this.userModalTitle="Add User";
            },
            resendModalOpen(data) {
                this.resendEmail = data
                $('#resend_modal').modal('show');
            },
            editUser(id) {
                this.userModalTitle="Edit User";
                axios.get("/api/user/edit/"+id).then((response) => {
                    this.$data.formValues = response.data;
                    let image = this.$data.formValues.image
                    if(image != null && image != '') {
                      this.image =  '/assets/img/users/'+this.$data.formValues.image;
                    } else {
                      this.image=''
                    }

                });
            },
            onFileChange(e) {
              var files = e.target.files || e.dataTransfer.files;
              if (!files.length)
                return;
              this.createImage(files[0]);
            },
            createImage(file) {
              var image = new Image();
              var reader = new FileReader();
              var vm = this;

            reader.onload = (e) => {
                vm.image = e.target.result;
              };
              reader.readAsDataURL(file);
            },
            removeImage: function (e) {
              this.image = '';
              e.preventDefault();
            },

            prepareDeleteResource(id) {
                this.deleteAction="/api/user/delete/"+id;
            },
            updateUserList() {
                axios.get("/api/getUsersByRegisterType/"+this.$route.params.registerType).then((response) => {
                    // console.log(response)
                    if('users' in response.data) {
                        this.userList.userData = response.data.users;
                        this.userList.userCount = response.data.users.length;
                    } else {
                        this.userList.userData = [];
                        this.userList.userCount = 0;
                    }
                },
                (error) => {
                    console.log(error)
                });
            },
            validateBeforeSubmit1() {
                this.$validator.validateAll().then(() => {
                    if(this.$data.formValues.id=="") {
                        this.formValues.user_image = this.image;
                        axios.post("/api/user/create", this.formValues).then((response) => {
                            toastr.success('User has been added successfully.', 'Add User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                             setTimeout(Plugin.reloadPage, 1000);
                            this.$data.formValues = this.initialState();
                            this.updateUserList();
                        });
                    } else {
                    this.formValues.user_image = this.image;
                    let that = this
                    setTimeout(function(){
                        axios.post("/api/user/update/"+that.formValues.id, that.formValues).then((response) => {
                            toastr.success('User has been updated successfully.', 'Update User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                             setTimeout(Plugin.reloadPage, 500);
                            that.$data.formValues = that.initialState();
                            that.updateUserList();
                        });
                    },1000)

                    }
                }).catch((errors) => {
                     console.log(errors)
                    // toastr['error']('Please fill all required fields ', 'Error')
                 });
            },
            deleteConfirmed() {
                axios.post(this.deleteAction).then((response) => {
                    $("#delete_modal").modal("hide");
                     setTimeout(Plugin.reloadPage, 500);
                    toastr.success('User has been deleted successfully.', 'Delete User', {timeOut: 5000});
                    this.updateUserList();
                });
            },
        }
    }
</script>
