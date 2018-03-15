<template>
    <div style="height: 300px">
        <div class="alert alert-dismissible alert-warning fade position-absolute show" role="alert" v-for="message in recent_messages" :key="message.id">
          {{ message.content }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="dismissMessage(message.id)">
            <span aria-hidden="true">&times;</span>
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
            getRecentMessages() {
                return this.messages;
            },
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
