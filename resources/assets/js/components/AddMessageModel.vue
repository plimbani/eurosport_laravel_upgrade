<template>
<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true"  data-animation="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New Message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
        <form name="addMessage" id="addMessage" class="col-md-5 mt-4">
          <div class="form-group text-left">
            <label>{{$lang.summary_message_compose_message}}</label>

            <textarea class="form-control" rows="6"
            v-validate="'required'"
            name="content"
            :class="{'is-danger': errors.has('content') }"
            v-model="content" ></textarea>
            <span class="help is-danger" v-show="errors.has('content')">
              Field is required
            </span>

          </div>
        </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.competation_modal_button_cancle}}</button>
            <button type="button" class="btn btn-primary" @click="sendMessage" id="saveAge">Sent</button>
            <button type="button" class="btn btn-primary" @click="setDraft" id="setDraft">Draft</button>
        </div>
    </div>
  </div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'


export default {

  data() {
    return  {
      content:''
    }
  },


  mounted() {

  },
  created: function() {

  },

  methods: {

    // Function used to Sent Message through push notification
    sendMessage() {
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
                     toastr.success('Notification sent successfully.', 'Push notification ', {timeOut: 2000});
                    } else {
                      toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                    }

                     vm.$root.$emit('displayMessageList')
                     $('#exampleModal').modal('hide')
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
            'contents':this.content,'type':'draft'}
            Tournament.sendMessage(messageData).then(
                (response) => {
                    if(response.data.status_code == 200) {
                     if(response.data.message == 'success') {
                     toastr.success('Notification sent successfully.', 'Push notification ', {timeOut: 2000});
                    } else {
                      toastr.error(response.data.data, 'Push notification ', {timeOut: 2000});
                    }

                     vm.$root.$emit('displayMessageList')
                     $('#exampleModal').modal('hide')
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
