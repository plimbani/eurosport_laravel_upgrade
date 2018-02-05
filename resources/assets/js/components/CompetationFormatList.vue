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
                  <th class="text-center">{{$lang.competation_match_schedule}}</th>
                  <th class="text-center" width="79px">{{$lang.competation_manage}}</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="(competation, index) in competationList">
              <td class="text-left">{{competation.group_name}} </td>
              <td class="text-left">{{competation.category_age}}</td>
              <td class="text-left">{{competation.template_name}}</td>
              <td class="text-left">{{competation.disp_format_name}}</td>
              <td class="text-center">{{competation.total_match}}</td>
              <td>{{competation.total_time | formatTime}}
              </td>
              <td class="text-center">
                  <a href="#"  @click="viewCompFormat(competation.tournament_template_id,competation.total_time)" class="btn btn-primary btn-sm">View</a>
              </td>
              <td class="text-center">
                <span class="align-middle">
                  <a class="text-primary" href="#"
                  @click="editCompFormat(competation.id)"><i class="jv-icon jv-edit"></i></a>
                </span>
                <span class="align-middle">
                  <a href="javascript:void(0)"
                  data-confirm-msg="Are you sure you would like to delete this user record?"
                  data-toggle="modal"
                  data-target="#delete_modal"
                  @click="prepareDeleteResource(competation.id)">
                  <i class="jv-icon jv-dustbin"></i></a>
                </span>
              </td>
            </tr>
          </tbody>
          <AddAgeCateogryModel v-if="categoryStatus"></AddAgeCateogryModel>
          <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
          <competationModal :templateData="templateData" :totalTime="totalTime" :templateImage="templateImage"></competationModal>
          <!-- <div class="modal fade p-0" id="template-image-modal" tabindex="-1" role="dialog" aria-labelledby="template-image-modal" aria-hidden="true">
            <div class="modal-dialog modal-full" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Template {{templateData.tournament_name}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="d-flex align-items-center justify-content-centers">
                    <div class="d-block mx-auto">
                      <img v-bind:src="'/'+templateImage">
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
       <button type="button" class="btn btn-primary" @click="addCategory()"><small><i class="jv-icon jv-plus"></i></small>&nbsp;{{$lang.competation_add_age_category}}</button>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import DeleteModal from './DeleteModal.vue'
import CompetationModal from './CompetationModal.vue'
import AddAgeCateogryModel from './AddAgeCategoryModal.vue'

export default {
  data() {
    return {
      competationList : {}, TournamentId: 0, competation_id: '',setTime:'',
      tournamentTemplateId: '', totalTime:'',
      deleteConfirmMsg: 'Are you sure you would like to delete this age category?',deleteAction: '',
      templateData:[],
      totalTime: '',
      templateImage: '',
      categoryStatus: false
    }
  },
  components: {
    DeleteModal,CompetationModal,AddAgeCateogryModel
  },
  mounted () {
    let that = this
     $("#exampleModal").on('hidden.bs.modal', function () {
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
          $("#exampleModal").on('hidden.bs.modal', function () {
            vm.categoryStatus = false
        });
        },1000)
    },
    addCategory() {
      let vm =this
      this.categoryStatus = true
      this.type='add'
      setTimeout(function(){
        $('#exampleModal').modal('show')
          $("#exampleModal").on('hidden.bs.modal', function () {
            vm.categoryStatus = false
        });
      },500)
    },
    viewCompFormat(id,tTime) {
        $("#competationmodal").modal('show');
         let TemplateData = {tournamentTemplateId : id}
         Tournament.getTemplate(TemplateData).then(
          (response) => {
          if(response.data.status_code==200){
            this.templateData = JSON.parse(response.data.data.json_data)
            this.templateImage = response.data.data.image
            this.totalTime = tTime
             $("#competationmodal").modal("show");
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
        this.competationList = response.data.data
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
    }
  },
  created: function() {
    // We listen for the event on the eventHub
     this.$root.$on('setTemplate', this.next);
     this.$root.$on('displayCompetationList', this.displayTournamentCompetationList);
  },

}
</script>
