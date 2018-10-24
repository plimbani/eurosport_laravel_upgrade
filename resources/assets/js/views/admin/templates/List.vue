<template>
    <div>
        <div class="add_user_btn d-flex align-items-center justify-content-end">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#template_form_modal" @click="addTemplate()">{{$lang.template_management_add_new_template}}</button>
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row d-flex flex-row align-items-center mb-3 ">
                      <div class="col-md-5">
                      </div>
                      <div class="col-md-7">
                        <div class="row align-items-center justify-content-end">
                          <div class="col-12">
                            <div class="row">
                              <div class="col-md-5">
                                <select class="form-control ls-select2" v-model="teamSearch" name="teams" id="teams">
                                    <option value="">Number of teams</option>
                                    <option value="Internal.administrator">Internal administrator</option>
                                    <option value="Master.administrator">Master administrator</option>
                                    <option value="mobile.user">Mobile user</option>
                                    <option value="Super.administrator">Super administrator</option>
                                    <option value="tournament.administrator">Tournament administrator</option>
                                </select>
                              </div>
                              <div class="col-md-5">
                                <select class="form-control ls-select2" v-model="createdBySearch" name="created_by" id="created_by">
                                    <option value="">Created by</option>
                                    <option value="Internal.administrator">Internal administrator</option>
                                    <option value="Master.administrator">Master administrator</option>
                                    <option value="mobile.user">Mobile user</option>
                                    <option value="Super.administrator">Super administrator</option>
                                    <option value="tournament.administrator">Tournament administrator</option>
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
                            <table class="table add-category-table">
                                <thead>
                                    <tr>
                                        <th>{{$lang.template_name}}</th>
                                        <th>{{$lang.template_teams}}</th>
                                        <th>{{$lang.template_created_date}}</th>
                                        <th>{{$lang.template_action}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="template in templateList">
                                    <td>{{ template.name }}</td>
                                    <td>{{ template.total_teams }}</td>
                                    <td>{{ template.created_at | createdAtFilter }}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                          data-confirm-msg="Are you sure you would like to delete
                                          this template record?" data-toggle="modal" data-target="#delete_modal"
                                          @click="deleteTournament(template.id)">
                                          <i class="jv-icon jv-dustbin"></i>
                                        </a>
                                        <a class="text-primary" href="javascript:void(0)"
                                         @click="editTemplate(template.id)">
                                          <i class="jv-icon jv-edit"></i>
                                        </a>
                                        <a class="text-primary" href="javascript:void(0)"
                                         @click="openTemplateInfoModal(template)">
                                          <i class="fa fa-info-circle"></i>
                                        </a>
                                    </td>
                                  </tr>
                                  <tr><td colspan="8"></td></tr>
                                </tbody>
                            </table>
                            <!-- <paginate v-if="shown" name="templatePagination" :list="templateList" ref="paginator" :per="no_of_records"  class="paginate-list"> -->
                            <!-- </paginate> -->
                            <div class="row d-flex flex-row align-items-center">
                              <div class="col page-dropdown">
                                <select class="form-control ls-select2" name="no_of_records" v-model="no_of_records">
                                  <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
                                      {{ recordCount }}
                                  </option>
                                </select>
                              </div>
                              <div class="col">
                                <span v-if="$refs.paginator">
                                  Viewing {{ $refs.paginator.pageItemsCount }} results
                                </span>
                              </div>
                              <div class="col-md-6">
                                <paginate-links for="templatePagination"
                                  :show-step-links="true" :async="true" class="mb-0">
                                </paginate-links>
                              </div>
                            </div>
                        </div>
                        <div v-if="templateList.length == 0" class="col-md-12">
                            <h6 class="block text-center">No record found</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <template-info-modal v-if="templateInfoModal" :templateDetail="templateDetail"></template-info-modal>
    </div>
</template>
<script type="text/babel">
    import moment from 'moment'
    import Template from '../../../api/template.js'
    import Tournament from '../../../api/tournament.js'
    import VuePaginate from 'vue-paginate'
    import DeleteModal from '../../../components/DeleteModal.vue'
    import TemplateInfoModal from '../../../components/TemplateInfoModal.vue'

    export default {
        components: {
          TemplateInfoModal, DeleteModal
        },
        data() {
            return {
                page: '',
                enb: false,
                paginate: ['templatePagination'],
                shown: false,
                no_of_records: 20,
                recordCounts: [5,10,20,50,100],
                teamSearch: '',
                createdBySearch: '',
                templateInfoModal: false,
                templateDetail: '',
                deleteConfirmMsg: 'Are you sure you would like to delete this template',
            }
        },
        props: {
            templateList: this.templateList,
        },
        computed: {
        },
        filters: {
          createdAtFilter(value) {
            return moment(value).format("Do MMMM YYYY");
          }
        },
        mounted() {
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
          }, 2000);

         setTimeout(() => {
            this.shown = true
          }, 1000)
        },
        methods: {
          openTemplateInfoModal(template) {
            this.templateInfoModal = true;
            this.templateDetail = template;
            this.$root.$emit('getTemplateDetail');
            $('#template_info_modal').modal('show');
          },
          editTemplate(templateId) {
            return;
          },
          deleteTournament(templateId) {
            return;
          },
          clear() {
            this.teamSearch = '';
            this.createdBySearch = '';
          },
          deleteConfirmed() {

          }
        }
    }
</script>
