<template>
<div>
  <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true"  data-animation="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$lang.summary_message_popup_new_message}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
          <form name="addMessage" id="addMessage" class="col-md-6 mt-4">
            <div class="form-group text-left">
              <label>{{$lang.summary_message_compose_message}}*</label>

              <textarea class="form-control" rows="6"
              v-validate="'required'"
              name="content"
              :class="{'is-danger': errors.has('content') }"
              v-model="content"></textarea>
              <span class="help is-danger" v-show="errors.has('content')">
                Field is required
              </span>
              <div>
                <span>Suggested max characters 100. Characters used</span> <span v-bind:class="{'text-danger': hasError }" class="limiter">{{charactersLeft}}</span>
              </div>
            </div>
          </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.competation_modal_button_cancle}}</button>

              <button type="button" class="btn btn-warning" @click="setDraft" id="setDraft">{{$lang.summary_message_popup_draft_button}}</button>

              <button type="button" class="btn btn-primary" @click="sendMessage" id="saveAge">{{$lang.summary_message_popup_send_button}}</button>
          </div>

      </div>


    </div>
  </div>
   <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import DeleteModal from '../components/DeleteModal.vue'

export default {

  data() {
    return  {
      content:'',
      deleteConfirmMsg:'Are you sure you would like to send this message?',
      hasError: false


    }
  },

computed: {
    charactersLeft() {
      var char = this.content.length,
      limit = 100;
      let remaining_char = (limit - char) ;
      this.hasError = this.content.length > 100;
      return char +".";
    }
  },
  mounted() {

  },
  created: function() {

  },
  components: {
      DeleteModal
  },
  methods: {
    deleteConfirmed(){
      $("#delete_modal").modal("hide");
            let vm = this
          this.$validator.validateAll().then(
              (response) => {
                // Now here we sent the data for push notification
                let messageData = {'tournament_id':this.$store.state.Tournament.tournamentId,'user_id':this.$store.state.Users.userDetails.id,
                'contents':this.content,'type':'save'}
                Tournament.sendMessage(messageData).then(
                    (response) => {
                      if(response.data.status_code == 200) {
                      if(response.data.message == 'success') {
                         toastr.success('Message sent successfully', 'App Message', {timeOut: 2000});
                        } else {
                          toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                        }
                         $('#exampleModal').modal('hide')
                         vm.$root.$emit('displayMessageList')

                      }
                    },
                  (error) => {                  
                  }
                )
              }
          );
        },
    // Function used to Sent Message through push notification
    sendMessage() {
      $("#delete_modal").modal("show");
      // return false;


    },

    // functions used to set message as draft
    setDraft() {
      let vm = this
       this.$validator.validateAll().then(
          (response) => {
            // Now here we sent the data for push notification
            let messageData = {'tournament_id':this.$store.state.Tournament.tournamentId,'user_id':this.$store.state.Users.userDetails.id,'contents':this.content,'type':'draft'}
            Tournament.sendMessage(messageData).then(
                (response) => {
                    if(response.data.status_code == 200) {
                     if(response.data.message == 'success') {
                     toastr.success('Message saved successfully', 'App Message', {timeOut: 2000});
                    } else {
                      toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                    }

                     vm.$root.$emit('displayMessageList')
                     $('#exampleModal').modal('hide')
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
