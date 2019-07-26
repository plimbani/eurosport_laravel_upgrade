<title>Print</title>
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
    margin: 0.35cm auto;
  }
</style>

<center>
  <?php 
    $logo = (Config::get('config-variables.current_layout') == 'tmp') ? 'assets/img/tmplogo.svg' : 'assets/img/easy-match-manager/emm.svg';
  ?>
  <img  src="{{ asset($logo)}}" alt="Laraspace Logo" width="200px">
</center>
  <h4>Match Details</h4>
  @foreach($resultData as $data)
    <table class="tblpage" border="1" cellpadding="1" cellspacing="0">
        <thead>
          <tr>
            <td width="230px">&nbsp;Match number</td>
            <td>&nbsp;{{ $data['display_match_number'] }}</td>
          </tr>
          <tr>
            <td width="230px">&nbsp;Match</td>
            <td>&nbsp;Team 1 
                @if($data['home_team'] == 0 && $data['home_team_name'] == '@^^@')
                    @if(strpos($data['competition']['actual_name'], 'Group') !== false)
                        ({{ $data['display_home_team_placeholder_name'] }})
                    @elseif(strpos($data['competition']['actual_name'], 'Pos') !== false)
                        ({{ 'Pos-' . $data['display_home_team_placeholder_name'] }})
                    @endif
                @elseif($data['home_team'] != 0)
                    ({{ $data['home_team_name'] }})
                @else
                    ({{ $data['display_home_team_placeholder_name'] }})
                @endif
                 and Team 2
                @if($data['away_team'] == 0 && $data['away_team_name'] == '@^^@')
                    @if(strpos($data['competition']['actual_name'], 'Group') !== false)
                        ({{ $data['display_away_team_placeholder_name'] }})
                    @elseif(strpos($data['competition']['actual_name'], 'Pos') !== false)
                        ({{ 'Pos-' . $data['display_away_team_placeholder_name'] }})
                    @endif
                @elseif($data['away_team'] !=0)
                    ({{ $data['away_team_name'] }})
                @else
                    ({{ $data['display_away_team_placeholder_name']}})
                @endif
            </td>
          </tr>
          <tr>
            <td width="230px">&nbsp;Date</td>
            <td>&nbsp;{{ Carbon\Carbon::parse( $data['match_datetime'])->format('H:i D d M Y') }}</td>
          </tr>
          <tr>
            <td width="230px">&nbsp;Pitch</td>
            <td>&nbsp;{{ $data['pitch']['pitch_number']}}</td>
          </tr>
          <tr>
            <td width="230px">&nbsp;Referee</td>
              @if($data['referee']['last_name'] && $data['referee']['first_name'])
                <td>&nbsp;{{ $data['referee']['last_name']}}, {{ $data['referee']['first_name']}}</td>
              @else
            <td width="230px"></td>
              @endif
          </tr>
          <tr>
            <td width="230px">&nbsp;Result</td>
            <td>&nbsp;Team 1
              @if($data['home_team'] == 0 && $data['home_team_name'] == '@^^@')
                  @if(strpos($data['competition']['actual_name'], 'Group') !== false)
                      ({{ $data['display_home_team_placeholder_name'] }})
                  @elseif(strpos($data['competition']['actual_name'], 'Pos') !== false)
                      ({{ 'Pos-' . $data['display_home_team_placeholder_name'] }})
                  @endif
              @elseif($data['home_team'] != 0)
                  ({{ $data['home_team_name'] }})
              @else
                  ({{ $data['display_home_team_placeholder_name']}})
              @endif
               - {{$data['hometeam_score']}}<br>
              &nbsp;Team 2
              @if($data['away_team'] == 0 && $data['away_team_name'] == '@^^@')
                  @if(strpos($data['competition']['actual_name'], 'Group') !== false)
                      ({{ $data['display_away_team_placeholder_name'] }})
                  @elseif(strpos($data['competition']['actual_name'], 'Pos') !== false)
                      ({{ 'Pos-' . $data['display_away_team_placeholder_name'] }})
                  @endif
              @elseif($data['away_team'] !=0)
                  ({{ $data['away_team_name'] }})
              @else
                  ({{ $data['display_away_team_placeholder_name']}})
              @endif
               - {{$data['awayteam_score']}}
            </td>
          </tr>
          <tr>
            <td width="230px">&nbsp;Remarks</td>
            <td>&nbsp;{{ $data['comments']}}</td>
          </tr>
        </thead>
    </table>
  @endforeach