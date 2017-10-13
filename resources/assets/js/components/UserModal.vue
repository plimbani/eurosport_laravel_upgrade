<template>
  <div class="modal" id="user_form_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
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
                      <input v-model="formValues.name" v-validate="'required|alpha_spaces'"
                      :class="{'is-danger': errors.has('name') }"
                      name="name" type="text"
                      class="form-control" placeholder="Enter first name">
                      <i v-show="errors.has('name')" class="fa fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}
                      </span>
                  </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 form-control-label">{{$lang.user_management_add_surname}}</label>
                    <div class="col-sm-6">
                        <input v-model="formValues.surname" v-validate="'required|alpha_spaces'" :class="{'is-danger': errors.has('surname') }" name="surname" type="text" class="form-control" placeholder="Enter second name">
                        <i v-show="errors.has('surname')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('surname')">{{ errors.first('surname') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 form-control-label">{{$lang.user_management_email}}</label>
                    <div class="col-sm-6">
                        <input v-model="formValues.emailAddress" v-validate="'required|email'" :class="{'is-danger': errors.has('email_address') }" name="email_address" type="email" class="form-control" placeholder="Enter email address">
                        <i v-show="errors.has('email_address')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('email_address')">{{$lang.user_management_email_required}}</span>
                       <span class="help is-danger" v-if="existEmail == true">Email already exist</span>
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
                    <label class="col-sm-5 form-control-label">{{$lang.user_management_user_type}}</label>
                    <div class="col-sm-6">
                      <select v-validate="'required'":class="{'is-danger': errors.has('user_type') }" class="form-control ls-select2" name="user_type" v-model="formValues.userType" @change="userTypeChanged()">
                        <option value="">Select</option>
                        <option v-for="role in userRolesOptions" v-bind:value="role.id">
                            {{ role.name }}
                        </option>
                      </select>
                      <span class="help is-danger" v-show="errors.has('user_type')">{{$lang.user_management_user_type_required}}</span>
                    </div>
                </div>
                <div class="form-group row" v-show="showOrganisation">
                    <label class="col-sm-5 form-control-label">{{$lang.user_management_organisation}}</label>
                    <div class="col-sm-6">
                        <input v-model="formValues.organisation" v-validate="{ rules: { required: showOrganisation } }" :class="{'is-danger': errors.has('organisation') }" name="organisation" type="text" class="form-control" placeholder="Enter organisation name">
                        <i v-show="errors.has('organisation')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('organisation')">{{$lang.user_management_organisation_required}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 form-control-label">{{$lang.user_management_default_app_tournament}}</label>
                    <div class="col-sm-6">
                      <select v-validate="'required'":class="{'is-danger': errors.has('tournament_id') }" class="form-control ls-select2" name="tournament_id" v-model="formValues.tournament_id">
                        <option value="">Select</option>
                        <option v-for="tournament in publishedTournaments" v-bind:value="tournament.id">
                            {{ tournament.name }}
                        </option>
                      </select>
                      <span class="help is-danger" v-show="errors.has('tournament_id')">{{$lang.user_management_default_app_tournament_required}}</span>
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
</template>
<script type="text/javascript">
import User from '../api/users.js'
import { ErrorBag } from 'vee-validate';
    export default {
        data() {
            return {
                formValues: {
                    id: '',
                    name: '',
                    surname: '',
                    emailAddress: '',
                    organisation: '',
                    userType: '',
                    resendEmail: '',
                    userEmailData1: this.userEmailData,
                    userEmail2: '',
                    tournament_id: '',
                },

                userRolesOptions: [],
                userModalTitle: 'Add User',
                deleteConfirmMsg: 'Are you sure you would like to delete this user record?',
                resendConfirm: 'Are you sure you would like to send this user another invite?',

                deleteAction: '',
                emailData:[],
                existEmail: false,
                showOrganisation: false,
                errorMessages: {
                  en: {
                    custom: {
                      name: {
                        required: 'This field is required.',
                        alpha_spaces: 'Only alphabetic characters and spaces are allowed.',
                      },
                      surname: {
                        required: 'This field is required.',
                        alpha_spaces: 'Only alphabetic characters and spaces are allowed.',
                      }
                    }
                  },
                  fr: {
                    custom: {
                      name: {
                        required: 'FThis field is required.',
                        alpha_spaces: 'FOnly alphabetic characters and spaces are allowed.',
                      },
                      surname: {
                        required: 'FThis field is required.',
                        alpha_spaces: 'FOnly alphabetic characters and spaces are allowed.',
                      }
                    }
                  }
                }
            }
        },
        created() {
        },
        mounted(){
            if(this.userId!=''){
                this.editUser(this.userId)
            }
            this.userRolesOptions =  this.userRoles

            this.$validator.updateDictionary(this.errorMessages);
        },
        props:['userId','userRoles','userEmailData','publishedTournaments'],
        methods: {
            initialState() {
                this.$data.formValues.id = '',
                this.$data.formValues.name =  '',
                this.formValues.surname= '',
                this.formValues.emailAddress= '',
                this.formValues.organisation= '',
                this.formValues.userType= '',
                 this.formValues.resendEmail= ''
            },
           editUser(id) {

                //TODO: refactor the Code For Move to Api User
                User.getEditUser(id).then(
                  (response)=> {
                    this.userModalTitle="Edit User";
                    this.$data.formValues = response.data;
                    this.$data.formValues.userEmail2 = this.$data.formValues.emailAddress;
                    this.userTypeChanged();
                  },
                  (error)=> {
                  }
                )

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
            prepareDeleteResource(id) {
                this.deleteAction="/api/user/delete/"+id;
            },
            updateUserList() {
              let data = {}
              //if(this.$route.params.registerType == '' || this.$route.params.registerType == null)
                //  registerType = this.registerType
              //  alert('hello called')
              User.getUsersByRegisterType(data).then(
                (response)=> {
                   if('users' in response.data) {
                        this.userList.userData = response.data.users;
                        this.userList.userCount = response.data.users.length;
                    } else {
                        this.userList.userData = [];
                        this.userList.userCount = 0;
                    }
                },
                (error) => {
                }
              )
              /*
               axios.get("/api/getUsersByRegisterType/"+this.$route.params.registerType).then((response) => {

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
                }); */
            },
            validateBeforeSubmit1() {
                this.$validator.validateAll().then(() => {
                    if(this.$data.formValues.id=="") {
                    var a = this.userEmailData.emaildata.indexOf(this.$data.formValues.emailAddress)

                    // Means exist throw error
                    if(a != -1) {
                      this.existEmail = true
                      return false
                    }

                        // here we add code for Mobile user for create user

                        User.createUser(this.formValues).then(
                          (response)=> {
                            toastr.success('User has been added successfully.', 'Add User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                             setTimeout(Plugin.reloadPage, 1000);
                            // this.$data.formValues = this.initialState();
                            // this.updateUserList();

                          },
                          (error)=>{
                          }

                        )
                      /*  axios.post("/api/user/create", this.formValues).then((response) => {
                            toastr.success('User has been added successfully.', 'Add User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                             setTimeout(Plugin.reloadPage, 1000);
                            this.$data.formValues = this.initialState();
                            this.updateUserList();
                        });*/
                    } else {
                    // First we remove the value from array
                    let arr=this.userEmailData.emaildata

                    var a = arr.indexOf(this.$data.formValues.emailAddress)
                    // Means exist throw error


                    // the value is exist in
                    if(a != -1 ) {
                      // here we check value position is diferent
                      if(arr[a] == this.$data.formValues.userEmail2) {
                      } else {
                        this.existEmail = true
                      return false
                      }

                    }



                    let that = this
                    setTimeout(function(){
                      User.updateUser(that.formValues.id,that.formValues).then(
                          (response)=> {
                          toastr.success('User has been updated successfully.', 'Update User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                            setTimeout(Plugin.reloadPage, 500);
                            // that.$data.formValues = that.initialState();
                            // that.updateUserList();
                          },
                          (error)=>{
                          }

                        )

                        /*axios.post("/api/user/update/"+that.formValues.id, that.formValues).then((response) => {
                            toastr.success('User has been updated successfully.', 'Update User', {timeOut: 5000});
                            $("#user_form_modal").modal("hide");
                             setTimeout(Plugin.reloadPage, 500);
                            that.$data.formValues = that.initialState();
                            that.updateUserList();
                        }); */
                    },1000)

                    }
                }).catch((errors) => {
                    // toastr['error']('Please fill all required fields ', 'Error')
                 });
            },
            userTypeChanged() {
              let roleData = null;
              let selectedUserType = this.formValues.userType;

              if(selectedUserType) {
                roleData = _.head(_.filter(this.userRolesOptions, function(o) { return selectedUserType == o.id; }));
              }

              this.showOrganisation = false;
              if(roleData && roleData.slug !== 'mobile.user') {
                this.showOrganisation = true;
              }
            },
        }
    }
</script>
