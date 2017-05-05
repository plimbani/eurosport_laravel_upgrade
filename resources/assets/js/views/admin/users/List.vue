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
                                            <a href="javascript:void(0)" @click="editUser(user.id)"><i class="jv-icon jv-edit"></i></a>
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
        <user-modal v-if="userStatus" :userId="userId"></user-modal>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <resend-modal :resendConfirm="resendConfirm" @confirmed="resendConfirmed()"></resend-modal>
    </div>
</template>
<script type="text/babel">
    import DeleteModal from '../../../components/DeleteModal.vue'
    import ResendModal from '../../../components/Resendmail.vue'
    import UserModal  from  '../../../components/UserModal.vue'

    export default {
        components: {
            DeleteModal,
            ResendModal,
            UserModal
        },
        data() {
            return {
                userRolesOptions: [],
                userModalTitle: 'Add User',
                deleteConfirmMsg: 'Are you sure you would like to delete this user record?',
                resendConfirm: 'Are you sure you would like to send this user another invite?',

                deleteAction: '',
                image: '',
                userStatus: false,
                userId: ''
            }
        },
 
        props: {
            userList: Object
        },
       
        methods: {
            addUser() {    
                this.userId = ''
                this.userModalTitle = "Add User";
                this.userStatus = true
                this.type = 'user'
                let vm = this
                 setTimeout(function(){
                $('#user_form_modal').modal('show')

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

            prepareDeleteResource(id) {
                this.deleteAction="/api/user/delete/"+id;
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
