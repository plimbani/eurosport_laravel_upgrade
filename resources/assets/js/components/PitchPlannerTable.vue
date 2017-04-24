<template>
    <div class="row">
        <div class="col-md-9">
            <div class="pitch-planner-wrapper">
                <div class="pitch-planner-item" v-for="stage in tournamentStages">
                    <div class="card">
                      <div class="card-block text-center">
                        <h4>Stage - {{ stage.stageNumber }}</h4>                        
                      </div>
                    </div>
                    <pitch-planner-stage :stage="stage"></pitch-planner-stage>        
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="grey_bg">
                <div class="tabs tabs-primary">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" role="tab" href="#game-list">Games</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" role="tab" href="#referee-list">Referees</a>
                        </li>
                    </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="game-list" role="tabpanel">
                            <games-tab></games-tab>
                        </div>
                        <div class="tab-pane" id="referee-list" role="tabpanel">
                            <referees-tab></referees-tab>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import GamesTab from './GamesTab.vue'
    import RefereesTab from './RefereesTab.vue'
    import PitchPlannerStage from './PitchPlannerStage.vue'
    
    export default  {
        components: {
            GamesTab, RefereesTab, PitchPlannerStage
        },
        computed: {
            tournamentDays() {
                return this.$store.state.Tournament.tournamentDays;
            },
            tournamentStartDate() {
                return this.$store.state.Tournament.tournamentStartDate;
            },            
            pitches() {
                return this.$store.state.Pitch.pitches;
            },     
            tournamentStages() {
                let tournamentStartDate = moment(this.tournamentStartDate, 'DD/MM/YYYY');
                let stages = [];

                for (var i = 1; i <= this.tournamentDays; i++) {
                    // fetch pitches available for this day                    
                    let currentDateString  = tournamentStartDate.format('DD/MM/YYYY');                    
                    let availablePitchesForStage = _.filter(this.pitches, (pitch) => {
                        return _.find(pitch.pitch_availability, { 'stage_start_date': currentDateString});                        
                    });

                    stages.push({
                        stageNumber: i,
                        tournamentStartDate: currentDateString,
                        pitches: availablePitchesForStage
                    });

                    tournamentStartDate = tournamentStartDate.add(i, 'days');
                }

                console.log('tournamentStages', stages);
                return stages;
            }       
        },
        data() {
            return {
                'currentView':'gamesTab'
            };
        },
        props: {
        },
        mounted() {
        },
        methods: {          
        }
    }
</script>