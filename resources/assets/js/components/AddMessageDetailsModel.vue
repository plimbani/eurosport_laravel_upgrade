<template>
<div>
  <div class="modal" id="exampleDetailsModel" tabindex="-1" role="dialog" aria-labelledby="exampleDetailsModel" style="display: none;" aria-hidden="true"  data-animation="false">
    <div class="modal-dialog modal-lg" id="messageDetail" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{$lang.summary_message_popup_messagedetails}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
        <form name="addMessage" id="addMessage" class="col-md-6 mt-4">
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">Status</div>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-sm-12" v-if="messageDetail.status == 'queued'">
                    Draft
                  </div>
                  <div class="col-sm-12" v-else>
                    Sent
                  </div>
                </div>
              </div>
          </div>
          <div class="form-group row align-items-center">
            <div class="col-sm-4 form-control-label">{{$lang.summary_message_popup_messagedetails_date}}</div>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-sm-12">
                    {{messageDetail.created_at | formatDate}}
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
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-12">
                   <textarea class="form-control" rows="6"
                      v-validate="'required'"
                      name="content"
                      :class="{'is-danger': errors.has('content') }"
                      v-model="messageDetail.content"></textarea>
                      <span class="help is-danger" v-show="errors.has('content')">
                        Field is required
                      </span>
                      <span>Suggested max characters 100. Characters used</span> <span v-bind:class="{'text-danger': hasError }" class="limiter">{{charactersLeft}}</span>
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
          <div class="form-group text-left" v-if="messageDetail.status == 'sent' ">
            <div class="form-group row align-items-center">
              <div class="col-sm-4 form-control-label">Sent tournament</div>
                <div class="col-sm-8">
                  <div class="row" v-if="messageDetail.receiver">
                    <div class="col-sm-12" >
                      {{sentUser}}
                    </div>
                  </div>
                  <div class="row" v-else>
                    <div class="col-sm-12" >
                      -
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <!-- <span class="limiter">{{charactersLeft}}</span> -->
        </form>
        </div>
          <div class="modal-footer">
              <div>
              <button type="button" class="btn btn-danger pull-left"   @click="modalOpen('delete')"
              v-if="messageDetail.status != 'sent' ">Delete</button>
              </div>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-warning" @click="setDraft" id="setDraft" v-if="messageDetail.status != 'sent' ">{{$lang.summary_message_popup_draft_button}}</button>
              <button type="button" class="btn btn-primary" @click="modalOpen('send')" id="saveAge"  v-if="messageDetail.status != 'sent' ">{{$lang.summary_message_popup_send_button}}</button>
          </div>
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
                <div class="modal-body js-delete-confirmation-msg" v-if="status == 'delete'">{{ deleteConfirmMsg }}</div>
                <div class="modal-body js-delete-confirmation-msg" v-else>{{ sendConfirmMsg }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_close}}</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmedAction(status)">{{$lang.user_management_save}}</button>
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
      deleteConfirmMsg: 'Are you sure you would like to delete this message?',
      sendConfirmMsg: 'Are you sure you would like to send this message?',
      deleteAction: '',
      status: '',
      hasError: false
    }
  },
  props:['messageDetail'],
  computed: {
    charactersLeft() {
      var char = this.messageDetail.content.length,
          limit = 100;
      let remaining_char = (limit - char) ;
      this.hasError = this.messageDetail.content.length > 100;
      return char+".";
    },
    sentUser() {
      if(this.messageDetail.receiver.length > 1 ){

        return this.messageDetail.receiver.length + " users";
      }else {
        return this.messageDetail.receiver.length + " user";

      }
    }
  },
  mounted() {

  },
  filters: {
      formatDate: function(date) {
      return moment(date).format("Do MMM YYYY HH:mm:ss");
       },
    },
  created: function() {

  },
  // components: {
  //   DeleteModal
  // },
  methods: {
    // Function used to Sent Message through push notification
    modalOpen(type) {
      this.status = type
      $('#delete_modal').modal('show')
    },
    confirmedAction(status) {
      if(status == 'delete'){
        this.setDelete()
      } else {
        this.sendMessage()
      }
    },
    setDelete() {
       let vm = this
      this.$validator.validateAll().then(
          (response) => {
            // Now here we sent the data for push notification
            let messageData = {
              'message_id': this.messageDetail.id,
              'is_delete': true
            }
            Tournament.sendMessage(messageData).then(
                (response) => {
                  if(response.data.status_code == 200) {
                     toastr.success('Message deleted successfully', 'App Message ', {timeOut: 2000});
                     vm.$root.$emit('displayMessageList')
                     $('#delete_modal').modal('hide')
                     $('#exampleDetailsModel').modal('hide')

                  }
                },
              (error) => {                
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
            let messageData = {
              'tournament_id':this.$store.state.Tournament.tournamentId,
              'user_id':this.$store.state.Users.userDetails.id,
              'message_id': this.messageDetail.id,
              'contents':this.messageDetail.content,
              'type':'save'
            }
            Tournament.sendMessage(messageData).then(
                (response) => {
                  if(response.data.status_code == 200) {
                    if(response.data.message == 'success') {
                      toastr.success('Message sent successfully', 'App Message ', {timeOut: 2000});
                    } else {
                      toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                    }   
                      $('#delete_modal').modal('hide')                   
                      $('#exampleDetailsModel').modal('hide')
                      vm.$root.$emit('displayMessageList')
                      // setTimeout(Plugin.reloadPage, 1000);
                  }

                },
              (error) => {                
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
                     toastr.success('Message saved successfully', 'Push notification ', {timeOut: 2000});
                    } else {
                       toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                    }

                     vm.$root.$emit('displayMessageList')
                     $('#exampleDetailsModel').modal('hide')

                  }
                },
              (error) => {                
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
