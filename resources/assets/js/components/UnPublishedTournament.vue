<template>
    <div class="modal fade" id="publish_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">UnPublished Tournament</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>                    
                </div>
                <div class="modal-body text-left">
                    <p>
                        Hit "Confirm" to UnPublish your tournament details.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" 
                    @click.prevent="updateStatus">Confirm</button>
                </div>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    export default  {
      methods: {
        updateStatus() {
            // here we set status to Publish For Tournament
            this.$root.$emit('StatusUpdate','UnPublished');
        }
      }
    }
</script>