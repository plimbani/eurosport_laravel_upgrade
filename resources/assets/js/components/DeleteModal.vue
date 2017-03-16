<template>
    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog delete-modal" role="document">
        <div class="modal-content">
            <form method="delete" class="js-delete-modal-form">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body js-delete-confirmation-msg">{{ deleteConfirmMsg }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmDelete()">Yes</button>
                </div>
                <input name="_method" value="DELETE" type="hidden" />
            </form>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {
        props: {
            deleteConfirmMsg: String
        },
        methods: {
            confirmDelete() {
               // this.$emit('confirmed');                
            }
        }
    }
</script>