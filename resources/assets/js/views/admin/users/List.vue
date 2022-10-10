<template>
    <div>
        <div class="add_user_btn d-flex align-items-center justify-content-end">
          <button v-if="!isMasterAdmin" type="button" class="btn btn-primary mr-1" @click='exportTableReport()'>{{$lang.summary_button_download}}</button>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#user_form_modal" @click="addUser()">{{$lang.user_management_add_new_user}}</button>
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row d-flex flex-row align-items-center mb-3 ">
                      <div class="col-md-5">
                            <p class="mb-0" v-if="currentLayout == 'commercialisation'">{{$lang.user_management_commercialisation_all_users_sentence}}</p>
                            <p class="mb-0" v-else>{{$lang.user_management_all_users_sentence}}</p>
                      </div>
                      <div class="col-md-7">
                        <div class="row align-items-center justify-content-end">
                          <div class="col-12">
                            <div class="row">
                              <div class="col-md-5">
                                <input type="text" class="form-control"
                                      v-on:keyup="searchUserData" v-model="userListSearch"
                                      placeholder="Search for a user">
                              </div>
                              <div class="col-md-5">
                                <select class="form-control ls-select2" v-on:change="searchTypeData"
                                    v-model="userTypeSearch" name="user_type" id="user_type">
                                    <option value="">Filter by user type</option>
                                    <option value="customer">Customer</option>
                                    <option value="Internal.administrator">Internal administrator</option>
                                    <option v-if="currentLayout == 'tmp'" value="Master.administrator">Master administrator</option>
                                    <option v-if="!isMasterAdmin" value="mobile.user">Mobile user</option>
                                    <option v-if="currentLayout == 'tmp'" value="Results.administrator">Results administrator</option>
                                    <option v-if="!isMasterAdmin" value="Super.administrator">Super administrator</option>
                                    <option v-if="currentLayout == 'tmp'" value="tournament.administrator">Tournament administrator</option>
                                </select>
                              </div>
                              <div class="col-md-2">
                                <button type="button" class="btn btn-primary w-100" @click='clear()'>{{$lang.user_management_clear_button}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row d-flex flex-row align-items-center">
                        <div class="col-md-12">
                          <div class="table-responsive mb-3">
                            <table class="table add-category-table users-table mb-0" style="border-bottom: 1px solid #eceeef">
                                <thead>
                                    <tr>
                                        <th>{{$lang.user_desktop_name}}</th>
                                        <th>{{$lang.user_list_desktop_surname}}</th>
                                        <th>{{$lang.user_desktop_email}}</th>
                                        <th>{{$lang.user_desktop_source}}</th>
                                        <th>{{$lang.user_desktop_usertype}}</th>
                                        <th>{{$lang.use_desktop_role}}</th>
                                        <th>{{$lang.use_desktop_country}}</th>
                                        <th>{{$lang.use_desktop_language}}</th>
                                        <th>{{$lang.user_desktop_status}}</th>
                                        <th>{{$lang.user_desktop_tournment}}</th>
                                        <th>{{$lang.user_device}}</th>
                                        <th>{{$lang.user_app_version}}</th>
                                        <th class="text-center">{{$lang.user_desktop}}</th>
                                        <th class="text-center">{{$lang.user_mobile}}</th>
                                        <th>{{$lang.user_desktop_action}}</th>
                                    </tr>
                                </thead>
                                <tbody v-if="!isListGettingUpdate">
                                  <tr class="" v-for="user in userList.data">
                                    <td>{{ user.first_name }}</td>
                                    <td>{{ user.last_name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.provider | capitalize }}</td>
                                    <td>{{ user.role_name }}</td>
                                    <td>{{ user.role }}</td>
                                    <td>{{ user.country }}</td>
                                    <td>{{ allLanguages[user.locale] }}</td>
                                    <td v-if="user.is_verified == 1">Verified</td>
                                    <td v-else>
                                      <a href="#"  @click="resendModalOpen(user.email)"><u>Re-send</u></a>
                                    </td>
                                    <td class="text-center">
                                      <a v-if="user.role_slug == 'customer' && user.tournaments_count != 0" @click="redirectToTournamentList(user)" href="javascript:void(0)" class="centered text-primary"><u>{{ user.tournaments_count }}</u></a>
                                      <span v-else-if="user.role_slug == 'customer' && user.tournaments_count == 0">{{ user.tournaments_count }}
                                      </span>
                                      <span v-else>-</span>
                                    </td>
                                    <td>{{ user.device }}</td>
                                    <td>{{ user.app_version }}</td>
                                    <td class="text-center">
                                      <i class="fas fa-check text-success"
                                        v-if="user.is_desktop_user == true"></i>
                                      <i class="fas fa-times text-danger"
                                        v-else></i>
                                    </td>
                                    <td class="text-center">
                                      <i class="fas fa-check text-success"
                                        v-if="user.is_mobile_user == true"></i>
                                      <i class="fas fa-times text-danger"
                                        v-else></i>
                                    </td>
                                    <td>
                                        <a class="text-primary" href="javascript:void(0)"
                                         @click="editUser(user.id)" v-if="!(isMasterAdmin == true && user.role_slug == 'Super.administrator')">
                                        <i class="fas fa-pencil"></i>
                                        </a>

                                        <a href="javascript:void(0)"
                                        data-confirm-msg="Are you sure you would like to delete
                                        this user record?" data-toggle="modal" data-target="#delete_modal"
                                        @click="prepareDeleteResource(user.id)"
                                        v-if="(!(isMasterAdmin == true && user.role_slug == 'Super.administrator'
                                        || user.role_slug != 'tournament.administrator') || !isTournamentAdmin)">
                                        <i class="fas fa-trash"></i>
                                        </a>

                                        <a v-if="(user.role_slug == 'tournament.administrator' || user.role_slug == 'Results.administrator')" class="text-primary icon-size-1-2" href="javascript:void(0)"
                                        @click="editTournamentPermission(user, false)">
                                        <i class="fas fa-eye fa-1x"></i>
                                        </a>

                                        <!--<a v-if="IsSuperAdmin == true"
                                        href="javascript:void(0)"
                                        data-confirm-msg="Are you sure you
                                        would like to
                                        re-activate this user?"
                                        data-toggle="modal"
                                        data-target="#active_modal"
                                        @click="prepareDisableResource(user.id,user.is_active)"
                                        >
                                        <i class="fas fa-check text-success"
                                        v-if="user.is_active == true"></i>
                                        <i class="fas fa-times text-danger"
                                        v-else></i>
                                        </a>-->
                                    </td>
                                  </tr>
                                </tbody>
                            </table>
                          </div>
                            <div class="row d-flex flex-row align-items-center" v-if="!isListGettingUpdate && userList.data.length > 0">
                              <div class="col page-dropdown">
                                <select class="form-control ls-select2" name="no_of_records" v-model="no_of_records" @change="onNoOfRecordsChange()">
                                  <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
                                      {{ recordCount }}
                                  </option>
                                </select>
                              </div>
                              <div class="col">
                                <span>
                                  Viewing {{ userList.from + '-' + userList.to }} of {{ userList.total }} results
                                </span>
                              </div>
                              <div class="col-md-6">
                                <pagination :align="'right'" :class="'mb-0'" :show-disabled="true" :limit="1" :data="userList" @pagination-change-page="getResults"></pagination>
                              </div>
                            </div>
                          <div v-if="userList.userCount == 0" class="col-md-12">
                              <h6 class="block text-center">No record found</h6>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <user-modal v-if="userStatus" :userId="userId"
        :userRoles="userRoles" :publishedTournaments="publishedTournaments" :isMasterAdmin="isMasterAdmin" @showChangePrivilegeModal="showChangePrivilegeModal()" @editTournamentPermission="editTournamentPermission"></user-modal>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <resend-modal :resendConfirm="resendConfirm" @confirmed="resendConfirmed()"></resend-modal>
        <active-modal
           :activeConfirm="activeConfirm"
           :uStatusData="uData"
           @confirmed="activeConfirmed()"
           @closeModal="closeConfirm()">
         </active-modal>
         <!-- <tournament-permission-modal :user="currentUserInTournamentPermission"></tournament-permission-modal> -->
         <permission-modal :user="currentUserInTournamentPermission" :isCompulsoryTournamentSelection="isCompulsoryTournamentSelection"></permission-modal>
         <confirm-privilege-change-modal @confirmed="privilegeChangeConfirmed()"></confirm-privilege-change-modal>
    </div>
</template>
<script type="text/babel">
    import DeleteModal from '../../../components/DeleteModal.vue'
    import ResendModal from '../../../components/Resendmail.vue'
    import UserModal  from  '../../../components/UserModal.vue'
    import ActiveModal  from  '../../../components/ActiveModal.vue'
    import TournamentPermissionModal from '../../../components/TournamentPermissionModal.vue'
    import PermissionModal from '../../../components/PermissionModal.vue'
    import ConfirmPrivilegeChangeModal from '../../../components/ConfirmPrivilegeChangeModal.vue'
    import User from '../../../api/users.js'
    import Tournament from '../../../api/tournament.js'
    import VuePaginate from 'vue-paginate'
    import pagination from 'laravel-vue-pagination'


    export default {
        components: {
            DeleteModal,
            ResendModal,
            UserModal,
            ActiveModal,
            TournamentPermissionModal,
            PermissionModal,
            ConfirmPrivilegeChangeModal,
            pagination,
        },
        data() {
            return {
                userModalTitle: 'Add User',
                deleteConfirmMsg: 'Are you sure you would like to delete this user? Removing this user will delete their account and information.',
                resendConfirm: 'Are you sure you would like to send this user another invite?',
                activeConfirm: 'Are you sure you would like to de-activate this user?',
                deleteAction: '',
                image: '',
                userData: '',
                userType: '',
                page: '',
                userListSearch: '',
                userTypeSearch: '',
                userStatus: false,
                userId: '',
                uStatusData:'',
                reportQuery:'',
                enb: false,
                userRoles: [],
                publishedTournaments: [],
                shown: false,
                no_of_records: 20,
                recordCounts: [5,10,20,50,100],
                currentUserInTournamentPermission: null,
                allLanguages: [],
                currentLayout: this.$store.state.Configuration.currentLayout,
                isCompulsoryTournamentSelection: false,
            }
        },

        props: {
            userList: Object,
            isListGettingUpdate: Boolean
        },
        computed: {
            IsSuperAdmin() {
                return this.$store.state.Users.userDetails.role_slug == 'Super.administrator';
            },
            uData(){
              return this.uStatusData
            },
            isMasterAdmin() {
              return this.$store.state.Users.userDetails.role_slug == 'Master.administrator';
            },
            isTournamentAdmin() {
              return this.$store.state.Users.userDetails.role_slug == 'tournament.administrator';
            },
        },
        filters: {
            formatDate: function(date) {
              return moment(date).format("HH:mm  DD MMM YYYY");
            },
            capitalize: function (value) {
              if (!value) return '';
              value = value.toString();
              return value.charAt(0).toUpperCase() + value.slice(1);
            }
          },
        created() {
            this.$root.$on('getResults', this.getResults)
        },
        beforeCreate: function() {
          this.$root.$off('getResults');
        },
        mounted() {
          // here we check the permission to allowed to access users list
          let role_slug = this.$store.state.Users.userDetails.role_slug
          if(role_slug == 'Internal.administrator') {
            toastr['error']('Permission denied', 'Error');
            this.$router.push({name: 'welcome'});
          }

          this.getRolesWithData();
          this.getPublishedTournaments();
          this.getLanguageData();

         setTimeout(() => {
            this.shown = true
          }, 1000)
        },
        methods: {
          clear() {
            this.userListSearch = ''
            this.userTypeSearch = ''
            //call method for refresh
            this.$root.$emit('clearSearch')
          },
          searchUserData(e) {
            this.$root.$emit('setSearch', this.userListSearch,this.userTypeSearch, 1, this.no_of_records);
            var first_name = $("#user_first_name").val();
            var last_name = $("#user_last_name").val();
            var email = $("#user_email").val();
            var searchdata = "&first_name="+ first_name + "&last_name=" + last_name + "&email=" + email;
         },
          searchTypeData() {
            this.searchUserData();
          },
          getRolesWithData() {
              User.getRolesWithData().then(
                (response)=> {
                  this.userRoles = response.data.roles;
                },
                (error)=> {
                }
              )
            },
            getPublishedTournaments() {
              let data = { 'status' : 'Published' }
              Tournament.getTournamentByStatus(data).then(
                (response)=> {
                  this.publishedTournaments = response.data.data;
                },
                (error)=> {
                }
              )
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
                this.userId = id;
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
            },
            privilegeChangeConfirmed() {
              this.$root.$emit('privilegeChangeConfirmed');
              $('#confirm_privilege_modal').modal('hide');
            },
            exportTableReport() {
                let userData = this.reportQuery
                let userSearch = '';
                let userSlugType = '';
                userSearch = 'userData='+this.userListSearch;
                userSlugType = 'userType='+this.userTypeSearch;

                userData += 'report_download=yes&' + userSearch + '&' + userSlugType;
                $("body .js-loader").removeClass('d-none');
                User.getUserTableData(userData).then(
                  (response)=> {
                    toastr.success('Downloaded file will be sent to you shortly via email', 'File sent', {timeOut: 5000});
                    $("body .js-loader").addClass('d-none');
                    //setTimeout(Plugin.reloadPage, 500);
                  },
                  (error)=>{
                    $("body .js-loader").addClass('d-none');
                  }
                )
             },
            editTournamentPermission(user, isCompulsorySelection) {
              this.isCompulsoryTournamentSelection = isCompulsorySelection;
              this.currentUserInTournamentPermission = user;
              this.$root.$emit('getUserTournaments', user);
              if(this.$store.state.Users.userDetails.role_slug != 'tournament.administrator') {
                this.$root.$emit('getUserWebsites', user);
              }
              $('#permission_modal').modal('show');
              $('#permission_modal ul.nav-tabs a').first().trigger('click');

            },
            showChangePrivilegeModal() {
              $('#confirm_privilege_modal').modal('show');
            },
            redirectToTournamentList(user){
                this.$router.push({name: 'userstourmanent', query: {id:user.id}});
            },
            getLanguageData() {
              User.getAllLanguages().then(
                (response)=> {
                  this.allLanguages = response.data;
                },
                (error)=> {
                }
              )
            },
            getResults(page = 1) {
                this.$root.$emit('setSearch', this.userListSearch,this.userTypeSearch, page, this.no_of_records);
            },
            onNoOfRecordsChange() {
                this.$root.$emit('setSearch', this.userListSearch,this.userTypeSearch, 1, this.no_of_records);
            }
        }
    }
</script>
