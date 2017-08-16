<template>
<div>	
	<!-- <h6 class="mt-4 mb-0">{{$lang.summary_message_text}}</h6> -->
		<div class="tab-content summary-report-content">
		    <div class="card">
		      <div class="card-block">
		          <h6 class="mt4"><strong>Message history</strong></h6>
		          <messageHistoryList></messageHistoryList>
		      </div>
		    </div>
		</div>
	</div>
</template>
<script type="text/babel">
import MessageHistoryList from './MessageHistoryList.vue'
export default {
components: {
      MessageHistoryList
  }
}
</script>
