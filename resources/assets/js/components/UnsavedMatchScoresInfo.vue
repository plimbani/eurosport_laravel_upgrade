<template>
	<div>
	    <div class="modal" id="unSavedMatchScoresModal" tabindex="-1" role="dialog" aria-labelledby="viewPlacingsLabel" style="display: none;" aria-hidden="true" data-animation="false">
	      <div class="modal-dialog modal-md" role="document">
	        <div class="modal-content">
	          <div class="modal-header">
	             <h5 class="modal-title" id="competationmodalLabel">Unsaved Match Score Info</h5>
	             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	             <span aria-hidden="true">×</span>
	             </button>
	          </div>
	          <div class="modal-body modal-fixed-height">
	                <p>Following are the matches whose scores are not updated:</p>
	                <div v-for="match in unChangedMatchScores">
	                	<p>{{ match }}</p>
	                </div>
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
	          </div>          
	         </div>
	      </div>
	    </div>
	</div>
</template>
<script type="text/babel">
	export default {
		props: ['unChangedMatchScores'],
		methods: {
		    closeModal() {
		        $('#unSavedMatchScoresModal').modal('hide');
		        return false;
		    },
		}
	}
</script>