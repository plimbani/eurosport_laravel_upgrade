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
                                            <a class="text-primary" href="javascript:void(0)" @click="editUser(user.id)"><i class="jv-icon jv-edit"></i></a>
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
        :userRoles="userRoles"></user-modal>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <resend-modal :resendConfirm="resendConfirm" @confirmed="resendConfirmed()"></resend-modal>
        <active-modal v-if="enb == true"
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
                deleteConfirmMsg: 'Are you sure you would like to delete this user record?',
                resendConfirm: 'Are you sure you would like to send this user another invite?',
                activeConfirm: 'Are you sure you would like to de-activate this user?',
                deleteAction: '',
                image: '',
                userStatus: false,
                userId: '',
                uStatusData:'',
                enb: false,
                userRoles: []
            }
        },

        props: {
            userList: Object
        },
        computed: {
            IsSuperAdmin() {
                return this.$store.state.Users.userDetails.role_slug == 'Super.administrator';
            },
            uData(){
              return this.uStatusData
            }
        },
        mounted() {
          setTimeout( function(){
            if ($(document).height() > $(window).height()) {
              $('.site-footer').removeClass('sticky');
           } else {
             $('.site-footer').addClass('sticky');
           }
          },2000 )
          this.getRoles()
        },
        methods: {
          getRoles() {
            axios.get("/api/roles-for-select").then((response) => {
                    this.userRoles = response.data;
                });
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
                let emailData = this.resendEmail
                axios.post("/api/user/resendEmail",{'email':emailData}).then((response) => {
                    $("#resend_modal").modal("hide");
                     toastr.success('The invite email has been re-sent successfully.', 'Mail Sent', {timeOut: 5000});
                });
            },
            resendModalOpen(data) {
                this.resendEmail = data
                $('#resend_modal').modal('show');
            },

            prepareDeleteResource(id) {
                this.deleteAction="/api/user/delete/"+id;
            },
            prepareDisableResource(id,status){
              this.enb =  true
              this.uStatusData={'id':id,'status':status}
            },
            activeConfirmed() {
              axios.post("/api/user/status",{'userData':this.uStatusData}).then((response) => {
                  $("#active_modal").modal("hide");
                  if(response.data.status_code == 200) {
                      toastr.success(response.data.message,{timeOut: 3000});
                      setTimeout(Plugin.reloadPage, 500);
                  }

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
