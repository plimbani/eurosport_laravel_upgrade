<template>
    <div class="position-absolute main-alert-wrapper custom-alert">
        <div class="alert alert-dismissible alert-warning bg-shade-50 border-0 fade mb-2 show text-white" role="alert" v-for="message in recent_messages" :key="message.id">
          {{ message.content }}
          <button type="button" class="close p-0 h7 font-weight-normal" data-dismiss="alert" aria-label="Close" @click="dismissMessage(message.id)">
            <span aria-hidden="true"><i class="fas fa-times font-weight-normal"></i></span>
          </button>
        </div>
    </div>
</template>
<script type="text/babel">
    import _ from 'lodash';
    import store from './../../store';
    import AppMessages from './../../api/frontend/appmessages.js';
    export default  {
        props: ['recent_messages'],
        data() {
            return {
            };
        },
        computed: {
        },
        methods: {
            dismissMessage(messageId) {
                var dismissedMessages = localStorage.getItem("dismissedMessages") !== null ? JSON.parse(localStorage.getItem('dismissedMessages')) : [];
                dismissedMessages.push(messageId);
                localStorage.setItem('dismissedMessages', JSON.stringify(dismissedMessages));
                this.$emit('updaterecentmessages');
            }
        },
    }
</script>
