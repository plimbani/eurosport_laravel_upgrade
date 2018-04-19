<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
    html {
      font-family: sans-serif;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    .headfoot th {
      background-color: #eee;
      border-bottom: solid 1px #ccc;
      border-top: solid 1px #ccc;
    }
    .headfoot th:first-child {
      border-left: solid 1px #ccc;
    }
    .headfoot th:last-child{
      border-right: solid 1px #ccc;
    }
    .foot th{
      padding: 8px 4px;
    }
    .tblpage{
      width: 18cm;
      min-height: 22.7cm;
      margin: 0cm auto;
    }
    div.breakNow {
      page-break-inside:avoid;
      page-break-after:always;
    }
    thead {
      display: table-row-group;
    }
    p {
      font-size: 13px;
    }
</style>
<center>
  <img  src="{{ asset('assets/img/logo-desk.svg')}}" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="100px">
  <h3>League table summary</h3>
</center>
@foreach($data['leagueTable'] as $league)
  <div class="row">
    <div class="col-sm-12">
      <h5>{{ $league['name'] }} standings</h5>
    </div>
    <div class="col-sm-12">
      @if(count($league['standings']) > 0)
        <table class="tblpage" border="1" cellpadding="1" cellspacing="0" style="font-size: 70%">
          <thead>
            <tr>
              <th></th>
              <th>Played</th>
              <th>Won</th>
              <th>Draws</th>
              <th>Lost</th>
              <th>For</th>
              <th>Against</th>
              <th>Difference</th>
              <th>Points</th>
            </tr>
          </thead>
          <tbody>
            @foreach($league['standings'] as $match)
              @php $match = (array) $match; @endphp
              <tr>
                <td align="left" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 15px"><img src="{{ $match['teamFlag'] }}" height="10" style="display: inline;"> <span>{{ $match['name'] }}</span></td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['played'] }}</td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['won'] }}</td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['draws'] }}</td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['lost'] }}</td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['goal_for'] }}</td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['goal_against'] }}</td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['GoalDifference'] }}</td>
                <td align="center"  style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match['points'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <p>No information available</p>
      @endif
    </div>
    <br>
  </div>
@endforeach
<div class="breakNow"></div>
<center><h3>First round group summary</h3></center>
@foreach($data['resultGridTable'] as $competitionId => $resultGrid)
  @if($resultGrid['actual_competition_type'] == 'Round Robin')
    <h5>{{ $resultGrid['name'] }} results grid</h5>
    @if(count($resultGrid['results']) > 0)
      <table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%" style="font-size: 70%">
        <thead>
          <tr>
            <th></th>
            @foreach($resultGrid['results'] as $team)
              <th style="vertical-align: middle; padding: 5px 0 3px 0">
                @if($team['TeamFlag'] != null)<img src="{{ asset($team['TeamFlag']) }}" height="10" style="display: inline;">@endif <span>{{ $team['TeamName'] }}</span></th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($resultGrid['results'] as $team)
            <tr>
              <td align="center" style="vertical-align: middle; padding: 5px 0 3px 0">
                @if($team['TeamFlag'] != null)<img src="{{ asset($team['TeamFlag']) }}" height="10" style="display: inline;">@endif <span>{{ $team['TeamName'] }}</span>
              </td>
              @foreach($team['matches'] as $match)
                <td align="center" @if($match == 'Y') bgcolor="#ededed" @endif>
                  @if($match != 'Y' && $match != 'X' && $match['score'] == null)
                    {{ isset($match['date']) ? $match['date'] : '' }}
                  @else
                    {{ isset($match['score']) ? $match['score'] : '' }}
                  @endif
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>No information available</p>
    @endif
  @endif
  @if(isset($data['leagueTable'][$competitionId]))
    <h5>{{ $resultGrid['name'] }} standings</h5>
    @if(count($data['leagueTable'][$competitionId]['standings']) > 0)
      <table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%" style="font-size: 70%">
        <thead>
          <tr>
            <th></th>
            <th>Played</th>
            <th>Won</th>
            <th>Draws</th>
            <th>Lost</th>
            <th>For</th>
            <th>Against</th>
            <th>Difference</th>
            <th>Points</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data['leagueTable'][$competitionId]['standings'] as $match)
            @php $match = (object) $match; @endphp
            <tr>
              <td align="left" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 15px"><img src="{{ $match->teamFlag }}" height="10" style="display: inline;"> <span>{{ $match->name }}</span></td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->played }}</td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->won }}</td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->draws }}</td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->lost }}</td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->goal_for }}</td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->goal_against }}</td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->GoalDifference }}</td>
              <td align="center" style="vertical-align: middle; width: 80px; padding: 5px 0 3px 0">{{ $match->points }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>No information available</p>
    @endif
  @endif
  <h5>{{ $resultGrid['name'] }} matches</h5>
  @if(count($data['resultMatchesTable'][$competitionId]['results']) > 0)
    <table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%" style="font-size: 70%">
      <thead>
        <th>Date and time</th>
        <th>Categories</th>
        <th>Team</th>
        <th>Team</th>
        <th>Score</th>
        <th>Location</th>
      </thead>
      <tbody>
        @foreach($data['resultMatchesTable'][$competitionId]['results'] as $match)
          <tr>
            <td align="center">{{ Carbon\Carbon::parse($match->match_datetime)->format('jS M Y H:i') }}</td>
            <td align="center">{{ $match->competation_name }}</td>
            <td align="right" style="vertical-align: middle; padding: 5px 10px 3px 0">
              <span>
                @if($match->Home_id == '0' && $match->homeTeamName == '@^^@')
                  @if(strpos($match->competition_actual_name, "Group") != -1)
                    {{ $match->homePlaceholder }}
                  @else
                    Pos-{{ $match->homePlaceholder }}
                  @endif
                @else
                  {{ $match->HomeTeam }}
                @endif
              </span>
              @if($match->Home_id != '0')
                <img src="{{ $match->HomeFlagLogo }}" height="10" style="display: inline;">
              @endif
            </td>
            <td align="left" style="vertical-align: middle; padding: 5px 0 3px 10px">
              @if($match->Away_id != '0')
                <img src="{{ $match->AwayFlagLogo }}" height="10" style="display: inline;">
              @endif
              <span>
                @if($match->Away_id == '0' && $match->awayTeamName == '@^^@')
                  @if(strpos($match->competition_actual_name, "Group") != -1)
                    {{ $match->awayPlaceholder }}
                  @else
                    Pos-{{ $match->awayPlaceholder }}
                  @endif
                @else
                  {{ $match->AwayTeam }}
                @endif
              </span>
            </td>
            <td align="center">{{ $match->homeScore . ' - ' . $match->AwayScore }}</td>
            <td align="center">{{ $match->venue_name . ' - ' . $match->pitch_number }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>No information available</p>
  @endif
  <div class="breakNow"></div>
@endforeach
<center><h3>Tournament progression</h3></center>
@foreach($data['resultMatchesTableAfterFirstRound'] as $matches)
  <h5>{{ $matches['name'] }} matches</h5>
  @if(count($matches['results']) > 0)
    <table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%" style="font-size: 70%">
      <thead>
        <th>Date and time</th>
        <th>Categories</th>
        <th>Team</th>
        <th>Team</th>
        <th>Score</th>
        @if($matches['actual_competition_type'] == 'Elimination')<th>Placing</th>@endif
        <th>Location</th>
      </thead>
      <tbody>
        @foreach($matches['results'] as $match)
          <tr>
            <td align="center">{{ $match->match_datetime ? Carbon\Carbon::parse($match->match_datetime)->format('jS M Y H:i') : '-' }}</td>
            <td align="center">{{ $match->competation_name }}</td>
            <td align="right" style="vertical-align: middle; padding: 5px 10px 3px 0">
              <span>
                @if($match->Home_id == '0')
                            @if((strpos($match->displayMatchNumber, 'wrs') != false) || (strpos($match->displayMatchNumber, 'lrs') != false)) 
                        <?php
                            $homeTeamDisplay = $match->displayHomeTeamPlaceholderName;
                        ?>
                            @else
                                <?php $homeTeamDisplay = $match->displayHomeTeamPlaceholderName ?>
                            @endif 
                        <?php if(strpos($match->competition_actual_name, 'Pos') !== false)
                            $homeTeamDisplay = 'Pos-' . $match->displayHomeTeamPlaceholderName; ?>
                      
                    @else
                     <?php  $homeTeamDisplay =$match->HomeTeam; ?>
                        
                    @endif
                    <?php echo $homeTeamDisplay; ?>
              </span>
              @if($match->Home_id != '0')
                <img src="{{ $match->HomeFlagLogo }}" height="10" style="display: inline;">
              @endif
            </td>
            <td align="left" style="vertical-align: middle; padding: 5px 0 3px 10px">
              @if($match->Away_id != '0')
                <img src="{{ $match->AwayFlagLogo }}" height="10" style="display: inline;">
              @endif
              <span>
                @if($match->Home_id == '0')
                            @if((strpos($match->displayMatchNumber, 'wrs') != false) || (strpos($match->displayMatchNumber, 'lrs') != false)) 
                        <?php
                            $awayTeamDisplay = $match->displayAwayTeamPlaceholderName;
                        ?>
                            @else
                                <?php $awayTeamDisplay = $match->displayAwayTeamPlaceholderName ?>
                            @endif 
                        <?php if(strpos($match->competition_actual_name, 'Pos') !== false)
                            $awayTeamDisplay = 'Pos-' . $match->displayAwayTeamPlaceholderName; ?>
                    @else
                     <?php  $awayTeamDisplay =$match->AwayTeam; ?>                        
                    @endif
                    <?php echo $awayTeamDisplay; ?>
              </span>
            </td>
            <td align="center">{{ $match->homeScore . ' - ' . $match->AwayScore }}</td>
            @if($matches['actual_competition_type'] == 'Elimination')
              <td align="center">
                {{ $match->position != null ? $match->position : 'N/A' }}
              </td>
            @endif
            <td align="center">{{ $match->venue_name . ' - ' . $match->pitch_number }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>No information available</p>
  @endif
@endforeach