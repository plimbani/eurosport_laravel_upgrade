<template>
    <div class="modal fade bg-modal-color refdel" id="template_version_confirm_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog template-in-use-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$lang.template_confirmation}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{ $lang.template_edit_modal }}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.user_management_cancel}}</button>
                <button type="submit" class="btn btn-primary" @click.prevent="confirmDelete()">{{$lang.user_management_save}}</button>
            </div>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {
        methods: {
            confirmDelete() {
                this.$emit('confirmed');
            },
            closeModal() {
                $('#template_version_confirm_modal').modal('hide')
                return false
            }
        }
    }
</script>