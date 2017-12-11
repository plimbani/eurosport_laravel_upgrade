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
                'maxDatePitch': '23:05:00',
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
             this.$root.$on('reloadAllEvents', this.reloadAllEvents);


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
                $("body .js-loader").removeClass('d-none');
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
                
                let sPitch = [];
                _.forEach(this.stage.pitches, (pitch) => {
                    _.forEach(pitch.pitch_availability, (availability) => {
                        _.forEach(availability.pitch_breaks, (pitchBreak) => {
                        sPitch.push({
                            'id': '',
                            'resourceId': pitch.id,
                            'start':moment.utc(availability.stage_start_date+' '+pitchBreak.break_start,'DD/MM/YYYY hh:mm:ss'),
                            'end': moment.utc(availability.stage_start_date+' '+pitchBreak.break_end,'DD/MM/YYYY hh:mm:ss'),
                            'refereeId': -1,
                            'refereeText': '',
                            'title':'Pitch',
                            'matchId':''
                            })
                        });  
                    });
                });
                this.pitchBreak = sPitch
            },
            initScheduler() {
                let vm = this;
                 $("body .js-loader").addClass('d-none');
                $(this.$el).fullCalendar({
                    editable: true,
                    aspectRatio: 1.8,
                    eventDurationEditable: false,
                    eventOverlap:vm.currentView == 'refereeTab',
                    droppable: true,
                    defaultTimedEventDuration: '00:00',
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
                            maxTime:  vm.maxDatePitch?vm.maxDatePitch:'23:00:00',
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
                            maxTime:  vm.maxDatePitch?vm.maxDatePitch:'23:00:00',
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
                                        if(response.data.data != -1 && response.data.data != -2){
                                            vm.$store.dispatch('setMatches');
                                             toastr.success(response.data.message, 'Schedule Match', {timeOut: 5000});
                                             vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue)
                                             vm.reloadAllEvents()
                                        } else {
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
                         // Code for horizontal scroll bar
                         let totalPitches = vm.stage.pitches.length;
                         if(totalPitches > 8) {
                            $(vm.$el).find('.fc-view-container .fc-view > table').css('width', (totalPitches * 95) + 'px');
                         }
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
                                    if(response.data.data != -1 && response.data.data != -2){
                                            toastr.success('Match schedule has been updated successfully', 'Schedule Match', {timeOut: 5000});
                                            let matchScheduleChk =new Promise((resolve, reject) => {
                                                resolve(vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue));
                                            });

                                            matchScheduleChk.then((successMessage) => {
                                              vm.reloadAllEvents();
                                            });
                                            // vm.reloadAllEvents()
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
                let vm = this;
                let ev = this.$el;
                $(ev).fullCalendar( 'removeEvents' )
                 // console.log(this.$el);
                setTimeout(function(){
                    $(ev).fullCalendar('addEventSource', vm.scheduledMatches);
                },1000)
            },
            getScheduledMatch(filterKey='',filterValue='') {
                // this.$store.dispatch('SetScheduledMatches');
                let tournamentData= [];
                let fixtureDate = moment(this.stageDate).format('YYYY-MM-DD');
                if(filterKey != '' && filterValue != '') {
                    tournamentData ={'tournamentId':this.tournamentId ,'filterKey':filterKey,'filterValue':filterValue.id,'is_scheduled':true,'fixture_date':fixtureDate}
                } else {
                    tournamentData ={'tournamentId':this.tournamentId,'is_scheduled':true,'fixture_date':fixtureDate}
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

                            let displayMatchNumber = match.displayMatchNumber
                            let displayHomeTeamPlaceholder = match.displayHomeTeamPlaceholderName
                            let displayAwayTeamPlaceholder = match.displayAwayTeamPlaceholderName
                            let displayMatchName = displayMatchNumber;

                            let mtchNumber = match.match_number
                            let mtchNumber1 = mtchNumber.split(".")
                            let mtchNum = mtchNumber1[0]+'.'+mtchNumber1[1]

                            let lastElm = mtchNumber1[2]
                            let teams = lastElm.split("-")

                            let Placehometeam =  teams[0]
                            let Placeawayteam =  teams[1]

                            if(match.Home_id != 0){
                                Placehometeam = displayHomeTeamPlaceholder = match.HomeTeam
                            } else if(match.Home_id == 0 && match.homeTeamName == '@^^@') {
                                if(match.competition_actual_name.indexOf('Group') !== -1) {
                                    Placehometeam = displayHomeTeamPlaceholder = match.homePlaceholder
                                } else if(match.competition_actual_name.indexOf('Pos') !== -1){
                                    Placehometeam = displayHomeTeamPlaceholder = 'Pos-' + match.homePlaceholder
                                }
                            }

                            if(match.Away_id != 0){
                                Placeawayteam = displayAwayTeamPlaceholder = match.AwayTeam
                            } else if(match.Away_id == 0 && match.awayTeamName == '@^^@') {
                                if(match.competition_actual_name.indexOf('Group') !== -1) {
                                    Placeawayteam = displayAwayTeamPlaceholder = match.awayPlaceholder
                                } else if(match.competition_actual_name.indexOf('Pos') !== -1){
                                    Placeawayteam = displayAwayTeamPlaceholder = 'Pos-' + match.awayPlaceholder
                                }
                            }

                            let mtc = ''
                            mtc = mtchNum+'.'+Placehometeam+'-'+Placeawayteam
                            match.match_number = mtc

                            displayMatchName = displayMatchName.replace('@HOME', displayHomeTeamPlaceholder).replace('@AWAY', displayAwayTeamPlaceholder)
                            
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
                              let colorVal = match.category_age_color;
                              var isBright = (parseInt(vm.getBrightness(match.category_age_color)) > 160);
                              let borderColorVal;
                              if(isBright) {
                                borderColorVal = vm.LightenDarkenColor(match.category_age_color, -40);
                              } else {
                                borderColorVal = vm.LightenDarkenColor(match.category_age_color, 40);
                              }
                              let textColorVal = match.category_age_font_color;
                              let fixtureStripColor = match.competation_color_code != null ? match.competation_color_code : '#FFFFFF';

                              if(scheduleBlock){
                                colorVal = 'grey'
                                textColorVal = '#FFFFFF'
                                borderColorVal = 'grey'
                                fixtureStripColor = 'grey'
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
                                matchTitle = 'Match scheduled - '+displayMatchName
                              }else{
                                refereeId = match.referee_id?match.referee_id:0
                                 matchTitle = displayMatchName
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
                                    'textColor': textColorVal,
                                    'borderColor': borderColorVal,
                                    'matchId':match.fid,
                                    'matchAgeGroupId':match.age_group_id,
                                    'fixtureStripColor': fixtureStripColor,
                                    'displayFlag': match.min_interval_flag == 1 ?'block':''
                                }
                            sMatches.push(mData)
                            }
                        });

                        let sPitch = []

                        _.forEach(this.stage.pitches, (pitch) => {
                            _.forEach(pitch.pitch_availability, (availability) => {
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
                                        'textColor': '#FFFFFF',
                                        'borderColor': 'grey',
                                        'matchId':-1,
                                        'matchAgeGroupId':'',
                                        'fixtureStripColor': '',
                                        'displayFlag':''
                                    }
                                    sMatches.push(mData1)
                                    counter = counter+1;
                                }
                                if(availability.stage_end_time != '23:00'){
                                    let mData2 = {
                                        'id': 'end_'+counter,   
                                        'resourceId': pitch.id,
                                        'start':moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+'23:00:00','DD/MM/YYYY HH:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title':'Pitch is not available',
                                        'color': 'grey',
                                        'textColor': '#FFFFFF',
                                        'borderColor': 'grey',
                                        'matchId': -1,
                                        'matchAgeGroupId':'',
                                        'fixtureStripColor': '',
                                        'displayFlag':''
                                    }
                                    sMatches.push(mData2)
                                    counter = counter+1;
                                }
                                _.forEach(availability.pitch_breaks, (pitchBreak) => {
                                    if(pitchBreak.break_start != pitchBreak.break_end) {
                                        let mData = {
                                            'id': counter,
                                            'resourceId': pitch.id,
                                            'start':moment.utc(availability.stage_start_date+' '+pitchBreak.break_start,'DD/MM/YYYY hh:mm:ss'),
                                            'end': moment.utc(availability.stage_start_date+' '+pitchBreak.break_end,'DD/MM/YYYY hh:mm:ss'),
                                            'refereeId': -1,
                                            'refereeText': 'R',
                                            'title':'Pitch is not available',
                                            'color': 'grey',
                                            'textColor': '#FFFFFF',
                                            'borderColor': 'grey',
                                            'matchId':-1,
                                            'matchAgeGroupId':'',
                                            'fixtureStripColor': '',
                                            'displayFlag': ''
                                        }

                                        sMatches.push(mData)
                                        counter = counter+1;
                                    }
                                });
                            });
                        });
                        this.scheduledMatches =sMatches

                        this.stageWithoutPitch()
                        this.getUnavailablePitch()

                        $('.fc-referee').each(function(referee){
                            if(this.id == -1 || this.id == -2 ){
                                $(this).closest('.fc-event').addClass('bg-grey');
                            }
                        })

                    }

                )
              return "Finish";
            },
            stageWithoutPitch() {

              // here we check if no pitch is added

              if(this.stage.pitches.length == 0) {
                let start_date = this.stage.tournamentStartDate + '08:00:00'
                let end_date = this.stage.tournamentStartDate + '23:00:00'

                let mData21 = {
                    'id': '111212',
                    'resourceId': '111213',
                    'start':moment.utc(start_date,'DD/MM/YYYY hh:mm:ss'),
                    'end': moment.utc(end_date,'DD/MM/YYYY HH:mm:ss'),
                    'refereeId': -2,
                    'refereeText': '',
                    'title': 'Pitch is not available',
                    'color': 'grey',
                    'textColor': '#FFFFFF',
                    'matchId': '111212',
                    'matchAgeGroupId':'',
                    'displayFlag':''

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
                            'textColor': '#FFFFFF',
                            'matchId': 'block_'+block.id,
                            'matchAgeGroupId':'',
                            'displayFlag':''
                        }
                        this.scheduledMatches.push(mData2)
                        this.unavailableBlock.push(mData2)
                        });
                     vm1.initScheduler();
                    },
                    (error) => {
                        vm1.initScheduler();
                    }
                )
            },
            LightenDarkenColor(colorCode, amount) {
                var usePound = false;

                if (colorCode[0] == "#") {
                    colorCode = colorCode.slice(1);
                    usePound = true;
                }

                var num = parseInt(colorCode, 16);

                var r = (num >> 16) + amount;

                if (r > 255) {
                    r = 255;
                } else if (r < 0) {
                    r = 0;
                }

                var b = ((num >> 8) & 0x00FF) + amount;

                if (b > 255) {
                    b = 255;
                } else if (b < 0) {
                    b = 0;
                }

                var g = (num & 0x0000FF) + amount;

                if (g > 255) {
                    g = 255;
                } else if (g < 0) {
                    g = 0;
                }

                return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);
            },
            getBrightness(hexCode) {
              hexCode = hexCode.replace('#', '');
              var c_r = parseInt(hexCode.substr(0, 2),16);
              var c_g = parseInt(hexCode.substr(2, 2),16);
              var c_b = parseInt(hexCode.substr(4, 2),16);
              return ((c_r * 299) + (c_g * 587) + (c_b * 114)) / 1000;
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
