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
                <p>Due to a potential match scheduling conflict please save your changes and re-load the Match Planner page.</p>
                <!-- <p class="font-weight-bold" v-else>The following fixtures are not up to date:</p>
            	<ul class="list-unstyled" v-for="match in unChangedMatchFixtures">
            		<li>{{ match }}</li>
            	</ul> -->
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
		props: ['unChangedMatchFixtures', 'isAnotherMatchScheduled'],
		methods: {
		    closeModal() {
		        $('#unChangedMatchFixtureModal').modal('hide');
		        return false;
		    },
		}
	}
</script>