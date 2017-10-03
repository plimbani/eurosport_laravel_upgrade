<template>
  <div>
    <div class="row">
      <div class="col-md-12">
         <table class="table table-hover table-bordered add-category-table" style="font-size:93%;">
          <thead>
              <tr>
                  <th class="text-center" style="width:180px;">{{$lang.summary_table_heading_date_sent}}</th>
                  <th class="text-center">{{$lang.summary_table_heading_message}}</th>
                  <th class="text-center">{{$lang.summary_table_heading_sender}}</th>
                  <th class="text-center">{{$lang.summary_table_heading_tournament}}</th>
                  <th class="text-center">{{$lang.summary_table_heading_status}}</th>
                  <th class="text-center">{{$lang.summary_table_heading_details}}</th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="(message, index) in messageList">
                  <td class="text-center">{{message.created_at | formatDate}} </td>
                  <td class="text-left wordbreak">{{message.content}}</td>
                  <td class="text-center">{{message.sender.email}}</td>
                  <td class="text-center">{{message.tournament.name}}</td>
                  <td class="text-center" v-if="message.status == 'queued' ">Draft</td>
                  <td class="text-center" v-else>Sent</td>
                  <td class="text-center">
                    <a href="#" @click="messageDetails(index)" class="text-primary"><i class="jv-icon jv-find-doc text-decoration icon-big"></i></a>
                  </td>
              </tr>
          </tbody>
         <!-- <AddAgeCateogryModel v-if="categoryStatus"></AddAgeCateogryModel>
          <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
          <competationModal :templateData="templateData" :totalTime="totalTime"></competationModal>-->
        </table>
          <AddMessageModel v-if="messageStatus"></AddMessageModel>
          <AddMessageDetailsModel v-if="messageDetailsStatus" :messageDetail="messageDetail"></AddMessageDetailsModel>
      </div>
    </div>
    <div class="row">
          <div class="col-md-12">
           <button type="button" class="btn btn-primary"
           @click="addMessage()"><small><i class="jv-icon jv-plus"></i></small>&nbsp;{{$lang.summary_message_button}}</button>
          </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import AddMessageModel from './AddMessageModel.vue'
import AddMessageDetailsModel from './AddMessageDetailsModel.vue'

export default {
  data() {
    return {
      messageList : {}, TournamentId: 0, competation_id: '',setTime:'',
      tournamentTemplateId: '', totalTime:'',
      deleteConfirmMsg: 'Are you sure you would like to delete this age category?',deleteAction: '',
      templateData:[],
      totalTime: '',
      messageStatus: false,
      messageDetailsStatus: false,
      messageDetail: {}
    }
  },
  components: {
    AddMessageModel,AddMessageDetailsModel
  },
  mounted () {
    let that = this
     $("#exampleModal").on('hidden.bs.modal', function () {
               that.displayMessageList()
            });
    // here we load the Competation Format data Based on tournament Id
    this.displayMessageList()
  },
  //  filters: {
  //   formatTime: function(time) {
  //     var hours = Math.floor( time /   60);
  //     var minutes = Math.floor(time % 60);
  //     return hours+ 'h '+minutes+'m'
  //   }
  // },
  filters: {
      formatDate: function(date) {
      return moment(date).format("Do MMM YYYY HH:mm:ss");
       },
    },
  methods: {
    messageDetails(index) {

      let vm = this

      this.messageDetail = this.messageList[index]
      // console.log(this.messageDetail)
      this.messageDetailsStatus = true

       setTimeout(function(){
        $('#exampleDetailsModel').modal('show')
          $("#exampleDetailsModel").on('hidden.bs.modal', function () {
            vm.messageDetailsStatus = false
        });
      },500)
    },
    addMessage() {
      let vm =this
      this.messageStatus = true
      this.type='add'
      setTimeout(function(){
        $('#exampleModal').modal('show')
          $("#exampleModal").on('hidden.bs.modal', function () {
            vm.messageStatus = false
        });
      },500)
    },

    prepareDeleteResource(Id) {
       this.deleteAction=Id;
    },

    displayMessageList () {
    this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
    // Only called if valid tournament id is Present
    if (!isNaN(this.TournamentId)) {
      // here we add data for
      let TournamentData = {'tournament_id': this.TournamentId}
      Tournament.getTournamentMessages(TournamentData).then(
      (response) => {
        if(response.data.status_code == 200) {
          this.messageList =  response.data.data
        }
      },
      (error) => {
      }
      )
    } else {
      this.TournamentId = 0;
    }
    }

  },
  created: function() {
    // We listen for the event on the eventHub
     this.$root.$on('setTemplate', this.next);
     this.$root.$on('displayMessageList', this.displayMessageList);
  },

}
</script>
