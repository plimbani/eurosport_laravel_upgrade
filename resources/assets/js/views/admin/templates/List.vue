<template>
    <div>
        <div class="add_user_btn d-flex align-items-center justify-content-end add-new-template">
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
                          <select class="form-control ls-select2" v-model="teamSearch" name="teams" id="teams" v-on:change="filterData">
                              <option value="">Number of teams</option>
                              <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                          </select>
                        </div>
                        <div class="col-md-5">
                          <select class="form-control ls-select2" v-model="createdBySearch" name="created_by" id="created_by" v-on:change="filterData">
                              <option value="">Created by</option>
                              <option :value="user.id" v-for="user in users"> {{ user.email }}</option>
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
                    <div class="table-responsive">
                      <table class="table add-category-table tbl-template">
                        <thead>
                            <tr>
                                <th>{{$lang.template_name}}</th>
                                <th>{{$lang.template_teams}}</th>
                                <th>{{$lang.template_min_matches}}</th>
                                <th>{{$lang.template_avg_teams}}</th>
                                <th>{{$lang.template_total_matches}}</th>
                                <th>{{$lang.template_divisions}}</th>
                                <th>{{$lang.template_version}}</th>
                                <th>{{$lang.template_created_date}}</th>
                                <th>{{$lang.template_created_by}}</th>
                                <th>{{$lang.template_action}}</th>
                            </tr>
                        </thead>
                        <tbody v-if="!isListGettingUpdate">
                          <tr class="" v-for="template in templateList.templateData.data">
                            <td>{{ template.name }}</td>
                            <td>{{ template.total_teams }}</td>
                            <td>{{ template.minimum_matches }}</td>
                            <td>{{ template.avg_matches }}</td>
                            <td>{{ template.total_matches }}</td>
                            <td>{{ template.no_of_divisions }}</td>
                            <td>{{ template.version }}</td>
                            <td>{{ template.created_at | createdAtFilter }}</td>
                            <td>{{ template.userEmail }}</td>
                            <td>
                                <a class="text-primary" href="javascript:void(0)"
                                 @click="openTemplateInfoModal(template)">
                                  <i class="fa fa-info-circle"></i>
                                </a>
                                <a class="text-primary" href="javascript:void(0)"
                                 @click="editTemplate(template.id)">
                                  <i class="jv-icon jv-edit"></i>
                                </a>
                                <a href="javascript:void(0)"
                                  @click="deleteTemplate(template)">
                                  <i class="jv-icon jv-dustbin"></i>
                                </a>
                            </td>
                          </tr>
                          <tr><td colspan="10"></td></tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="row d-flex flex-row align-items-center" v-if="!isListGettingUpdate">
                      <div class="col page-dropdown">
                        <select class="form-control ls-select2" name="no_of_records" v-model="no_of_records" @change="onNoOfRecordsChange()">
                          <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
                              {{ recordCount }}
                          </option>
                        </select>
                      </div>
                      <div class="col">
                        <span>
                          Viewing {{ templateList.templateData.from + '-' + templateList.templateData.to }} of {{ templateList.templateData.total }} results
                        </span>
                      </div>
                      <div class="col-md-6">
                        <pagination :align="'right'" :show-disabled="true" :limit="1" :data="templateList.templateData" @pagination-change-page="getResults"></pagination>
                      </div>
                    </div>
                  </div>
                  <div v-if="templateList.templateData.data == 0" class="col-md-12">
                      <h6 class="block text-center">No record found</h6>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <template-info-modal v-show="templateInfoModal" :templateDetail="templateDetail"></template-info-modal>
        <template-in-use-modal v-show="templateInUseModal"></template-in-use-modal>
        <add-template-modal @addTemplateModalHidden="addTemplateModalHidden" v-if="isAdd"></add-template-modal>
        <edit-template-modal :editTemplateDetail="editTemplateDetail" @editTemplateModalHidden="editTemplateModalHidden" v-if="isEdit"></edit-template-modal>
    </div>
</template>
<script type="text/babel">
    import moment from 'moment'
    import Template from '../../../api/template.js'
    import Tournament from '../../../api/tournament.js'
    import VuePaginate from 'vue-paginate'
    import pagination from 'laravel-vue-pagination'
    import DeleteModal from '../../../components/DeleteModal.vue'
    import TemplateInfoModal from '../../../components/TemplateInfoModal.vue'
    import TemplateInUseModal from '../../../components/TemplateInUseModal.vue'
    import AddTemplateModal from '../../../components/Template/AddTemplateModal.vue'
    import EditTemplateModal from '../../../components/Template/EditTemplateModal.vue'

    export default {
        components: {
          TemplateInfoModal, DeleteModal, TemplateInUseModal, AddTemplateModal, EditTemplateModal, pagination
        },
        data() {
            return {
                users: '',
                page: '',
                // paginate: ['templateData'],
                shown: false,
                no_of_records: 20,
                recordCounts: [5,10,20,50,100],
                teamSearch: '',
                createdBySearch: '',
                templateInfoModal: false,
                templateInUseModal: false,
                templateDetail: '',
                deleteAction: '',
                editTemplateDetail: '',
                isEdit: false,
                isAdd: false,
                deleteConfirmMsg: 'Are you sure you would like to delete this template?',
            }
        },
        props: {
          templateList: Object,
          isListGettingUpdate: Boolean
        },
        filters: {
          createdAtFilter(value) {
            return moment(value).format("Do MMMM YYYY");
          }
        },
        created() {
          this.getUsersForFilter();
        },
        mounted() {
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
            this.templateDetail = template;
            this.templateInfoModal = true;
            setTimeout(() => {
              $('#template_info_modal').modal('show');
              this.$root.$emit('getTemplateDetail');
            }, 500);
          },
          deleteTemplate(template) {
            Template.getTemplateDetail(template).then(
              (response)=> {
                  if(response.data.data.length == 0) {
                    this.deleteAction="template/delete/"+template.id;
                    $('#delete_modal').modal('show');
                  } else {
                    this.templateInUseModal = true;
                    $('#template_in_use_modal').modal('show');
                  }
              },
              (error)=> {
              }
            )
          },
          addTemplate() {
            this.isAdd = true;
            Vue.nextTick()
            .then(function () {
              $('#add_new_template_modal').modal('show');
            });
          },
          addTemplateModalHidden() {
            this.isAdd = false;
          },
          editTemplateModalHidden() {
            this.isEdit = false;
          },
          editTemplate(templateId) {
            Template.editTemplate(templateId).then(
              (response)=> {
                this.isEdit = true;
                this.editTemplateDetail = response.data.data;
                Vue.nextTick()
                  .then(function () {
                    $('#edit_template_modal').modal('show');
                });
              },
              (error)=> {
              }
            )
          },
          clear() {
            this.teamSearch = '';
            this.createdBySearch = '';
            this.$root.$emit('clearSearch');
          },
          deleteConfirmed() {
            $("body .js-loader").removeClass('d-none');
            Template.deleteTemplate(this.deleteAction).then(
              (response)=> {
                $("body .js-loader").addClass('d-none');
                $("#delete_modal").modal("hide");
                toastr.success('Template has been deleted successfully.', 'Delete Template', {timeOut: 5000});
                this.$parent.getTemplates();
              },
              (error)=> {
              }
            )
          },
          filterData() {
            this.$root.$emit('setSearch', this.teamSearch, this.createdBySearch);
          },
          getUsersForFilter() {
            Template.getUsersForFilter().then(
              (response)=> {
                this.users = response.data.data;
              },
              (error)=> {
              }
            )
          },
          getResults(page = 1) {
              this.$root.$emit('setSearch', this.teamSearch,this.createdBySearch, page, this.no_of_records);
          },
          onNoOfRecordsChange() {
              this.$root.$emit('setSearch', this.teamSearch,this.createdBySearch, 1, this.no_of_records);
          }
        }
    }
</script>
