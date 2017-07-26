<template>
    <div>
        <div class="add_user_btn">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#user_form_modal" @click="addUser()">{{$lang.user_management_add_new_user}}</button>
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row d-flex flex-row align-items-center">
                        <div class="col-md-6">
                            <p v-if="registerType != 'mobile'">{{$lang.user_management_sentence}}</p>
                            <p v-else>{{$lang.user_management_sentence_tournament}}</p>
                        </div>
                        <div class="col-md-6">
                          <div class="row justify-content-end">
                              <div class="col-md-6">
                                 <div class="form-group">
                                      <div>
                                          <input type="text" class="form-control"
                                          v-on:keyup="searchUserData" v-model="userListSearch"
                                          placeholder="Search for a user">
                                      </div>
                                  </div>
                              </div>
                               <div class="col-md-3">
                                  <div class="form-group">
                                      <button type="button" class="btn btn-primary w-100" @click='exportTableReport()'>Clear</button>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <button type="button" class="btn btn-primary w-100" @click='exportTableReport()'>{{$lang.summary_button_download}}</button>
                                  </div>
                              </div>
                          </div>
                        </div>

                        <div class="col-md-12">
                            <table class="table add-category-table">
                                <thead>
                                    <tr>
                                        <th>{{$lang.user_desktop_name}}</th>
                                        <th>{{$lang.user_desktop_surname}}</th>
                                        <th>{{$lang.user_desktop_email}}</th>
                                        <th v-if="registerType != 'mobile'">{{$lang.user_desktop_organisation}}</th>
                                        <th v-else>Date & time</th>
                                        <th v-if="registerType != 'mobile'">{{$lang.user_desktop_usertype}}</th>
                                        <th>{{$lang.user_desktop_status}}</th>
                                        <th>{{$lang.user_desktop_action}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="user in paginated('userpagination')">
                                    <td>{{ user.person_detail.first_name }}</td>
                                    <td>{{ user.person_detail.last_name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td v-if="user.is_mobile_user == 0">{{ user.organisation }}</td>
                                    <td v-else>{{user.created_at | formatDate}}</td>
                                    <td v-if="(user.roles).length>0 && registerType != 'mobile'">{{ user.roles[0].name }}</td>

                                    <td v-if="user.is_verified == 1 && user.is_mobile_user == 0">Accepted</td>
                                    <td class="text-left" v-if="user.is_mobile_user == 1 && user.is_verified == 1">Verified</td>
                                    <td class="text-left" v-if="user.is_verified == 0">
                                    <a href="#"  @click="resendModalOpen(user.email)"><u>Re-send</u></a>
                                    </td>
                                    <td>
                                        <a class="text-primary" href="javascript:void(0)"
                                         @click="editUser(user.id)">
                                        <i class="jv-icon jv-edit"></i>
                                        </a>
                                        &nbsp;
                                        <a href="javascript:void(0)"
                                        data-confirm-msg="Are you sure you would like to delete
                                        this user record?" data-toggle="modal" data-target="#delete_modal"
                                        @click="prepareDeleteResource(user.id)">
                                        <i class="jv-icon jv-dustbin"></i>
                                        </a>
                                        &nbsp;
                                        <a v-if="IsSuperAdmin == true"

                                        href="javascript:void(0)"
                                        data-confirm-msg="Are you sure you
                                        would like to
                                        re-activate this user?"
                                        data-toggle="modal"
                                        data-target="#active_modal"
                                        @click="prepareDisableResource(user.id,user.is_active)"
                                        >
                                        <i class="jv-icon jv-checked-arrow text-success"
                                        v-if="user.is_active == true"></i>
                                        <i class="jv-icon jv-close text-danger"
                                        v-else></i>
                                        </a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="7">
                                      <paginate v-if="shown" name="userpagination" :list="userList.userData" ref="paginator" :per="20"  class="paginate-list">
                                      </paginate>
                                      <paginate-links for="userpagination"
                                        :show-step-links="true"
                                         :async="true"
                                      ></paginate-links>
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
        <user-modal v-if="userStatus" :userId="userId"
        :userRoles="userRoles" :userEmailData="userEmailData"></user-modal>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <resend-modal :resendConfirm="resendConfirm" @confirmed="resendConfirmed()"></resend-modal>
        <active-modal
         :activeConfirm="activeConfirm"
         :uStatusData="uData"
         @confirmed="activeConfirmed()"
         @closeModal="closeConfirm()">

         </active-modal>
    </div>
</template>
<script type="text/babel">
    import DeleteModal from '../../../components/DeleteModal.vue'
    import ResendModal from '../../../components/Resendmail.vue'
    import UserModal  from  '../../../components/UserModal.vue'
    import ActiveModal  from  '../../../components/ActiveModal.vue'
    import User from '../../../api/users.js'
    import VuePaginate from 'vue-paginate'


    export default {
        components: {
            DeleteModal,
            ResendModal,
            UserModal,
            ActiveModal
        },
        data() {
            return {
                userRolesOptions: [],
                userModalTitle: 'Add User',
                deleteConfirmMsg: 'Are you sure you would like to delete this user? Removing this user will delete their account and information.',
                resendConfirm: 'Are you sure you would like to send this user another invite?',
                activeConfirm: 'Are you sure you would like to de-activate this user?',
                deleteAction: '',
                image: '',
                userData: '',
                page: '',
                userListSearch: '',
                userStatus: false,
                userId: '',
                uStatusData:'',
                reportQuery:'',
                enb: false,
                userRoles: [],
                userEmailData: this.userList,
                paginate: ['userpagination'],
                shown: false
            }
        },

        props: {
            userList: Object,
            registerType: String
        },
        computed: {
            IsSuperAdmin() {
                return this.$store.state.Users.userDetails.role_slug == 'Super.administrator';
            },
            uData(){
              return this.uStatusData
            }
        },
        filters: {
            formatDate: function(date) {
            return moment(date).format("HH:mm  DD MMM YYYY");
             },
          },
        mounted() {
          // here we check the permission to allowed to access users list
          let role_slug = this.$store.state.Users.userDetails.role_slug
          if(role_slug == 'tournament.administrator' || role_slug == 'Internal.administrator') {
            toastr['error']('Permission denied', 'Error');
            this.$router.push({name: 'welcome'});
          }
          setTimeout( function(){
            if ($(document).height() > $(window).height()) {
              $('.site-footer').removeClass('sticky');
           } else {
             $('.site-footer').addClass('sticky');
           }
          },2000 )
          this.getRoles()

         setTimeout(() => {
            this.shown = true
          }, 1000)
        },
        methods: {
          searchUserData() {
            // console.log(this.userListSearch);
            this.$root.$emit('setSearch',this.registerType,this.userListSearch);
            var first_name = $("#user_first_name").val();
            var last_name = $("#user_last_name").val();
            var email = $("#user_email").val();
            var searchdata = "&first_name="+ first_name + "&last_name=" + last_name + "&email=" + email;
         },
          getRoles() {
            User.getRoles().then(
              (response)=> {
                this.userRoles = response.data;
              },
              (error)=> {
                console.log('error in getting Roles')
              }
            )
           // axios.get("/api/roles-for-select").then((response) => {
             //       this.userRoles = response.data;
               // });
            },
            closeConfirm() {
              this.enb =  false
            },
            addUser() {
                this.userId = ''
                this.userModalTitle = "Add User";
                this.userStatus = true
                this.type = 'user'
                let vm = this
                 setTimeout(function(){
                $('#user_form_modal').modal('show')
                $("#user_form_modal").on('hidden.bs.modal', function () {
                     vm.userStatus = false
                });
              },1000)
            },
            editUser(id) {
                this.userId = id
                this.userModalTitle="Edit User";
                 this.userStatus = true
                 let vm = this
                setTimeout(function(){
                    $('#user_form_modal').modal('show')
                    $("#user_form_modal").on('hidden.bs.modal', function () {
                     vm.userStatus = false
                });
                },1000)
            },
             resendConfirmed() {
                let resendEmail = this.resendEmail
                let emailData = {'email':resendEmail}
                User.resendEmail(emailData).then(
                  (response)=> {
                    $("#resend_modal").modal("hide");
                     toastr.success('The invite email has been re-sent successfully.', 'Mail Sent', {timeOut: 5000});
                  },
                  (error)=> {

                  }
                )
               /* axios.post("/api/user/resendEmail",{'email':emailData}).then((response) => {
                    $("#resend_modal").modal("hide");
                     toastr.success('The invite email has been re-sent successfully.', 'Mail Sent', {timeOut: 5000});
                }); */
            },
            resendModalOpen(data) {
                this.resendEmail = data
                $('#resend_modal').modal('show');
            },

            prepareDeleteResource(id) {
                this.deleteAction="user/delete/"+id;
            },
            prepareDisableResource(id,status){
              this.uStatusData={'id':id,'status':status,'test':'test2'}
            },
            activeConfirmed() {
              let userData = {'userData':this.uStatusData}
              User.changeStatus(userData).then(
                (response)=> {
                  $("#active_modal").modal("hide");
                  if(response.data.status_code == 200) {
                      toastr.success(response.data.message,{timeOut: 3000});
                      setTimeout(Plugin.reloadPage, 500);
                  }
                }
              )
              /*axios.post("/api/user/status",{'userData':this.uStatusData}).then((response) => {
                  $("#active_modal").modal("hide");
                  if(response.data.status_code == 200) {
                      toastr.success(response.data.message,{timeOut: 3000});
                      setTimeout(Plugin.reloadPage, 500);
                  }

                }); */
            },
            deleteConfirmed() {
                User.deleteUser(this.deleteAction).then(
                  (response)=> {
                     $("#delete_modal").modal("hide");
                     setTimeout(Plugin.reloadPage, 500);
                    toastr.success('User has been deleted successfully.', 'Delete User', {timeOut: 5000});
                    this.updateUserList();
                  },
                  (error)=> {

                  }
                )
               /* axios.post(this.deleteAction).then((response) => {
                    $("#delete_modal").modal("hide");
                     setTimeout(Plugin.reloadPage, 500);
                    toastr.success('User has been deleted successfully.', 'Delete User', {timeOut: 5000});
                    this.updateUserList();
                }); */
            },
            exportTableReport() {
                let userData = this.reportQuery
                let userSearch = '';
                // console.log(userData);
                // console.log(ReportData)
                // let newdata = $.parseHTML( ReportData )
                // let newdata =  $(ReportData).parse();
                // let newdata = $('#frmReport').serialize();
                  if(this.userListSearch!=''){
                      userSearch = 'userData='+this.userListSearch
                  }
                   window.location.href = "/api/users/getUserTableData?report_download=yes&registerType="+this.registerType+'&'+userSearch;
                   // userData += '&report_download=yes'
                   // window.location.href = "/api/users/getUserTableData?=report_download=yes&registerType=desktop&userData=";

             }
        }
    }
</script>
