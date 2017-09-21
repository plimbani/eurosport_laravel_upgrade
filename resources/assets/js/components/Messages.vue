<template>
<div>	
	<!-- <h6 class="mt-4 mb-0">{{$lang.summary_message_text}}</h6> -->
		<div class="tab-content summary-report-content">

	    <div class="card">
	      <div class="card-block">
	      	<div class="row align-items-center msg-history-add-msg">
	            <div class="col-3">
	               <h6 class="mb-0"><strong>{{$lang.summary_table_text}}</strong></h6>
	            </div>
	        	<div class="col-9">
                    <button type="button" class="btn btn-primary pull-right"
                       @click="addMessage()"><small><i class="jv-icon jv-plus"></i>
                       </small>&nbsp;{{$lang.summary_message_button}}
                    </button>
                </div>
           	</div>   
	         <!--  <h6 class="mt4"><strong>{{$lang.summary_table_text}}</strong></h6> -->
	          <messageHistoryList></messageHistoryList>
	           <AddMessageModel v-if="messageStatus"></AddMessageModel>
	      </div>
	    </div>

		</div>
	</div>
</template>
<script type="text/babel">
import MessageHistoryList from './MessageHistoryList.vue'
import AddMessageModel from './AddMessageModel.vue'
export default {

	data() {
		return {
 			messageStatus: false
 		}
	},
components: {
      MessageHistoryList, AddMessageModel
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
