<template>
    <div class="modal fade bg-modal-color refdel" id="remove_venue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog delete-modal" role="document">
        <div class="modal-content">
            <form method="delete" class="js-delete-modal-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Action Required</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body js-delete-confirmation-msg">{{ removeVenue }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="hideModal()">{{$lang.user_management_ok}}</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {
        props: {
            removeVenue: String
        },
        methods: {
            hideModal() {
                $('#remove_venue').modal('hide');
                return false
            }
        }
    }
</script>