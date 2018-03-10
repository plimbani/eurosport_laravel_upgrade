<template>
    <div>
        <div class="alert alert-dismissible alert-warning fade position-absolute show" role="alert" v-for="message in messages">
          {{ message.content }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    </div>
</template>
<script type="text/babel">
    import store from './../../store';
    import AppMessages from './../../api/appmessages.js';
    export default  {
        props: ['messages'],
        data() {
            return {
            };
        },
    }
</script>
