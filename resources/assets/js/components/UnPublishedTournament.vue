<template>
    <div class="modal fade" id="unpublish_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$lang.summary_button_popup_unpublish_text}}</h5>
                <button type="button" class="close" @click.prevent="cancelStatusChange()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-left">
                <p>
                    {{$lang.summary_button_unpublish_text}}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click.prevent="cancelStatusChange()">No</button>
                <button type="submit" class="btn btn-primary" @click.prevent="updateStatus">Yes</button>
            </div>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    export default  {
        methods: {
            updateStatus() {
                this.$root.$emit('StatusUpdate','Unpublished');
            },
            cancelStatusChange() {
                $('#unpublish_modal').modal('hide');
                this.$root.$emit('cancelStatusChange');
            }
        }
    }
</script>
