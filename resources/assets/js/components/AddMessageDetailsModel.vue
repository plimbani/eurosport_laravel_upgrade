<template>
<div class="modal" id="exampleDetailsModel" tabindex="-1" role="dialog" aria-labelledby="exampleDetailsModel" style="display: none;" aria-hidden="true"  data-animation="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{$lang.summary_message_popup_messagedetails}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
        <form name="addMessage" id="addMessage" class="col-md-5 mt-4">
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.summary_message_popup_messagedetails_date}}</div>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-sm-12">
                    {{messageDetail.created_at}}
                  </div>
                </div>
              </div>
          </div>
          <div class="form-group text-left">
            <div class="form-group row align-items-center">
              <div class="col-sm-4 form-control-label">{{$lang.summary_message_popup_messagedetails_sender}}</div>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                      {{messageDetail.sender.email}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group text-left" v-if="messageDetail.status == 'queued'">
              <div class="form-group row align-items-center">
              <div class="col-sm-4 form-control-label">{{$lang.summary_message_popup_messagedetails_message}}</div>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-sm-12">
                   <textarea class="form-control" rows="6"
                      v-validate="'required'"
                      name="content"
                      :class="{'is-danger': errors.has('content') }"
                      v-model="messageDetail.content" ></textarea>
                      <span class="help is-danger" v-show="errors.has('content')">
                        Field is required
                      </span>
                  </div>
                </div>
              </div>
              </div>
            </div>
            <div v-else class="form-group text-left">
              <div class="form-group row align-items-center">
              <div class="col-sm-4 form-control-label">{{$lang.summary_message_popup_messagedetails_message}}</div>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                      {{messageDetail.content}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <div>
          <button type="button" class="btn btn-danger pull-left"  data-toggle="modal" data-target="#delete_modal"
          v-if="messageDetail.status != 'sent' ">Delete</button>
          </div>
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.competation_modal_button_cancle}}</button>
          <button type="button" class="btn btn-primary" @click="sendMessage" id="saveAge"  v-if="messageDetail.status != 'sent' ">{{$lang.summary_message_popup_send_button}}</button>
          <button type="button" class="btn btn-primary" @click="setDraft" id="setDraft" v-if="messageDetail.status != 'sent' ">{{$lang.summary_message_popup_draft_button}}</button>
        </div>
    </div>
  </div>
  <div class="modal fade bg-modal-color refdel" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog delete-modal" role="document">
      <div class="modal-content">
          <form method="delete" class="js-delete-modal-form">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">{{$lang.user_management_confirmation}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body js-delete-confirmation-msg">{{ deleteConfirmMsg }}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_cancel}}</button>
                <button type="submit" class="btn btn-primary" @click.prevent="setDelete()">{{$lang.user_management_save}}</button>
            </div>
            <input name="_method" value="DELETE" type="hidden" />
          </form>
      </div>
    </div>
  </div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import DeleteModal from './DeleteModal.vue'

export default {

  data() {
    return  {
      deleteConfirmMsg: 'Are you sure you would like to delete this message?',deleteAction: '',
    }
  },
  props:['messageDetail'],
  mounted() {

  },
  created: function() {

  },
  components: {
    DeleteModal
  },
  methods: {
    // Function used to Sent Message through push notification
    deleteConfirmed() {
      alert('its confirmed')
    },
    setDelete() {
       let vm = this
      this.$validator.validateAll().then(
          (response) => {
            // Now here we sent the data for push notification
            let messageData = {
            'message_id': this.messageDetail.id,
            'is_delete': true}
            Tournament.sendMessage(messageData).then(
                (response) => {
                  if(response.data.status_code == 200) {
                     toastr.success('Notification Deleted successfully.', 'Push notification ', {timeOut: 2000});
                     vm.$root.$emit('displayMessageList')
                     $('#delete_modal').modal('hide')
                     $('#exampleDetailsModel').modal('hide')
                  }
                },
              (error) => {
                console.log('Error occured during Save Compeation Fomat api ', error)
              }
            )
          }
      );
    },
    sendMessage() {
      let vm = this
      this.$validator.validateAll().then(
          (response) => {
            // Now here we sent the data for push notification
            let messageData = {'tournament_id':this.$store.state.Tournament.tournamentId,
            'user_id':this.$store.state.Users.userDetails.id,
            'message_id': this.messageDetail.id,
            'contents':this.messageDetail.content,'type':'save'}
            Tournament.sendMessage(messageData).then(
                (response) => {

                  if(response.data.status_code == 200) {
                    if(response.data.message == 'success') {
                     toastr.success('Notification sent successfully.', 'Push notification ', {timeOut: 2000});
                    } else {
                      toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                    }
                     vm.$root.$emit('displayMessageList')
                     $('#exampleDetailsModel').modal('hide')
                  }

                },
              (error) => {
                console.log('Error occured during Save Compeation Fomat api ', error)
              }
            )
          }
      );

    },

    // functions used to set message as draft
    setDraft() {
      let vm = this
       this.$validator.validateAll().then(
          (response) => {
            // Now here we sent the data for push notification
            let messageData = {'tournament_id':this.$store.state.Tournament.tournamentId,
            'user_id':this.$store.state.Users.userDetails.id,
            'message_id': this.messageDetail.id,
            'contents':this.messageDetail.content,'type':'draft'}
            Tournament.sendMessage(messageData).then(
                (response) => {
                    if(response.data.status_code == 200) {
                      if(response.data.message == 'success') {
                     toastr.success('Notification sent successfully.', 'Push notification ', {timeOut: 2000});
                    } else {
                       toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                    }

                     vm.$root.$emit('displayMessageList')
                     $('#exampleDetailsModel').modal('hide')
                  }
                },
              (error) => {
                console.log('Error occured during Save Compeation Fomat api ', error)
              }
            )
          }
      );
    },

    setEdit(id) {


    },

  }
}
</script>
