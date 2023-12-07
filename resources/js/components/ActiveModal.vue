<template>
    <div class="modal fade" id="active_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog delete-modal" role="document">
        <div class="modal-content">
            <form method="delete" class="js-delete-modal-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{$lang.user_management_confirmation}}</h5>
                    <button type="button" class="close"
                    data-dismiss="modal" aria-label="Close" @click.prevent="close()">
                    <span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body js-delete-confirmation-msg"
                v-if="userData.status == true">{{$lang.user_management_de_activate}}</div>
                <div class="modal-body js-delete-confirmation-msg" v-else>
               {{$lang.user_management_re_activate}}</div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                     @click="close()">{{$lang.user_management_user_cancle}}</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmActive()">{{$lang.user_management_save}}</button>
                </div>
               <!-- <input name="_method" value="DELETE" type="hidden"/>-->
            </form>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {

        props:['activeConfirm','uStatusData'],
        computed: {
            userData() {
                return this.uStatusData
            }
        },
        methods: {
            confirmActive() {
                this.$emit('confirmed');
            },
            close() {
                this.$parent.enb = false
             // this.$emit('closeModal')
            }
        }
    }
</script>
