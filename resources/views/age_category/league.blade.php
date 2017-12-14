<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<style type="text/css">
  html {
    font-family: sans-serif;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
  }
  .headfoot th{
    background-color: #eee;
    border-bottom: solid 1px #ccc;
    border-top: solid 1px #ccc;
  }
  .headfoot th:first-child{
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
</style>
<center>
  <img  src="{{ asset('assets/img/logo-desk.svg')}}" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="100px">
</center>
@foreach($data['leagueTable'] as $league)
  <h4>{{ $league['name'] }} standings</h4>
  @if(count($league['standings']) > 0)
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
        @foreach($league['standings'] as $match)
          <tr>
            <td align="center"><img src="{{ $match->teamFlag }}" height="15" width="15"> {{ $match->name }}</td>
            <td align="center">{{ $match->played }}</td>
            <td align="center">{{ $match->won }}</td>
            <td align="center">{{ $match->draws }}</td>
            <td align="center">{{ $match->lost }}</td>
            <td align="center">{{ $match->goal_for }}</td>
            <td align="center">{{ $match->goal_against }}</td>
            <td align="center">{{ $match->GoalDifference }}</td>
            <td align="center">{{ $match->points }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
  @if(count($league['standings']) == 0)
    <span>No information available</span>
  @endif
  <br>
@endforeach
@foreach($data['resultGridTable'] as $competitionId => $resultGrid)
  <h4>{{ $resultGrid['name'] }} results grid</h4>
  @if(count($resultGrid['results']) > 0)
    <table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%" style="font-size: 70%">
      <thead>
        <tr>
          <th></th>
          @foreach($resultGrid['results'] as $team)
            <th><img src="{{ asset($team['TeamFlag']) }}" height="15" width="15"> {{ $team['TeamName'] }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($resultGrid['results'] as $team)
          <tr>
            <td align="center"><img src="{{ asset($team['TeamFlag']) }}" height="15" width="15"> {{ $team['TeamName'] }}</td>
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
  @endif
  @if(count($resultGrid['results']) == 0)
    <span>No information available</span>
  @endif
  @if(isset($data['leagueTable'][$competitionId]))
    <h4>{{ $resultGrid['name'] }} standings</h4>
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
            <tr>
              <td align="center"><img src="{{ $match->teamFlag }}" height="15" width="15"> {{ $match->name }}</td>
              <td align="center">{{ $match->played }}</td>
              <td align="center">{{ $match->won }}</td>
              <td align="center">{{ $match->draws }}</td>
              <td align="center">{{ $match->lost }}</td>
              <td align="center">{{ $match->goal_for }}</td>
              <td align="center">{{ $match->goal_against }}</td>
              <td align="center">{{ $match->GoalDifference }}</td>
              <td align="center">{{ $match->points }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
    @if(count($data['leagueTable'][$competitionId]['standings']) == 0)
      <span>No information available</span>
    @endif
  @endif
  <h4>{{ $resultGrid['name'] }} matches</h4>
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
            <td align="right">
              @if($match->Home_id == '0' && $match->homeTeamName == '@^^@')
                @if(strpos($match->competition_actual_name, "Group") != -1)
                  {{ $match->homePlaceholder }}
                @else
                  Pos-{{ $match->homePlaceholder }}
                @endif
              @else
                {{ $match->HomeTeam }}
              @endif
              @if($match->Home_id != '0')
                <img src="{{ $match->HomeFlagLogo }}" height="15" width="15">
              @endif
            </td>
            <td align="left">
              @if($match->Away_id != '0')
                <img src="{{ $match->AwayFlagLogo }}" height="15" width="15">
              @endif
              @if($match->Away_id == '0' && $match->awayTeamName == '@^^@')
                @if(strpos($match->competition_actual_name, "Group") != -1)
                  {{ $match->awayPlaceholder }}
                @else
                  Pos-{{ $match->awayPlaceholder }}
                @endif
              @else
                {{ $match->AwayTeam }}
              @endif
            </td>
            <td align="center">{{ $match->homeScore }}</td>
            <td align="center">{{ $match->AwayScore }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
  @if(count($data['resultMatchesTable'][$competitionId]['results']) == 0)
    <span>No information available</span>
  @endif
  <div class="breakNow"></div>
@endforeach
@foreach($data['resultMatchesTableAfterFR'] as $matches)
  <h4>{{ $matches['name'] }} matches</h4>
  @if(count($matches['results']) > 0)
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
        @foreach($matches['results'] as $match)
          <tr>
            <td align="center">{{ Carbon\Carbon::parse($match->match_datetime)->format('jS M Y H:i') }}</td>
            <td align="center">{{ $match->competation_name }}</td>
            <td align="right">
              @if($match->Home_id == '0' && $match->homeTeamName == '@^^@')
                @if(strpos($match->competition_actual_name, "Group") != -1)
                  {{ $match->homePlaceholder }}
                @else
                  Pos-{{ $match->homePlaceholder }}
                @endif
              @else
                {{ $match->HomeTeam }}
              @endif
              @if($match->Home_id != '0')
                <img src="{{ $match->HomeFlagLogo }}" height="15" width="15">
              @endif
            </td>
            <td align="left">
              @if($match->Away_id != '0')
                <img src="{{ $match->AwayFlagLogo }}" height="15" width="15">
              @endif
              @if($match->Away_id == '0' && $match->awayTeamName == '@^^@')
                @if(strpos($match->competition_actual_name, "Group") != -1)
                  {{ $match->awayPlaceholder }}
                @else
                  Pos-{{ $match->awayPlaceholder }}
                @endif
              @else
                {{ $match->AwayTeam }}
              @endif
            </td>
            <td align="center">{{ $match->homeScore }}</td>
            <td align="center">{{ $match->AwayScore }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
  @if(count($matches['results']) == 0)
    <span>No information available</span>
  @endif
@endforeach