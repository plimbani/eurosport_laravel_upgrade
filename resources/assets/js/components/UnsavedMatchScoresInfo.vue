<template>
	<div>
	    <div class="modal" id="unSavedMatchScoresModal" tabindex="-1" role="dialog" aria-labelledby="viewPlacingsLabel" style="display: none;" aria-hidden="true" data-animation="false">
	      <div class="modal-dialog modal-md" role="document">
	        <div class="modal-content">
	          <div class="modal-header">
	             <h5 class="modal-title" id="competationmodalLabel">{{ $lang.unsaved_match_score_info_modal_header }}</h5>
	             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	             <span aria-hidden="true">×</span>
	             </button>
	          </div>
	          <div class="modal-body">
                <p class="font-weight-bold">{{ $lang.unsaved_match_score_info_modal_body_header }}</p>
            	<ul class="list-unstyled" v-for="match in unChangedMatchScores">
            		<li>{{ match }}</li>
            	</ul>

            	<strong>{{ $lang.note }}</strong> {{ $lang.unsaved_match_score_info_modal_note  }}
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.modal_close_button}}</button>
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