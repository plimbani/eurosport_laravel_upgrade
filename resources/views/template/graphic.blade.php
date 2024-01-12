@php($colorCodes = getColorCodeOfMatches($allMatches))
<div class="grid-round-wrapper">
  <div class="grid-round">
    @foreach(rounds($templateData) as $roundIndex=>$round)
      <div class="col-round">
        <div class="round-img-wrapper">
          <img src="/assets/img/img-round.png" class="img-fluid"><span class="round-number">{{ $roundIndex + 1 }}</span>
        </div>
        <div class="round-details-wrapper">
          <!-- <h6 class="text-center text-uppercase font-weight-bold mb-2">Round Robin</h6> -->
          @foreach(getAllRoundGroups($roundIndex, $round['match_type']) as $groupIndex=>$group)
            <div class="{{ $groupIndex !== 0 ? 'mt-4' : '' }}">
              <!-- Round 2 - PM -->
              @if(getGroupType($group) == 'PM')
                @foreach($group['groups']['match'] as $matchIndex=>$match)
                  <div class="row-round">
                    <div class="bordered-box" style="background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}">
                      @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                      <div><span class="font-weight-bold small">Match {{ getMatchNumber($match['display_match_number']) . ($matchDetail && $matchDetail['is_scheduled'] === 1 ? " (" . date("d/m/Y", strtotime($matchDetail['match_datetime'])) . ")" : "") }}</span></div>
                      @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                        <div><span class="small">{{ $matchDetail['venue_name'] }}</span></div>
                        <div><span class="small">{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}</span></div>
                      @endif
                    </div>

                    @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                      <div class="bordered-box d-flex flex-column justify-content-center"><span class="font-weight-bold small">{{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}</span></div>
                    @endif

                    @if(!checkIfWinnerLoserMatch($match['match_number']))
                      <div class="bordered-box d-flex flex-column justify-content-center">
                        <div>
                          <?php
                            $homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge);
                            $awayTemScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge);
                          ?>
                          <span class="small">{{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}</span>
                          @if(!is_null($homeTeamScoreValue) && !is_null($awayTemScoreValue))
                            <span class="team-scores">
                              @if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['home_team'])<span class="circle-badge left-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif{{ $homeTeamScoreValue }}-{{ $awayTemScoreValue }}@if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['away_team'])<span class="circle-badge right-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif
                            </span>
                          @endif
                        </div>
                      </div>
                    @endif

                    @if(checkIfWinnerLoserMatch($match['match_number']))
                      @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                      <div class="bordered-box d-flex flex-column justify-content-center" style="background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                        <div>
                          <span class="small d-block">{{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}</span>
                          @php($homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge))
                          @if(!is_null($homeTeamScoreValue))
                            <span class="team-scores">@if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['home_team'])<span class="circle-badge left-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif{{ $homeTeamScoreValue }}</span>
                          @endif
                        </div>
                      </div>
                    @endif

                    @if(checkIfWinnerLoserMatch($match['match_number']))
                      @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                      <div class="bordered-box d-flex flex-column justify-content-center" style="background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                        <div>
                          <span class="small d-block">{{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}</span>
                          @php($awayTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge))
                          @if(!is_null($awayTeamScoreValue))
                            <span class="team-scores">{{ $awayTeamScoreValue }}@if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['away_team'])<span class="circle-badge right-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif</span>
                          @endif
                        </div>
                      </div>
                    @endif
                  </div>
                @endforeach
              @endif

              <!-- round 2 - RR -->
              @if(getGroupType($group) == 'RR')
                <div>
                  <div class="row-round">
                    @if($roundIndex == 0)
                      <div class="group-column">
                          <div class="m-0 font-weight-bold group-title">{{ "Group " . getGroupName($group['groups']['group_name']) }}</div>
                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                            <div class="bordered-box">
                              <span class="small">{{ getRoundRobinAssignedTeam($assignedTeams, getGroupName($group['groups']['group_name']), $teamIndex, isset($tournamentHasStandings) ? $tournamentHasStandings : '') }}</span>
                            </div>
                          @endfor
                      </div>
                    @endif

                    @if($roundIndex >= 1)
                      <div class="group-column">
                        <div class="m-0 font-weight-bold group-title">{{ "Group " . getGroupName($group['groups']['group_name']) }}</div>
                        @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                          <div class="bordered-box" style="background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
                            <span class="small">{{ $team['name'] }}</span>
                          </div>
                        @endforeach
                      </div>
                    @endif

                    @if($roundIndex >= 1)
                      <div class="group-column">
                        <div class="m-0 font-weight-bold group-title">&nbsp;</div>
                        @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                          <div class="bordered-box">
                            <span class="small">{{ ($teamIndex) . getGroupName($group['groups']['group_name']) }}</span>
                          </div>
                        @endfor
                      </div>
                    @endif

                    @if(isAnyRankingInPosition($templateData, getGroupName($group['groups']['group_name']), $group['group_count']))
                      <div class="group-column">
                        <div class="m-0 font-weight-bold group-title">Ranking</div>
                        @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                          @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                            <div class="bordered-box">
                              <span class="small">{{ checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])) }}</span>
                            </div>
                          @endif
                        @endfor
                      </div>
                    @endif
                  </div>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
    @foreach(getDivisionRounds($templateData) as $roundIndex=>$round)
      <div class="col-round">
        <div class="round-img-wrapper">
          <img src="/assets/img/img-round.png" class="img-fluid"><span class="round-number">{{ getMainNoOfRoundCount($templateData) + $roundIndex + 1 }}</span>
        </div>

        @foreach($round['match_type'] as $groupIndex=>$group)
          <div class="round-details-wrapper {{ $roundIndex == 0 && isset($divisions) && count($divisions) > 0 ? 'pt-1' : '' }}" class="{{ $groupIndex !== 0 ? 'mt-4' : '' }}">
            <!-- Round 2 - PM -->
           {{ $roundIndex == 0 && isset($divisions) && is_array($divisions) && count($divisions) > 0 && array_key_exists($groupIndex, $divisions) ? $divisions[$groupIndex] : '' }}
            @if(getGroupType($group) == 'PM')
              @foreach($group['groups']['match'] as $matchIndex=>$match)
                <div class="row-round">
                  <div class="bordered-box" style="background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}">
                    @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                    <div><span class="font-weight-bold small">Match {{ getMatchNumber($match['display_match_number']) . ($matchDetail && $matchDetail['is_scheduled'] === 1 ? " (" . date("d/m/Y", strtotime($matchDetail['match_datetime'])) . ")" : "") }}</span>
                    </div>
                    @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                      <div><span class="small">{{ $matchDetail['venue_name'] }}</span></div>
                      <div><span class="small">{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}</span></div>
                    @endif
                  </div>

                  @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                    <div class="bordered-box d-flex flex-column justify-content-center"><span class="font-weight-bold small">{{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}</span></div>
                  @endif
                  
                  @if(!checkIfWinnerLoserMatch($match['match_number']))
                    <?php
                      $homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge);
                      $awayTemScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge);
                    ?>
                    <div class="bordered-box d-flex flex-column justify-content-center">
                      <div>
                        <span class="small">{{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}</span>
                          @if(!is_null($homeTeamScoreValue) && !is_null($awayTemScoreValue))
                            <span class="team-scores">
                              @if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['home_team'])<span class="circle-badge left-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif{{ $homeTeamScoreValue }}-{{ $awayTemScoreValue }}@if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['away_team'])<span class="circle-badge right-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif
                            </span>
                          @endif
                        </div>
                    </div>
                  @endif
                  @if(checkIfWinnerLoserMatch($match['match_number']))
                    @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                    <div class="bordered-box d-flex flex-column justify-content-center" style="background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                      <div>
                        <span class="small d-block">{{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}</span>
                        @php($homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge))
                        @if(!is_null($homeTeamScoreValue))
                          <span class="team-scores">@if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['home_team'])<span class="circle-badge left-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif{{ $homeTeamScoreValue }}</span>
                        @endif
                      </div>
                    </div>
                  @endif
                  @if(checkIfWinnerLoserMatch($match['match_number']))
                    @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                    <div class="bordered-box d-flex flex-column justify-content-center" style="background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                      <div>
                        <span class="small d-block">{{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}</span>
                        @php($awayTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge))
                        @if(!is_null($awayTeamScoreValue))
                          <span class="team-scores">{{ $awayTeamScoreValue }}@if($matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['away_team'])<span class="circle-badge right-input"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></span>@endif</span>
                        @endif
                      </div>
                    </div>
                  @endif
                </div>
              @endforeach
            @endif

            <!-- round 2 - RR -->
            @if(getGroupType($group) == 'RR')
              <div>
                <div class="row-round">
                  <div class="group-column">
                    <div class="m-0 font-weight-bold group-title">{{ "Group " . getGroupName($group['groups']['group_name']) }}</div>
                    @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                      <div class="bordered-box" style="background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
                        <span class="small">{{ $team['name'] }}</span>
                      </div>
                    @endforeach
                  </div>

                  <div class="group-column">
                    <div class="m-0 font-weight-bold group-title">&nbsp;</div>
                    @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                      <div class="bordered-box">
                        <span class="small">{{ ($teamIndex) . getGroupName($group['groups']['group_name']) }}</span>
                      </div>
                    @endfor
                  </div>

                  @if(isAnyRankingInPosition($templateData, getGroupName($group['groups']['group_name']), $group['group_count']))
                    <div class="group-column">
                      <div class="m-0 font-weight-bold group-title">Ranking</div>
                      @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                        @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                          <div class="bordered-box">
                            <span class="small">{{ checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])) }}</span>
                          </div>
                        @endif
                      @endfor
                    </div>
                  @endif
                </div>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endforeach
  </div>
</div>
