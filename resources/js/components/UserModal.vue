<template>
  <div class="modal" id="user_form_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form name="frmUser" id="frmUser">
            <div class="modal-header">
                <h5 class="modal-title">{{ userModalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div v-if="isNormalUserFields"> 
              <div class="modal-body">
                  <div class="form-group row" :class="{'has-error': errors.has('name') }">
                    <label class="col-sm-5 form-control-label">{{$lang.user_management_add_name}}</label>
                    <div class="col-sm-6">
                        <input v-model="formValues.name" v-validate="'required|alpha_spaces'"
                        :class="{'is-danger': errors.has('name') }"
                        name="name" type="text"
                        key="name"
                        class="form-control" placeholder="Enter first name">
                        <i v-show="errors.has('name')" class="fas fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}
                        </span>
                    </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-5 form-control-label">{{$lang.user_management_add_surname}}</label>
                      <div class="col-sm-6">
                          <input v-model="formValues.surname" v-validate="'required|alpha_spaces'" :class="{'is-danger': errors.has('surname') }" name="surname" key="surname" type="text" class="form-control" placeholder="Enter second name">
                          <i v-show="errors.has('surname')" class="fas fa-warning"></i>
                          <span class="help is-danger" v-show="errors.has('surname')">{{ errors.first('surname') }}</span>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-5 form-control-label">{{$lang.user_management_email}}</label>
                      <div class="col-sm-6">
                          <input v-model="formValues.emailAddress" v-validate="'required|email'" :class="{'is-danger': errors.has('email_address') }" name="email_address" key="email_address" type="email" class="form-control" placeholder="Enter email address">

                          <i v-show="errors.has('email_address')" class="fas fa-warning"></i>
                          <span class="help is-danger" v-show="errors.has('email_address')">{{$lang.user_management_email_required}}</span>
                         <span class="help is-danger" v-if="existEmail == true">Email already exists</span>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-5 form-control-label">{{$lang.user_management_user_type}}</label>
                      <div class="col-sm-6">
                        <select v-validate="'required'":class="{'is-danger': errors.has('user_type') }" class="form-control ls-select2" name="user_type" key="user_type" v-model="formValues.userType" @change="userTypeChanged()" :disabled="formValues.provider == 'facebook'" v-if="userRole != 'Tournament administrator'">
                          <option value="">Select</option>
                          <option v-for="role in getUserRolesOptions" v-bind:value="role.id" v-if="(!(isMasterAdmin == true && (role.slug == 'Super.administrator' || role.slug == 'mobile.user')))">
                              {{ role.name1 }}
                          </option>
                        </select>
                        <select class="form-control ls-select2" key="user_type" name="user_type" v-model="formValues.userType" disabled v-if="userRole == 'Tournament administrator'">
                          <option v-bind:value="getResultAdminRoleId()">Results administrator</option>
                        </select>
                        <span class="help is-danger" v-show="errors.has('user_type')">{{$lang.user_management_user_type_required}}</span>
                      </div>
                  </div>
                  <div class="form-group row" v-show="showOrganisation">
                      <label class="col-sm-5 form-control-label">{{$lang.user_management_organisation}}</label>
                      <div class="col-sm-6">
                          <input v-model="formValues.organisation" v-validate="{ rules: { required: showOrganisation } }" :class="{'is-danger': errors.has('organisation') }" name="organisation" key="organisation" type="text" class="form-control" placeholder="Enter organisation name">
                          <i v-show="errors.has('organisation')" class="fas fa-warning"></i>
                          <span class="help is-danger" v-show="errors.has('organisation')">{{$lang.user_management_organisation_required}}</span>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-5 form-control-label">{{$lang.user_management_role}}</label>
                      <div class="col-sm-6">
                        <select class="form-control ls-select2" name="sub_role" key="role" v-model="formValues.sub_role">
                            <option value="">Select</option>
                            <option v-for="role in roleOptions" :value="role">
                              {{ role }}
                            </option>
                        </select>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-5 form-control-label">{{$lang.user_management_default_app_tournament}}</label>
                      <div class="col-sm-6">
                        <select v-validate="'required'":class="{'is-danger': errors.has('tournament_id') }" class="form-control ls-select2" name="tournament_id" key="tournament_id" v-model="formValues.tournament_id">
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
            </div>
            <div v-else>
              <div class="modal-body">
                <div class="form-group row mb-0">
                  <div class="col-sm-12">
                    <p>Please enter the email address of the results administrator you would like to add.</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-5 form-control-label">{{$lang.user_management_email}}</label>
                  <div class="col-sm-6">
                      <input v-model="result_admin_email" key="result_admin_email" v-validate="resultTournamentAdministratorEmail" name="result_admin_email" :class="{'is-danger': errors.has('result_admin_email') }" type="email" class="form-control" placeholder="Enter email address">
                      <i v-show="errors.has('result_admin_email')" class="fas fa-warning"></i>
                      <span class="help is-danger" v-show="errors.has('result_admin_email')">{{$lang.user_management_email_required}}</span>
                     <span class="help is-danger" v-if="existEmail == true">Email already exists</span>
                  </div>
                </div>
                <div class="form-group row" v-if="isUserExists">
                  <div class="col-sm-12">
                    <p class="text-danger mb-0">This user already exists but cannot be added a results administrator. Please contact your super administrator for assistance.</p>
                  </div>
                </div>
                <div class="form-group row" v-if="isAlreadyAdded">
                  <div class="col-sm-12">
                    <p class="text-danger mb-0">This user has already been added.</p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_user_cancle}}</button>
                <button type="button" class="btn btn-primary" @click="verifyResultAdminUser()">{{$lang.tournament_administrator_add_user}}</button>
              </div>              
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
                    userEmail2: '',
                    tournament_id: '',
                    role: '',
                    provider: '',
                },
                userRolesOptions: [],
                userModalTitle: 'Add User',
                deleteConfirmMsg: 'Are you sure you would like to delete this user record?',
                resendConfirm: 'Are you sure you would like to send this user another invite?',

                deleteAction: '',
                emailData:[],
                existEmail: false,
                showOrganisation: false,
                initialUserType: null,
                roleOptions: ['Player', 'Coach/Manager/Trainer', 'Other'],

                result_admin_email: '',
                isUserExists: false,
                normalUserFields: false,
                userRole:this.$store.state.Users.userDetails.role_name,
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
                },
                isAlreadyAdded: false,
                currentLayout: this.$store.state.Configuration.currentLayout,
            }
        },
        computed: {
          userDetails() {
            return this.$store.state.Users.userDetails;
          },
          isNormalUserFields() {
            if((this.userDetails.role_slug != 'tournament.administrator') || (this.isUserExists == false && this.normalUserFields == true) ) {
              return true;
            }
            return false;
          },
          resultTournamentAdministratorEmail(){
            if(this.normalUserFields == false) {
              return 'required|email';
            } else {
              return '';
            }
          },
          isTournamentAdmin() {
            return this.$store.state.Users.userDetails.role_slug == 'tournament.administrator';
          },
          getUserRolesOptions(){
            let userRolesOptions = _.cloneDeep(this.userRolesOptions);
            if(this.currentLayout == 'commercialisation' && userRolesOptions.length > 0){
              userRolesOptions = _.filter(userRolesOptions, function(userRolesOption) {
                  if(userRolesOption.slug != 'Results.administrator' && userRolesOption.slug != 'tournament.administrator') {
                    return userRolesOption;
                  }
              });
            }
            return userRolesOptions;
          },
        },
        created() {
          this.$root.$on('privilegeChangeConfirmed', this.updateUser);
          this.$root.$on('updateUserList', this.updateUserList);
        },
        mounted(){
            if(this.userId!=''){
                this.editUser(this.userId)
            }
            this.userRolesOptions =  this.userRoles
            // this.$validator.updateDictionary(this.errorMessages);
            this.$validator.localize('en', this.errorMessages.en);
        },
        props:['userId','userRoles','publishedTournaments','isMasterAdmin'],
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
                    this.normalUserFields = true;
                    this.userModalTitle="Edit User";
                    this.$data.formValues = response.data;
                    this.initialUserType = response.data.userType;
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
            },
            validateBeforeSubmit1() {
                let vm = this;
                this.$validator.validateAll().then((response) => {
                  if(response) {
                    this.existEmail = false;

                    let data = {};
                    data.email = this.formValues.emailAddress;
                    if(this.formValues.id != "") {
                      data.id = this.formValues.id;
                    }
                    User.validateUserEmail(data).then(
                      (response)=> {
                        if(response.data.emailexists != true) {
                          if(vm.$data.formValues.id=="") {
                            $("body .js-loader").removeClass('d-none');
                            User.createUser(vm.formValues).then(
                              (response)=> {
                                toastr.success('User has been added successfully.', 'Add User', {timeOut: 5000});
                                $("#user_form_modal").modal("hide");
                                $("body .js-loader").addClass('d-none');

                                if(response.data.user.role_slug === 'Results.administrator' && this.isTournamentAdmin) {
                                  this.$emit('editTournamentPermission', response.data.user, true);
                                  return true;
                                }
                                vm.$root.$emit('getResults');
                              },
                              (error)=>{
                                $("body .js-loader").addClass('d-none');
                              }
                            )
                          } else {
                            let initailUserType = vm.initialUserType;
                            let initialRole = _.head(_.filter(vm.userRolesOptions, function(o) { return initailUserType == o.id; }));
                            
                            let selectedUserType = vm.formValues.userType;
                            let selectedRole = _.head(_.filter(vm.userRolesOptions, function(o) { return selectedUserType == o.id; }));

                            if(initialRole.slug == 'Super.administrator' && selectedRole.slug != 'Super.administrator') {
                              vm.$emit('showChangePrivilegeModal');
                              return false;
                            }
                            vm.updateUser();
                          }
                        } else {
                          vm.existEmail = true;
                          $("body .js-loader").addClass('d-none');
                        }
                      },
                      (error)=>{
                      }
                    )
                  }
                }).catch((errors) => {});
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
            updateUser() {
              let that = this;
              $("body .js-loader").removeClass('d-none');
              User.updateUser(that.formValues.id,that.formValues).then(
                  (response)=> {
                  toastr.success('User has been updated successfully.', 'Update User', {timeOut: 5000});
                    $("#user_form_modal").modal("hide");
                    $("body .js-loader").addClass('d-none');
                    setTimeout(Plugin.reloadPage, 500);
                  },
                  (error)=>{
                    $("body .js-loader").addClass('d-none');
                  }
                )
            },
            verifyResultAdminUser() {
              this.$validator.validateAll().then((response) => {
                if(response) {
                  let data = {'email': this.result_admin_email};
                  this.isAlreadyAdded = false;
                  User.verifyResultAdminUser(data).then(
                    (response)=> {
                      if(typeof response.data.isAlreadyAdded != 'undefined' && response.data.isAlreadyAdded == true) {
                        this.isAlreadyAdded = true;
                        return false;
                      }
                      if(response.data.emailExists) {
                        this.isUserExists = true;
                        this.normalUserFields = false;
                      } else {
                        this.isUserExists = false;
                        this.normalUserFields = true;
                        this.formValues.emailAddress = this.result_admin_email;
                      }
                      if(response.data.isResultAdmin) {
                        $("#user_form_modal").modal("hide");
                        this.$emit('editTournamentPermission', response.data.user, true);
                        // setTimeout(Plugin.reloadPage, 500);
                      }
                      this.$validator.errors.clear()
                      this.$validator.reset();
                    },
                    (error)=>{
                    }
                  )
                }
              }).catch((errors) => {});
            },
            getResultAdminRoleId() {
              var result = _.result(_.find(this.userRolesOptions, function(obj) {
                return obj.slug === 'Results.administrator';
              }), 'id');

              this.formValues.userType = result;

              return result;
            }
        }
    }
</script>
