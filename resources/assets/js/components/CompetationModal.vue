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
                <div class="jumbotron h-100 mb-0 px-4 py-4">
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
                      <!-- <label class="col-md-6">{{templateData.tournament_teams}} teams<br/> {{templateData.competition_group_round}} <br/> {{templateData.competition_round}}</label> -->
                      <label class="col-md-6">{{ displayRoundSchedule() }}</label>
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
            <div class="row">
              <div class="col-md-12 text-center">
                <img class="img-fluid" :src="templateGraphicViewImage">
              </div>
            </div>


            <!-- my code -->
            <!-- <div class="row">
              <div class="col-md-4" v-for="(round, roundIndex) in rounds()">
                <h4>{{ round.name }}</h4>
                <div v-for="(group, groupIndex) in round.match_type">
                  
                round 1
                <h5>{{ group.groups.group_name }}</h5>

                <div v-if="roundIndex == 0">
                  <ul class="list-unstyled">
                    <li v-for="team in group.group_count"># {{ team }}</li>
                  </ul>
                </div>
                
                <div v-for="(match, matchIndex) in group.groups.match">
                  round 2 for PM
                  <div class="row" v-if="roundIndex == 1 && getGroupType(group) == 'PM'">
                    <div class="col-md-6">
                      <p>Match {{ roundIndex + 1 }} . {{ matchIndex + 1 }}</p>
                    </div>
                    <div class="col-md-6">
                      <p>{{ match.display_home_team_placeholder_name }} - {{ match.display_away_team_placeholder_name }}</p>
                    </div>
                  </div>

                  round 2 for RR
                  <div class="row" v-if="roundIndex == 1 && getGroupType(group) == 'RR'">
                    <div class="col-md-6">
                      <p>Group</p>
                      <p>Ranking</p>

                      {{ getUniqueTeams(group.groups.match) }}
                    </div>
                    <div class="col-md-6">
                      <p>D</p>
                      <p>#1D</p>
                    </div>
                  </div>

                  round 3
                  <div v-if="roundIndex == lastRoundIndex">
                    <div class="col-md-6 row">
                        <p>Final</p>
                    </div>
                    <div class="col-md-6 row">
                        <p> winner {{ match.display_home_team_placeholder_name  }} - winner {{ match.display_away_team_placeholder_name }}</p>
                    </div>
                  </div>
                </div>

                </div>
              </div>
            </div> -->

            <div class="grid-round">
              <div class="col-round" v-for="(round, roundIndex) in rounds()">
                <div class="round-img-wrapper">
                  <img src="/assets/img/img-round.png" class="img-fluid"><span class="round-number">{{ roundIndex + 1 }}</span>
                </div>
                <div class="round-details-wrapper">
                  <h6 class="text-center text-uppercase font-weight-bold mb-2">Round Robin</h6>
                  <div v-for="(group, groupIndex) in round.match_type">
                    
                    <!-- round 1 -->
                    <div class="group-listing" v-if="roundIndex == 0">
                        <div class="row-round">
                            <div class="group-column">
                                <h6 class="m-0 font-weight-bold">{{ group.groups.group_name }}</h6>
                                <div class="bordered-box" v-for="team in group.group_count">
                                    <span class="font-weight-bold"># {{ team }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- round 2 - PM -->
                    <div class="row-round" v-if="roundIndex == 1 && getGroupType(group) == 'PM' && roundIndex != lastRoundIndex" v-for="(match, matchIndex) in group.groups.match">
                      <div class="bordered-box"><span class="font-weight-bold small">Match {{ roundIndex + 1 }}.{{ matchIndex + 1 }}</span></div>
                      <div class="bordered-box"><span class="small">{{ match.display_home_team_placeholder_name }}-{{ match.display_away_team_placeholder_name }}</span></div>
                    </div>

                    <!-- round 2 - RR -->
                    <div v-if="roundIndex == 1 && getGroupType(group) == 'RR' && roundIndex != lastRoundIndex">
                        <div class="group-listing">
                          <div class="group-column" v-if="roundIndex == 0">
                            <h6 class="m-0 font-weight-bold">{{ group.groups.group_name }}</h6>
                            <div class="bordered-box" v-for="(team, teamIndex) in getUniqueTeams(group.groups.match)">
                              <span class="font-weight-bold">{{ team }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="group-listing">
                          <div class="row-round">
                            <div class="group-column">
                              <h6 class="m-0 font-weight-bold">{{ group.groups.group_name }}</h6>
                              <div class="bordered-box" v-for="(team, teamIndex) in getUniqueTeams(group.groups.match)">
                                <span class="font-weight-bold">{{ team }}</span>
                              </div>
                            </div>

                            <div class="group-column" v-if="roundIndex == 1">
                              <h6 class="m-0 font-weight-bold">Ranking</h6>
                              <div class="bordered-box" v-for="(team, teamIndex) in group.group_count">
                                <span class="font-weight-bold"># {{teamIndex+1}}{{getGroupNameIntial(group)}}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    
                    <!-- for last round -->
                    <div v-if="roundIndex == lastRoundIndex" v-for="(match, matchIndex) in group.groups.match">
                      <div class="row-round">
                        <div class="bordered-box"><span class="font-weight-bold small">Final</span></div>
                        <div class="bordered-box" v-if="getGroupType(group) == 'PM' && getMatchesWithWinnerOrLooser(match) == '#'">
                          <span class="font-weight-bold small">
                          {{ match.display_home_team_placeholder_name }}-{{match.display_away_team_placeholder_name}}
                          </span>
                        </div>
                        <div class="bordered-box" v-if="getMatchesWithWinnerOrLooser(match) == 'Winner' || getMatchesWithWinnerOrLooser(match) == 'Loser'">
                          <span class="font-weight-bold small">{{getMatchesWithWinnerOrLooser(match)}} {{ match.display_home_team_placeholder_name }}</span>
                        </div>
                        <div class="bordered-box" v-if="getMatchesWithWinnerOrLooser(match) == 'Winner' || getMatchesWithWinnerOrLooser(match) == 'Loser'">
                          <span class="font-weight-bold small">{{getMatchesWithWinnerOrLooser(match)}} {{ match.display_away_team_placeholder_name }}</span>
                        </div>
                      </div>
                      <!-- <div class="row-round">
                          <div class="bordered-box"><span class="font-weight-bold small">Place 3-4</span></div>
                          <div class="bordered-box" v-if="roundIndex == 1"><span class="font-weight-bold small">Loser 3.1</span></div>
                          <div class="bordered-box" v-if="roundIndex != 1"><span class="font-weight-bold small">Loser 3.2</span></div>
                      </div> -->
                    </div>
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
    data() {
      return {
      }
    },
    props: ['templateData','totalTime','templateGraphicViewImage', 'fixures'],
    filters: {
      formatTime: function(time) {
        var hours = Math.floor( time /   60);
        var minutes = Math.floor(time % 60);

        return hours+ 'h '+minutes+'m'
      }
    },
    computed: {
      lastRoundIndex() {
        return _.findLastIndex(this.templateData.tournament_competation_format.format_name);
      },
    },
   methods:{
    displayRoundSchedule() {
      var roundScheduleData = this.templateData.round_schedule;
      if(roundScheduleData) {
        return this.templateData.tournament_teams +" teams " + roundScheduleData.join(" - ");
      }
    },
    rounds() {
      if(this.templateData.tournament_competation_format != undefined) {
        return this.templateData.tournament_competation_format.format_name;
      }
      return [];
    },
    positions() {
      return this.templateData.tournament_positions;
    },
    getGroupType(group) {
      let groupName = group.name;
      let groupNameArray = groupName.split("-");
      return groupNameArray[0];
    },
    getUniqueTeams(teams) {
      let uniqueTeamsArray = [];
      teams.forEach(function(team) {
        uniqueTeamsArray.push(team.display_home_team_placeholder_name);
        uniqueTeamsArray.push(team.display_away_team_placeholder_name);
      });

      return _.uniq(uniqueTeamsArray);
    },
    getGroupNameIntial(group) {
      let groupName = group.groups.group_name;
      let groupNameArray = groupName.split("-");

      return groupNameArray[1];
    },
    getMatchesWithWinnerOrLooser(match) {
      let displayMatchNumber = match.display_match_number;
      let displayMatchNumberArray = displayMatchNumber.split(".");
      if(displayMatchNumberArray.includes("wrs") || displayMatchNumberArray.includes("lrs")) {
        return displayMatchNumberArray[3] == 'wrs' ? 'Winner' : 'Loser';
      } 
      return "#";
    }
   }
 }
</script>
