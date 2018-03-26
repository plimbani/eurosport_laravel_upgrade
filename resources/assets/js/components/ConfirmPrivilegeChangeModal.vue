<template>
    <div class="modal fade bg-modal-color" id="confirm_privilege_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="delete" class="js-privilege-change-modal-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body js-delete-confirmation-msg">Are you sure you would like to lower down privilege of Super administrator? By doing this it will remove super level access.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"  @click="hideModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmChange()">Confirm</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {
        methods: {
            confirmChange() {
                this.$emit('confirmed');
            },
            hideModal() {
                $('#confirm_privilege_modal').modal('hide');
                return false;
            }
        }
    }
</script>
