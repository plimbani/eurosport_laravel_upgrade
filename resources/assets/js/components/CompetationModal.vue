<template>
  <div class="modal" id="competationmodal" tabindex="-1" role="dialog" aria-labelledby="competationmodalLabel" style="display: none;" aria-hidden="true" data-animation="false">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
           <h5 class="modal-title" id="competationmodalLabel">{{$lang.competation_modal_age_category}} {{templateData.tournament_name}}</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">×</span>
           </button>
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
            <div class="row">
              <div class="col-md-12 text-center">
                <img class="img-fluid" :src="templateGraphicViewImage">
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
  export default {
    data() {
      return {
      }
    },
    props: ['templateData','totalTime','templateGraphicViewImage', 'graphicHtml'],
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
    // rounds() {
    //   if(this.templateData.tournament_competation_format != undefined) {
    //     return this.templateData.tournament_competation_format.format_name;
    //   }
    //   return [];
    // },
    // getMainNoOfRoundCount() {
    //   if(this.templateData.tournament_competation_format != undefined) {
    //     return this.templateData.tournament_competation_format.format_name.length;
    //   }
    //   return 0;
    // },
    // getGroupType(group) {
    //   let groupName = group.name;
    //   if(typeof group.actual_name !== 'undefined') {
    //     groupName = group.actual_name;
    //   }
    //   let groupNameArray = groupName.split("-");
    //   return groupNameArray[0];
    // },
    // getRoundRobinUniqueTeams(matches) {
    //   let uniqueTeamsArray = [];
    //   let vm = this;
    //   matches.forEach(function(match) {
    //     let modifiedMatchNumber = (match.match_number).replace('CAT.', vm.groupName + '-' + vm.categoryAge + '-');

    //     if(typeof vm.fixtures[modifiedMatchNumber] !== 'undefined' && vm.fixtures[modifiedMatchNumber].home_team !== 0) {
    //       uniqueTeamsArray.push(vm.fixtures[modifiedMatchNumber].home_team_name);
    //     } else {
    //       if((match.display_home_team_placeholder_name).indexOf(".") !== -1) {
    //         let matchNumber = (match.match_number).split('.');
    //         let homeAwayTeam = matchNumber[matchNumber.length - 1].split('-');
    //         if(homeAwayTeam[0].indexOf('WR') !== -1) {
    //           uniqueTeamsArray.push('Winner ' + match.display_home_team_placeholder_name);
    //         }
    //         if(homeAwayTeam[0].indexOf('LR') !== -1) {
    //           uniqueTeamsArray.push('Loser ' + match.display_home_team_placeholder_name);
    //         }
    //       } else {
    //         uniqueTeamsArray.push(match.display_home_team_placeholder_name);
    //       }
    //     }

    //     if(typeof vm.fixtures[modifiedMatchNumber] !== 'undefined' && vm.fixtures[modifiedMatchNumber].away_team !== 0) {
    //       uniqueTeamsArray.push(vm.fixtures[modifiedMatchNumber].away_team_name);
    //     } else {
    //       if((match.display_away_team_placeholder_name).indexOf(".") !== -1) {
    //         let matchNumber = (match.match_number).split('.');
    //         let homeAwayTeam = matchNumber[matchNumber.length - 1].split('-');
    //         if(homeAwayTeam[1].indexOf('WR') !== -1) {
    //           uniqueTeamsArray.push('Winner ' + match.display_away_team_placeholder_name);
    //         }
    //         if(homeAwayTeam[1].indexOf('LR') !== -1) {
    //           uniqueTeamsArray.push('Loser ' + match.display_away_team_placeholder_name);
    //         }
    //       } else {
    //         uniqueTeamsArray.push(match.display_away_team_placeholder_name);
    //       }
    //     }
    //   });

    //   return _.uniq(uniqueTeamsArray);
    // },
    // checkIfWinnerLoserMatch(matchNumber) {
    //   return (matchNumber.indexOf("WR") !== -1 || matchNumber.indexOf("LR") !== -1);
    // },
    // getPlacingTeam(match, teamType) {
    //   let matchNumber = (match.match_number).replace('CAT.', this.groupName + '-' + this.categoryAge + '-');
    //   if(teamType === 'home') {
    //     if(typeof this.fixtures[matchNumber] !== 'undefined' && this.fixtures[matchNumber].home_team !== 0) {
    //       return this.fixtures[matchNumber].home_team_name;
    //     }
    //     return match.display_home_team_placeholder_name;
    //   }

    //   if(teamType === 'away') {
    //     if(typeof this.fixtures[matchNumber] !== 'undefined' && this.fixtures[matchNumber].away_team !== 0) {
    //       return this.fixtures[matchNumber].away_team_name;
    //     }
    //     return match.display_away_team_placeholder_name;
    //   }
    // },
    // getPlacingWinnerLoserTeam(match, teamType) {
    //   let modifiedMatchNumber = (match.match_number).replace('CAT.', this.groupName + '-' + this.categoryAge + '-');
    //   if(teamType === 'home' && typeof this.fixtures[modifiedMatchNumber] !== 'undefined' && this.fixtures[modifiedMatchNumber].home_team !== 0) {
    //     return this.fixtures[modifiedMatchNumber].home_team_name;
    //   }

    //   if(teamType === 'away' && typeof this.fixtures[modifiedMatchNumber] !== 'undefined' && this.fixtures[modifiedMatchNumber].away_team !== 0) {
    //     return this.fixtures[modifiedMatchNumber].away_team_name;
    //   }

    //   let matchNumber = (match.match_number).split('.');
    //   let homeAwayTeam = matchNumber[matchNumber.length - 1].split('-');
    //   if(teamType === 'home') {
    //     if(homeAwayTeam[0].indexOf('WR') !== -1) {
    //       return 'Winner ' + match.display_home_team_placeholder_name;
    //     }
    //     if(homeAwayTeam[0].indexOf('LR') !== -1) {
    //       return 'Loser ' + match.display_home_team_placeholder_name;
    //     }
    //     return match.display_home_team_placeholder_name;
    //   }

    //   if(teamType === 'away') {
    //     if(homeAwayTeam[1].indexOf('WR') !== -1) {
    //       return 'Winner ' + match.display_away_team_placeholder_name;
    //     }
    //     if(homeAwayTeam[1].indexOf('LR') !== -1) {
    //       return 'Loser ' + match.display_away_team_placeholder_name;
    //     }
    //     return match.display_away_team_placeholder_name;
    //   }
    // },
    // getDivisionRounds() {
    //   let divisions = [];
    //   if(typeof this.templateData.tournament_competation_format !== 'undefined' && typeof this.templateData.tournament_competation_format.divisions !== 'undefined') {
    //     _.forEach(this.templateData.tournament_competation_format.divisions, function(division) {
    //       _.forEach(division.format_name, function(round, roundIndex) {
    //         if(typeof divisions[roundIndex] === 'undefined') {
    //           divisions[roundIndex] = [];
    //           divisions[roundIndex]['match_type'] = [];
    //         }
    //         divisions[roundIndex]['match_type'] = _.concat(divisions[roundIndex]['match_type'], round.match_type);
    //       });
    //     });
    //     return divisions;
    //   }
    //   return divisions;
    // },
    // getGroupName(groupName) {
    //   groupName = groupName.split('-');
    //   return groupName[1];
    // },
    // checkForMatchNumberOrRankingInPosition(roundType, matchOrRanking) {
    //   let dependentType = roundType === 'round_robin' ? 'ranking' : 'match';
    //   let filteredPositions = _.filter(this.templateData.tournament_positions, function(o) {
    //     if(dependentType === 'ranking') {
    //       return o.ranking === matchOrRanking;
    //     }
    //     if(dependentType === 'match') {
    //       return o.match_number === matchOrRanking;
    //     }
    //   });
    //   if(filteredPositions.length > 0) {
    //     if(roundType === 'placing_match' && filteredPositions.length === 2) {
    //       let winnerPosition = _.head(_.filter(filteredPositions, function(o) { return o.result_type === 'winner'; }));
    //       let loserPosition = _.head(_.filter(filteredPositions, function(o) { return o.result_type === 'loser'; }));
    //       if(winnerPosition.position === 1 && loserPosition.position === 2) {
    //         return "Final";
    //       }
    //       return "Place " + winnerPosition.position + "-" + loserPosition.position;
    //     }
    //     return filteredPositions[0].position;
    //   }
    //   return false;
    // },
    // getMatchNumber(displayMatchNumber) {
    //   let matchCode = displayMatchNumber.split('.');
    //   return matchCode[1] + '.' + matchCode[2];
    // },
    // isAnyRankingInPosition(groupName, groupCount) {
    //   let isAnyRankingInPosition = false;
    //   for(let i=0; i<groupCount; i++) {
    //     if((this.checkForMatchNumberOrRankingInPosition('round_robin', (i+1) + groupName)) !== false) {
    //       isAnyRankingInPosition = true;
    //     }
    //   }
    //   return isAnyRankingInPosition;
    // },
    // getRoundRobinAssignedTeam(groupName, teamIndex) {
    //   let assignedTeam = _.head(_.filter(this.assignedTeams, function(o) { return o.group_name === "Group-" + groupName + teamIndex; }));
    //   if(typeof assignedTeam !== 'undefined') {
    //     return assignedTeam.name;
    //   }
    //   return '#' + teamIndex;
    // },
   }
 }
</script>
