<template>
    <div class="">
        <img src="/assets/img/user_icon.png" width="40">
        <p>{{referee.first_name}}</p>      
    </div>    
</template>

<script type="text/babel">

export default {
    props: ['referee'],
    mounted() {
        // this.initEvents();
    },
    methods: {
        initEvents() {
            // make the event draggable using jQuery UI
            $(this.$el).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0,  //  original position after the drag
                containment: ".fc-time-grid-event",
            });
        }    
    }    
};
    
</script>