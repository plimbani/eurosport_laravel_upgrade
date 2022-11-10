<template>
    <div>
        <div class='pitchPlanner' :id="'pitchPlanner'+stage.stageNumber"></div>
        <pitch-modal :matchFixture="matchFixture" :section="section" v-if="setPitchModal" :stageIndex="stageIndex"></pitch-modal>
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
                'currentScheduledMatch': null,
                'unChangedMatchFixtureModalOpen': false,
                'unChangedMatchFixtures': [],
                // 'currentView': this.$store.getters.curStageView
                'isAnotherMatchScheduled': false,
                'eventDropped': false,
                'scrollBeforeEventDropped': null,
            }
        },
        props: [ 'stage' , 'defaultView', 'scheduleMatchesArray', 'isMatchScheduleInEdit', 'stageIndex', 'enableScheduleFeatureAsDefault'],
        components: {
            PitchModal,
            DeleteModal1,
        },
        computed: {
            pitchesData() {
              /*if(this.tournamentFilter.filterKey == 'location' && this.tournamentFilter.filterValue != ''){
                    var pitches = _.remove(this.stage.pitches, (pitch) => {
                        let assign = false;
                        pitch.title = pitch.pitch_number+' ('+pitch.size+')';
                        pitch.resourceAreaWidth = '500px';
                        if(this.tournamentFilter.filterValue.id == pitch.venue_id){
                            assign = true;
                        }

                        return assign;
                    });
                    return pitches;
                }

                return _.forEach(this.stage.pitches, (pitch) => {
                    pitch.title = pitch.pitch_number+' ('+pitch.size+')';
                    pitch.resourceAreaWidth = '500px';
                });*/

              let pitches = this.stage.pitches;

              if (this.tournamentFilter.filterKey === 'location' && this.tournamentFilter.filterValue !== '') {

                pitches = this.stage.pitches.filter(pitch => pitch.venue_id === this.tournamentFilter.filterValue.id);

              } /*else if (this.tournamentFilter.filterKey === 'age_category' && this.tournamentFilter.filterValue !== '' && this.tournamentFilter.filterDependentValue === '') {

                pitches = this.stage.pitches.filter(pitch => pitch.matchAgeGroupId === this.tournamentFilter.filterValue.id);

              } else if (this.tournamentFilter.filterKey === 'age_category' && this.tournamentFilter.filterValue !== '' && this.tournamentFilter.filterDependentValue !== '') {

                pitches = this.stage.pitches.filter(pitch => pitch.matchCompetitionId === this.tournamentFilter.filterDependentValue);

              }*/ else if (this.tournamentFilter.filterKey === 'pitch_type' && this.tournamentFilter.filterValue !== '') {

                pitches = this.stage.pitches.filter(pitch => pitch.type.toLowerCase() === this.tournamentFilter.filterValue.id.toLowerCase());
              }

              pitches = pitches.map(function(pitch){
                var pitch = pitch;
                pitch.title = pitch.pitch_number+' ('+pitch.size+')';
                pitch.resourceAreaWidth = '500px';
                return pitch;
              });

              return pitches;
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
            this.$root.$on('reloadAllEvents', this.reloadAllEvents);
            this.$root.$on('arrangeLeftColumn', this.arrangeLeftColumn);
            this.$root.$on('refreshPitch' + this.stageIndex, this.refreshPitch);
            this.$root.$on('reloadPitch' + this.stageIndex, this.reloadPitch);
        },
        beforeCreate: function() {
            // Remove custom event listener
            this.$root.$off('reloadAllEvents');
            this.$root.$off('arrangeLeftColumn');
            this.$root.$off('reloadPitch');
        },
        beforeDestroy: function() {
            this.$root.$off('refreshPitch' + this.stageIndex);
        },
        mounted() {
            let vm = this;

            $( document ).ready(function() {
                $(document).on('click','.js-horizontal-view', function (){
                    $('.pitch-planner-wrapper .pitch-planner-item').each(function(index){
                        var canvasWidth = $(this).find('.fc-unselectable .fc-scroller-canvas').width();
                        $(this).find('.fc-view-container table').attr('style', 'width: ' + parseInt(canvasWidth + 107) + 'px');
                    });

                })

                //  Unschedule fixtures checkbox check uncheck
                $(document).on('change','.match-unschedule-checkbox', function (e){
                    if($('.match-unschedule-checkbox:checked').length) {
                        // $('#unschedule_fixtures').html('Confirm unscheduling').addClass('btn btn-success');
                        $("#automatic_planning").hide();
                        $("#schedule_fixtures").hide();
                        $("#unschedule_all_fixtures_btn").hide();
                        $("#unschedule_fixtures").hide();
                        $("#confirm_unscheduling").show();
                        $("#cancle_unscheduling_fixtures").show();
                        // $('.cancle-match-unscheduling').removeClass('d-none');
                    } else {
                        // $("#unschedule_fixtures").html('Unschedule fixture').removeClass('btn btn-success');
                        // $("#unschedule_fixtures").addClass('btn btn-primary btn-md btn-secondary');
                        $("#automatic_planning").show();
                        $("#schedule_fixtures").show();
                        $("#unschedule_all_fixtures_btn").show();
                        $("#confirm_unscheduling").hide();
                        $("#cancle_unscheduling_fixtures").hide();
                        $("#unschedule_fixtures").show().removeClass('btn-success').addClass('btn-primary');
                        $(".match-unschedule-checkbox-div").addClass('d-none');
                        $(".match-unschedule-checkbox").prop( "checked", false);
                    }
                });
            });

            var timeGridContainerHeight = $('.fc-time-grid-container').height();
            $('.fc-time-grid-container').css('height', timeGridContainerHeight);

            let cal = this.$el;
            vm.initComponent()
            $(this.$el).fullCalendar('changeView', 'agendaDay');

            $(window).scroll(function() {
                if($(".pitch_planner_section").length > 0) {
                    setGameAndRefereeTabHeight();
                }
            });
        },
        methods: {
            initComponent(){
                let vm = this
                $("body .js-loader").removeClass('d-none');
                // setTimeout(function(){
                    vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue,vm.tournamentFilter.filterDependentKey,vm.tournamentFilter.filterDependentValue)
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
                    //filterResourcesWithEvents: true,
                    resourceAreaWidth: '400px',
                    //resources: vm.pitchesData,
                    resources: function(callback) {
                        callback(vm.pitchesData);
                    },
                    events: vm.scheduledMatches,
                    drop: function(date, jsEvent, ui, resourceId) {
                        vm.currentScheduledMatch = $(this);
                        // jsEvent.draggedEl.parentNode.removeChild(jsEvent.draggedEl);
                        // $(this).remove();
                        vm.eventDropped = true;
                        vm.scrollBeforeEventDropped = $(".js-stage-top-horizontal-scroll" + vm.stage.stageNumber).scrollLeft();
                    },
                    eventReceive: function( event, delta, revertFunc, jsEvent, ui, view) { // called when a proper external event is dropped
                        if(vm.isMatchScheduleInEdit === true || (vm.isMatchScheduleInEdit === false && vm.enableScheduleFeatureAsDefault === true)) {
                            event.borderColor = '#FF0000';
                            $('#pitchPlanner' + (vm.stage.stageNumber)).parent('.fc-unthemed').fullCalendar('updateEvent', event);
                        }
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
                                        vm.$store.dispatch('getAllReferee',vm.tournamentId).then(function() {
                                            if($("#save_schedule_fixtures").is(':visible') === true) {
                                                $('.js-referee-draggable-block').draggable('disable');
                                            } else {
                                                $('.js-referee-draggable-block').draggable('enable');
                                            }
                                        });
                                        vm.reloadPitch();

                                    }else{
                                        let errorMsg = updatedMatch.data;
                                        toastr.error(errorMsg, 'Assigned Referee ', {timeOut: 5000});

                                        $('.fc.fc-unthemed').fullCalendar( 'removeEvents', [event.matchId] )
                                        vm.$store.dispatch('getAllReferee',vm.tournamentId).then(function() {
                                            if($("#save_schedule_fixtures").is(':visible') === true) {
                                                $('.js-referee-draggable-block').draggable('disable');
                                            } else {
                                                $('.js-referee-draggable-block').draggable('enable');
                                            }
                                        });
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
                                'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD HH:mm:ss'),
                                'scheduleLastUpdateDateTime': event.scheduleLastUpdateDateTime,
                                'ageGroupId': event.matchAgeGroupId,
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
                            let data = {
                                matchData: matchData,
                                scheduleMatchesArray: vm.scheduleMatchesArray,
                                isMultiSchedule: (vm.isMatchScheduleInEdit === true || (vm.isMatchScheduleInEdit === false && vm.enableScheduleFeatureAsDefault === true)),
                            }
                            Tournament.setMatchSchedule(data).then(
                                (response) => {
                                    if(response.data.status_code == 200 ){
                                        let enableScheduleFeatureAsDefault = false;
                                        vm.unChangedMatchFixtures = response.data.unChangedFixturesArray;
                                        if(response.data.data.is_another_match_scheduled == true) {
                                            vm.isAnotherMatchScheduled = true;
                                            vm.$emit('conflicted-for-same-match-fixutres', vm.unChangedMatchFixtures, vm.isAnotherMatchScheduled);
                                        }
                                        if(response.data.data != -1 && response.data.data != -2 && response.data.data != -3){
                                            if(vm.isMatchScheduleInEdit === false && vm.enableScheduleFeatureAsDefault === true) {
                                                enableScheduleFeatureAsDefault = true;
                                                vm.$emit('make-schedule-matches-as-default');
                                            }

                                            if(vm.unChangedMatchFixtures.length > 0 && response.data.data.is_another_match_scheduled == false) {
                                                vm.isAnotherMatchScheduled = false;
                                                vm.$emit('conflicted-for-another-match-fixutres', vm.unChangedMatchFixtures, vm.isAnotherMatchScheduled);
                                            }

                                            vm.currentScheduledMatch.remove();
                                            vm.currentScheduledMatch = null;
                                            if(vm.isMatchScheduleInEdit === true || enableScheduleFeatureAsDefault === true) {
                                                vm.$emit('schedule-match-result', matchData);
                                            } else {
                                                if(response.data.areAllMatchFixtureScheduled == true) {
                                                    if(typeof response.data.data.maximum_interval_flag !== 'undefined' && response.data.data.maximum_interval_flag === 1) {
                                                        toastr.warning(response.data.message, 'Schedule Match', {timeOut: 5000});
                                                    } else {
                                                        toastr.success(response.data.message, 'Schedule Match', {timeOut: 5000});
                                                    }
                                                    // toastr.success(response.data.message, 'Schedule Match', {timeOut: 5000});
                                                }
                                                vm.$store.dispatch('setMatches').then((response) => {
                                                    vm.reloadPitch();
                                                });
                                            }
                                        }
                                        else {
                                            $('.fc.fc-unthemed').fullCalendar( 'removeEvents', [event._id] )
                                            vm.$store.dispatch('setMatches');
                                            vm.matchFixture = {}
                                            vm.getScheduledMatch()
                                            toastr.error(response.data.message, 'Schedule Match', {timeOut: 5000});
                                            $('.tooltip').removeClass('show');
                                        }
                                    }
                                },
                                (error) => {
                                }
                            )
                            }
                        }
                    },
                    eventLeave: function( info ) {
                    },
                    eventAfterAllRender: function(view ){
                        // if($(".js-stage-top-horizontal-scroll" + vm.stage.stageNumber).scrollLeft() !== $('#stage_outer_div' + vm.stage.stageNumber +  ' .fc-content-skeleton').scrollLeft()){
                        //         vm.eventDropped = true;
                        //         vm.scrollBeforeEventDropped = $(".js-stage-top-horizontal-scroll" + vm.stage.stageNumber).scrollLeft();
                        //     }
                        if(vm.eventDropped) {
                            vm.eventDropped = false;
                            $('#stage_outer_div' + vm.stage.stageNumber + ' .fc-content-skeleton').scrollLeft(vm.scrollBeforeEventDropped);
                        }
                        $('span[data-toggle="popover"]').popover({trigger: 'hover'});
                        $('[data-toggle="tooltip"]').tooltip();
                        $('[data-toggle="tooltip"]').each(function() {
                            let tt = $(this);
                            let fixtureStripColor = tt.data('fixture-strip-color');
                            let categoryColor = tt.data('category-color');

                            tt.on('shown.bs.tooltip', function() {
                                var tooltipId = $(this).attr('aria-describedby');

                                $('#' + tooltipId + ' .tooltip-inner').css('background-color', categoryColor);

                                $('<style>#' + tooltipId + ' .tooltip-inner::before { border-top-color: '+ fixtureStripColor +'; }</style>' ).appendTo( 'head');
                            });
                        });

                         $('#add_referee').prop('disabled', false);
                         // Code for horizontal scroll bar
                        // let totalPitches = vm.stage.pitches.length;
                        // if(totalPitches > 8) {
                        //     $(vm.$el).find('.fc-view-container .fc-view > table').css('width', (totalPitches * ($('.pitch_planner_section').width()/8)) + 'px');
                        // }
                      arrangeLeftColumn();
                    },
                    eventDragStart: function( event, jsEvent, ui, view ) {
                    },
                    eventMouseout: function( event, jsEvent, view ) {
                        vm.eventDropped = true;
                        vm.scrollBeforeEventDropped = $(".js-stage-top-horizontal-scroll" + vm.stage.stageNumber).scrollLeft();
                    },
                    unselect: function(event) {
                    },
                    eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) { // called when an event (already on the calendar) is moved
                        // update api call
                        if(vm.isMatchScheduleInEdit === true || (vm.isMatchScheduleInEdit === false && vm.enableScheduleFeatureAsDefault === true)) {
                            event.borderColor = '#FF0000';
                            $('#pitchPlanner' + (vm.stage.stageNumber)).parent('.fc-unthemed').fullCalendar('updateEvent', event);
                        }
                        if(vm.currentView == 'refereeTab'){
                            revertFunc()
                            return false;
                        }
                        let ed = $(this)
                        if(event.refereeId == -1 || event.refereeId == -2){
                            revertFunc();

                        }else{
                            let enableScheduleFeatureAsDefault = false;
                            if(vm.isMatchScheduleInEdit === false && vm.enableScheduleFeatureAsDefault === true) {
                                enableScheduleFeatureAsDefault = true;
                                vm.$emit('make-schedule-matches-as-default');
                            }
                            let matchId = event.id ? event.id : event.matchId
                            let matchData = {
                                'tournamentId': vm.tournamentId,
                                'pitchId': event.resourceId,
                                'matchId': matchId,
                                'matchStartDate': moment.utc(event.start._d).format('YYYY-MM-DD HH:mm:ss'),
                                'matchEndDate':moment.utc(event.end._d).format('YYYY-MM-DD HH:mm:ss'),
                                'scheduleLastUpdateDateTime': event.scheduleLastUpdateDateTime,
                                'ageGroupId': event.matchAgeGroupId,
                            };
                            let data = {
                                matchData: matchData,
                                scheduleMatchesArray: vm.scheduleMatchesArray,
                                isMultiSchedule: enableScheduleFeatureAsDefault ? true : vm.isMatchScheduleInEdit,
                            }
                            Tournament.setMatchSchedule(data).then(
                                (response) => {
                                    if(response.data.data != -1 && response.data.data != -2 && response.data.data != -3){
                                        if(vm.isMatchScheduleInEdit === false) {
                                            if(typeof response.data.data.maximum_interval_flag !== 'undefined' && response.data.data.maximum_interval_flag === 1) {
                                                toastr.warning(response.data.message, 'Schedule Match', {timeOut: 5000});
                                            } else {
                                                toastr.success(response.data.message, 'Schedule Match', {timeOut: 5000});
                                            }

                                            vm.$store.dispatch('setMatches').then((response) => {
                                                vm.reloadPitch();
                                            });
                                        } else {
                                            vm.$emit('schedule-match-result', matchData);
                                        }
                                    }else{
                                        revertFunc();
                                        toastr.error(response.data.message, 'Schedule Match', {timeOut: 5000});
                                        $('.tooltip').removeClass('show');
                                    }
                                },
                                (error) => {
                                }
                            )
                        }
                        vm.eventDropped = true;
                        vm.scrollBeforeEventDropped = $(".js-stage-top-horizontal-scroll" + vm.stage.stageNumber).scrollLeft();
                    },
                    eventClick: function(calEvent, jsEvent, view) {
                        if(vm.isMatchScheduleInEdit === true) {
                            return false;
                        }

                        if($('.match-unschedule-checkbox-div').has(jsEvent.target).length) {
                            return true;
                        }

                        if(typeof calEvent.id === 'undefined') {
                            return true;
                        }

                        vm.$root.$emit('cancelUnscheduleFixtures');

                        var posX = $(this).offset().left, posY = $(this).offset().top;
                        var eventPositionLeft = (jsEvent.pageX - posX);
                        var matchBlockWidth = $(this).width();

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
                                $('#matchScheduleModal').modal('show');
                                if((matchBlockWidth - eventPositionLeft) <= 15) {
                                    $("#matchScheduleModal #pitch_model_body .tabs li a").removeClass('active');
                                    $("#matchScheduleModal #pitch_model_body .tabs li.nav-item:last-child a").tab('show');
                                }
                                $("#matchScheduleModal").on('hidden.bs.modal', function () {
                                    vm.setPitchModal = 0
                                    vm.matchFixture = {}
                                    // vm.$store.dispatch('setCompetationWithGames');
                                    // vm.getScheduledMatch()
                                    // vm.reloadAllEvents();
                                });
                             },100);
                        }
                    },
                    resourceAreaWidth: {
                        default:'300px',
                    },
                    eventRender: function eventRender(event, element, view) {

                      // if event is break then no need to filter
                      // if (event.matchId === -1) return true;

                      if (vm.tournamentFilter.filterKey === 'location' && vm.tournamentFilter.filterValue !== '') {
                            return ['all', event.matchVenueId].indexOf(vm.tournamentFilter.filterValue.id) >= 0
                        } else if (vm.tournamentFilter.filterKey === 'age_category' && vm.tournamentFilter.filterValue !== '' && vm.tournamentFilter.filterDependentValue === '') {
                            return ['all', event.matchAgeGroupId].indexOf(vm.tournamentFilter.filterValue.id) >= 0
                        } else if (vm.tournamentFilter.filterKey === 'age_category' && vm.tournamentFilter.filterValue !== '' && vm.tournamentFilter.filterDependentValue !== '') {
                            return ['all', event.matchCompetitionId].indexOf(vm.tournamentFilter.filterDependentValue) >= 0
                        } else if (vm.tournamentFilter.filterKey === 'pitch_type' && vm.tournamentFilter.filterValue !== '') {
                          return ['all', event.resourceType].indexOf(vm.tournamentFilter.filterValue.id) >= 0
                        } else {
                            return true;
                        }
                    },
                    //schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                    schedulerLicenseKey: '0097912839-fcs-1497264705',
                });
                arrangeLeftColumn();
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
                $(ev).fullCalendar('removeEvents');
                $("body .js-loader").removeClass('d-none');

                setTimeout(function(){
                    $(ev).fullCalendar('addEventSource', vm.scheduledMatches);
                    arrangeLeftColumn();
                    $("body .js-loader").addClass('d-none');
                }, 1000);
            },
            getScheduledMatch(filterKey='',filterValue='',filterDependentKey='',filterDependentValue='') {
                // this.$store.dispatch('SetScheduledMatches');
                let tournamentData= [];
                let fixtureDate = moment(this.stageDate).format('YYYY-MM-DD');
                if(filterKey != '' && filterValue != '') {
                    tournamentData ={'tournamentId':this.tournamentId ,'filterKey':filterKey,'filterValue':filterValue.id,'filterDependentKey':filterDependentKey,'filterDependentValue':filterDependentValue,'is_scheduled':true,'fixture_date':fixtureDate}
                } else {
                    tournamentData ={'tournamentId':this.tournamentId,'is_scheduled':true,'fixture_date':fixtureDate}
                }
                Tournament.getFixtures(tournamentData).then(
                    (response)=> {
                        let vm = this
                        let counter =999;
                        let rdata = response.data.data
                        let sMatches = []

                        _.forEach(rdata, function(match) {
                            let scheduleBlock = false
                            let locationCheckFlag = true;
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

                            let splittedGroupName = match.competition_actual_name.split('-');
                            let groupName = splittedGroupName[2]+ ' ' +splittedGroupName[3];

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
                                    if(filterDependentKey != '' && filterDependentValue != ''  && filterDependentValue != match.competitionId) {
                                        scheduleBlock = true
                                    }
                                } else if(filterKey == 'location'){
                                    if( filterValue != '' && filterValue.id != match.venueId){
                                        scheduleBlock = true;
                                        locationCheckFlag = false;
                                    }
                                }

                              let colorVal = match.category_age_color;
                              var isBright = (parseInt(vm.getBrightness(match.category_age_color)) > 160);
                              let borderColorVal;

                              let textColorVal = match.category_age_font_color;
                              let fixtureStripColor = match.competation_color_code != null ? match.competation_color_code : '#FFFFFF';

                              let ageCategoryColor = match.age_category_color;
                              let groupColor = match.group_color;

                              if(ageCategoryColor != null) {
                                colorVal = ageCategoryColor;
                              }

                              if(groupColor != null) {
                                fixtureStripColor = groupColor;
                              }

                              if(isBright) {
                                borderColorVal = vm.LightenDarkenColor(colorVal, -40);
                              } else {
                                borderColorVal = vm.LightenDarkenColor(colorVal, 40);
                              }

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

                              if(ageCategoryColor != null) {
                                colorVal = ageCategoryColor;
                              }

                              if(groupColor != null) {
                                fixtureStripColor = groupColor;
                              }

                                let mData =  {
                                    'id': match.fid,
                                    'resourceId': match.pitchId,
                                    'resourceType': match.pitchType,
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
                                    'matchCompetitionId':match.competitionId,
                                    'matchVenueId':match.venueId,
                                    'fixtureStripColor': fixtureStripColor,
                                    'homeScore': match.homeScore,
                                    'awayScore': match.AwayScore,
                                    'minimumTeamIntervalDisplayFlag': match.min_interval_flag == 1 ? 'block' : 'none',
                                    'maximumTeamIntervalDisplayFlag': match.max_interval_flag == 1 ? 'block' : 'none',
                                    'homeTeam': match.Home_id,
                                    'awayTeam': match.Away_id,
                                    'matchStatus': match.match_status,
                                    'matchWinner': match.match_winner,
                                    'isResultOverride': match.isResultOverride,
                                    'homeTeamPlaceHolder': displayHomeTeamPlaceholder,
                                    'awayTeamPlaceHolder': displayAwayTeamPlaceholder,
                                    'remarks': match.matchRemarks,
                                    'locationCheckFlag': locationCheckFlag,
                                    'competition_type': match.actual_round,
                                    'groupName': groupName,
                                    'displayMatchName': displayMatchName,
                                    'categoryAgeColor': match.category_age_color,
                                    'categoryAgeFontColor': match.category_age_font_color,
                                    'competitionColorCode': match.competation_color_code,
                                    'matchRefereeId': match.referee_id ? match.referee_id : 0,
                                    'scheduleLastUpdateDateTime': match.schedule_last_update_date_time
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
                                        'resourceType': pitch.type,
                                        'start':moment.utc(availability.stage_start_date+' '+'08:00:00','DD/MM/YYYY HH:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+availability.stage_start_time,'DD/MM/YYYY hh:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title': '',
                                        'color': 'grey',
                                        'textColor': '#FFFFFF',
                                        'borderColor': 'grey',
                                        'matchId':-1,
                                        'matchAgeGroupId':'',
                                        'matchCompetitionId':'',
                                        'matchVenueId':'',
                                        'fixtureStripColor': '',
                                        'homeScore': null,
                                        'awayScore': null,
                                        'minimumTeamIntervalDisplayFlag':'none',
                                        'maximumTeamIntervalDisplayFlag':'none',
                                        'homeTeam': null,
                                        'awayTeam': null,
                                        'matchStatus': null,
                                        'matchWinner': null,
                                        'isResultOverride': null,
                                        'homeTeamPlaceHolder': null,
                                        'awayTeamPlaceHolder': null,
                                        'remarks': null,
                                        'locationCheckFlag': null,
                                        'competition_type': null,
                                        'groupName': null,
                                        'displayMatchName': null,
                                        'categoryAgeColor': null,
                                        'categoryAgeFontColor': null,
                                        'competitionColorCode': null,
                                        'matchRefereeId': null,
                                        'scheduleLastUpdateDateTime': null
                                    }
                                    sMatches.push(mData1)
                                    counter = counter+1;
                                }
                                if(availability.stage_end_time != '23:00'){
                                    let mData2 = {
                                        'id': 'end_'+counter,
                                        'resourceId': pitch.id,
                                        'resourceType': pitch.type,
                                        'start':moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'),
                                        'end': moment.utc(availability.stage_start_date+' '+'23:00:00','DD/MM/YYYY HH:mm:ss'),
                                        'refereeId': -1,
                                        'refereeText': 'R',
                                        'title':'',
                                        'color': 'grey',
                                        'textColor': '#FFFFFF',
                                        'borderColor': 'grey',
                                        'matchId': -1,
                                        'matchAgeGroupId':'',
                                        'matchCompetitionId':'',
                                        'matchVenueId':'',
                                        'fixtureStripColor': '',
                                        'homeScore': null,
                                        'awayScore': null,
                                        'minimumTeamIntervalDisplayFlag':'none',
                                        'maximumTeamIntervalDisplayFlag':'none',
                                        'homeTeam': null,
                                        'awayTeam': null,
                                        'matchStatus': null,
                                        'matchWinner': null,
                                        'isResultOverride': null,
                                        'homeTeamPlaceHolder': null,
                                        'awayTeamPlaceHolder': null,
                                        'remarks': null,
                                        'locationCheckFlag': null,
                                        'competition_type': null,
                                        'groupName': null,
                                        'displayMatchName': null,
                                        'categoryAgeColor': null,
                                        'categoryAgeFontColor': null,
                                        'competitionColorCode': null,
                                        'matchRefereeId': null,
                                        'scheduleLastUpdateDateTime': null
                                    }
                                    sMatches.push(mData2)
                                    counter = counter+1;
                                }
                                _.forEach(availability.pitch_breaks, (pitchBreak) => {
                                    if(pitchBreak.break_start != pitchBreak.break_end) {
                                        let mData = {
                                            'id': counter,
                                            'resourceId': pitch.id,
                                            'resourceType': pitch.type,
                                            'start':moment.utc(availability.stage_start_date+' '+pitchBreak.break_start,'DD/MM/YYYY hh:mm:ss'),
                                            'end': moment.utc(availability.stage_start_date+' '+pitchBreak.break_end,'DD/MM/YYYY hh:mm:ss'),
                                            'refereeId': -1,
                                            'refereeText': 'R',
                                            'title':'',
                                            'color': 'grey',
                                            'textColor': '#FFFFFF',
                                            'borderColor': 'grey',
                                            'matchId':-1,
                                            'matchAgeGroupId':'',
                                            'matchCompetitionId':'',
                                            'matchVenueId':'',
                                            'fixtureStripColor': '',
                                            'homeScore': null,
                                            'awayScore': null,
                                            'minimumTeamIntervalDisplayFlag': 'none',
                                            'maximumTeamIntervalDisplayFlag':'none',
                                            'homeTeam': null,
                                            'awayTeam': null,
                                            'matchStatus': null,
                                            'matchWinner': null,
                                            'isResultOverride': null,
                                            'homeTeamPlaceHolder': null,
                                            'awayTeamPlaceHolder': null,
                                            'remarks': null,
                                            'locationCheckFlag': null,
                                            'competition_type': null,
                                            'groupName': null,
                                            'displayMatchName': null,
                                            'categoryAgeColor': null,
                                            'categoryAgeFontColor': null,
                                            'competitionColorCode': null,
                                            'matchRefereeId': null,
                                            'scheduleLastUpdateDateTime': null
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
                    'title': '',
                    'color': 'grey',
                    'textColor': '#FFFFFF',
                    'matchId': -1,
                    'matchAgeGroupId':'',
                    'matchCompetitionId':'',
                    'matchVenueId':'',
                    'homeScore': null,
                    'awayScore': null,
                    'minimumTeamIntervalDisplayFlag':'none',
                    'maximumTeamIntervalDisplayFlag':'none',
                    'homeTeam': null,
                    'awayTeam': null,
                    'matchStatus': null,
                    'matchWinner': null,
                    'isResultOverride': null,
                    'displayMatchName': null,
                    'categoryAgeColor': null,
                    'categoryAgeFontColor': null,
                    'competitionColorCode': null,
                    'matchRefereeId': null,

              }
               this.scheduledMatches.push(mData21)
               // Also Add for Resources as well
                //let resources = {'id':'111213','eventColor':'grey'}
               //this.pitchesData = resources
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
                            'title': '',
                            'color': 'grey',
                            'textColor': '#FFFFFF',
                            'matchId': -1,
                            'matchAgeGroupId':'',
                            'matchCompetitionId':'',
                            'matchVenueId':'',
                            'homeScore': null,
                            'awayScore': null,
                            'minimumTeamIntervalDisplayFlag': 'none',
                            'maximumTeamIntervalDisplayFlag':'none',
                            'remarks': null,
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
            },
            arrangeLeftColumn() {
                arrangeLeftColumn();
            },
            refreshPitch() {
                let vm = this;
                this.$store.dispatch('setCompetationWithGames');
                vm.reloadPitch();
            },
            reloadPitch() {
                let vm = this;
                let matchScheduleChk = new Promise((resolve, reject) => {
                    resolve(vm.getScheduledMatch(vm.tournamentFilter.filterKey,vm.tournamentFilter.filterValue,vm.tournamentFilter.filterDependentKey,vm.tournamentFilter.filterDependentValue));
                });
                matchScheduleChk.then((successMessage) => {
                    vm.reloadAllEvents();
                });
            },
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
        var headerHeight = $("header").length > 0 ? $("header").height() : 0;
        if(($(window).scrollTop() + headerHeight) > $el.offset().top) {
            considerHeaderHeight = headerHeight;
        }
        var leftViewHeight = Math.max(0, t>0? Math.min(elH, H-t) : (b<H?b:H)) - $("#gameReferee .nav.nav-tabs").height() - parseInt($("#gameReferee .tab-content").css('margin-top').replace('px', '')) - considerHeaderHeight - 10;
        $("#game-list").css('height', leftViewHeight + 'px');
        $("#referee-list").css('height', leftViewHeight + 'px');
    }

    function arrangeLeftColumn() {
        var scrollableBodys = document.querySelectorAll('.fc-content-skeleton');
        var index = 1;
        var plannerwidth = $('.pitch_planner_section').width()/16;
        $('.stage-top-horizontal-scroll').hide();
        [].forEach.call(scrollableBodys, function(scrollableBody) {
            var totalPitches = document.querySelectorAll('.pitch-planner-item:nth-child('+index+') .fc-head-container > .fc-row > table > thead > tr > th').length - 1;
            if(totalPitches>13){
                var pitchplanneritem = document.querySelectorAll('.pitch-planner-item:nth-child('+index+')');
                pitchplanneritem[0].classList.add("ppitem");
                var width = (plannerwidth*(totalPitches+2));
                var width2 = (plannerwidth*(totalPitches));

                var scrollableHeader = document.querySelector('.pitch-planner-item:nth-child('+index+') .fc-head-container > .fc-row');
                var scrollableHeaderTable = document.querySelector('.pitch-planner-item:nth-child('+index+') .fc-head-container > .fc-row > table');
                scrollableHeaderTable.style.width = (width-40)+'px';

                var scrollableBg = document.querySelector('.pitch-planner-item:nth-child('+index+') .fc-bg');
                var scrollableBgTable = document.querySelector('.pitch-planner-item:nth-child('+index+') .fc-bg > table');
                scrollableBgTable.style.width = width+'px';
                var fcsktable = document.querySelector('.pitch-planner-item:nth-child('+index+') .fc-time-grid .fc-content-skeleton > table');
                fcsktable.style.width = (width-40)+'px';

                var fcsktable2 = document.querySelector('.pitch-planner-item:nth-child('+index+') .fc-agenda-view > table');
                fcsktable2.style.width = '100%';

                // Top Horizontal Scroll
                document.querySelector('.pitch-planner-item:nth-child('+index+') .stage-top-horizontal-scroll').style.display = 'block';
                var topHorizontalScroll = document.querySelector('.pitch-planner-item:nth-child('+index+') .stage-top-horizontal-scroll div');
                topHorizontalScroll.style.width = (width-40)+'px';
                scrollableBody.addEventListener('scroll', () => {
                    scrollableHeader.scrollTo(scrollableBody.scrollLeft, 0);
                    scrollableBg.scrollTo(scrollableBody.scrollLeft, 0);
                    let stageNo = $(scrollableBody).closest('.js-stage-outer-div').data('stage-number');
                    $(".js-stage-top-horizontal-scroll" + stageNo).scrollLeft($(scrollableBody).scrollLeft());
                });
            } else {
                $(scrollableBody).closest('table').css('width', ($('.pitch_planner_section').width() - 20) + 'px');
            }
            index++;
        });
        $('.stage-top-horizontal-scroll').on('scroll', function (e){
            let stageNo = $(this).data('stage-number');
            $("#stage_outer_div" + stageNo).find('.fc-content-skeleton').first().scrollLeft($(this).scrollLeft());
        });
    }
    $(window).load(function(){
        setTimeout(function() {
            arrangeLeftColumn();
        }, 5000);
    });
</script>
