<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{-- <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> --}}
    <title>Match Schedule – Template {{ $templateData['tournament_name'] }}</title>
    <style type="text/css">
      html {
        font-family: sans-serif;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
      }
      body {
        /*font-family: Lato, sans-serif;*/
        /*color: #595959;*/
      }
      td,
      th {
        /*border-width: 0;
        border-color: #000;*/
          border: 1px solid #000;
          border-collapse: collapse;
          text-align: center;
          padding: 2px;
          line-height: 1.5;
          font-size: 12px;
      }
      table {
        border-color: #000;
        color: #595959;
      },
      th {
          padding: 5px 2px;
      }
      .row-round+.row-round {
          margin-top: 20px;
      }
      .round-img-wrapper {
          position: relative;
      }
      .round-number {
          position: absolute;
          right: 32px;
          color: #fff;
          top: 12px;
          font-weight: bold;
          font-size: 13px;
      }
      .break {
        page-break-before: always;
      }
    </style>
  </head>
  <body>
    <center>
      @if($tournamentData->tournamentLogo != null)
        <img src="{{ $tournamentData->tournamentLogo }}" class="hidden-sm-down text-center" alt="Laraspace Logo" width="200px">
      @elseif(Config::get('config-variables.current_layout') == 'tmp')
        <img  src="{{ asset('assets/img/tmplogo.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
      @elseif(Config::get('config-variables.current_layout') == 'commercialisation')
        <img  src="{{ asset('assets/img/easy-match-manager/emm.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
      @endif
      <h4 style="margin-top: 30px; margin-bottom: 30px;">Match Schedule – Template {{ $templateData['tournament_name'] }}</h4>
    </center>
    @php($colorCodes = getColorCodeOfMatches($allMatches))
    @foreach(rounds($templateData) as $roundIndex=>$round)
      @php($pageBreakClass = ($roundIndex > 0 ? "break" : ""))
      <table border="0" cellpadding="0" cellspacing="0" align="center" class="{{ $pageBreakClass }}">
        <tr>
            <td style="border:0;width: 140px;">
                <div class="round-img-wrapper">
                    <img src="{{ asset('assets/img/img-round.png') }}" style="width: 100%;">
                    <span class="round-number">{{ $roundIndex + 1 }}</span>
                </div>
            </td>
        </tr>
      </table>
      <table border="1" align="center" cellpadding="0" cellspacing="5" style="margin-top: 20px;"> 
        <tr>
          <td style="border: 0;padding: 0;">
            <div class="grid-round-wrapper">
              <div class="grid-round">
                <div class="col-round">
                  <div class="round-details-wrapper">
                    @foreach(getAllRoundGroups($roundIndex, $round['match_type']) as $groupIndex=>$group)
                      <div class="row-round">
                        <table cellpadding="0" cellspacing="0">

                          <!-- Round 2 - PM -->
                          @if(getGroupType($group) == 'PM')
                            @foreach($group['groups']['match'] as $matchIndex=>$match)
                              <tr>
                                <td style="border: 0;padding: 0;">
                                  <table cellpadding="0" cellspacing="5">
                                    <tr>
                                      <td style="width: 150px; background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}">
                                        <span style="font-weight: bold;">Match {{ getMatchNumber($match['display_match_number']) }}</span>
                                        @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                                        @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                                          <br/>{{ $matchDetail['venue_name'] }}
                                          <br/>{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}
                                        @endif
                                      </td>

                                      <td style="padding: 0;border:0;">
                                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                          <tr>
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                                              <td style="width: 100px; font-weight: bold;">
                                                {{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}
                                              </td>
                                            @endif
                                            
                                            @if(!checkIfWinnerLoserMatch($match['match_number']))
                                              <td style="width: 100px;">
                                                {{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                                                  {{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                                                  {{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            @endforeach
                          @endif

                          <!-- round 2 - RR -->
                          @if(getGroupType($group) == 'RR')

                            <tr>
                              <td style="border: 0;padding: 0;">
                                <table cellpadding="0" cellspacing="5">
                                  <tr>
                                    <!-- Column 1 -->
                                    @if($roundIndex == 0)
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">{{ "Group " . getGroupName($group['groups']['group_name']) }}
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            <tr>
                                              <td style="width: 100px;">
                                                {{ getRoundRobinAssignedTeam($assignedTeams, getGroupName($group['groups']['group_name']), $teamIndex) }}
                                              </td>
                                            </tr>
                                          @endfor
                                        </table>
                                      </td>
                                    @endif

                                    @if($roundIndex >= 1)
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <!-- Column 2 -->
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              {{ "Group " . getGroupName($group['groups']['group_name']) }}
                                            </td>
                                          </tr>
                                          @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                                            <tr>
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
                                                {{ $team['name'] }}
                                              </td>
                                            </tr>
                                          @endforeach
                                        </table>
                                      </td>
                                    @endif

                                    @if($roundIndex >= 1)
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <!-- Column 3 -->
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              &nbsp;
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            <tr>
                                              <td style="width: 100px;">
                                                {{ ($teamIndex) . getGroupName($group['groups']['group_name']) }}
                                              </td>
                                            </tr>
                                          @endfor
                                        </table>
                                      </td>
                                    @endif

                                    <!-- Column 4 -->
                                    @if(isAnyRankingInPosition($templateData, getGroupName($group['groups']['group_name']), $group['group_count']))
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              Ranking
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                                              <tr>
                                                <td style="width: 100px;">
                                                  {{ checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])) }}
                                                </td>
                                              </tr>
                                            @endif
                                          @endfor
                                        </table>
                                      </td>
                                    @endif
                                  </tr>
                                </table>
                              </td>
                            </tr>

                          @endif
                        </table>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>
    @endforeach
    @foreach(getDivisionRounds($templateData) as $roundIndex=>$round)
      <table border="0" cellpadding="0" cellspacing="0" align="center" class="break">
        <tr>
            <td style="border:0;width: 140px;">
                <div class="round-img-wrapper">
                    <img src="{{ asset('assets/img/img-round.png') }}" style="width: 100%;">
                    <span class="round-number">{{ getMainNoOfRoundCount($templateData) + $roundIndex + 1 }}</span>
                </div>
            </td>
        </tr>
      </table>
      @foreach($round['match_type'] as $groupIndex=>$group)
        <table border="1" align="center" cellpadding="0" cellspacing="5" style="margin-top: 20px;"> 
          <tr>
            <td style="border: 0;padding: 0;">
              <div class="grid-round-wrapper">
                <div class="grid-round">
                  <div class="col-round">
                    <div class="round-details-wrapper">
                      <div class="row-round">
                        <table cellpadding="0" cellspacing="0">
                          <!-- Round 2 - PM -->
                          @if(getGroupType($group) == 'PM')
                            @foreach($group['groups']['match'] as $matchIndex=>$match)
                              <tr>
                                <td style="border: 0;padding: 0;">
                                  <table cellpadding="0" cellspacing="5">
                                    <tr>
                                      <td style="width: 150px; background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}">
                                        <span style="font-weight: bold;">Match {{ getMatchNumber($match['display_match_number']) }}</span>
                                        @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                                        @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                                          <br/>{{ $matchDetail['venue_name'] }}
                                          <br/>{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}
                                        @endif
                                      </td>
                                      <td style="padding: 0;border:0;">
                                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                          <tr>
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                                              <td style="width: 100px;font-weight: bold;">
                                                {{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}
                                              </td>
                                            @endif

                                            @if(!checkIfWinnerLoserMatch($match['match_number']))
                                              <td style="width: 100px;">
                                                {{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                                                {{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                                                {{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            @endforeach
                          @endif

                          <!-- round 2 - RR -->
                          @if(getGroupType($group) == 'RR')
                            <tr>
                              <td style="border: 0;padding: 0;">
                                <table cellpadding="0" cellspacing="5">
                                  <tr>
                                    <td style="border: 0;padding: 0;">
                                      <table cellpadding="0" cellspacing="0">
                                        <!-- Column 1 -->
                                        <tr>
                                          <td style="font-weight: bold;width: 100px;">
                                            {{ "Group " . getGroupName($group['groups']['group_name']) }}
                                          </td>
                                        </tr>
                                        @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                                          <tr>
                                            <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
                                                {{ $team['name'] }}
                                            </td>
                                          </tr>
                                        @endforeach
                                      </table>
                                    </td>

                                    <td style="border: 0;padding: 0;">
                                      <table cellpadding="0" cellspacing="0">
                                        <!-- Column 2 -->
                                        <tr>
                                          <td style="font-weight: bold;width: 100px;">
                                            &nbsp;
                                          </td>
                                        </tr>
                                        @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                          <tr>
                                            <td style="width: 100px;">
                                              {{ ($teamIndex) . getGroupName($group['groups']['group_name']) }}
                                            </td>
                                          </tr>
                                        @endfor
                                      </table>
                                    </td>

                                    <!-- Column 3 -->
                                    @if(isAnyRankingInPosition($templateData, getGroupName($group['groups']['group_name']), $group['group_count']))
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              Ranking
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                                              <tr>
                                                <td style="width: 100px;">
                                                  {{ checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])) }}
                                                </td>
                                              </tr>
                                            @endif
                                          @endfor
                                        </table>
                                      </td>
                                    @endif
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          @endif
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        </table>
      @endforeach
    @endforeach
  </body>
</html>