<template>
    <div class="modal fade" id="resend_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog delete-modal" role="document">
        <div class="modal-content">
            <form method="delete" class="js-delete-modal-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{$lang.user_management_confirmation}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body js-delete-confirmation-msg">{{ resendConfirm }}</div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_user_cancle}}</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmResend()">{{$lang.user_management_resend}}</button>
                </div>
                <input name="_method" value="DELETE" type="hidden"/>
            </form>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {
        props: {
            resendConfirm: String
        },
        methods: {
            confirmResend() {
                this.$emit('confirmed');                
            }
        }
    }
</script>