<template>
  <div class="modal" id="competationmodal" tabindex="-1" role="dialog" aria-labelledby="competationmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
           <h5 class="modal-title" id="competationmodalLabel">{{$lang.competation_modal_age_category}} {{templateData.tournament_name}}</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">×</span>
           </button>
        </div>
        <div class="modal-body">
          <form name="ageCategoryName">
            <!-- <div class="row">
              <div class="col-md-12">
                <div class="d-flex justify-content-end">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#template-image-modal">Enlarge</button>
                </div>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-md-12">
                <div class="d-flex justify-content-center">
                  <div class="d-block mx-auto">
                    <img class="img-fluid" v-bind:src="'/'+templateImage">
                  </div>
                </div>
              </div>
            </div> -->
            <div class="row">
              <div class="col-md-6">
                <div class="jumbotron mb-0 px-4 py-4">
                  <p class="row">
                      <label class="col-md-6"><strong>{{$lang.competation_modal_format_team}}</strong></label>
                      <label class="col-md-6">{{ templateData['tournament_teams'] }}</label>
                  </p>
                  <p class="row">
                      <label class="col-md-6"><strong>{{$lang.competation_modal_minimum_matches}}</strong></label>
                      <label class="col-md-6">{{ templateData['tournament_min_match'] }}</label>
                  </p>
                  <p class="row mb-0">
                      <label class="col-md-6"><strong>{{$lang.competation_modal_foramt_competation_foramt}}</strong></label>
                      <!-- <label class="col-md-4">{{ templateData['competation_format'] }}</label> -->
                      <label class="col-md-6">{{templateData.tournament_teams}} teams<br/> {{templateData.competition_group_round}} <br/> {{templateData.competition_round}}</label>
                  </p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="jumbotron mb-0 h-100 px-4 py-4 m-h-214">
                  <p class="row">
                      <label class="col-md-6"><strong>{{$lang.competation_modal_matches_total_matches}}</strong></label>
                      <label class="col-md-6">{{ templateData['total_matches'] }}</label>
                  </p>
                  <p class="row">
                      <label class="col-md-6"><strong>{{$lang.competation_modal_time}}</strong></label>
                      <label class="col-md-6">{{totalTime | formatTime}} </label>
                  </p>
                   <p class="row">
                      <label class="col-md-6"><strong>{{$lang.competation_modal_remark}}</strong></label>
                      <label class="col-md-6">
                        <span  v-if="templateData['remark']">
                        {{templateData['remark']}} </span>
                        <span v-else>Not applicable</span>
                      </label>
                  </p>
                  <p class="row">
                    <label class="col-md-6"><strong>{{$lang.competation_modal_avg_games_team}}</strong></label>
                    <label class="col-md-6">
                      <span  v-if="templateData['avg_game_team']">
                      {{templateData['avg_game_team']}} </span>
                      <span v-else>Not applicable</span>
                    </label>
                  </p>
                </div>
              </div>
            </div>
          </form>
        </div>
       </div>
    </div>
  </div>
</template>
<script type="text/babel">
   export default {
     props: ['templateData','totalTime','templateImage'],
     filters: {
    formatTime: function(time) {
      var hours = Math.floor( time /   60);
      var minutes = Math.floor(time % 60);

      return hours+ 'h '+minutes+'m'
    }
   }
 }
</script>
