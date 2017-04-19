<template>
    <div class="row">
        <div class="col-md-9">
            <div id='pitchPlanner'></div>
        </div>
        <div class="col-md-3">
            <div class="grey_bg">
                <div class="tabs tabs-primary planner_list">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item active">
                            <a href="" data-toggle="tab" href="#game-list">Games</a>
                        </li>
                        <li class="nav-item">
                            <a href="" data-toggle="tab" href="#referee-list">Referees</a>
                        </li>
                    </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="game-list" role="tabpanel">
                            <games-tab></games-tab>
                        </div>
                        <div class="tab-pane" id="referee-list" role="tabpanel">
                            <referees-tab></referees-tab>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import GamesTab from './GamesTab.vue'
    import RefereesTab from './RefereesTab.vue'
    
    export default  {
        components: {
            GamesTab, RefereesTab
        },
        data() {
            return {
                'currentView':'gamesTab'
            };
        },
        props: {
        },
        mounted() {
            this.initScheduler();
        },
        methods: {            
            initScheduler() {
                $('#pitchPlanner').fullCalendar({
                    editable: true,
                    durationEditable: false,
                    droppable: true,
                    // aspectRatio: 1.8,
                    defaultView: 'agendaDay',
                    defaultDate: '2017-04-07',
                    selectable: true,
                    eventLimit: true, // allow "more" link when too many events
                    header: false,
                    views: {
                        agendaDay: {
                            slotDuration: '00:05'
                        }
                    },
                    eventOverlap: false,
                    //// uncomment this line to hide the all-day slot
                    allDaySlot: false,

                    resources: [
                        { id: 'a', title: 'Pitch 1' },
                        { id: 'b', title: 'Pitch 2', eventColor: 'green' },
                        { id: 'c', title: 'Pitch 3', eventColor: 'orange' },
                        { id: 'd', title: 'Pitch 4', eventColor: 'red' }
                    ],
                    events: [
                        { id: '1', resourceId: 'a', start: '2017-04-06', end: '2017-04-08', title: 'event 1' },
                        { id: '2', resourceId: 'a', start: '2017-04-07T09:00:00', end: '2017-04-07T14:00:00', title: 'event 2' },
                        { id: '3', resourceId: 'b', start: '2017-04-07T12:00:00', end: '2017-04-08T06:00:00', title: 'event 3' },
                        { id: '4', resourceId: 'c', start: '2017-04-07T07:30:00', end: '2017-04-07T09:30:00', title: 'event 4' },
                        { id: '5', resourceId: 'd', start: '2017-04-07T10:00:00', end: '2017-04-07T15:00:00', title: 'event 5' }
                    ],
                    drop: function(date, jsEvent, ui, resourceId) {
                        console.log('drop', date.format(), resourceId);
                        // is the "remove after drop" checkbox checked?
                        if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }
                    },
                    eventReceive: function(event) { // called when a proper external event is dropped
                        console.log('eventReceive', event);
                    },
                    eventDrop: function(event) { // called when an event (already on the calendar) is moved
                        console.log('eventDrop', event);
                    },
                    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                });
            }
        }
    }
</script>