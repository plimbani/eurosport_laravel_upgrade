<template>
    <div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <button class="btn btn-secondary btn-md js-pitch-planner-bt horizontal"  @click="setView('timelineDay')">{{$lang.pitch_planner_horizontal}}</button>
                <button class="btn btn-primary btn-md js-pitch-planner-bt vertical"  @click="setView('agendaDay')">{{$lang.pitch_planner_vertical}}</button>
                <button v-if="isPitchPlannerInEnlargeMode == 0" class="btn btn-primary btn-md vertical" @click="enlargePitchPlanner()">Enlarge</button>
                <button class="btn btn-primary btn-md vertical" v-if="isGroupFilterSet" @click="openGroupCompetitionColourModal()">{{$lang.pitch_planner_group_colours}}</button>
            </div>
        </div>

        <div class="row">
            <div class="pitch_planner_section pitch" v-bind:class="[isPitchPlannerInEnlargeMode == 0 ? 'col-md-9' : 'col-md-10']">
                <div class="pitch-planner-wrapper">
                    <div class="pitch-planner-item" v-if="stageStatus" v-for="stage in tournamentStages">
                        <div class="card">
                          <!-- <div class="card-block text-center pb-0">
                            <h4 class="table_heading">Stage {{ stage.stageNumber }}: {{dispDate(stage.tournamentStartDate)}}</h4>
                          </div> -->
                          <button class="btn pnl" data-toggle="collapse"
                          @click="toggleStage(stage.stageNumber)"
                          :id="stage.stageNumber"
                          v-bind:data-target="'#demo'+stage.stageNumber">
                           <i :id="'opt_icon_'+stage.stageNumber"  class="fa fa-minus"></i>
                           Day {{ stage.stageNumber }}: {{dispDate(stage.tournamentStartDate)}}</button>
                          <div :id="'demo'+stage.stageNumber"
                          class="stages collapse in show" aria-expanded="true">
                            <pitch-planner-stage :stage="stage"  :defaultView="defaultView"></pitch-planner-stage>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" id="outerGame" v-bind:class="[isPitchPlannerInEnlargeMode == 0 ? 'col-md-3' : 'col-md-2']">
                <div class="grey_bg" id="gameReferee">
                    <div class="tabs tabs-primary">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="text-center" :class="[currentView == 'gamesTab' ? 'active' : '', 'nav-link px-3']"
                                @click="setCurrentTab('gamesTab')"
                                data-toggle="tab" role="tab" href="#game-list">Games <span>({{totalMatchCount}})</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="text-center" :class="[currentView == 'refereeTab' ? 'active' : '', 'nav-link px-3']"
                                @click="setCurrentTab('refereeTab')"
                                data-toggle="tab" role="tab" href="#referee-list">Referees <span>({{totalRefereeCount}})</span></a>
                            </li>
                        </ul>
                         <div class="tab-content">
                            <div
                            :class="[currentView == 'gamesTab' ? 'active' : '', 'tab-pane']"
                            v-if="GameStatus" id="game-list" role="tabpanel">
                                <games-tab></games-tab>
                            </div>
                            <div :class="[currentView == 'refereeTab' ? 'active' : '', 'tab-pane']" v-if="refereeStatus"  id="referee-list" role="tabpanel">
                                <referees-tab></referees-tab>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <GroupCompetitionColour></GroupCompetitionColour>
        <AddRefereesModel :formValues="formValues" :competationList="competationList" :tournamentId="tournamentId" :refereeId="refereeId" ></AddRefereesModel>
    </div>
</template>
<script>
    import moment from 'moment'
    import GamesTab from './GamesTab.vue'
    import RefereesTab from './RefereesTab.vue'
    import PitchPlannerStage from './PitchPlannerStage.vue'
    import GroupCompetitionColour from './GroupCompetitionColourModal.vue'
    import AddRefereesModel from './AddRefereesModel.vue'
    import Tournament from '../api/tournament.js'

    export default  {
        components: {
            GamesTab, RefereesTab, PitchPlannerStage, GroupCompetitionColour, AddRefereesModel
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
            isGroupFilterSet() {
              if(this.$store.state.Tournament.tournamentFiler.filterKey == 'age_category' && this.$store.state.Tournament.tournamentFiler.filterValue != '') {
                return true;
              }
              return false;
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
            }
        },
        created: function() {
            this.$root.$on('setPitchReset', this.resetPitch);
            this.$root.$on('setGameReset', this.gameReset);
            this.$root.$on('setRefereeReset', this.refereeReset);
            this.$root.$on('RefereeCount', this.refereeCount);
            this.$root.$on('resetPitchesOnCategoryColorSave', this.resetPitchesOnCategoryColorSave);
            this.$root.$on('getPitchesByTournamentFilter', this.setFilter);
            this.$root.$on('setPitchPlanTab',this.setCurrentTab)
            this.$root.$on('getAllReferee', this.getAllreferees);
            // this.$root.$on('getTeamsByTournamentFilter', this.resetPitch);

            this.$root.$on('editReferee', this.editReferee);
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
                'competationList': [{}],
                'formValues': this.initialState(),
            };
        },
        props: {
        },
        mounted() {
            $('.pitch_planner_section').mCustomScrollbar({
                'autoHideScrollbar':true
            });
                let vm = this
            setTimeout(function(){
                vm.resetPitch();
            },500)    
            
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
                // setTimeout(function(){
                //      // vm.stageStatus = true
                //     // vm.GameStatus = true
                //     if(currentTab == 'refereeTab'){
                //       // vm.refereeReset()
                //       vm.$emit('getAllReferee');
                //       // vm.$store.dispatch('getAllReferee', vm.$store.state.Tournament.tournamentId)
                //     }
                   
                // },500)
              
            },
            
            // myFilter: function(){
            //     this.isActive = !this.isActive;
            //   // some code to filter users
            // },
            toggleStage(stageNo){
                // Change the opt_icon as well
            if($('#opt_icon_'+stageNo).hasClass('fa-plus') == true){
                $('#opt_icon_'+stageNo).addClass('fa-minus')
                $('#opt_icon_'+stageNo).removeClass('fa-plus')
            }else{
                $('#opt_icon_'+stageNo).addClass('fa-plus')
                $('#opt_icon_'+stageNo).removeClass('fa-minus')
            }
                let vm =this
                setTimeout(function(){
                        if(vm.defaultView == 'timelineDay'){
                        $('.fc-timelineDay-button').click()
                    }else{
                        $('.fc-agendaDay-button').click()
                    }
                },100)

            },
            setView(view) {
                let vm = this
                this.defaultView = view
                if(vm.defaultView == 'timelineDay'){
                    $('.fc-timelineDay-button').click()
                }else{
                    $('.fc-agendaDay-button').click()
                }
            },
            refereeCount(totReferee) {
                this.refereeCount = totReferee
            },
            resetPitch() {
                let vm = this
                this.stageStatus = false
                vm.tournamentStages = ''
                // this.GameStatus = false
                // this.refereeStatus = false
                this.tournamentStages = ''
                let tournamentStartDate = moment(this.tournamentStartDate, 'DD/MM/YYYY');
                let stages = [];
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
                    // vm.stageStatus = true
                    // vm.GameStatus = true
                    // vm.refereeStatus = true
                    vm.$store.dispatch('setTournamentStages',stages)

                setTimeout(function(){
                     vm.stageStatus = true
                    // vm.GameStatus = true
                    // vm.refereeStatus = true
                     vm.tournamentStages = stages
                },500)
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
          openGroupCompetitionColourModal(){
            this.$root.$emit('getCategoryCompetitions')
            $('#group_competition_modal').modal('show');
          },
          enlargePitchPlanner() {
            this.$router.push({name: 'enlarge_pitch_planner'})
          },
        }
    }
</script>
