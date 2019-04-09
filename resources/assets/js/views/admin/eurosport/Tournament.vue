<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs tabs-primary">
             <div class="row justify-content-between">
              <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='summaryTab'">
                      <div class="wrapper-tab">{{$lang.summary_label_summary}}</div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='summaryReport'">
                      <div class="wrapper-tab">{{$lang.summary_label_reports}}</div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='scheduleResultsAdmin'">
                      <div class="wrapper-tab">{{$lang.summary_label_schedule}}</div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='messages'">
                      <div class="wrapper-tab">{{$lang.summary_label_message}}</div>
                    </a>
                  </li>
             <!--      <div class="col display-flex align-items-center justify-content-end padding-right-zero" v-show="currentView=='messages'">
                    <button type="button" class="btn btn-primary"
                         @click="addMessage()"><small><i class="fas fa-plus"></i></small>&nbsp;{{$lang.summary_message_button}}</button>
                  </div> -->
                 <AddMessageModel v-if="messageStatus"></AddMessageModel>
                </ul>
                </div>
              </div>
							<component :is="currentView" v-if="!modalOpen"> </component>
              <UnsaveMatchScoreModel></UnsaveMatchScoreModel>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">

import SummaryTab from '../../../components/SummaryTab.vue'
import SummaryReport from '../../../components/SummaryReport.vue'
import ScheduleResultsAdmin from '../../../components/ScheduleResultsAdmin.vue'
import Messages from '../../../components/Messages.vue'
import AddMessageModel from '../../../components/AddMessageModel.vue'
import UnsaveMatchScoreModel from '../../../components/UnsaveMatchScoreModel.vue'

export default {

    data() {
       return {
         currentView:'summaryTab',
        messageStatus: false
       }
    },
    computed: {
      modalOpen() {
        return this.$store.state.Tournament.modalOpen;
      }
    },
    components: {
        SummaryTab, SummaryReport, ScheduleResultsAdmin, Messages, AddMessageModel, UnsaveMatchScoreModel
    },
    beforeRouteLeave(to, from, next) {
      let redirectName = to.name; 
      let matchResultChange = this.$store.state.Tournament.matchResultChange;
      let currentSection = from.name;
      if ( matchResultChange && currentSection == 'tournaments_summary_details')
      { 
        $('#unSaveMatchModal').modal('show');

        let vm = this;
        $("#unSaveMatchModal").on('hidden.bs.modal', function () {

          vm.$store.dispatch('UnsaveMatchData',[]);
          vm.$store.dispatch('UnsaveMatchStatus',false);

          vm.$router.push({'name':redirectName});
        });
      }
      else{
        next();
      }
    },
    mounted() {
    	let tournamentId = this.$store.state.Tournament.tournamentId
      if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
      	toastr['error']('Please Select Tournament', 'Error');
        this.$router.push({name: 'welcome'});
      } else {
          // First Set Menu and ActiveTab
        let currentNavigationData = {activeTab:'tournaments_summary_details', currentPage: 'Summary'}
          this.$store.dispatch('setActiveTab', currentNavigationData)
      }
      // Here we set currenct Schedule view null
      this.$store.dispatch('setCurrentScheduleView','')
    },

     methods: {
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
    }
    }
}
</script>
