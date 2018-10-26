<template>
    <div class="modal fade bg-modal-color refdel" id="template_in_use_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog template-in-use-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templateInUseModalLabel">{{$lang.template_in_use_modal_header}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{ $lang.template_in_use_modal_message }}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.template_in_use_modal_button_text}}</button>
            </div>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {
        methods: {
            closeModal() {
                $('#template_in_use_modal').modal('hide')
                return false
            }
        }
    }
</script>