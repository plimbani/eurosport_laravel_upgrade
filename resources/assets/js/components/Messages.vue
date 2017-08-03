<template>
	<div class="tab-content summary-report-content">
		<h6>{{$lang.summary_messages_text}}</h6>
		<span>{{$lang.summary_message_text}}</span>
    <div class="card">
      <div class="card-block">
          <h6 class="mt4"><strong>Message History</strong></h6>
          <messageHistoryList></messageHistoryList>
      </div>
    </div>
		<!--<div class="row">
			<form class="col-md-5 mt-4">
				<div class="form-group text-left">
					<div class="">
						<label>{{$lang.summary_message_compose_message}}</label>
						<div class="input-group">
							<textarea class="form-control" rows="6"></textarea>
						</div>
					</div>
				</div>
				<button type="button" class="btn btn-primary pull-right">{{$lang.summary_message_button_send}}</button>
			</form>
		</div>-->
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
