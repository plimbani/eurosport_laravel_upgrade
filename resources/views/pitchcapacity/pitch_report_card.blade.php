<title>Print</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
  html {
    font-family: sans-serif;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
  }
  
  table, th, td {
    border: 2px solid black;
    border-collapse: collapse;
    padding: 10px;
  }

  .mb-100 {
    margin-bottom: 100px;
  }

  .title {
    font-weight: bold;
  }

  .text-center {
    text-align: center;
  }

  .p-0 {
    padding: 0px;
  }

  .border-none {
    border: none;
  }

  .border-y-none {
    border-top: none; 
    border-bottom: none;
  }

  .bg-black {
    background-color: black;
  }

  .no-break {
    page-break-inside: avoid;
  } 
</style>

<center>
  <?php 
    $logo = ($currentLayout == "commercialisation") ? 'assets/img/easy-match-manager/emm.svg' : 'assets/img/tmplogo.png';
  ?>
  <img src="{{ asset($logo)}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
</center>
  <h4>{{ $pitchReport['pitch_number'] }} - Match Schedule</h4>

  @foreach($pitchRecord as $data)
    <table width="100%" class="mb-100 no-break">
      <tr>
        <td class="title" width="25%;">Date & Time</td>
        <td colspan="3" class="text-center">{{ Carbon\Carbon::parse( $data['match_datetime'])->format('H:i D d M Y') }}</td>
      </tr>
      <tr>
        <td class="title">Venue</td>
        <td colspan="3" class="text-center p-0">
          <table width="100%" class="border-none">
            <tr>
              <td width="33.33%;" class="text-center border-none">{{ (isset($pitchReport['venue'])) ? $pitchReport['venue']['name'] : '' }}</td>
              <td width="33.33%;" class="title text-center border-y-none">Pitch Number</td>
              <td width="33.33%;" class="text-center border-none">{{ $data['pitch']['pitch_number']}}</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="title">Match code</td>
        <td colspan="3" class="text-center">{{ $data['display_match_number'] }}</td>
      </tr>
      <tr>
        <td class="title">Referee</td>
        <td colspan="3" class="text-center">
          @if($data['referee']['last_name'] && $data['referee']['first_name'])
            {{ $data['referee']['last_name']}}, {{ $data['referee']['first_name']}}
          @endif
        </td>
      </tr>
      <tr>
        <td class="bg-black">&nbsp;</td>
        <td width="37.5%;" class="text-center title">Team A</td>
        <td width="37.5%;" class="text-center title">Team B</td>
      </tr>
      <tr>
        <td class="title">Name</td>
        <td class="text-center">
          @if($data['home_team'] == 0 && $data['home_team_name'] == '@^^@')
              @if(strpos($data['competition']['actual_name'], 'Group') !== false)
                  {{ $data['display_home_team_placeholder_name'] }}
              @elseif(strpos($data['competition']['actual_name'], 'Pos') !== false)
                  {{ 'Pos-' . $data['display_home_team_placeholder_name'] }}
              @endif
          @elseif($data['home_team'] != 0)
              {{ $data['home_team_name'] }}
          @else
              {{ $data['display_home_team_placeholder_name'] }}
          @endif
        </td>
        <td class="text-center">
          @if($data['away_team'] == 0 && $data['away_team_name'] == '@^^@')
              @if(strpos($data['competition']['actual_name'], 'Group') !== false)
                  {{ $data['display_away_team_placeholder_name'] }}
              @elseif(strpos($data['competition']['actual_name'], 'Pos') !== false)
                  {{ 'Pos-' . $data['display_away_team_placeholder_name'] }}
              @endif
          @elseif($data['away_team'] !=0)
              {{ $data['away_team_name'] }}
          @else
              {{ $data['display_away_team_placeholder_name']}}
          @endif
        </td>
      </tr>
      <tr>
        <td class="title">Score</td>
        <td class="text-center">
          {{ $data['hometeam_score'] }}
        </td>
        <td class="text-center">
          {{ $data['awayteam_score'] }}
        </td>
      </tr>
      <tr>
        <td class="title">Yellow / Red Card</td>
        <td class="text-center">
          @if($data['home_yellow_cards'] || $data['home_red_cards'])
            {{ $data['home_yellow_cards'] }} / {{ $data['home_red_cards'] }}
          @endif
        </td>
        <td class="text-center">
          @if($data['away_yellow_cards'] || $data['away_red_cards'])
            {{ $data['away_yellow_cards'] }} / {{ $data['away_red_cards'] }}
          @endif
        </td>
      </tr>
      <tr>
        <td class="title">Fair Play</td>
        <td class="text-center"></td>
        <td class="text-center"></td>
      </tr>
      <tr>
        <td class="title">Signed</td>
        <td class="text-center"></td>
        <td class="text-center"></td>
      </tr>
    </table>
  @endforeach
    