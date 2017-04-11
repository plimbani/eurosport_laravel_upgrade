<template>
   <div class="modal" id="competationmodal" tabindex="-1" role="dialog" aria-labelledby="competationmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="competationmodalLabel">{{$lang.competation_modal_age_category}}</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
               </button>
            </div>
            <div class="modal-body">
               <form name="ageCategoryName">
                <div  v-for="format in templateData.tournament_competation_format" class="row">
                  <div v-for="grp in format"  class="col-sm-6">
                    <div class="card text-center">
                      <div class="card-header">
                        <h4 class="card-title">{{grp.name}}</h4>
                      </div>
                      <div class="card-block">
                        <p class="card-text">{{ grp.match_type[0].name }}</p>
                        <table class="table">
                          <thead>
                            <tr>
                              <th>Match Number:</th>
                              <th>{{ grp.match_type[0].groups.group_name }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr  v-for="match in grp.match_type[0].groups.match">
                              <th>{{ match.match_number }}</th>
                              <td>{{ match.in_between }}</td>
                            </tr>
                            <tr>
                            <td colspan="2">{{ grp.match_type[0].total_match }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  
                  <div class="col-md-6">
                    <div class="result col-md-12">
                        <div class="dashbox">
                            <p class="row">
                                <label class="col-md-9"><strong>{{$lang.competation_modal_format_team}}</strong></label>
                                <label class="col-md-3">{{ templateData['tournament_teams'] }}</label>
                            </p>
                            <p class="row">
                                <label class="col-md-9"><strong>{{$lang.competation_modal_foramt_competation_foramt}}</strong></label>
                                <label class="col-md-3">{{ templateData['competation_format'] }}</label>
                            </p>
                            <p class="row">
                                <label class="col-md-9"><strong>{{$lang.competation_modal_minimum_matches}}</strong></label>
                                <label class="col-md-3">{{ templateData['tournament_min_match'] }}</label>
                            </p>
                            <p class="row">
                                <label class="col-md-9"><strong>{{$lang.competation_modal_format_remark}}</strong></label>
                                <label class="col-md-3"></label>
                            </p>
                        </div>
                    </div>
                </div>
                  <div class="col-md-6">
                    <div class="result col-md-12">
                        <div class="dashbox">
                            <p class="row">
                                <label class="col-md-9"><strong>{{$lang.competation_modal_matches_total_matches}}</strong></label>
                                <label class="col-md-3">{{ templateData['total_matches'] }}</label>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="result col-md-12">
                        <div class="dashbox">
                            <p class="row">
                                <label class="col-md-9"><strong>{{$lang.competation_modal_time}}</strong></label>
                                <label class="col-md-3">{{totalTime | formatTime}} </label>
                            </p>
                           
                        </div>
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
     props: ['templateData','totalTime'],
     filters: {
    formatTime: function(time) {
      var hours = Math.floor( time /   60); 
      var minutes = Math.floor(time % 60);

      return hours+ ' Hours And '+minutes+' Minutes'
    }
   }
 }
</script>