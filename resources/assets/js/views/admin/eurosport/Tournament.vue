<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <label for="status_rules" class="mb-0"><b>{{$lang.summary_status}}:</b> </label>

          <div class="d-flex status-rules-btns">
            <div>
                <button class="btn btn-default btn-sm"><span data-toggle="popover" data-animation="false" data-placement="bottom" :data-popover-content="'#preview_status_rules'">Preview</span></button>

                <div v-bind:id="'preview_status_rules'" style="display: none;">
                    <div class="popover-body">
                        Publish key details of the tournament only to the app
                    </div>
                </div>
            </div>

            <div class="btn-group status-rules-btn-group ml-2">
                <button class="btn btn-default btn-sm"><span data-toggle="popover" data-animation="false" data-placement="bottom" :data-popover-content="'#publish_status_rules'">Published</span></button>

                <button class="btn btn-default btn-sm"><span data-toggle="popover" data-animation="false" data-placement="bottom" :data-popover-content="'#unpublish_status_rules'" tabindex="0">Unpublished</span></button>
            </div>

            <div v-bind:id="'publish_status_rules'" style="display: none;">
              <div class="popover-body">
                  Publish all details of the tournament to the app
              </div>
            </div>

            <div v-bind:id="'unpublish_status_rules'" style="display: none;">
              <div class="popover-body">
                  No information about the tournament is published to the app
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="row gutters-tiny align-items-center justify-content-end">
          <label for="status_rules" class="col-md-2 text-right mb-0"><b>{{$lang.summary_status}}:</b>
            <span class="text-primary" data-toggle="popover" data-animation="false" data-placement="bottom" :data-popover-content="'#status_rules'"><i class="fas fa-info-circle"></i>
            </span>
            <div v-bind:id="'status_rules'" style="display:none;">
                      <div class="popover-body">
                        Preview = publish key details of the tournament only to the app<br /><br />
                        Published = publish all details of the tournament to the app<br /><br />
                        Unpublished = no information about the tournament is published to the app
                      </div>
            </div>
          </label>
          <div class="col-md-3">
            <TournamentStatus :tournamentStatus='tournamentStatus'></TournamentStatus>
          </div>
          <UnPublishedTournament>
          </UnPublishedTournament>
          <PublishTournament :canDuplicateFavourites='canDuplicateFavourites'>
          </PublishTournament>
          <PreviewTournament :canDuplicateFavourites='canDuplicateFavourites'>
          </PreviewTournament>
        </div> -->

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
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='contactDetailsTab'">
                      <div class="wrapper-tab">{{$lang.summary_label_contact_details}}</div>
                    </a>
                  </li>
                  <li class="nav-item" v-if="!isResultAdmin">
                    <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" @click="currentView='sportsParksDetailsTab'">
                      <div class="wrapper-tab">{{$lang.summary_label_sports_parks_details}}</div>
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
                  <li class="nav-item" v-if="!isResultAdmin">
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
    <div class="row" v-if="(userDetails.role_name == 'Super administrator' || userDetails.role_name == 'Internal administrator' || userDetails.role_name == 'Master administrator')">
      <div class="col-md-12">
        <div class="pull-right">
          <button type="button" data-toggle="modal"
          data-confirm-msg="Are you sure you would like to delete this user record?"
          data-target="#delete_modal"
          class="btn btn-danger w-135"
          >{{$lang.summary_button_delete}}</button>
          <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">

import SummaryTab from '../../../components/SummaryTab.vue'
import ContactDetailsTab from '../../../components/ContactDetailsTab.vue'
import SportsParksDetailsTab from '../../../components/SportsParksDetailsTab.vue'
import SummaryReport from '../../../components/SummaryReport.vue'
import ScheduleResultsAdmin from '../../../components/ScheduleResultsAdmin.vue'
import Messages from '../../../components/Messages.vue'
import PresentationSettings from '../../../components/PresentationSettings.vue'
import AddMessageModel from '../../../components/AddMessageModel.vue'
import UnsaveMatchScoreModel from '../../../components/UnsaveMatchScoreModel.vue'
// import UnSavedMatchScoresInfoModal from '../../../components/UnsavedMatchScoresInfo.vue'
import PublishTournament from '../../../components/PublishTournament.vue'
import UnPublishedTournament from '../../../components/UnPublishedTournament.vue'
import PreviewTournament from '../../../components/PreviewTournament.vue'
import TournamentStatus from '../../../components/TournamentStatus.vue'
import Tournament from '../../../api/tournament.js'
import DeleteModal from '../../../components/DeleteModal.vue'

export default {

    data() {
       return {
         currentView:'summaryTab',
        messageStatus: false,
        tournamentStatus:'',
        canDuplicateFavourites: false,
        deleteConfirmMsg: 'Are you sure you would like to delete this tournament?',
       }
    },
    components: {
        SummaryTab, ContactDetailsTab, SportsParksDetailsTab, SummaryReport, ScheduleResultsAdmin, Messages, PresentationSettings, AddMessageModel, UnsaveMatchScoreModel,PublishTournament, UnPublishedTournament, TournamentStatus, PreviewTournament, DeleteModal
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
      this.getSummaryData()
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
      }
    },
    created: function() {
      this.$root.$on('StatusUpdate', this.updateStatus);
    },
    beforeCreate: function() {
      this.$root.$off('StatusUpdate');
    },
    methods: {
      getSummaryData() {
        let tournamentId = this.$store.state.Tournament.tournamentId;
        if(tournamentId != undefined)
        {
          Tournament.tournamentSummaryData(tournamentId).then(
          (response) => {
            if(response.data.status_code == 200) {
            this.tournamentSummary = response.data.data;
            // here modified data According to display
            if(response.data.data.tournament_contact != undefined || response.data.data.tournament_contact != null )
            {
              this.tournamentSummary.tournament_contact = response.data.data.tournament_contact.first_name+' '+response.data.data.tournament_contact.last_name
            }
            let locations='';
            if(response.data.data.locations != undefined || response.data.data.locations != null )
            {
              response.data.data.locations.reduce(function (a,b) {
                locations += b.name + ' (' +b.country +')'+', '
              },0);
              // remove last comma
              if(locations.length > 0)
                locations = locations.substring(0,locations.length - 2)
                this.tournamentSummary.locations = locations
            }
            this.canDuplicateFavourites = (this.tournamentSummary['tournament_detail']['duplicated_from'] !== null &&  this.tournamentSummary['tournament_detail']['is_published_preview_once'] === 0) ? true : false;
            }
          },
          (error) => {
            // if no Response Set Zero
            //
          }
        );
        this.tournamentId = this.$store.state.Tournament.tournamentId
        this.tournamentName = this.$store.state.Tournament.tournamentName
        this.tournamentStatus = this.$store.state.Tournament.tournamentStatus
        let SDate = moment(this.$store.state.Tournament.tournamentStartDate,'DD/MM/YYYY')
        let EDate = moment(this.$store.state.Tournament.tournamentEndDate,'DD/MM/YYYY')
        this.tournamentDates = SDate.format('DD MMM YYYY')+'  - '+EDate.format('DD MMM YYYY')
        let tournamentDays = this.$store.state.Tournament.tournamentDays || 0
        this.tournamentDays= parseInt(tournamentDays)
        this.tournamentLogo= this.$store.state.Tournament.tournamentLogo
      }
    },
      updateStatus(status, switchDefaultTournament){
        // here we call method to update Status
        let tournamentId = this.$store.state.Tournament.tournamentId;
        let tournamentData = {'tournamentId': tournamentId, 'status': status, 'switchDefaultTournament': switchDefaultTournament};
        if(tournamentId != undefined)
        {
          Tournament.updateStatus(tournamentData).then(
          (response) => {
            if(response.data.status_code == 200) {
              if ( status == 'Published')
              {
                var modal = "publish_modal";
              }

              if ( status == 'Unpublished')
              {
                var modal = "unpublish_modal";
              }

              if ( status == 'Preview')
              {
                var modal = "preview_modal";
              }
              $("#"+modal).modal("hide");
              this.tournamentStatus = status
              if( (this.tournamentStatus === 'Preview' || this.tournamentStatus === 'Published') && this.tournamentSummary['tournament_detail']['duplicated_from'] !== null && this.tournamentSummary['tournament_detail']['is_published_preview_once'] === 0) {
                this.canDuplicateFavourites = false;
              }
              toastr['success']('This tournament has been '+status, 'Success');
              let tournamentField = {'tournamentStatus': status}
              this.$store.dispatch('setTournamentStatus',tournamentField)
            }
          },
          (error) => {
          }
          );
        }
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
      unChangedMatchScoresModal(data) {
        this.$parent.setUnChangedMatchScoresModal(data);
      },
      deleteConfirmed() {
        Tournament.deleteTournament(this.tournamentId).then(
          (response) => {
            if(response.data.status_code==200){
               $("#delete_modal").modal("hide");
               toastr.success('Tournament has been deleted successfully.', 'Delete Tournament', {timeOut: 5000});
               //Redirect on Login Page
               setTimeout(this.$router.push({name: 'welcome'}), 5000);
               //this.displayTournamentCompetationList();
            }
          },
          (error) => {
            alert('error occur')
          }
        )
      },
    }
}
</script>
