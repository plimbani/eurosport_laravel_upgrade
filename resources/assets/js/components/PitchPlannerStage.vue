<template>
    <div>
        <div class='pitchPlanner'></div>
        <pitch-modal :matchFixture="matchFixture" v-if="setPitchModal"></pitch-modal>
    </div>
</template>

<script>
import moment from 'moment'
import Tournament from '../api/tournament.js'
import PitchModal from '../components/PitchModal.vue';

import _ from 'lodash'
    export default {
        data() {
            return {
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'scheduledMatches': [],
                'setPitchModal': 0,
                'matchFixture': {},
                'pitchBreak':{},
                'minDatePitch': '08:00:00',
                'maxDatePitch': '19:00:00',
                'tournamentFilter': this.$store.state.Tournament.tournamentFiler

            }
        },
        props: [ 'stage' ],
        components: {
            PitchModal
        },
        computed: {
            pitchesData() {
                return _.forEach(this.stage.pitches, (pitch) => {
                    pitch.title =  pitch.pitch_number;
                });
            },
            stageDate() {
                return moment(this.stage.tournamentStartDate, 'DD/MM/YYYY');
            }
        },
        created: function() {
            this.$root.$on('getTeamsByTournamentFilter', this.setPitchPlannerFilter);
        },
        mounted() {
            let vm = this
            // this.getScheduledMatch()
            setTimeout(function(){

                vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue)
            },500)
            setTimeout(function(){
                $('.fc-referee').each(function(referee){
                    if(this.id == -1){
                        $(this).closest('.fc-event').addClass('bg-grey');
                    }
                })
            },1000)

        },
        methods: {
            pitchBreakAdd() {
                let sPitch = []
                _.forEach(this.stage.pitches, (pitch) => {
                    _.forEach(pitch.pitch_availability, (availability) => {


                    sPitch.push({
                            'id': '',
                            'resourceId': availability.id,
                            'start':moment.utc(availability.stage_start_date+' '+availability.break_start_time,'DD/MM/YYYY hh:mm:ss'),
                            'end': moment.utc(availability.stage_start_date+' '+availability.break_end_time,'DD/MM/YYYY hh:mm:ss'),
                            'refereeId': -1,
                            'refereeText': 'R',
                            'title':'Pitch is not available',
                            'matchId':''
                        })
                    });
                });
                this.pitchBreak = sPitch
            },
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
                            minTime:  vm.minDatePitch?vm.minDatePitch:'08:00:00',
                            maxTime:  vm.maxDatePitch?vm.maxDatePitch:'19:00:00',
                            slotDuration: '00:05',
                            slotLabelInterval: '00:15',
                            // allDay: false,
                         //   timeFormat: 'H(:mm)',
                            slotLabelFormat:"HH:mm"
                        }
                    },
                    timeFormat: 'H:mm',
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
                    eventReceive: function( event, delta, revertFunc, jsEvent, ui, view) { // called when a proper external event is dropped
                         // add match to scheduled matches table - api call

                        let matchId = event.id?event.id:event.matchId
                        let matchData = {
                            'tournamentId': vm.tournamentId,
                            'pitchId': event.resourceId,
                            'matchId': matchId,
                            'matchStartDate': moment.utc(event.start._d).format('YYYY-MM-DD HH:mm:ss'),
                            'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD HH:mm:ss')
                        };

                        if(event.refereeId == -1){
                            vm.$root.$emit('setGameReset')
                            $('.fc-referee').each(function(referee){
                                if(this.id == -1){
                                    $(this).closest('.fc-event').addClass('bg-grey');
                                }
                            })

                        }else{
                        Tournament.setMatchSchedule(matchData).then(
                            (response) => {
                                // console.log(response)
                                toastr.success('Match has been scheduled successfully', 'Schedule Match', {timeOut: 5000});
                                    vm.$root.$emit('setGameReset')
                            },
                            (error) => {
                                console.log('Error occured during tournament api', error)
                            }
                        )
                        }
                            // console.log('eventReceive', event);
                    },
                    eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) { // called when an event (already on the calendar) is moved
                        // update api call
                        let ed = $(this)
                         if(event.refereeId == -1){
                            // vm.$root.$emit('setGameReset')
                            revertFunc();
                            setTimeout(function(){
                                $('.fc-referee').each(function(referee){
                                   if(this.id == -1){
                                        $(this).closest('.fc-event').addClass('bg-grey');
                                    }
                                })
                            },200)

                        }else{
                            setTimeout(function(){
                                $('.fc-referee').each(function(referee){
                                    if(this.id == -1){
                                        $(this).closest('.fc-event').addClass('bg-grey');
                                    }
                                })
                            },200)
                            let matchId = event.id?event.id:event.matchId
                            let matchData = {
                                'tournamentId': vm.tournamentId,
                                'pitchId': event.resourceId,
                                'matchId': matchId,
                                'matchStartDate': moment.utc(event.start._d).format('YYYY-MM-DD HH:mm:ss'),
                                'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD HH:mm:ss')
                            };
                            Tournament.setMatchSchedule(matchData).then(
                                (response) => {
                                    // console.log(response)
                                    toastr.success('Match schedule has been updated successfully', 'Schedule Match', {timeOut: 5000});
                                },
                                (error) => {
                                    console.log('Error occured during Tournament api ', error)
                                }
                            )
                        }
                        // console.log('eventDrop', event);
                    },
                    eventClick: function(calEvent, jsEvent, view) {
                        if(calEvent.refereeId == -1){
                            return false
                        }else{
                            vm.setPitchModal = 1
                            vm.matchFixture = calEvent
                            setTimeout(function() {
                                $('#matchScheduleModal').modal('show')
                                $("#matchScheduleModal").on('hidden.bs.modal', function () {
                                    vm.setPitchModal = 0
                                    vm.matchFixture = {}
                                    vm.getScheduledMatch('age_category','')
                                });
                            },200);
                        }
                    },
                    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                });
            },
            handleEventClick(calEvent, jsEvent, view) {
                // console.log(calEvent);
            },
            setPitchPlannerFilter(filterKey,filterValue) {
              let vm =this

              this.getScheduledMatch(filterKey,filterValue)
              setTimeout(function(){
                vm.$root.$emit('setPitchReset')
              },1000)
            },
            getScheduledMatch(filterKey='',filterValue='') {

              let tournamentData= []
              if(filterKey != '' && filterValue != '') {
                 tournamentData ={'tournamentId':this.tournamentId ,'filterKey':filterKey,'filterValue':filterValue.id}
              } else {
                 tournamentData ={'tournamentId':this.tournamentId }
              }

              // let tournamentData ={'tournamentId':this.tournamentId }
                Tournament.getFixtures(tournamentData).then(
                    (response)=> {

                        let vm = this
                        let counter =999;
                         let rdata = response.data.data
                            // this.reports = response.data.data
                            let sMatches = []
                            _.forEach(rdata, function(match) {
                                if(match.is_scheduled == 1){
                                    let mData =  {
                                        'id': match.fid,
                                        'resourceId': match.pitchId,
                                        'start':moment.utc(match.match_datetime,'YYYY-MM-DD HH:mm:ss'),
                                        'end': moment.utc(match.match_endtime,'YYYY-MM-DD HH:mm:ss'),
                                        'refereeId': match.referee_id?match.referee_id:0,
                                        'refereeText': 'R',
                                        'title':match.match_number,
                                        'matchId':match.fid
                                    }

                                sMatches.push(mData)
                                }
                            });
                            let minTimePitchAvail = []
                            let maxTimePitchAvail = []
                            let sPitch = []
                            _.forEach(this.stage.pitches, (pitch) => {
                                _.forEach(pitch.pitch_availability, (availability) => {
                                    if(availability.stage_start_time != '08:00:00' ){
                                        minTimePitchAvail.push(moment.utc(availability.stage_start_date+' '+availability.stage_start_time,'DD/MM/YYYY hh:mm:ss'))
                                    }
                                    if(availability.stage_start_time != '19:00:00' ){
                                        maxTimePitchAvail.push(moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'))
                                    }
                                    let mData = {
                                        'id': counter,
                                        'resourceId': pitch.id,
                                        'start':moment(availability.stage_start_date+' '+availability.break_start_time,'DD/MM/YYYY hh:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+availability.break_end_time,'DD/MM/YYYY hh:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title':'Pitch is not available',
                                        'matchId':-1
                                    }
                                    if(availability.stage_start_time != '8:00:00'){
                                        let mData1 = {
                                            'id': 'start_'+counter,
                                            'resourceId': pitch.id,
                                            'start':moment.utc(availability.stage_start_date+' '+'08:00:00','DD/MM/YYYY HH:mm:ss'),
                                            'end': moment.utc(availability.stage_start_date+' '+availability.stage_start_time,'DD/MM/YYYY hh:mm:ss'),
                                            'refereeId': -1,
                                            'refereeText': 'R',
                                            'title':'Pitch is not available',
                                            matchId:-1
                                        }
                                    sMatches.push(mData1)
                                    }
                                    if(availability.stage_end_time != '19:00:00'){
                                        let mData2 = {
                                            'id': 'end_'+counter,
                                            'resourceId': pitch.id,
                                            'start':moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'),
                                            'end': moment.utc(availability.stage_start_date+' '+'19:00:00','DD/MM/YYYY HH:mm:ss'),
                                            'refereeId': -1,
                                            'refereeText': 'R',
                                            'title':'Pitch is not available',
                                            'matchId': -1
                                        }
                                    sMatches.push(mData2)
                                    }

                                    sMatches.push(mData)
                                     counter = counter+1;
                                    });
                                });
                            // let minDatePitch = moment.min(minTimePitchAvail).format()
                            // vm.minDatePitch = moment.utc(minDatePitch).format('HH:mm:ss')
                            // console.log(maxTimePitchAvail)
                            // let maxDatePitch = moment.max(maxTimePitchAvail).format()
                            // vm.maxDatePitch = moment.utc(maxDatePitch).format('HH:mm:ss')
                            // vm.maxDatePitch = '16:00:00'
                            // console.log(maxDatePitch,'minDatePitch')
                            // this.pitchBreakAdd()
                            // sMatches.push(this.pitchBreak)
                            // console.log(sMatches,'sMatches')
                            this.scheduledMatches =sMatches
                            this.initScheduler();
                    }
                )
            }
        }
    };
</script>
