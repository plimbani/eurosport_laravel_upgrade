<template>
    <div>
        <div class='pitchPlanner' :id="'pitchPlanner'+stage.stageNumber"></div>
        <pitch-modal :matchFixture="matchFixture" v-if="setPitchModal"></pitch-modal>
        <delete-modal1 :deleteConfirmMsg="deleteConfirmMsg"  @confirmedBlock="deleteConfirmedBlock()"></delete-modal1>
    </div>
</template>

<script>
import moment from 'moment'
import Tournament from '../api/tournament.js'
import PitchModal from '../components/PitchModal.vue';
import DeleteModal1 from '../components/DeleteModalBlock.vue'

import _ from 'lodash'
    export default {
        data() {
            return {
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'scheduledMatches': [],
                'unavailableBlock': [],
                'setPitchModal': 0,
                'matchFixture': {},
                'pitchBreak':{},
                'minDatePitch': '08:00:00',
                'maxDatePitch': '19:05:00',
                'tournamentFilter': this.$store.state.Tournament.tournamentFiler,
                'deleteConfirmMsg': 'Are you sure you would like to delete this block?',
                'remBlock_id': 0
            }
        },
        props: [ 'stage' , 'defaultView'],
        components: {
            PitchModal,
            DeleteModal1,
        },
        computed: {
            pitchesData() {
                return _.forEach(this.stage.pitches, (pitch) => {
                    pitch.title = pitch.pitch_number;
                    pitch.resourceAreaWidth = '500px';
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

            $(this.$el).fullCalendar('changeView', 'agendaDay');
            // this.getScheduledMatch()
            setTimeout(function(){
                vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue)
                // vm.getUnavailablePitch()
            },500)
            setTimeout(function(){
                $('.fc-referee').each(function(referee){
                    if(this.id == -1 || this.id == -2 ){
                        $(this).closest('.fc-event').addClass('bg-grey');
                    }
                })
            },2000)
            setTimeout(function(){
                $('.fc-referee').each(function(referee){
                    if(this.id == -1 || this.id == -2 ){
                        $(this).closest('.fc-event').addClass('bg-grey');
                    }
                })
            },4000)

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
                            'refereeText': '',
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
                    aspectRatio: 1.8,
                    eventDurationEditable: false,
                    eventOverlap: false,
                    droppable: true,
                    // height: 350,
                    width:'100px',
                    defaultView: vm.defaultView,
                    defaultDate: vm.stageDate,
                    selectable: true,
                    header: false,
                    header: {
                        left: '',
                        right: 'timelineDay,agendaDay'
                    },
                    // scrollTime: '14:00',
                    eventLimit: true, // allow "more" link when too many events

                    views: {
                        timelineDay: {
                            name:'timeView',
                            buttonText: 'Time view',
                            minTime:  vm.minDatePitch?vm.minDatePitch:'08:00:00',
                            maxTime:  vm.maxDatePitch?vm.maxDatePitch:'19:00:00',
                            slotDuration: '00:05',
                            slotLabelInterval: '00:15',
                            slotLabelFormat:"HH:mm",
                            timeFormat: 'H:mm',
                            resourceAreaWidth: '100px',
                            width:100

                        },
                        agendaDay: {
                            name:'agendaView',
                            buttonText: 'Agenda view',
                            minTime:  vm.minDatePitch?vm.minDatePitch:'08:00:00',
                            maxTime:  vm.maxDatePitch?vm.maxDatePitch:'19:00:00',
                            slotDuration: '00:05',
                            slotLabelInterval: '00:15',
                            slotLabelFormat:"HH:mm",
                            timeFormat: 'H:mm',
                            resourceAreaWidth: '100px',
                            width:100

                        },
                    },

                    timeFormat: 'H:mm',
                    // uncomment this line to hide the all-day slot
                    allDaySlot: false,
                    resourceAreaWidth: '400px',
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

                        if(event.refereeId == -2){
                             Tournament.setUnavailableBlock(matchData).then(
                            (response) => {
                                // console.log(response)
                                let msg = 'Unavailable block has been scheduled successfully'
                                toastr.success('', 'Schedule Block', {timeOut: 5000});
                                    vm.$root.$emit('setPitchReset')
                            },
                            (error) => {
                                console.log('Error occured during tournament api', error)
                            }
                        )
                            vm.$root.$emit('setGameReset')
                            $('.fc-referee').each(function(referee){
                                if(this.id == -1 || this.id == -2){
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
                        if(event.refereeId == -1 || event.refereeId == -2){

                            // vm.$root.$emit('setGameReset')
                            revertFunc();
                            setTimeout(function(){
                                $('.fc-referee').each(function(referee){
                                    if(this.id == -1 || this.id == -2){
                                        $(this).closest('.fc-event').addClass('bg-grey');
                                    }
                                })
                            },200)

                        }else{
                            setTimeout(function(){
                                $('.fc-referee').each(function(referee){
                                    if(this.id == -1 || this.id == -2){
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
                        } else if(calEvent.refereeId == -2) {
                            let block_id = calEvent.id
                            let block = block_id.replace('block_','')
                            vm.remBlock_id = block
                            $("#delete_modal_block").modal("show");

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
                    resourceAreaWidth: {
                        default:'300px',
                    },
                    //schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                    schedulerLicenseKey: '0097912839-fcs-1497264705',
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
            deleteConfirmedBlock() {

                Tournament.removeUnavailableBlock(this.remBlock_id).then(
                    (response) => {
                        $("#delete_modal_block").modal("hide");
                        this.$root.$emit('setPitchReset')
                        toastr.success('Block has been deleted successfully.', 'Delete Block', {timeOut: 5000});
                    },
                    (error) => {
                        console.log('Error occured during tournament api', error)
                    }
                )

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
                            let scheduleBlock = false
                            let refereeId = ''
                            let matchTitle = ''
                            
                            if(match.is_scheduled == 1){
                                if(filterKey == 'age_category'){
                                    if( filterValue != '' && filterValue.id != match.tid){
                                        scheduleBlock = true
                                    }
                                }else if(filterKey == 'location'){
                                    if( filterValue != '' && filterValue.id != match.venueId){
                                        scheduleBlock = true
                                    }
                                }
                              // console.log('match is'+JSON.stringify(match))
                              let colorVal = (match.homeScore == null && match.AwayScore == null) ? '#2196F3' : 'green'
                              if(scheduleBlock){
                                colorVal = 'grey'
                              }
                              let lastName = match.last_name
                              let firstName = match.first_name
                              // console.log(lastName,firstName)
                              let refereeName = ''
                              if(lastName != null && firstName!= null){
                                //refereeName = lastName.substr(0,1)+firstName.substr(0,1)
                                refereeName = firstName+ ' '+lastName
                              }
                              if(scheduleBlock){
                                refereeId = -1
                                matchTitle = 'Match scheduled'
                              }else{
                                refereeId = match.referee_id?match.referee_id:0
                                 matchTitle = match.match_number
                              }
                             // console.log(val)
                                let mData =  {
                                    'id': match.fid,
                                    'resourceId': match.pitchId,
                                    'start':moment.utc(match.match_datetime,'YYYY-MM-DD HH:mm:ss'),
                                    'end': moment.utc(match.match_endtime,'YYYY-MM-DD HH:mm:ss'),
                                    'refereeId': refereeId,
                                    'refereeText': refereeName,
                                    'title':matchTitle,
                                    'color': colorVal,
                                    'matchId':match.fid
                                }
                               // console.log('match typeof')
                               // console.log(typeof match.homeScore)
                              //  console.log(mData)
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
                                    'color': 'grey',
                                    'matchId':-1
                                }

                                if(availability.stage_start_time != '08:00'){

                                     let mData1 = {
                                        'id': 'start_'+counter,
                                        'resourceId': pitch.id,
                                        'start':moment.utc(availability.stage_start_date+' '+'08:00:00','DD/MM/YYYY HH:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+availability.stage_start_time,'DD/MM/YYYY hh:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title':'Pitch is not available',
                                        'color': 'grey',
                                        'matchId':-1
                                    }
                                sMatches.push(mData1)
                                }
                                if(availability.stage_end_time != '19:00'){
                                    let mData2 = {
                                        'id': 'end_'+counter,
                                        'resourceId': pitch.id,
                                        'start':moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+'19:00:00','DD/MM/YYYY HH:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title':'Pitch is not available',
                                        'color': 'grey',
                                        'matchId': -1
                                    }
                                sMatches.push(mData2)
                                }

                                sMatches.push(mData)
                                 counter = counter+1;
                                });
                            });
                        this.scheduledMatches =sMatches
                        this.getUnavailablePitch()
                        this.stageWithoutPitch()
                        setTimeout(function(){
                            vm.initScheduler();
                        },2000)
                    }
                )
            },
            stageWithoutPitch() {

              // here we check if no pitch is added

              if(this.stage.pitches.length == 0) {
                let start_date = this.stage.tournamentStartDate + '08:00:00'
                let end_date = this.stage.tournamentStartDate + '19:00:00'

                let mData21 = {
                    'id': '111212',
                    'resourceId': '111213',
                    'start':moment.utc(start_date,'DD/MM/YYYY hh:mm:ss'),
                    'end': moment.utc(end_date,'DD/MM/YYYY HH:mm:ss'),
                    'refereeId': -2,
                    'refereeText': '',
                    'title': 'Pitch is not available',
                    'color': 'grey',
                    'matchId': '111212'
              }
               this.scheduledMatches.push(mData21)
               // Also Add for Resources as well
                let resources = {'id':'111213','eventColor':'grey'}
               this.pitchesData = resources
             }

            },
            getUnavailablePitch() {
                let vm1 =this
                let tournamentData ={'tournamentId':this.tournamentId,'startDate': moment(this.stageDate).format('YYYY-MM-DD')}
                Tournament.getUnavailablePitch(tournamentData).then(
                    (response) => {
                    _.forEach(response.data.data, (block) => {
                        let mData2 = {
                                'id': 'block_'+block.id,
                                'resourceId': block.pitch_id,
                                'start':moment.utc(block.match_start_datetime,'YYYY/MM/DD hh:mm:ss'),
                                'end': moment.utc(block.match_end_datetime,'YYYY/MM/DD HH:mm:ss'),
                                'refereeId': -2,
                                'refereeText': '',
                                'title': 'Unavailable',
                                'color': 'grey',
                                'matchId': 'block_'+block.id
                            }
                        this.scheduledMatches.push(mData2)
                        this.unavailableBlock.push(mData2)
                        });
                    },
                    (error) => {
                        console.log('Error occured during Tournament api ', error)
                    }
                )
            }
        }
    };
</script>
