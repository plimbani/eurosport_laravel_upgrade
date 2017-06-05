<template>
    <div class="row">
        <div class="col-md-9 pitch_planner_section ">
            <div class="pitch-planner-wrapper">
                <div class="pitch-planner-item" v-if="stageStatus" v-for="stage in tournamentStages">
                    <div class="card">
                      <div class="card-block text-center pb-0">
                        <h4 class="table_heading">Stage {{ stage.stageNumber }}: {{dispDate(stage.tournamentStartDate)}}</h4>
                      </div>
                      <pitch-planner-stage :stage="stage"></pitch-planner-stage>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="grey_bg">
                <div class="tabs tabs-primary">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a
                            :class="[currentView == 'gamesTab' ? 'active' : '', 'nav-link px-3']"
                            data-toggle="tab" role="tab" href="#game-list">Games ({{totalMatchCount}})</a>
                        </li>
                        <li class="nav-item">
                            <a
                            :class="[currentView == 'refereeTab' ? 'active' : '', 'nav-link px-3']"
                            data-toggle="tab" role="tab" href="#referee-list">Referees ({{totalRefereeCount}})</a>
                        </li>
                    </ul>
                     <div class="tab-content">
                        <div
                        :class="[currentView == 'gamesTab' ? 'active' : '', 'tab-pane']"
                        v-if="GameStatus" id="game-list" role="tabpanel">
                            <games-tab></games-tab>
                        </div>
                        <div :class="[currentView == 'refereeTab' ? 'active' : '', 'tab-pane']" v-if="refereeStatus" id="referee-list" role="tabpanel">
                            <referees-tab></referees-tab>
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
            },
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
                'matchCount':'',
                'tournamentStages': {},
                'stageStatus':false,
                'GameStatus':false,
                'refereeStatus':false,
                'refereeCount': ''
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
        },
        methods: {
            setCurrentTab(currentTab = 'gamesTab') {
              this.currentView = currentTab
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
