<template>
  <div>
    <div class="row">
      <div class="col-md-12">
         <table class="table table-hover table-bordered add-category-table">
          <thead>
              <tr>
                  <th class="text-center">{{$lang.competation_name_category}}</th>
                  <th class="text-center">{{$lang.competation_category_age}}</th>
                  <th class="text-center">{{$lang.compeattion_template}}</th>
                  <th class="text-center">{{$lang.competation_competation_format}}</th>
                  <th class="text-center">{{$lang.competation_total_matches}}</th>
                  <th class="text-center" width="90px">{{$lang.competation_total_time}}</th>
                  <th class="text-center" width="79px">{{$lang.competation_manage}}</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="(competation, index) in competationList">
              <td class="text-left">{{competation.group_name}} </td>
              <td class="text-left">{{competation.category_age}}</td>
              <td class="text-left">{{competation.template_name}}</td>
              <td class="text-left">
                <div class="d-flex justify-content-between align-items-center">
                  {{competation.disp_format_name}} 
                  <span class="pl-2">
                    <a href="#"  @click="viewCompFormat(competation.id, competation.tournament_template_id, competation.total_time)" class="btn btn-primary btn-sm ml-1 float-right">View</a>
                  </span>
                </div>
              </td>
              <td class="text-center">{{competation.total_match}}</td>
              <td>{{competation.total_time | formatTime}}
              </td>
              <td class="text-center">
                <span class="align-middle pr-1">
                  <a class="text-primary" href="#"
                  @click="editCompFormat(competation.id)"><i class="fas fa-pencil"></i></a>
                </span>
                <span class="align-middle pr-1">
                  <a class="text-primary" href="#"
                  @click="copyCompFormat(competation.id)"><i class="fas fa-copy"></i></a>
                </span>                
                <span class="align-middle">
                  <a href="javascript:void(0)"
                  data-confirm-msg="Are you sure you would like to delete this user record?"
                  data-toggle="modal"
                  data-target="#delete_modal"
                  @click="prepareDeleteResource(competation.id)">
                  <i class="fas fa-trash text-danger"></i></a>
                </span>
              </td>
            </tr>
          </tbody>
          <AddAgeCateogryModel v-if="categoryStatus" :categoryRules="categoryRules"></AddAgeCateogryModel>
          <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
          <competationModal :templateData="templateData" :totalTime="totalTime" :graphicHtml="graphicHtml" :categoryId="categoryId" :tournamentTemplateId="tournamentTemplateId" v-if="isViewModalShown"></competationModal>
          <CopyAgeCategoryModal v-if="copyCategoryStatus" :copiedAgeCategoryId="copiedAgeCategoryId"></CopyAgeCategoryModal>
          <!-- <div class="modal fade p-0" id="template-image-modal" tabindex="-1" role="dialog" aria-labelledby="template-image-modal" aria-hidden="true">
            <div class="modal-dialog modal-full" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="AgeCategoryModalLabel">Template {{templateData.tournament_name}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="d-flex align-items-center justify-content-centers">
                    <div class="d-block mx-auto">
                      <img v-bind:src="'/'+templateGraphicViewImage">
                    </div>
                  </div>
                </div>  
              </div>
            </div>
          </div> -->
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
       <button type="button" class="btn btn-primary" @click="addCategory()"><small><i class="fas fa-plus"></i></small>&nbsp;{{$lang.competation_add_age_category}}</button>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import DeleteModal from './DeleteModal.vue'
import CompetationModal from './CompetationModal.vue'
import AddAgeCateogryModel from './AddAgeCategoryModal.vue'
import CopyAgeCategoryModal from './CopyAgeCategoryModal.vue'

export default {
  data() {
    return {
      competationList : {}, TournamentId: 0, categoryId: '',setTime:'',
      tournamentTemplateId: null, totalTime:'',
      deleteConfirmMsg: 'Are you sure you would like to delete this age category?',deleteAction: '',
      templateData:[],
      totalTime: '',
      categoryStatus: false,
      categoryRules: [],
      copyCategoryStatus: false,
      copiedAgeCategoryId: '',
      ageCategoryTempFixtures: [],
      assignedTeams: [],
      groupName: null,
      categoryAge: null,
      assignedTeams: [],
      isViewModalShown: false,
      graphicHtml: '',
    }
  },
  components: {
    DeleteModal,CompetationModal,AddAgeCateogryModel, CopyAgeCategoryModal
  },
  mounted () {
    let that = this
     $("#AgeCategoryModal").on('hidden.bs.modal', function () {
               that.displayTournamentCompetationList()
            });
    // here we load the Competation Format data Based on tournament Id
    this.displayTournamentCompetationList()
  },
   filters: {
    formatTime: function(time) {
      var hours = Math.floor( time /   60);
      var minutes = Math.floor(time % 60);
      return (hours < 10 ? '0' + hours : hours) + 'h '+ (minutes < 10 ? '0' + minutes : minutes) +'m'
    }
  },
  methods: {
    editCompFormat(Id) {
       let vm =this
       // Call Child Class Component Method
      this.categoryStatus = true
        setTimeout(function(){
          vm.$root.$emit('setCompetationFormatData',  Id)
          $("#AgeCategoryModal").on('hidden.bs.modal', function () {
            vm.categoryStatus = false
        });
        },1000)
    },
    addCategory() {
      let vm =this
      this.categoryStatus = true
      this.type='add'
      setTimeout(function(){
        $('#AgeCategoryModal').modal('show')
          $("#AgeCategoryModal").on('hidden.bs.modal', function () {
            vm.categoryStatus = false
        });
      },500)
    },
    viewCompFormat(ageCategoryId, id,tTime) {
        this.isViewModalShown = true;
        $("body .js-loader").removeClass('d-none');
        // $("#competationmodal").modal('show');
         let TemplateData = {tournamentTemplateId : id, ageCategoryId: ageCategoryId}
         this.categoryId = ageCategoryId;
         this.tournamentTemplateId = id;
         Tournament.getTemplate(TemplateData).then(
          (response) => {
          if(response.data.status_code==200){
            this.templateData = JSON.parse(response.data.data.json_data)
            this.graphicHtml = response.data.data.graphicHtml;
            this.totalTime = tTime
            $("#competationmodal").modal("show");
            $("body .js-loader").addClass('d-none');
          }
        },
        (error) => {
          alert('error occur')
        }
      )
    },
    prepareDeleteResource(Id) {
       this.deleteAction=Id;
    },
    deleteConfirmed() {
      Tournament.deleteCompetation(this.deleteAction).then(
        (response) => {
          if(response.data.status_code==200){
             $("#delete_modal").modal("hide");
             toastr.success('Competition has been deleted successfully.', 'Delete Competition', {timeOut: 5000});
             this.displayTournamentCompetationList();
          }
        },
        (error) => {
          alert('error occur')
        }
      )
    },
    displayTournamentCompetationList () {
      this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
      // Only called if valid tournament id is Present
      if (!isNaN(this.TournamentId)) {
        // here we add data for
        let TournamentData = {'tournament_id': this.TournamentId}
        Tournament.getCompetationFormat(TournamentData).then(
        (response) => {
          let category_rules_info = response.data.category_rules_info;
          this.categoryRules = _.map(response.data.category_rules, (value, key) => {
            return {
              'key': key,
              'description': category_rules_info[key],
              'title': value,
              'checked': false,
            };
          });
          this.competationList = response.data.data
          this.$store.dispatch('setCompetationList',this.competationList)          
          let time_sum= 0;
          this.competationList.reduce(function (a,b) {
            time_sum += b['total_time']
          },0);
         this.$store.dispatch('SetTournamentTotalTime', time_sum);
        },
        (error) => {
        }
        )
      } else {
        this.TournamentId = 0;
      }
    },
    next() {
      let time_sum= 0;
      let objectLength = Object.keys(this.competationList).length
      // Here we check length of competaion list
      if(objectLength > 0) {
      this.competationList.reduce(function (a,b) {
        time_sum += b['total_time']
      },0);
       this.$store.dispatch('SetTournamentTotalTime', time_sum);
     } else {
       this.$store.dispatch('SetTournamentTotalTime', 0);
     }
      /*console.log(this.competationList)
      let s= 0;
      var sum = this.competationList.reduce(function (a,b) {
        s += b['total_time']
        console.log('b is'+b['total_time'])
        console.log('sum is'+s)
      },0);
      console.log('sum is'+s)*/
      /*let index = $('input[name=competationFormatTemplate]:checked').val()
      if(!isNaN(index)) {
        console.log(this.competationList)
        let tournamentTemplateId =  this.competationList[index].tournament_template_id
        let tournamentTotalTime =  this.competationList[index].total_time
        let tournamentData  = {'tournamentTemplateId' : tournamentTemplateId,
         'totalTime':tournamentTotalTime}
        // Now here we set the template for it
       // this.$store.dispatch('SetTemplate', tournamentData);
      }*/
      this.$router.push({name: 'pitch_capacity'});
    },
    copyCompFormat(id) {
      this.copyCategoryStatus = true;
      this.copiedAgeCategoryId = id;
      setTimeout(function(){
        $('#copyAgeCategoryModal').modal('show');
      },500)      
    }
  },
  created: function() {
    // We listen for the event on the eventHub
     this.$root.$on('setTemplate', this.next);
     this.$root.$on('displayCompetationList', this.displayTournamentCompetationList);
  },
  beforeCreate: function() {
    // Remove custom event listener
    this.$root.$off('setTemplate');
    this.$root.$off('displayCompetationList');
  },
}
</script>
