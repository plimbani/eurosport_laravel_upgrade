<template>
  <div class="modal" id="competationmodal" tabindex="-1" role="dialog" aria-labelledby="competationmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="competationmodalLabel">{{$lang.competation_modal_age_category}} {{templateData.tournament_name}}</h5>
          <div class="d-flex align-items-center">
            <button type="button" class="btn btn-primary mr-4" @click="generateMatchSchedulePrint()">Print</button>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
        </div>
        <div class="modal-body">
          <form name="ageCategoryName">
            <div class="row">
              <div class="col-md-6">
                <div class="jumbotron h-100 mb-0 px-4 py-4">
                  <p class="row no-gutters">
                      <label class="col-md-7"><strong>{{$lang.competation_modal_format_team}}</strong></label>
                      <label class="col-md-5 pl-2">{{ templateData['tournament_teams'] }}</label>
                  </p>
                  <p class="row no-gutters" v-if="templateData['tournament_min_match'] != null">
                      <label class="col-md-7"><strong>{{$lang.competation_modal_minimum_matches}}</strong></label>
                      <label class="col-md-5 pl-2">{{ templateData['tournament_min_match'] }}</label>
                  </p>
                  <p class="row no-gutters mb-0">
                      <label class="col-md-7"><strong>{{$lang.competation_modal_foramt_competation_foramt}}</strong></label>
                      <label class="col-md-5 pl-2">{{ displayRoundSchedule() }}</label>
                  </p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="jumbotron mb-0 h-100 px-4 py-4 m-h-214">
                  <p class="row no-gutters">
                      <label class="col-md-7"><strong>{{$lang.competation_modal_matches_total_matches}}</strong></label>
                      <label class="col-md-5 pl-2">{{ templateData['total_matches'] }}</label>
                  </p>
                  <p class="row no-gutters">
                      <label class="col-md-7"><strong>{{$lang.competation_modal_time}}</strong></label>
                      <label class="col-md-5 pl-2">{{totalTime | formatTime}} </label>
                  </p>
                   <p class="row no-gutters">
                      <label class="col-md-7"><strong>{{$lang.competation_modal_remark}}</strong></label>
                      <label class="col-md-5 pl-2">
                        <span  v-if="templateData['remark']">
                        {{templateData['remark']}} </span>
                        <span v-else>Not applicable</span>
                      </label>
                  </p>
                  <p class="row no-gutters">
                    <label class="col-md-7"><strong>{{$lang.competation_modal_avg_games_team}}</strong></label>
                    <label class="col-md-5 pl-2">
                      <span  v-if="templateData['avg_game_team']">
                      {{templateData['avg_game_team']}} </span>
                      <span v-else>Not applicable</span>
                    </label>
                  </p>
                </div>
              </div>
            </div>
            <div v-html="graphicHtml"></div>
          </form>
        </div>
       </div>
    </div>
  </div>
</template>
<script type="text/babel">
  import Tournament from '../api/tournament.js';
  export default {
    data() {
      return {
      }
    },
    props: ['templateData','totalTime', 'graphicHtml', 'categoryId', 'tournamentTemplateId'],
    filters: {
      formatTime: function(time) {
        var hours = Math.floor( time /   60);
        var minutes = Math.floor(time % 60);

        return hours+ 'h '+minutes+'m'
      }
    },
    computed: {
    },
   methods:{
    displayRoundSchedule() {
      var roundScheduleData = this.templateData.round_schedule;
      if(roundScheduleData) {
        return this.templateData.tournament_teams +" teams " + roundScheduleData.join(" - ");
      }
    },
    generateMatchSchedulePrint() {
      let templateData = {'ageCategoryId': this.categoryId, 'templateId': this.tournamentTemplateId, tournamentId: this.$store.state.Tournament.tournamentId};
      let matchSchedulePrintWindow = window.open('', '_blank');
      Tournament.getSignedUrlForMatchSchedulePrint(templateData).then(
        (response) => {
          matchSchedulePrintWindow.location = response.data;
        },
        (error) => {

        }
      )
    }
   }
 }
</script>
