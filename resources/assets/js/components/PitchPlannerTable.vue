<template>
    <div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <button class="btn btn-secondary btn-md js-pitch-planner-bt horizontal"  @click="setView('timelineDay')">{{$lang.pitch_planner_horizontal}}</button>
                <button class="btn btn-primary btn-md js-pitch-planner-bt vertical"  @click="setView('agendaDay')">{{$lang.pitch_planner_vertical}}</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9 pitch_planner_section pitch">
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
                           Stage {{ stage.stageNumber }}: {{dispDate(stage.tournamentStartDate)}}</button>
                          <div :id="'demo'+stage.stageNumber"
                          class="stages collapse in show" aria-expanded="true">
                            <pitch-planner-stage :stage="stage" :currentView="currentView" :defaultView="defaultView"></pitch-planner-stage>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3" id="outerGame">
                <div class="grey_bg" id="gameReferee">
                    <div class="tabs tabs-primary">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a :class="[currentView == 'gamesTab' ? 'active' : '', 'nav-link px-3']"
                                @click="setCurrentTab('gamesTab')"
                                data-toggle="tab"  role="tab" href="#game-list">Games ({{totalMatchCount}})</a>
                            </li>
                            <li class="nav-item">
                                <a :class="[currentView == 'refereeTab' ? 'active' : '', 'nav-link px-3']"
                                @click="setCurrentTab('refereeTab')"
                                data-toggle="tab" role="tab" href="#referee-list">Referees ({{totalRefereeCount}})</a>
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
    </div>
</template>
<script>
    import moment from 'moment'
    import GamesTab from './GamesTab.vue'
    import RefereesTab from './RefereesTab.vue'
    import PitchPlannerStage from './PitchPlannerStage.vue'

    export default  {
        components: {
            GamesTab, RefereesTab, PitchPlannerStage
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
                return this.$store.state.Tournament.totalMatch
            },
            totalRefereeCount() {
                return this.$store.state.Tournament.totalReferee
            }
            // tournamentStages() {
            //     let tournamentStartDate = moment(this.tournamentStartDate, 'DD/MM/YYYY');
            //     let stages = [];

            //     for (var i = 1; i <= this.tournamentDays; i++) {
            //         // fetch pitches available for this day
            //         let currentDateString  = tournamentStartDate.format('DD/MM/YYYY');
            //         let availablePitchesForStage = _.filter(this.pitches, (pitch) => {
            //             return _.find(pitch.pitch_availability, { 'stage_start_date': currentDateString});
            //         });

            //         stages.push({
            //             stageNumber: i,
            //             tournamentStartDate: currentDateString,
            //             pitches: availablePitchesForStage
            //         });

            //         tournamentStartDate = tournamentStartDate.add(i, 'days');
            //     }

            //     return stages;
            // }
        },
        created: function() {
            this.$root.$on('setPitchReset', this.resetPitch);
            this.$root.$on('setGameReset', this.gameReset);
            this.$root.$on('setRefereeReset', this.refereeReset);
            this.$root.$on('RefereeCount', this.refereeCount);
            this.$root.$on('getPitchesByTournamentFilter', this.setFilter);
            this.$root.$on('setPitchPlanTab',this.setCurrentTab)

        },
        data() {
            return {
                'currentView':'gamesTab',
                'currentButton' : 'horizontal',
                'matchCount':'',
                'tournamentStages': {},
                'stageStatus':false,
                'GameStatus':false,
                'refereeStatus':false,
                'refereeCount': '',
                'defaultView': 'agendaDay'
            };
        },
        props: {
        },
        mounted() {
                $('.pitch_planner_section').mCustomScrollbar({
                'autoHideScrollbar':true
            });
                            // return stages;
            this.resetPitch()
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
                let tabWith = $('#gameReferee').width()+10;
                let setGameHeight = $('.tab-content').height()-100;
                // $('#gameReferee').css('height',setGameHeight);
                var stickyHeaderTop = (($('#gameReferee').offset().top ) - $('.site-header').offset().top);
                // console.log(stickyHeaderTop, $('.site-header').offset().top,'stickytop')
                $( window ).scroll(function() {
                      if( $(window).scrollTop() > (stickyHeaderTop - $('.site-header').height())  ) {
                        // console.log('msg')
                        // $('#gameReferee').addClass('affix');
                        $('#gameReferee').css({position: 'fixed', top: '0px', width: tabWith, 'margin-top':$('.site-header').height()});
                    } else {
                        $('#gameReferee').css({position: 'static', top: '0px',width:tabWith, 'margin-top':0});
                        // $('#stickyalias').css('display', 'none');

                    }
                    // console.log($('.pitch-planner-wrapper').offset())
                    // console.log($('.pitch-planner-wrapper').outerHeight(),'outer')

                  // $( "span" ).css( "display", "inline" ).fadeOut( "slow" );
                });
            })
         $(".stages").on('shown.bs.collapse', function(){

                alert('The collapsible content is about to be shown.');
            });

         // TODO set Default View

          //         this.setView(this.defaultView);
        },
        methods: {
            setCurrentTab(currentTab = 'refereeTab') {
                let vm =this;
             
                this.currentView = currentTab
                // vm.stageStatus = false;
               // vm.GameStatus = false
                setTimeout(function(){
                    // vm.stageStatus = true
                    // vm.GameStatus = true
                    if(currentTab == 'refereeTab'){
                      vm.refereeReset()
                    }
                   
                },500)
              
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

                // this.stageStatus = false

                // setTimeout(function(){
                //     vm.stageStatus = true
                // },200)
            },
            refereeCount(totReferee) {
                this.refereeCount = totReferee
            },
            resetPitch() {
                let vm = this
                this.stageStatus = false
                this.GameStatus = false
                this.refereeStatus = false
                this.tournamentStages = ''
               let tournamentStartDate = moment(this.tournamentStartDate, 'DD/MM/YYYY');
                let stages = [];
                for (var i = 1; i <= this.tournamentDays; i++) {

                    // fetch pitches available for this day
                    let currentDateString  = tournamentStartDate.format('DD/MM/YYYY');
                    // console.log(currentDateString)
                    let availablePitchesForStage = _.filter(this.pitches, (pitch) => {
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
                setTimeout(function(){
                    vm.stageStatus = true
                    vm.GameStatus = true
                    vm.refereeStatus = true
                    vm.tournamentStages = stages
                },500)
            },
          gameReset() {

            let vm =this
             vm.GameStatus = false
             vm.refereeStatus = false

             setTimeout(function(){
                  vm.refereeStatus = true
                  vm.GameStatus = true

                    $('.nav-tabs a[href="#game-list"]').tab('show');
              },500)
          },
          refereeReset() {
            let vm =this
             vm.GameStatus = false
             vm.refereeStatus = false

             setTimeout(function(){
                    vm.refereeStatus = true
                    vm.GameStatus = true
                    $('.nav-tabs a[href="#referee-list"]').tab('show');
                },500)
          },

          dispDate(date) {
            var date1 = moment(date, 'DD/MM/YYYY')
            return date1.format('ddd DD MMM YYYY')
          }
        }
    }
</script>
