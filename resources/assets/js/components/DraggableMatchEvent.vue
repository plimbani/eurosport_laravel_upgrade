<template>
    <div class="draggable-event dashbox p-2 text-center">
        <p>{{match.matchName}}</p>
        <p>{{match.fullGame}}</p>
        <p class="m-0">({{match.matchTime}} min)</p>    
    </div>    
</template>

<script type="text/babel">
import moment from 'moment'
export default {
    props: ['match'],
    mounted() {
        this.initEvents();
    },
    methods: {
        initEvents() {
            // store data so the calendar knows to render an event upon drop                
            $(this.$el).data('event', {
                id: this.match.id,
                title: this.match.matchName, // use the element's text as the event title
                stick: true, // maintain when user navigates (see docs on the renderEvent method),                
                duration: moment.duration(this.match.matchTime, 'minutes'),
                matchId: this.match.matchId,
                forceEventDuration: true
            });

            // make the event draggable using jQuery UI
            $(this.$el).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        }    
    }    
};
    
</script>