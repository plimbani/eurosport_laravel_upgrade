<template> 
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs tabs-primary summary-tab">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" 
									 role="tab" @click="currentView='summaryTab'">{{$lang.summary_label_summary}}</a>
								</li>					  		 
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" 
									role="tab" @click="currentView='summaryReport'">{{$lang.summary_label_reports}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" 
									role="tab" @click="currentView='scheduleResults'">{{$lang.summary_label_schedule}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" 
									role="tab" @click="currentView='messages'">{{$lang.summary_label_message}}</a>
								</li>
							</ul>
							<component :is="currentView"> </component>
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
import ScheduleResults from '../../../components/ScheduleResults.vue'
import Messages from '../../../components/Messages.vue'

export default {

    data() {
       return {
         currentView:'summaryTab'
       }
    },	
    components: {
        SummaryTab, SummaryReport, ScheduleResults, Messages
    },
    mounted() {
    	let tournamentId = this.$store.state.Tournament.tournamentId
      if(tournamentId == null || tournamentId == '') {
        this.$router.push({name: 'welcome'});
      } else {
          // First Set Menu and ActiveTab
        let currentNavigationData = {activeTab:'tournaments_summary_details', currentPage: 'Summary'}
          this.$store.dispatch('setActiveTab', currentNavigationData)
      }
      // Here we set currenct Schedule view null
      this.$store.dispatch('setCurrentScheduleView','') 
    }  
}
</script>