<template>
    <div class="raferee_details">
        <div class="raferee_list">
            <div class="d-flex align-items-center justify-content-between my-2">
                <div>
                    {{referee.last_name}}, {{referee.first_name}}
                </div>
                <div>
                    <a href="#" @click="generateRefereeReport()" title="Referee report card" class="text-primary" style="font-size:1.1em"><i class="fa fa-print"></i></a>&nbsp;&nbsp;
                    <a href="#" @click="editReferee()" title="Edit referee" class="text-primary mr-2"><i class="jv-icon jv-edit"></i></a>     
                </div>
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
                refereeText: this.referee.first_name+' '+this.referee.last_name, // use the element's text as the event title
                color: 'white',
                stick: true, // maintain when user navigates (see docs on the renderEvent method),
                duration: moment.duration(15, 'minutes'),
                matchId: this.referee.id,
                matchAgeGroupId: '',
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
        },

        generateRefereeReport() {
            let refereeId = this.referee.id
            var win = window.open("/api/match/reportCard/" + refereeId, '_blank');
            win.focus();
        },
        editReferee() { 
            this.$root.$emit('editReferee', this.referee.id)
        }
    }    
};
    
</script>