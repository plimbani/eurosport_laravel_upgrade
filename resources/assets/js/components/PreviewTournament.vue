<template>
    <div class="modal fade" id="preview_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$lang.summary_button_popup_preview_text}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <p>
                        {{$lang.summary_button_preview_text}}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.summary_tab_popup_preview_cancel_button}}</button>
                    <button type="submit" class="btn btn-primary"
                    @click.prevent="updateStatus">{{$lang.summary_tab_popup_preview_confirm_button}}</button>
                </div>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    export default  {
        props: ['isPublishedPreviewOnce'],
        methods: {
            updateStatus() {
                this.$root.$emit('StatusUpdate','Preview');
            }
        }
    }
</script>
