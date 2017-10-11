<template>
    <div>
        <div class='pitchPlanner' :id="'pitchPlanner'+stage.stageNumber"></div>
        <pitch-modal :matchFixture="matchFixture" :section="section" v-if="setPitchModal"></pitch-modal>
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
                'maxDatePitch': '20:05:00',
                'tournamentFilter': this.$store.state.Tournament.tournamentFiler,
                'deleteConfirmMsg': 'Are you sure you would like to delete this block?',
                'remBlock_id': 0,
                'section': 'pitchPlanner',
                // 'currentView': this.$store.getters.curStageView
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
                    pitch.title = pitch.pitch_number+' ('+pitch.size+')';
                    pitch.resourceAreaWidth = '500px';
                });
            },
            currentView() {
               return this.$store.getters.curStageView
            },
            stageDate() {
                return moment(this.stage.tournamentStartDate, 'DD/MM/YYYY');
            },
            // getScheduledMatches() {

            //     return this.$store.getters.scheduledMatches

            // }

        },
        created: function() {
            // this.$root.$on('getTeamsByTournamentFilter', this.setPitchPlannerFilter);
            // this.$root.$on('getPitchesByTournamentFilter', this.resetPitch);
            // this.$root.$on('matchSchedulerChange', this.matchSchedulerChange);

        },
        mounted() {
            let cal = this.$el;
            let vm = this
            vm.initComponent()
            $(this.$el).fullCalendar('changeView', 'agendaDay');

            $(window).scroll(function() {
                if($(".pitch_planner_section").length > 0) {
                    setGameAndRefereeTabHeight();
                }
            });
            // this.getScheduledMatch()
        },
        methods: {
            initComponent(){
                let vm = this
                // setTimeout(function(){
                    vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue)
                    if($(".pitch_planner_section").length > 0) {
                        setGameAndRefereeTabHeight();
                    }
                    // vm.getUnavailablePitch()
                // },500)
            
            setTimeout(function(){
                
                if($(".pitch_planner_section").length > 0) {
                    setGameAndRefereeTabHeight();
                }
            },1000)

            },
            matchSchedulerChange() {
                return this.$store.getters.scheduledMatches
            },
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
                    eventOverlap:vm.currentView == 'refereeTab',
                    droppable: true,
                    width:'100px',
                    defaultView: vm.defaultView,
                    defaultDate: vm.stageDate,
                    selectable: true,
                    durationEditable : true,
                    header: false,
                    header: {
                        left: '',
                        right: 'timelineDay,agendaDay'
                    },
                    eventLimit: true, // allow "more" link when too many events
                    views: {
                        timelineDay: {
                            name:'timeView',
                            buttonText: 'Time view',
                            minTime:  vm.minDatePitch?vm.minDatePitch:'08:00:00',
                            maxTime:  vm.maxDatePitch?vm.maxDatePitch:'20:00:00',
                            slotDuration: '00:05',
                            slotLabelInterval: '00:15',
                            slotLabelFormat:"HH:mm",
                            timeFormat: 'H:mm',
                            resourceAreaWidth: '100px',
                            // width:75,
                            slotWidth:18,
                            resourceLabelText: ' ',
                        },
                        agendaDay: {
                            name:'agendaView',
                            buttonText: 'Agenda view',
                            minTime:  vm.minDatePitch?vm.minDatePitch:'08:00:00',
                            maxTime:  vm.maxDatePitch?vm.maxDatePitch:'20:00:00',
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
                    events: vm.scheduledMatches,
                    drop: function(date, jsEvent, ui, resourceId) {
                       // $(this).remove();
                    },
                    eventReceive: function( event, delta, revertFunc, jsEvent, ui, view) { // called when a proper external event is dropped
                        if(event.refereeId == -3 ){
                            let matchData = {
                                'tournamentId': vm.tournamentId,
                                'refereeId': event.id,
                                'pitchId': event.resourceId,
                                'matchStartDate': moment.utc(event.start._d).format('YYYY-MM-DD HH:mm:ss'),
                                'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD HH:mm:ss'),
                                'filterKey':vm.tournamentFilter.filterKey,
                                'filterValue':vm.tournamentFilter.filterValue
                            };
                            let cal = this.$el;
                            Tournament.assignReferee(matchData).then(
                                (response) => {
                                    let updatedMatch = response.data.data;
                                    if(response.data.status_code == 200 && response.data.data.status == true){
                                         toastr.success('Referee has been assigned successfully', 'Assigned Referee ', {timeOut: 5000});
                                        vm.$store.dispatch('getAllReferee',vm.tournamentId);
                                        vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue)
                                       vm.reloadAllEvents()

                                    }else{
                                        let errorMsg = updatedMatch.data;
                                        toastr.error(errorMsg, 'Assigned Referee ', {timeOut: 5000});

                                        $('.fc.fc-unthemed').fullCalendar( 'removeEvents', [event.matchId] )
                                        vm.$store.dispatch('getAllReferee',vm.tournamentId);
                                    }
                                },
                                (error) => {
                                    toastr.error('Something goes wrong', 'Assigned Referee ', {timeOut: 5000});
                                    vm.$root.$emit('setPitchPlanTab');
                                }
                            )
                        }else{
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
                                        let msg = 'Unavailable block has been scheduled successfully'
                                        toastr.success('', 'Schedule Block', {timeOut: 5000});
                                    },
                                    (error) => {
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
                                    if(response.data.status_code == 200 ){
                                        if(response.data.data != -1){
                                            vm.$store.dispatch('setMatches');
                                             toastr.success(response.data.message, 'Schedule Match', {timeOut: 5000});
                                        }else{
                                            $('.fc.fc-unthemed').fullCalendar( 'removeEvents', [event._id] )
                                            vm.$store.dispatch('setMatches');
                                            vm.matchFixture = {}
                                            vm.getScheduledMatch('age_category','')
                                            toastr.error(response.data.message, 'Schedule Match', {timeOut: 5000});
                                        }
                                    }
                                },
                                (error) => {
                                }
                            )
                            }
                        }
                    },
                    eventAfterAllRender: function(view ){
                         $('#add_referee').prop('disabled', false);
                    },
                    eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) { // called when an event (already on the calendar) is moved
                        // update api call
                        if(vm.currentView == 'refereeTab'){
                            revertFunc()
                            return false;
                        }

                        let ed = $(this)
                        if(event.refereeId == -1 || event.refereeId == -2){
                            revertFunc();
                            
                        }else{
                            
                            let matchId = event.id ? event.id : event.matchId
                            let matchData = {
                                'tournamentId': vm.tournamentId,
                                'pitchId': event.resourceId,
                                'matchId': matchId,
                                'matchStartDate': moment.utc(event.start._d).format('YYYY-MM-DD HH:mm:ss'),
                                'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD HH:mm:ss')
                            };
                            Tournament.setMatchSchedule(matchData).then(
                                (response) => {
                                    if(response.data.data != -1){
                                            toastr.success('Match schedule has been updated successfully', 'Schedule Match', {timeOut: 5000});
                                            
                                        }else{
                                            revertFunc();
                                            toastr.error(response.data.message, 'Schedule Match', {timeOut: 5000});
                                        }
                                    
                                },
                                (error) => {
                                }
                            )
                        }
                    },
                    eventClick: function(calEvent, jsEvent, view) {

                        // $('div.fc-unthemed').fullCalendar('updateEvent', calEvent);
                       // return false;
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
                                    // setTimeout(function(){
                                    vm.matchFixture = {}
                                    vm.$store.dispatch('setCompetationWithGames');
                                    vm.getScheduledMatch('age_category','')
                                    // },500)
                                    
                                });
                             },100);
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
            
            deleteConfirmedBlock() {

                Tournament.removeUnavailableBlock(this.remBlock_id).then(
                    (response) => {
                        $("#delete_modal_block").modal("hide");
                        this.$root.$emit('setPitchReset')
                        toastr.success('Block has been deleted successfully.', 'Delete Block', {timeOut: 5000});
                    },
                    (error) => {
                        // console.log('Error occured during tournament api', error)
                    }
                )

            },
            reloadAllEvents(){
                let vm = this
                 $('.fc.fc-unthemed').fullCalendar( 'removeEvents' )

                setTimeout(function(){
                    $('div.fc-unthemed').fullCalendar('addEventSource', vm.scheduledMatches);
                },1000)
            },
            getScheduledMatch(filterKey='',filterValue='') {
                // this.$store.dispatch('SetScheduledMatches');
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
                        let sMatches = []

                        _.forEach(rdata, function(match) {
                            let scheduleBlock = false
                            let refereeId = ''
                            let matchTitle = ''

                            let mtchNumber = match.match_number
                            let mtchNumber1 = mtchNumber.split(".")
                            let mtchNum = mtchNumber1[0]+'.'+mtchNumber1[1]

                            let lastElm = mtchNumber1[2]
                            let teams = lastElm.split("-")

                            let Placehometeam =  teams[0]
                            let Placeawayteam =  teams[1]

                            if(match.Home_id != 0){
                            Placehometeam = match.HomeTeam
                            }
                            if(match.Away_id != 0){
                            Placeawayteam = match.AwayTeam
                            }
                            let mtc = ''
                            mtc = mtchNum+'.'+Placehometeam+'-'+Placeawayteam
                            match.match_number = mtc
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
                              let colorVal = (match.homeScore == null && match.AwayScore == null) ? '#e9e9e9' : 'green';
                              let borderColorVal = (match.homeScore == null && match.AwayScore == null) ? '#d3d3d3' : 'green';
                              if(scheduleBlock){
                                colorVal = 'grey'
                              }
                              let lastName = match.last_name
                              let firstName = match.first_name
                              let refereeName = ''
                              if(lastName != null && firstName!= null){
                                //refereeName = lastName.substr(0,1)+firstName.substr(0,1)
                                refereeName = firstName+ ' '+lastName
                              }
                              if(scheduleBlock){
                                refereeId = -1
                                matchTitle = 'Match scheduled - '+match.match_number
                              }else{
                                refereeId = match.referee_id?match.referee_id:0
                                 matchTitle = match.match_number
                              }
                                let mData =  {
                                    'id': match.fid,
                                    'resourceId': match.pitchId,
                                    'start':moment.utc(match.match_datetime,'YYYY-MM-DD HH:mm:ss'),
                                    'end': moment.utc(match.match_endtime,'YYYY-MM-DD HH:mm:ss'),
                                    'refereeId': refereeId,
                                    'refereeText': refereeName,
                                    'title':matchTitle,
                                    'color': colorVal,
                                    'borderColor': borderColorVal,
                                    'matchId':match.fid,
                                    'matchAgeGroupId':match.age_group_id,
                                    'categoryAgeColor': match.category_age_color
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
                                if(availability.stage_start_time != '20:00:00' ){
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
                                    'borderColor': 'grey',
                                    'matchId':-1,
                                    'matchAgeGroupId':'',
                                    'categoryAgeColor': ''
                                }

                                if(availability.stage_start_time != '08:00'){
                                    let mData1 = {
                                        'id': 'start_'+counter,
                                        'resourceId': pitch.id,
                                        'start':moment.utc(availability.stage_start_date+' '+'08:00:00','DD/MM/YYYY HH:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+availability.stage_start_time,'DD/MM/YYYY hh:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title': 'Pitch is not available',
                                        'color': 'grey',
                                        'borderColor': 'grey',
                                        'matchId':-1,
                                        'matchAgeGroupId':'',
                                        'categoryAgeColor': ''
                                    }
                                    sMatches.push(mData1)
                                }
                                if(availability.stage_end_time != '20:00'){
                                    let mData2 = {
                                        'id': 'end_'+counter,
                                        'resourceId': pitch.id,
                                        'start':moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+'20:00:00','DD/MM/YYYY HH:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title':'Pitch is not available',
                                        'color': 'grey',
                                        'borderColor': 'grey',
                                        'matchId': -1,
                                        'matchAgeGroupId':'',
                                        'categoryAgeColor': ''
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
                        vm.initScheduler();
                            $('.fc-referee').each(function(referee){
                                if(this.id == -1 || this.id == -2 ){
                                    $(this).closest('.fc-event').addClass('bg-grey');
                                }
                            })
                    }
                )
            },
            stageWithoutPitch() {

              // here we check if no pitch is added

              if(this.stage.pitches.length == 0) {
                let start_date = this.stage.tournamentStartDate + '08:00:00'
                let end_date = this.stage.tournamentStartDate + '20:00:00'

                let mData21 = {
                    'id': '111212',
                    'resourceId': '111213',
                    'start':moment.utc(start_date,'DD/MM/YYYY hh:mm:ss'),
                    'end': moment.utc(end_date,'DD/MM/YYYY HH:mm:ss'),
                    'refereeId': -2,
                    'refereeText': '',
                    'title': 'Pitch is not available',
                    'color': 'grey',
                    'matchId': '111212',
                    'matchAgeGroupId':''
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
                            'matchId': 'block_'+block.id,
                            'matchAgeGroupId':''
                        }
                        this.scheduledMatches.push(mData2)
                        this.unavailableBlock.push(mData2)
                        });
                    },
                    (error) => {
                    }
                )
            }
        }
    };

    $('.fc-referee').click(function(){
    })
    function setGameAndRefereeTabHeight() {
        var $el = $(".tab-content .card-block .pitch-planner-wrapper");
        var elH = $el.outerHeight(),
        H   = $(window).height(),
        r   = $el[0].getBoundingClientRect(), t=r.top, b=r.bottom;
        var considerHeaderHeight = 0;
        if(($(window).scrollTop() + $("header").height()) > $el.offset().top) {
            considerHeaderHeight = $("header").height();
        }              
        var leftViewHeight = Math.max(0, t>0? Math.min(elH, H-t) : (b<H?b:H)) - $("#gameReferee .nav.nav-tabs").height() - parseInt($("#gameReferee .tab-content").css('margin-top').replace('px', '')) - considerHeaderHeight - 10;
        $("#game-list").css('height', leftViewHeight + 'px');
        $("#referee-list").css('height', leftViewHeight + 'px');
    }
</script>
