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
									 role="tab" @click="currentView='summaryTab'">Summary</a>
								</li>					  		 
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" 
									href="#home2" role="tab" @click="currentView='summaryReport'">Reports</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" 
									href="#home2" role="tab" @click="GetSelectComponent('schedule_results')">Schedule and results</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" 
									href="#home2" role="tab" @click="GetSelectComponent('messages')">Messages</a>
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
export default {
    data() {
       return {
         currentView:'summaryTab'
       }
    },	
    components: {
        SummaryTab, SummaryReport
    }
}
</script>