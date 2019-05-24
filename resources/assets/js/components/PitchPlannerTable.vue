<template>
    <div>
        <div class="row">
            <div class="col-md-9 mb-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <button v-if="isPitchPlannerInEnlargeMode == 0" class="btn btn-primary btn-md vertical" @click="enlargePitchPlanner()">Enlarge</button>
                        <button class="btn btn-primary btn-md" @click="openAutomaticPitchPlanningModal()">{{$lang.pitch_planner_automatic_planning}}</button>
                        <button class="btn btn-md btn-secondary" id="unschedule_fixtures" @click="unscheduleFixtures()">{{$lang.pitch_planner_unschedule_fixtures}}</button>
                        <button class="btn btn-danger btn-md cancle-match-unscheduling d-none" id="cancle_unscheduling_fixtures" @click="cancelUnscheduleFixtures()">{{$lang.pitch_planner_cancel_unscheduling}}</button>
                    </div>
                    <div>
                        <button class="btn btn-default btn-md vertical" @click="printPitchPlanner()"><i class="fas fa-print text-primary"></i></button>
                        <button class="btn btn-default btn-md vertical" @click="exportPitchPlanner()"><i class="fas fa-download text-primary"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="pitch_planner_section pitch" v-bind:class="[isPrintPitchPlanner == 0 ? (isPitchPlannerInEnlargeMode == 0 ? 'col-md-9' : 'col-md-10') : 'col-md-12' ]">
                <div class="pitch-planner-wrapper">
                    <div class="pitch-planner-item" v-if="stageStatus" v-for="stage in tournamentStages">
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
                                    <pitch-planner-stage :stage="stage"  :defaultView="defaultView"></pitch-planner-stage>
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
                                <div class="wrapper-tab">Games <span>({{totalMatchCount}})</span></div></a>
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
                                <games-tab></games-tab>
                            </div>
                            <div :class="[currentView == 'refereeTab' ? 'active' : '', 'tab-pane']" v-if="refereeStatus"  id="referee-list" role="tabpanel">
                                <referees-tab v-if="isCompetitionCallProcessed" :competationList="competationList"></referees-tab>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <BulkUnscheduledfixtureModal :unscheduleFixture="unscheduleFixture" 
            @confirmed="confirmUnschedulingFixtures()"></BulkUnscheduledfixtureModal>
        <AutomaticPitchPlanning></AutomaticPitchPlanning>
        <AddRefereesModel :formValues="formValues" :competationList="competationList" :tournamentId="tournamentId" :refereeId="refereeId" ></AddRefereesModel>
        <UploadRefereesModel :tournamentId="tournamentId"></UploadRefereesModel>
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

    export default  {
        components: {
            GamesTab, RefereesTab, PitchPlannerStage, AddRefereesModel, UploadRefereesModel, AutomaticPitchPlanning, BulkUnscheduledfixtureModal
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
                return this.$store.getters.totalMatch
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
                'matchId': null,
            };
        },
        props: {
        },
        mounted() {
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
                    vm.stageStatus = true
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
                var manageClass = false;
                if($("#unschedule_fixtures").hasClass('btn-success')) {
                    $("#bulk_unscheduled_fixtures").modal('show');
                    return true;
                }
                if($("#unschedule_fixtures").hasClass('btn-secondary')) {
                    $("#unschedule_fixtures").removeClass('btn-secondary').addClass('btn-primary');
                    $(".match-unschedule-checkbox-div").removeClass('d-none');
                    return true;
                }

                if($("#unschedule_fixtures").hasClass('btn-primary')) {
                    $("#unschedule_fixtures").removeClass('btn-primary').addClass('btn-secondary');
                    $(".match-unschedule-checkbox-div").addClass('d-none');
                    return true;
                }
            },
            cancelUnscheduleFixtures() {
                $("#unschedule_fixtures").html('Unschedule fixture').removeClass('btn btn-success');
                $("#unschedule_fixtures").addClass('btn btn-primary btn-md btn-secondary');
                $(".match-unschedule-checkbox-div").addClass('d-none');
                $("#cancle_unscheduling_fixtures").hide();
                $(".match-unschedule-checkbox").prop( "checked", false);
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

                // Tournament.matchUnscheduledFixtures(matchId).then(
                Tournament.matchUnscheduledFixtures(matchDetail).then(
                (response) => {
                    $('#bulk_unscheduled_fixtures').modal('hide')
                    setTimeout(function(){
                        _.forEach(matchId, function(value, key) {
                            $('div.fc-unthemed').fullCalendar( 'removeEvents', [value] );
                        });
                    },200)
                    toastr.success('Fixtures unscheduled successfully', 'Fixtures Unscheduled', {timeOut: 5000});
                    $("#unschedule_fixtures").html('Unschedule fixture').removeClass('btn btn-success');
                    $("#unschedule_fixtures").addClass('btn btn-primary btn-md btn-secondary');
                    $(".match-unschedule-checkbox-div").addClass('d-none');
                    $("#cancle_unscheduling_fixtures").hide();

                    vm.$store.dispatch('setMatches');
                    vm.$store.dispatch('SetScheduledMatches');
                    vm.$root.$emit('reloadAllEvents')
                })
            }
        }
    }
</script>
