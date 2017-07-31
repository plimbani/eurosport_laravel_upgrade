<template>
    <div class="modal fade" id="publish_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Publish Tournament</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>                    
                </div>
                <div class="modal-body text-left">
                    <p>
                        Please confirm you would like to publish this tournament?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
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
           // $('#publish_modal').attr('data-dismiss','modal')
            this.$root.$emit('StatusUpdate','published');
        }
      }
    }
</script>