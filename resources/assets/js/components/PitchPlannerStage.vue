<template>
    <div class='pitchPlanner'></div>
</template>

<script>
 import moment from 'moment'
import Tournament from '../api/tournament.js'
import _ from 'lodash'
    export default {
        data() {
            return {
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'scheduledMatches': []
            }
        },
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
            Tournament.getAllScheduledMatch(this.tournamentId).then(
                (response) => { 
                // this.scheduledMatches = response.data.data
                let rdata = response.data.data 
                    // this.reports = response.data.data 
                    let sMatches = []
                    _.forEach(rdata, function(match) {
                        let mData =  {'id': match.id, 'resourceId': match.pitch_id,'start':moment.utc(match.match_datetime,'YYYY-MM-DD hh:mm:ss'), 'end': moment.utc(match.match_endtime,'YYYY-MM-DD hh:mm:ss'),'title':match.match_number,
                        matchId:match.id}
                        sMatches.push(mData)
                    });
                    
                   // console.log(sMatches)
                    this.scheduledMatches =sMatches
                    this.initScheduler();
                    // conole.log(response,'response')
                },
                (error) => {
                    console.log('Error occured during Tournament api ', error)
                }
            ) 
            // console.log('eventReceive', event);
            
            // console.log('this.stage', this.stage);
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
                    // events: [

                    //     { id: '2', resourceId: '1', start: '2017-03-28T09:00:00', end: '2017-03-28T14:00:00', title: 'event 2' },
                    //     { id: '3', resourceId: '1', start: '2017-03-28T12:00:00', end: '2017-03-28T06:00:00', title: 'event 3' },
                    //     { id: '4', resourceId: '10', start: '2017-03-28T07:30:00', end: '2017-03-28T09:30:00', title: 'event 4' },
                    //     { id: '5', resourceId: '10', start: '2017-03-28T10:00:00', end: '2017-03-28T15:00:00', title: 'event 5' }
                    // ],
                    events: vm.scheduledMatches,
                    drop: function(date, jsEvent, ui, resourceId) {
                        // console.log('drop', resourceId);
                        $(this).remove();
                    },
                    eventReceive: function(event) { // called when a proper external event is dropped
                         // add match to scheduled matches table - api call
                    let matchData = {'tournamentId': vm.tournamentId, 'pitchId': event.resourceId, 'matchId': event.matchId, matchStartDate: moment.utc(event.start._d).format('YYYY-MM-DD hh:mm:ss'), 'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD hh:mm:ss')};
                    
                        Tournament.setMatchSchedule(matchData).then(
                            (response) => {  
                                // console.log(response)
                                toastr.success('Match has been scheduled successfully', 'Schedule match', {timeOut: 5000});
                            },
                            (error) => {
                                console.log('Error occured during Tournament api ', error)
                            }
                        ) 
                        // console.log('eventReceive', event);
                    },
                    eventDrop: function(event) { // called when an event (already on the calendar) is moved
                        // update api call
                         let matchData = {'tournamentId': vm.tournamentId, 'pitchId': event.resourceId, 'matchId': event.matchId, matchStartDate: moment.utc(event.start._d).format('YYYY-MM-DD hh:mm:ss'), 'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD hh:mm:ss')};
                    
                        Tournament.setMatchSchedule(matchData).then(
                            (response) => {  
                                // console.log(response)
                                toastr.success('Match schedule has been updated successfully', 'Schedule match', {timeOut: 5000});
                            },
                            (error) => {
                                console.log('Error occured during Tournament api ', error)
                            }
                        ) 
                        // console.log('eventDrop', event);
                    },
                    eventClick: function(calEvent, jsEvent, view) {
                        vm.handleEventClick(calEvent, jsEvent, view);
                    },
                    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                });
            },
            handleEventClick(calEvent, jsEvent, view) {
                // console.log(calEvent);
            }
        }
    };
</script>