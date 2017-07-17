<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<style type="text/css">
  html {
    font-family: sans-serif;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
  }

  .tblpage{
    width: 18cm; 
    min-height: 22.7cm; 
    margin: 0cm auto;
  }
</style>

<center>
  <img  src="{{ asset('assets/img/logo-desk.svg')}}" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="100px">
</center>
  
<table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%"> 
    <h4>Match Detail</h4>
  <thead>
        <tr>
            <td align="center">Match number</td>
            <td>&nbsp;Team 1 ({{$data['home_team_name']}}) and Team 2 ({{$data['away_team_name']}})</td>
        </tr>
        <tr>
           <td align="center">Date</td>
           <td>&nbsp;{{ Carbon\Carbon::parse( $data['match_datetime'])->format('H:m D d M Y') }}</td>
        </tr>
        <tr>
             <td align="center">Pitch</td>
             <td>&nbsp;{{ $data['pitch']['pitch_number']}}</td>
        </tr> 
        <tr>
             <td align="center">Referee</td>
              <td>&nbsp;{{ $data['referee']['last_name']}},{{ $data['referee']['first_name']}}</td>
        </tr>
        <tr>
            <td align="center">Result</td>
             <td>&nbsp; {{$data['home_team_name']}} - {{$data['hometeam_score']}}<br>
                 &nbsp; {{$data['away_team_name']}} - {{$data['awayteam_score']}}</td>
        </tr> 
        <tr>
            <td align="center">Status</td>
            @if($data['match_status'] == 0)
            <td align="center"></td>
            @else
            <td align="center">&nbsp;{{ $data['match_status']}}</td>
            @endif
        </tr>
        <tr>
          <td align="center">Winner</td>
          <td>&nbsp;{{ $data['name']}}</td>
        </tr>
        <tr>
          <td align="center">Comments</td>
          <td>&nbsp;{{ $data['comments']}}</td>
        </tr>  
    </thead>
</table>