<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs tabs-primary">
             <div class="row justify-content-between">
              <div class="col-sm-12">
                <ul class="nav nav-tabs tournament-summary" role="tablist">
                  <li class="nav-item" v-if="!isResultAdmin">
                    <a class="nav-link active" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='summaryTab'">
                      <div class="wrapper-tab">{{$lang.summary_label_summary}}</div>
                    </a>
                  </li>
                  <li class="nav-item" v-if="!isResultAdmin">
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='summaryReport'">
                      <div class="wrapper-tab">{{$lang.summary_label_reports}}</div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" :class="isResultAdmin ? 'active' : ''" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='scheduleResultsAdmin'">
                      <div class="wrapper-tab">{{$lang.summary_label_schedule}}</div>
                    </a>
                  </li>
                  <li class="nav-item" v-if="!isResultAdmin && !isCustomer">
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='messages'">
                      <div class="wrapper-tab">{{$lang.summary_label_message}}</div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='PresentationSettings'">
                      <div class="wrapper-tab">{{$lang.summary_lable_tv_presentation}}</div>
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
							<component :is="currentView"> </component>
              <UnsaveMatchScoreModel @unchanged-match-scores="unChangedMatchScoresModal"></UnsaveMatchScoreModel>
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
import PresentationSettings from '../../../components/PresentationSettings.vue'
import AddMessageModel from '../../../components/AddMessageModel.vue'
import UnsaveMatchScoreModel from '../../../components/UnsaveMatchScoreModel.vue'
// import UnSavedMatchScoresInfoModal from '../../../components/UnsavedMatchScoresInfo.vue'

export default {

    data() {
       return {
         currentView:'summaryTab',
        messageStatus: false,
       }
    },
    components: {
        SummaryTab, SummaryReport, ScheduleResultsAdmin, Messages, PresentationSettings, AddMessageModel, UnsaveMatchScoreModel
    },
    beforeRouteLeave(to, from, next) {
      let redirectName = to.name; 
      let matchResultChange = this.$store.state.Tournament.matchResultChange;
      let currentSection = from.name;
      if ( matchResultChange && currentSection == 'tournaments_summary_details')
      { 
        window.sectionVal = -1;
        window.redirectPath = redirectName;
        $('#unSaveMatchModal').modal('show');
      }
      else{
        next();
      }
    },
    mounted() {
      if(this.isResultAdmin) {
        this.currentView = 'scheduleResultsAdmin';
      }
    	let tournamentId = this.$store.state.Tournament.tournamentId
      if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
      	toastr['error']('Please Select Tournament', 'Error');
        this.$router.push({name: 'welcome'});
      } else {
        let currentNavigationData = {activeTab:'tournaments_summary_details', currentPage: 'Administration'}
        this.$store.dispatch('setActiveTab', currentNavigationData)
      }
      this.$store.dispatch('setCurrentScheduleView','')
    },
    computed: {
      isResultAdmin() {
        return this.$store.state.Users.userDetails.role_slug == 'Results.administrator';
      },
      isCustomer() {
        return this.$store.state.Users.userDetails.role_slug == 'customer';
      },
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
      },
      unChangedMatchScoresModal(data) {
        this.$parent.setUnChangedMatchScoresModal(data);
      }      
    }
}
</script>
