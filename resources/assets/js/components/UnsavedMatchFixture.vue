<template>
	<div>
	    <div class="modal" id="unChangedMatchFixtureModal" tabindex="-1" role="dialog" aria-labelledby="viewPlacingsLabel" style="display: none;" aria-hidden="true" data-animation="false">
	      <div class="modal-dialog modal-md" role="document">
	        <div class="modal-content">
	          <div class="modal-header">
	             <h5 class="modal-title" id="competationmodalLabel">Match Fixtures Conflict</h5>
	             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	             <span aria-hidden="true">×</span>
	             </button>
	          </div>
	          <div class="modal-body">
                <p class="font-weight-bold">The following matches are not displaying the most up to date result:</p>
            	<ul class="list-unstyled" v-for="match in unChangedMatchFixtures">
            		<li>{{ match }}</li>
            	</ul>
            	<strong>{{ $lang.note }}</strong> The latest scores will synchronise and display.
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-danger"  @click="closeModal()">close</button>
	          </div>          
	         </div>
	      </div>
	    </div>
	</div>
</template>
<script type="text/babel">
	export default {
		props: ['unChangedMatchFixtures'],
		methods: {
		    closeModal() {
		        $('#unChangedMatchFixtureModal').modal('hide');
		        return false;
		    },
		}
	}
</script>