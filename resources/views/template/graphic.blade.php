<div class="grid-round">
  <div class="col-round" v-for="(round, roundIndex) in rounds()">
    <div class="round-img-wrapper">
      <img src="/assets/img/img-round.png" class="img-fluid"><span class="round-number">{{ roundIndex + 1 }}</span>
    </div>
    <div class="round-details-wrapper">
      <!-- <h6 class="text-center text-uppercase font-weight-bold mb-2">Round Robin</h6> -->
      <div :class="{'mt-4': groupIndex !== 0}" v-for="(group, groupIndex) in round.match_type">
        
        <!-- Round 2 - PM -->
        <div class="row-round" v-if="getGroupType(group) == 'PM'" v-for="(match, matchIndex) in group.groups.match">
          <div class="bordered-box" v-if="checkForMatchNumberOrRankingInPosition('placing_match', match.match_number)"><span class="font-weight-bold small">{{ checkForMatchNumberOrRankingInPosition('placing_match', match.match_number) }}</span></div>
          <div class="bordered-box" v-else><span class="font-weight-bold small">Match {{ getMatchNumber(match.display_match_number) }}</span></div>
          <div class="bordered-box" v-if="!checkIfWinnerLoserMatch(match.match_number)"><span class="small">{{ getPlacingTeam(match, 'home') }}-{{ getPlacingTeam(match, 'away') }}</span></div>
          <div class="bordered-box" v-if="checkIfWinnerLoserMatch(match.match_number)"><span class="small">{{ getPlacingWinnerLoserTeam(match, 'home') }}</span></div>
          <div class="bordered-box" v-if="checkIfWinnerLoserMatch(match.match_number)"><span class="small">{{ getPlacingWinnerLoserTeam(match, 'away') }}</span></div>
        </div>

        <!-- round 2 - RR -->
        <div v-if="getGroupType(group) == 'RR'">
          <div class="row-round">
            <div class="group-column" v-if="roundIndex == 0">
                <h6 class="m-0 font-weight-bold">{{ "Group " + getGroupName(group.groups.group_name) }}</h6>
                <div class="bordered-box" v-for="team in group.group_count">
                    <span class="font-weight-bold">{{ getRoundRobinAssignedTeam(getGroupName(group.groups.group_name), team) }}</span>
                </div>
            </div>

            <div class="group-column" v-if="roundIndex >= 1">
              <h6 class="m-0 font-weight-bold">{{ "Group " + getGroupName(group.groups.group_name) }}</h6>
              <div class="bordered-box" v-for="(team, teamIndex) in getRoundRobinUniqueTeams(group.groups.match)">
                <span class="font-weight-bold">{{ team }}</span>
              </div>
            </div>

            <div class="group-column" v-if="roundIndex >= 1">
              <h6 class="m-0 font-weight-bold">&nbsp;</h6>
              <div class="bordered-box" v-for="(team, teamIndex) in group.group_count">
                <span class="font-weight-bold">{{ getGroupName(group.groups.group_name) + (teamIndex+1) }}</span>
              </div>
            </div>

            <div class="group-column" v-if="isAnyRankingInPosition(getGroupName(group.groups.group_name), group.group_count)">
              <h6 class="m-0 font-weight-bold">Ranking</h6>
              <div class="bordered-box" v-if="checkForMatchNumberOrRankingInPosition('round_robin', (teamIndex+1) + getGroupName(group.groups.group_name))" v-for="(team, teamIndex) in group.group_count">
                <span class="font-weight-bold">{{ checkForMatchNumberOrRankingInPosition('round_robin', (teamIndex+1) + getGroupName(group.groups.group_name)) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-round" v-for="(round, roundIndex) in getDivisionRounds()">
    <div class="round-img-wrapper">
      <img src="/assets/img/img-round.png" class="img-fluid"><span class="round-number">{{ getMainNoOfRoundCount() + roundIndex + 1 }}</span>
    </div>
    <div class="round-details-wrapper" :class="{'mt-4': groupIndex !== 0}" v-for="(group, groupIndex) in round.match_type">
      <!-- Round 2 - PM -->
      <div class="row-round" v-if="getGroupType(group) == 'PM'" v-for="(match, matchIndex) in group.groups.match">
        <div class="bordered-box" v-if="checkForMatchNumberOrRankingInPosition('placing_match', match.match_number)"><span class="font-weight-bold small">{{ checkForMatchNumberOrRankingInPosition('placing_match', match.match_number) }}</span></div>
        <div class="bordered-box" v-else><span class="font-weight-bold small">Match {{ getMatchNumber(match.display_match_number) }}</span></div>
        <div class="bordered-box" v-if="!checkIfWinnerLoserMatch(match.match_number)"><span class="small">{{ getPlacingTeam(match, 'home') }}-{{ getPlacingTeam(match, 'away') }}</span></div>
        <div class="bordered-box" v-if="checkIfWinnerLoserMatch(match.match_number)"><span class="small">{{ getPlacingWinnerLoserTeam(match, 'home') }}</span></div>
        <div class="bordered-box" v-if="checkIfWinnerLoserMatch(match.match_number)"><span class="small">{{ getPlacingWinnerLoserTeam(match, 'away') }}</span></div>
      </div>

      <!-- round 2 - RR -->
      <div v-if="getGroupType(group) == 'RR'">
        <div class="row-round">
          <div class="group-column">
            <h6 class="m-0 font-weight-bold">{{ "Group " + getGroupName(group.groups.group_name) }}</h6>
            <div class="bordered-box" v-for="(team, teamIndex) in getRoundRobinUniqueTeams(group.groups.match)">
              <span class="font-weight-bold">{{ team }}</span>
            </div>
          </div>

          <div class="group-column">
            <h6 class="m-0 font-weight-bold">&nbsp;</h6>
            <div class="bordered-box" v-for="(team, teamIndex) in group.group_count">
              <span class="font-weight-bold">{{ getGroupName(group.groups.group_name) + (teamIndex+1) }}</span>
            </div>
          </div>

          <div class="group-column" v-if="isAnyRankingInPosition(getGroupName(group.groups.group_name), group.group_count)">
            <h6 class="m-0 font-weight-bold">Ranking</h6>
            <div class="bordered-box" v-if="checkForMatchNumberOrRankingInPosition('round_robin', (teamIndex+1) + getGroupName(group.groups.group_name))" v-for="(team, teamIndex) in group.group_count">
              <span class="font-weight-bold">{{ checkForMatchNumberOrRankingInPosition('round_robin', (teamIndex+1) + getGroupName(group.groups.group_name)) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@php
  
@endphp