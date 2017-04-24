<template>
    <div class='pitchPlanner'></div>
</template>

<script>
    export default {
        props: [ 'stage' ],
        computed: {
            pitchesData() {
                return _.forEach(this.stage.pitches, (pitch) => {
                    pitch.title = 'Pitch ' + pitch.id;
                });
            },
            stageDate() {
                return moment(this.stage.tournamentStartDate, 'DD/MM/YYYY');
            }
        },
        mounted() {
            this.initScheduler();
            console.log('this.stage', this.stage);
        },
        methods: {
            initScheduler() {
                let vm = this;
                $(this.$el).fullCalendar({
                    editable: true,
                    eventDurationEditable: false,
                    eventOverlap: false,
                    droppable: true,
                    height: 650,
                    defaultView: 'agendaDay',
                    defaultDate: vm.stageDate,
                    selectable: true,
                    eventLimit: true, // allow "more" link when too many events
                    header: false,
                    views: {
                        agendaDay: {
                            minTime: '08:00:00',
                            maxTime: '18:00:00',
                            slotDuration: '00:05',
                            slotLabelInterval: '00:15'
                        }
                    },                    
                    //// uncomment this line to hide the all-day slot
                    allDaySlot: false,

                    resources: vm.pitchesData,
                    events: [
                        // { id: '2', resourceId: 'a', start: '2017-04-07T09:00:00', end: '2017-04-07T14:00:00', title: 'event 2' },
                        // { id: '3', resourceId: 'b', start: '2017-04-07T12:00:00', end: '2017-04-08T06:00:00', title: 'event 3' },
                        // { id: '4', resourceId: 'c', start: '2017-04-07T07:30:00', end: '2017-04-07T09:30:00', title: 'event 4' },
                        // { id: '5', resourceId: 'd', start: '2017-04-07T10:00:00', end: '2017-04-07T15:00:00', title: 'event 5' }
                    ],
                    drop: function(date, jsEvent, ui, resourceId) {
                        console.log('drop', resourceId);
                        $(this).remove();
                    },
                    eventReceive: function(event) { // called when a proper external event is dropped
                        // add match to scheduled matches table - api call
                        console.log('eventReceive', event);
                    },
                    eventDrop: function(event) { // called when an event (already on the calendar) is moved
                        // update api call
                        console.log('eventDrop', event);
                    },
                    eventClick: function(calEvent, jsEvent, view) {
                        vm.handleEventClick(calEvent, jsEvent, view);
                    },
                    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                });
            },
            handleEventClick(calEvent, jsEvent, view) {
                console.log(calEvent);
            }
        }
    };
</script>