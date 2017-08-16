<template>
    <div class="raferee_details ">
        <div class="raferee_list">
            <div class="">
                <!-- <img src="/assets/img/user_icon.png" width="40"> -->
                <p class="help-block">
                    {{referee.last_name}}, {{referee.first_name}}
                    <a href="#" class="text-primary right"><i class="jv-icon jv-edit"></i></a>
                </p>      
            </div>   
        </div> 
    </div>
</template>

<script type="text/babel">

export default {
    props: ['referee'],
    mounted() {
        this.initEvents();
    },
    methods: {
        initEvents() {
            $(this.$el).data('event', {
                id: this.referee.id,

                title: 'Referee added', // use the element's text as the event title
                refereeId: -3, // use the element's text as the event title
                refereeText: 'R', // use the element's text as the event title
                color: 'green',
                stick: true, // maintain when user navigates (see docs on the renderEvent method),
                duration: moment.duration(15, 'minutes'),
                matchId: this.referee.id,
                forceEventDuration: true,
                overlap: true
                
            });
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