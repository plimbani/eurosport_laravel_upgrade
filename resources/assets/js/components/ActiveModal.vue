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
                v-if="userData.status == true">Are you sure you would like to de-activate this user?</div>
                <div class="modal-body js-delete-confirmation-msg" v-else>
               Are you sure you would like to re-activate this user?</div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" data-dismiss="modal"  @click.prevent="close()">{{$lang.user_management_user_cancle}}</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmActive()">Confirm</button>
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
        data() {
          return {
            userData: this.uStatusData
          }
        },
        methods: {
            confirmActive() {
                this.$emit('confirmed');
            },
            close() {
              this.$emit('closeModal')
            }
        }
    }
</script>
