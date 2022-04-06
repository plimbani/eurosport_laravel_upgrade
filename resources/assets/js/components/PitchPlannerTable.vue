<template>
    <div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <button class="btn btn-primary btn-md" id="automatic_planning" @click="openAutomaticPitchPlanningModal()">{{$lang.pitch_planner_automatic_planning}} <span v-if="currentLayout === 'commercialisation'" class="pl-2 text-primary js-basic-popover" data-toggle="popover" data-animation="false" data-placement="right" data-content="You can automatically plan the matches per category on the pitches you select"><i class="fas fa-info-circle text-white"></i></span></button>
                        <button class="btn btn-primary btn-md" id="schedule_fixtures" @click="scheduleMatches()">Manual planning <span v-if="currentLayout === 'commercialisation'" class="pl-2 text-primary js-basic-popover" data-toggle="popover" data-animation="false" data-placement="right" data-content="You can manually drag and drop the matches from the right hand side on to the pitches to allocate the matches"><i class="fas fa-info-circle text-white"></i></span></button>
                        <button class="btn btn-success btn-md" id="save_schedule_fixtures" @click="saveScheduleMatches()" style="display: none;">Save</button>
                        <button class="btn btn-danger btn-md" id="cancel_schedule_fixtures" @click="cancelScheduleMatches()" style="display: none;">Cancel</button>
                        <button class="btn btn-md btn-primary" id="unschedule_fixtures" @click="unscheduleFixtures()" v-if="this.totalNumberOfScheduledMatches > 0 && currentLayout === 'tmp'">Unschedule fixtures</button>
                        <button class="btn btn-md btn-primary" id="unschedule_fixtures" @click="unscheduleFixtures()" v-if="this.totalNumberOfScheduledMatches > 0 && currentLayout === 'commercialisation'">Unschedule matches</button>
                        <button class="btn btn-md btn-success" id="confirm_unscheduling" @click="confirmUnscheduling()" style="display: none;">Confirm unscheduling</button>
                        <button class="btn btn-danger btn-md cancle-match-unscheduling" id="cancle_unscheduling_fixtures" @click="cancelUnscheduleFixtures()" style="display: none;">{{$lang.pitch_planner_cancel_unscheduling}}</button>
                    </div>
                    <div>
                        <button v-if="isPitchPlannerInEnlargeMode == 0" class="btn btn-primary btn-md vertical" @click="enlargePitchPlanner()">Enlarge</button>
                        <button class="btn btn-default btn-md vertical" @click="printPitchPlanner()" title="Print"><i class="fas fa-print text-primary"></i></button>
                        <button class="btn btn-default btn-md vertical" @click="exportPitchPlanner()" title="Download"><i class="fas fa-download text-primary"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                Viewing mode:
                <span class="match-planner-view">
                   <button class="btn btn-sm horizontal js-horizontal-view" :class="{ 'btn-primary': isHorizontal }"  @click="setPlannerView('timelineDay')">{{$lang.pitch_planner_horizontal}}</button> /
                   <button class="btn btn-sm vertical" :class="{ 'btn-primary': isVertical }"  @click="setPlannerView('agendaDay')">{{$lang.pitch_planner_vertical}}</button>
                 </span>
            </div>
        </div>

        <div class="row">
            <div class="pitch_planner_section pitch" v-bind:class="[isPrintPitchPlanner == 0 ? (isPitchPlannerInEnlargeMode == 0 ? 'col-md-9' : 'col-md-10') : 'col-md-12' ]">
                <div class="pitch-planner-wrapper">
                    <div class="pitch-planner-item" v-if="stageStatus" v-for="(stage, stageIndex) in tournamentStages">
                        <div class="card">
                            <div class="btn pnl" :id="stage.stageNumber">
                                Day {{ stage.stageNumber }}: {{dispDate(stage.tournamentStartDate)}}
                                <a data-toggle="collapse" v-bind:data-target="'#stage_div'+stage.stageNumber" :id="'pitch_stage_open_close_'+stage.stageNumber" href="javascript:void(0)" data-status="open" @click="toggleStage(stage.stageNumber)" class="pull-right open-close-link">Close</a>
                            </div>
                            <div :id="'stage_div'+stage.stageNumber" class="stages collapse in show" aria-expanded="true">
                                <div :class="'stage-top-horizontal-scroll js-stage-top-horizontal-scroll'+stage.stageNumber" :data-stage-number="stage.stageNumber">
                                    <div></div>
                                </div>
                                <div :id="'stage_outer_div'+stage.stageNumber" :data-stage-number="stage.stageNumber" class="js-stage-outer-div">
                                    <pitch-planner-stage :stage="stage" :defaultView="defaultView" @schedule-match-result="saveScheduleMatchResult" :scheduleMatchesArray="scheduleMatchesArray" :isMatchScheduleInEdit="isMatchScheduleInEdit" 
                                    :enableScheduleFeatureAsDefault="enableScheduleFeatureAsDefault" :stageIndex="stageIndex" @conflicted-for-same-match-fixutres="showConflictedForSameMatchFixtures" @conflicted-for-another-match-fixutres="showConflictedForAnotherMatchFixtures" @make-schedule-matches-as-default="makeScheduleMatchesAsDefault()"></pitch-planner-stage>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" id="outerGame" v-bind:class="[isPitchPlannerInEnlargeMode == 0 ? 'col-md-3' : 'col-md-2']" v-if="isPrintPitchPlanner == 0">
                <div class="grey_bg" id="gameReferee">
                    <div class="tabs tabs-primary">
                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="text-center" :class="[currentView == 'gamesTab' ? 'active' : '', 'nav-link']"
                                @click="setCurrentTab('gamesTab')"
                                data-toggle="tab" role="tab" href="#game-list">
                                <div class="wrapper-tab" v-if="currentLayout === 'commercialisation'">Matches 
                                        <span class="gameCount">({{totalMatchCount}})</span>
                                        <span class="pl-2 text-primary js-basic-popover" data-toggle="popover" data-animation="false" data-placement="right" data-content="Matches to be planned. Time is including breaks."><i class="fas fa-info-circle text-white"></i></span>
                                    </div>
                                    <div class="wrapper-tab" v-else>Games 
                                        <span class="gameCount">({{totalMatchCount}})</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="text-center" :class="[currentView == 'refereeTab' ? 'active' : '', 'nav-link']"
                                @click="setCurrentTab('refereeTab')"
                                data-toggle="tab" role="tab" href="#referee-list"><div class="wrapper-tab">Referees <span>({{totalRefereeCount}})</span></div></a>
                            </li>
                        </ul>
                         <div class="tab-content">
                            <div
                            :class="[currentView == 'gamesTab' ? 'active' : '', 'tab-pane']"
                            v-if="GameStatus" id="game-list" role="tabpanel">
                                <games-tab :totalMatchCount="totalMatchCount"></games-tab>
                            </div>
                            <div :class="[currentView == 'refereeTab' ? 'active' : '', 'tab-pane']" v-if="refereeStatus"  id="referee-list" role="tabpanel">
                                <referees-tab v-if="isCompetitionCallProcessed" :competationList="competationList" :isMatchScheduleInEdit="isMatchScheduleInEdit"></referees-tab>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <BulkUnscheduledfixtureModal :unscheduleFixture="unscheduleFixture" 
            @confirmed="confirmUnschedulingFixtures()"></BulkUnscheduledfixtureModal>
        <UnscheduleAllFixturesModal :unscheduleAllFixtures="unscheduleAllFixtures" @confirmed="confirmUnschedulingAllFixtures()"></UnscheduleAllFixturesModal>
        <AutomaticPitchPlanning></AutomaticPitchPlanning>
        <AddRefereesModel :formValues="formValues" :competationList="competationList" :tournamentId="tournamentId" :refereeId="refereeId" ></AddRefereesModel>
        <UploadRefereesModel :tournamentId="tournamentId"></UploadRefereesModel>
        <UnsavedMatchFixture :unChangedMatchFixtures="conflictedMatchFixtures" :isAnotherMatchScheduled="isAnotherMatchScheduled"></UnsavedMatchFixture>
    </div>
</template>
<script type="text/babel">
    import moment from 'moment'
    import GamesTab from './GamesTab.vue'
    import RefereesTab from './RefereesTab.vue'
    import PitchPlannerStage from './PitchPlannerStage.vue'
    import AddRefereesModel from './AddRefereesModel.vue'
    import UploadRefereesModel from './UploadRefereesModel.vue'
    import Tournament from '../api/tournament.js'
    import AutomaticPitchPlanning from './AutomaticPitchPlanningModal.vue'
    import BulkUnscheduledfixtureModal from './BulkUnscheduledfixtureModal.vue'
    import UnscheduleAllFixturesModal from './UnscheduleAllFixturesModal.vue'
    import UnsavedMatchFixture from './UnsavedMatchFixture.vue'

    export default  {
        props: ['scheduleMatchesArray', 'isMatchScheduleInEdit'],
        components: {
            GamesTab, RefereesTab, PitchPlannerStage, AddRefereesModel, UploadRefereesModel, AutomaticPitchPlanning, BulkUnscheduledfixtureModal, UnscheduleAllFixturesModal, UnsavedMatchFixture
        },
        computed: {
            GameActiveTab () {
              return this.$store.state.Pitch.ActiveTab
            },
            tournamentDays() {
                return this.$store.state.Tournament.tournamentDays;
            },
            tournamentStartDate() {
                return this.$store.state.Tournament.tournamentStartDate;
            },
            pitches() {
                return this.$store.state.Pitch.pitches;
            },
            totalMatchCount() {
                return this.$store.getters.totalMatch - this.scheduleMatchesArray.length;
            },
            totalRefereeCount() {
                return this.$store.state.Tournament.totalReferee
            },
            currentView() {
              return this.$store.getters.curStageView  
            },
            competitionWithGames(){
      
              if(this.$store.state.Tournament.totalMatch > 0){
                // this.matchStatus = true
                return this.$store.getters.getAllCompetitionWithGames
              }else{
                return [];
              }
            },
            isPitchPlannerInEnlargeMode() {
                return this.$store.state.Pitch.isPitchPlannerInEnlargeMode
            },
            isPrintPitchPlanner() {
                return this.$store.state.Pitch.isPrintPitchPlanner
            }
        },
        created: function() {
            this.$root.$on('setPitchReset', this.resetPitch);
            this.$root.$on('setGameReset', this.gameReset);
            this.$root.$on('setRefereeReset', this.refereeReset);
            this.$root.$on('RefereeCount', this.setRefereeCount);
            this.$root.$on('resetPitchesOnCategoryColorSave', this.resetPitchesOnCategoryColorSave);
            this.$root.$on('getPitchesByTournamentFilter', this.setFilter);
            this.$root.$on('setPitchPlanTab',this.setCurrentTab)
            this.$root.$on('getAllReferee', this.getAllreferees);
            // this.$root.$on('getTeamsByTournamentFilter', this.resetPitch);

            this.$root.$on('editReferee', this.editReferee);
            this.$root.$on('displayTournamentCompetationList', this.displayTournamentCompetationList);
            this.$root.$on('setView', this.setView);
            this.$root.$on('cancelUnscheduleFixtures', this.cancelUnscheduleFixtures);
            this.$root.$on('filterMatches', this.filterMatches);
            this.$root.$on('showUnChangedMatchFixture', this.showUnChangedMatchFixture);
            this.$root.$on('getAllScheduledMatches', this.getAllScheduledMatches);
        },
        beforeCreate: function() {
            // Remove custom event listener
            this.$root.$off('setPitchReset');
            this.$root.$off('setGameReset');
            this.$root.$off('setRefereeReset');
            this.$root.$off('RefereeCount');
            this.$root.$off('resetPitchesOnCategoryColorSave');
            this.$root.$off('getPitchesByTournamentFilter');
            this.$root.$off('setPitchPlanTab');
            this.$root.$off('getAllReferee');
            this.$root.$off('editReferee');
            this.$root.$off('displayTournamentCompetationList');
            this.$root.$off('setView');
            this.$root.$off('filterMatches');
            this.$root.$off('showUnChangedMatchFixture');
            this.$root.$off('getAllScheduledMatches');
        },
        data() {
            return {
                // 'currentView':'gamesTab',
                'currentButton' : 'horizontal',
                'matchCount':'',
                'tournamentStages': {},
                'stageStatus':false,
                'GameStatus':true,
                'refereeStatus':true,
                'refereeCount': '',
                'defaultView': 'agendaDay',
                'refereeId': '',
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'competationList': [],
                'isCompetitionCallProcessed': false,
                'formValues': this.initialState(),
                'unscheduleFixture': 'Are you sure you would like to unschedule the selected fixtures?',
                'unscheduleAllFixtures': 'Are you sure you would like to unschedule all the fixtures?',
                'matchId': null,
                'conflictedMatchFixtures': [],
                'isAnotherMatchScheduled': false,
                'enableScheduleFeatureAsDefault': true,
                'totalNumberOfScheduledMatches': 0,
                'isHorizontal': false,
                'isVertical': true,
                currentLayout: this.$store.state.Configuration.currentLayout,
            };
        },
        mounted() {
            this.getAllScheduledMatches();
            $('.pitch_planner_section').mCustomScrollbar({
                'autoHideScrollbar':true
            });
            //     let vm = this
            // setTimeout(function(){
            //     vm.resetPitch();
            // },500)
            
            $(document).ready(function() {
                $(document).on('click', '.js-pitch-planner-bt', function(e){
                    $(".js-pitch-planner-bt").removeClass('btn-primary').addClass('btn-secondary');
                    $(this).removeClass('btn-secondary').addClass('btn-primary');
                });

                // $('#gameReferee').affix({
                //     offset: {
                //         // top: $('#outerGame'),
                //         top: '305px',
                //         // bottom: $('footer').outerHeight()
                //     }
                // });
                // Check the initial Poistion of the Sticky Header

                var siteHeaderTop = $('.site-header').length > 0 ? $('.site-header').offset().top : 0;
                var siteHeaderHeight = $('.site-header').length > 0 ? $('.site-header').height() : 0;
                var stickyHeaderTop = (($('#gameReferee').offset().top ) - siteHeaderTop);
                $( window ).scroll(function() {
                    let tabWith = $('#gameReferee').width()+10;
                    
                    if( $(window).scrollTop() > (stickyHeaderTop - siteHeaderHeight)) {
                        $('#gameReferee').css({position: 'fixed', top: '0', width: tabWith, 'margin-top':siteHeaderHeight});
                    } else {
                        $('#gameReferee').css({position: 'static', top: '0', width:tabWith, 'margin-top':0});
                    }
                });
            })
            $(".stages").on('shown.bs.collapse', function(){
                alert('The collapsible content is about to be shown.');
            });

            // let externalHeight = $("header").height() + $(".page-header").height() + $(".nav nav-tabs").height() + $(".card-block .items-center.justify-content-start").height() + $(".js-pitch-planner-bt").height() + 50;
            // let externalHeight = $("header").height() + $("#gameReferee .nav.nav-tabs").height() + parseInt($(".card").css('margin-bottom').replace('px', ''));
            // console.log('externalHeight', externalHeight);
            // $("#game-list").css('height', ($( window ).height()-externalHeight) + 'px');
            // $("#referee-list").css('height', ($( window ).height()-externalHeight) + 'px');

            this.displayTournamentCompetationList()

            let this1 = this
              $("#refreesModal").on('hidden.bs.modal', function () {
                if(!$('#refreesModal').is(':visible')){
                  this1.refereeId = ''
                  this1.formValues = this1.initialState()
                }
            });

            this.htmlEncodeDecode();


         // TODO set Default View

          //         this.setView(this.defaultView);
        },
        methods: {
            initialState() {
                return {
                            first_name: '',
                            last_name: '',
                            telephone: '',
                            email: '',
                            age_group_id: [],
                            availability: ''
                        }
            },
            displayTournamentCompetationList () {
            // Only called if valid tournament id is Present
                if (!isNaN(this.tournamentId)) {
                  // here we add data for
                  let responseData=[];
                  let TournamentData = {'tournament_id': this.tournamentId}
                  Tournament.getCompetationFormat(TournamentData).then(
                  (response) => {
                    responseData = response.data.data
                    // responseData.unshift({'id':0,'category_age':'Select all'}) 
                    // this.competationList.push({'id':0,'category_age':'Select all'})
                    this.competationList = responseData
                    this.$store.dispatch('setCompetationList', responseData)
                    this.isCompetitionCallProcessed = true;
                    // console.log(this.competationList);
                  },
                  (error) => {              
                  }
                  )
                } else {
                  this.TournamentId = 0;
                }
            },
            editReferee (rId){
                this.refereeId = rId
                Tournament.getRefereeDetail(rId).then(
                  (response) => {
                    // console.log(response.data.referee)
                    this.formValues = response.data.referee
                    this.formValues.age_group_id = JSON.parse("[" + this.formValues.age_group_id + "]");
                    $('#refreesModal').modal('show')
                    }
                 )
            },
            getAllreferees(){
                
            },
            setFilter(){
                this.$store.dispatch('setMatches')
                this.resetPitch()
            },
            resetPitchesOnCategoryColorSave() {
                let vm = this;
                this.$store.dispatch('setMatches').then(function() {
                    let agecategory = null;
                    if(vm.$store.state.Tournament.tournamentFiler.filterValue != '') {
                        let competitionWithGames = _.cloneDeep(vm.competitionWithGames);
                        _.map(competitionWithGames, function(category, index) {
                            if(vm.$store.state.Tournament.tournamentFiler.filterValue.id == category.id) {
                                agecategory = category;
                            }
                        });

                        if(agecategory != null) {
                            $(".js-draggable-events").each(function(index){
                                let event = $(this).data('event');
                                let draggableEvent = $(this);

                                if(typeof event != "undefined" && typeof event.matchId != "undefined") {
                                    _.map(agecategory.matchList, function(match, matchIndex) {
                                        if(event.matchId == match.matchId) {
                                          event.fixtureStripColor = match.competationColorCode != null ? match.competationColorCode : '#FFFFFF';    
                                          draggableEvent.data('event', event);
                                        }
                                    });
                                }
                                
                            });
                        }

                    }
                    
                })
                this.resetPitch()
            },
            setCurrentTab(currentTab = 'refereeTab') {
                let vm =this;
                vm.$store.dispatch('SetStageView',currentTab)
            },
            toggleStage(stageNo){
                // Change the pitch_stage_open_close as well
                if($('#pitch_stage_open_close_' + stageNo).data('status') == "open") {
                    $('#pitch_stage_open_close_' + stageNo).text("Open");
                    $('#pitch_stage_open_close_' + stageNo).data('status', "close");
                } else {
                    $('#pitch_stage_open_close_' + stageNo).text("Close");
                    $('#pitch_stage_open_close_' + stageNo).data('status', "open");
                }
                
                let vm = this;
                setTimeout(function(){
                    if(vm.defaultView == 'timelineDay'){
                        $('.fc-timelineDay-button').click()
                    }else{
                        $('.fc-agendaDay-button').click()
                    }
                },100);
            },
            setView(view) {
                let vm = this
                setTimeout(function() {
                    vm.$root.$emit('arrangeLeftColumn')                
                }, 2000);
                this.defaultView = view
                if(vm.defaultView == 'timelineDay'){
                    $('.fc-timelineDay-button').click()
                }else{
                    $('.fc-agendaDay-button').click()
                }
            },
            setRefereeCount(totReferee) {
                this.refereeCount = totReferee
            },
            resetPitch() {
                let vm = this
                this.stageStatus = false
                this.tournamentStages = {}
                let tournamentStartDate = moment(this.tournamentStartDate, 'DD/MM/YYYY');
                let stages = [];
                let setTournamentStages = new Promise((resolve, reject) => {
                    for (var i = 1; i <= this.tournamentDays; i++) {
                        // fetch pitches available for this day
                        let currentDateString  = tournamentStartDate.format('DD/MM/YYYY');
                        // console.log(currentDateString)
                        let availablePitchesForStage = _.filter(this.$store.state.Pitch.pitches, (pitch) => {
                            return  _.find(pitch.pitch_availability, {
                                'stage_start_date': currentDateString
                            });
                        });

                        tournamentStartDate = tournamentStartDate.add(1, 'days');
                        // console.log(currentDateString,i,tournamentStartDate.add(1, 'days'))
                        stages.push({
                            stageNumber: i,
                            tournamentStartDate: currentDateString,
                            pitches: availablePitchesForStage
                        });
                    }
                    resolve();
                });

                setTournamentStages.then( (msg) => {
                    this.$store.dispatch('setTournamentStages', stages)
                    setTimeout(function(){ vm.stageStatus = true; }, 200);
                    vm.tournamentStages = stages
                });
            },
            gameReset() {

            // let vm =this
            //  vm.GameStatus = false
            //  vm.refereeStatus = false

            //  setTimeout(function(){
            //       vm.refereeStatus = true
            //       vm.GameStatus = true

            //         $('.nav-tabs a[href="#game-list"]').tab('show');
            //   },500)
            },
            refereeReset() {
            // let vm =this
            //  vm.GameStatus = false
            //  vm.refereeStatus = false

            //  setTimeout(function(){
            //         vm.refereeStatus = true
            //         vm.GameStatus = true
            //         $('.nav-tabs a[href="#referee-list"]').tab('show');
            //     },500)
            },
            dispDate(date) {
                var date1 = moment(date, 'DD/MM/YYYY')
                return date1.format('ddd DD MMM YYYY')
            },
            openAutomaticPitchPlanningModal() {
                $('#automatic_pitch_planning_modal').modal('show');
            },
            enlargePitchPlanner() {
                 this.$router.push({name: 'enlarge_pitch_planner'})
            },
            htmlEncodeDecode() {
                (function(window){
                    window.htmlentities = {
                        /**
                         * Converts a string to its html characters completely.
                         *
                         * @param {String} str String with unescaped HTML characters
                         **/
                        encode : function(str) {
                            var buf = [];
                            
                            for (var i=str.length-1;i>=0;i--) {
                                buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
                            }
                            
                            return buf.join('');
                        },
                        /**
                         * Converts an html characterSet into its original character.
                         *
                         * @param {String} str htmlSet entities
                         **/
                        decode : function(str) {
                            return str.replace(/&#(\d+);/g, function(match, dec) {
                                return String.fromCharCode(dec);
                            });
                        }
                    };
                })(window);
             },
            printPitchPlanner() {
                var pitchPlannerPrintWindow = window.open('', '_blank');
                Tournament.getSignedUrlForPitchPlannerPrint(this.tournamentId).then(
                  (response) => {
                    pitchPlannerPrintWindow.location = response.data;
                  },
                  (error) => {

                  }
                );
            },

            exportPitchPlanner() {
                Tournament.getSignedUrlForPitchPlannerExport(this.tournamentId).then(
                  (response) => {
                    window.location.href = response.data;
                  },
                  (error) => {

                  }
                );
            },
            unscheduleFixtures() {
                if($("#unschedule_fixtures").hasClass('btn-success')) {
                    $("#unschedule_fixtures").removeClass('btn-success').addClass('btn-primary');
                    $(".match-unschedule-checkbox-div").addClass('d-none');
                    return true;
                }
                if($("#unschedule_fixtures").hasClass('btn-primary')) {
                    $("#unschedule_fixtures").removeClass('btn-primary').addClass('btn-success');
                    $(".match-unschedule-checkbox-div").removeClass('d-none');
                    return true;
                }
            },
            unscheduleAllFixturesClick() {
                $("#unschedule_all_fixtures").modal('show');
            },
            confirmUnscheduling() {
                $("#bulk_unscheduled_fixtures").modal('show');
            },
            cancelUnscheduleFixtures() {
                $("#unschedule_fixtures").show().removeClass('btn-success').addClass('btn-primary');
                // $("#unschedule_fixtures").addClass('btn btn-primary btn-md btn-secondary');
                $(".match-unschedule-checkbox-div").addClass('d-none');
                $("#cancle_unscheduling_fixtures").hide();
                $("#confirm_unscheduling").hide();
                $(".match-unschedule-checkbox").prop( "checked", false);
                $("#automatic_planning").show();
                $("#schedule_fixtures").show();
                $("#unschedule_all_fixtures_btn").show();
            },
            confirmUnschedulingFixtures() {
                let vm = this;
                var matchId = [];
                var matchDetail = [];
                $(".match-unschedule-checkbox").each(function( index ) {
                    var checkboxChecked = $(this).is(':checked');
                    if(checkboxChecked) {
                        matchId.push($(this).attr('id'));
                        matchDetail.push({
                            'matchId': $(this).attr('id'), 
                            'scheduleLastUpdateDateTime': $(this).data("schedulelastupdatedatetime")
                        }) 
                    }
                });

                Tournament.matchUnscheduledFixtures(matchDetail).then(
                (response) => {
                    $('#bulk_unscheduled_fixtures').modal('hide')
                    $('#unschedule_all_fixtures').modal('hide')
                    vm.conflictedMatchFixtures = response.data.conflictedFixturesArray;
                    if(vm.conflictedMatchFixtures.length > 0) {
                        $('#unChangedMatchFixtureModal').modal('show');
                    }

                    setTimeout(function(){
                        _.forEach(matchId, function(value, key) {
                            $('div.fc-unthemed').fullCalendar( 'removeEvents', [value] );
                        });
                    },200)

                    if(response.data.areAllMatchFixtureUnScheduled == true) {
                      toastr.success('Fixtures unscheduled successfully', 'Fixtures Unscheduled', {timeOut: 5000});
                    }

                    vm.cancelUnscheduleFixtures();
                    vm.$store.dispatch('setMatches')
                    .then((response) => {
                        _.forEach(vm.tournamentStages, function(stage, stageIndex) {
                            vm.$root.$emit('refreshPitch' + stageIndex);
                        });
                        vm.$root.$emit('refreshCompetitionWithGames');
                    });
                })
                setTimeout(function(){
                    vm.getAllScheduledMatches();
                    vm.clearScheduleMatchesCount();
                },500)
            },
            confirmUnschedulingAllFixtures() {
                let vm = this;
                $("body .js-loader").removeClass('d-none');
                Tournament.unscheduleAllFixtures(this.tournamentId).then(
                (response) => {
                    if(response.data.status_code == '200') {
                        $("body .js-loader").addClass('d-none');
                        $('#unschedule_all_fixtures').modal('hide')
                        toastr.success('All the fixtures are unscheduled successfully', 'All Fixtures Unscheduled', {timeOut: 5000});
                        vm.$store.dispatch('setMatches')
                        .then((response) => {
                            _.forEach(vm.tournamentStages, function(stage, stageIndex) {
                                vm.$root.$emit('refreshPitch' + stageIndex);
                            });
                            vm.$root.$emit('refreshCompetitionWithGames');
                        });
                        vm.getAllScheduledMatches();
                        vm.clearScheduleMatchesCount();
                    }
                })
            },
            saveScheduleMatchResult(matchData) {
                this.$emit("saveScheduleMatchResult", matchData);
            },
            clearScheduleMatches() {
                this.$emit("clearScheduleMatchesArray");
            },
            clearScheduleMatchesCount() {
                this.$emit("clearAllScheduleMatchesArray");
            },
            saveScheduleMatches() {
                let vm = this;
                $("body .js-loader").removeClass('d-none');
                Tournament.saveScheduleMatches(this.scheduleMatchesArray).then(
                    (response) => {
                        vm.conflictedMatchFixtures = response.data.conflictedFixturesArray;
                        if(vm.conflictedMatchFixtures.length > 0) {
                            $("body .js-loader").addClass('d-none');
                            $('#unChangedMatchFixtureModal').modal('show');
                        }
                        if(response.data.status_code == '200') {
                            if(response.data.areAllMatchFixtureScheduled == true) {
                              toastr.success('Match has been scheduled successfully.', 'Schedule Match');
                            } else {
                              toastr.error(response.data.message, 'Schedule Match');
                            }
                            vm.resetScheduleMatches();
                            vm.$store.dispatch('setMatches')
                            .then((response) => {
                                _.forEach(vm.tournamentStages, function(stage, stageIndex) {
                                    vm.$root.$emit('refreshPitch' + stageIndex);
                                });
                                vm.$root.$emit('refreshCompetitionWithGames');
                            })
                            .catch((response) => {
                                toastr['error']('Error while fetching data', 'Error');
                            });
                            $("body .js-loader").addClass('d-none');
                            vm.$emit('changeMatchScheduleStatus', false);
                            vm.enableScheduleFeatureAsDefault = true;
                        }
                    },  
                    (error) => {
                    }
                )
                setTimeout(function(){
                    vm.getAllScheduledMatches();
                },500)
            },
            scheduleMatches() {
                $('#schedule_fixtures').removeClass('btn-primary').addClass('btn-success');
                $('#save_schedule_fixtures').show();
                $('#cancel_schedule_fixtures').show();
                this.cancelUnscheduleFixtures();
                $("#unschedule_fixtures").hide();
                $("#unschedule_all_fixtures_btn").hide();
                $("#automatic_planning").hide();
                this.$emit('changeMatchScheduleStatus', true);
            },
            resetScheduleMatches() {
                let vm = this;
                $('#schedule_fixtures').removeClass('btn-success').addClass('btn-primary');
                $('#save_schedule_fixtures').hide();
                $('#cancel_schedule_fixtures').hide();
                $("#unschedule_fixtures").show();
                $("#unschedule_all_fixtures_btn").show();
                $("#automatic_planning").show();
                this.$emit('changeMatchScheduleStatus', false);
                this.clearScheduleMatches();
            },
            cancelScheduleMatches() {
                let vm = this;
                this.resetScheduleMatches();

                this.$store.dispatch('setMatches')
                .then((response) => {
                    _.forEach(vm.tournamentStages, function(stage, stageIndex) {
                        vm.$root.$emit('refreshPitch' + stageIndex);
                    });
                    vm.$root.$emit('refreshCompetitionWithGames');
                })
                .catch((response) => {
                    toastr['error']('Error while fetching data', 'Error');
                });

                $("body .js-loader").addClass('d-none');
                vm.$emit('changeMatchScheduleStatus', false);
                vm.enableScheduleFeatureAsDefault = true;
            },
            filterMatches(filterKey, filterValue, filterDependentKey, filterDependentValue) {
                let vm = this;
                _.forEach(this.tournamentStages, function(stage, stageIndex) {
                    let allEvents = $('#pitchPlanner' + (stageIndex + 1)).parent('.fc-unthemed').fullCalendar('clientEvents');
                    let events = _.filter(allEvents, function(o) { return o.matchId != -1; });

                    events = _.map(events, function(event) {
                        let scheduleBlock = false;
                        if(filterKey == 'age_category'){
                            if( filterValue != '' && filterValue.id != event.matchAgeGroupId){
                                scheduleBlock = true
                            }
                            if(filterDependentKey != '' && filterDependentValue != ''  && filterDependentValue != event.matchCompetitionId) {
                                scheduleBlock = true
                            }
                        } else if(filterKey == 'location'){
                            if( filterValue != '' && filterValue.id != event.matchVenueId){
                                scheduleBlock = true;
                            }
                        }

                        if(scheduleBlock){
                            event.color = 'grey';
                            event.textColor = '#FFFFFF';
                            event.borderColor = 'grey';
                            event.fixtureStripColor = 'grey';
                            event.refereeId = -1;
                            event.matchTitle = 'Match scheduled - ' + event.displayMatchName;
                        } else {
                            let borderColorVal;
                            let isBright = (parseInt(vm.getBrightness(event.categoryAgeColor)) > 160);
                            if(isBright) {
                                borderColorVal = vm.LightenDarkenColor(event.categoryAgeColor, -40);
                            } else {
                                borderColorVal = vm.LightenDarkenColor(event.categoryAgeColor, 40);
                            }
                            let fixtureStripColor = event.competitionColorCode != null ? event.competitionColorCode : '#FFFFFF';

                            event.color = event.categoryAgeColor;
                            event.textColor = event.categoryAgeFontColor;
                            event.borderColor = borderColorVal;
                            event.fixtureStripColor = fixtureStripColor;
                            event.refereeId = event.matchRefereeId;
                            event.matchTitle = event.displayMatchName;
                        }
                        return event;
                    });
                    $('#pitchPlanner' + (stageIndex + 1)).parent('.fc-unthemed').fullCalendar('updateEvents', events);
                });
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
            showConflictedForSameMatchFixtures(matchFixtureData, anotherMatchScheduled) {
                this.conflictedMatchFixtures = matchFixtureData;
                this.isAnotherMatchScheduled = anotherMatchScheduled;
                $('#unChangedMatchFixtureModal').modal('show');
            },
            showConflictedForAnotherMatchFixtures(matchFixtureData, anotherMatchScheduled) {
                this.conflictedMatchFixtures = matchFixtureData;
                this.isAnotherMatchScheduled = anotherMatchScheduled;
                $('#unChangedMatchFixtureModal').modal('show');
            },
            makeScheduleMatchesAsDefault() {
                this.enableScheduleFeatureAsDefault = true;
                this.scheduleMatches();
            },
            showUnChangedMatchFixture(conflictedFixturesArray) {
                this.conflictedMatchFixtures = conflictedFixturesArray;
                if(this.conflictedMatchFixtures.length > 0) {
                    $('#unChangedMatchFixtureModal').modal('show');
                }
            },
            getAllScheduledMatches() {
                Tournament.getAllScheduledMatch(this.tournamentId).then(
                    (response) => {
                        if (response.data.temp_fixtures) {
                            this.totalNumberOfScheduledMatches = response.data.temp_fixtures.length
                        } else {
                            this.totalNumberOfScheduledMatches = 0;
                        }
                    },
                    (error) => {
                    }
                );
            },
            setPlannerView(view) {
                if(view == 'timelineDay') {
                  this.isVertical = false;
                  this.isHorizontal = true;
                }
                if(view == 'agendaDay') {
                  this.isHorizontal = false;
                  this.isVertical = true;
                }
                this.$root.$emit('setView', view);
            },
        }
    }
</script>
