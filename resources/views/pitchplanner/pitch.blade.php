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
</style>

<center>
  <img  src="{{ asset('assets/img/logo-desk.svg')}}" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="100px">
</center>
  
<table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%"> 
    <h4>Match Details</h4>
  <thead>
        <tr>
            <td align="left">&nbsp;Match number</td>
            <td>&nbsp;{{$data['match_number']}}</td>
        </tr>
        <tr>
           <td align="left">&nbsp;Date</td>
           <td>&nbsp;{{ Carbon\Carbon::parse( $data['match_datetime'])->format('H:i D d M Y') }}</td>
        </tr>
        <tr>
             <td align="left">&nbsp;Pitch</td>
             <td>&nbsp;{{ $data['pitch']['pitch_number']}}</td>
        </tr> 
        <tr>
              <td align="left">&nbsp;Referee</td>
              @if($data['referee']['last_name'] && $data['referee']['first_name'])
              <td>&nbsp;{{ $data['referee']['last_name']}},{{ $data['referee']['first_name']}}</td>
            @else
            <td align="left"></td>
            @endif
        </tr>
        <tr>
            <td align="left">&nbsp;Result</td>
             <td>&nbsp;{{$data['home_team_name']}} - {{$data['hometeam_score']}}<br>
                 &nbsp;{{$data['away_team_name']}} - {{$data['awayteam_score']}}</td>
        </tr> 
        <tr>
            <td align="left">&nbsp;Status</td>
            @if($data['match_status'] == '0')
            <td align="left"></td>
            @else
            <td align="left">&nbsp;{{ $data['match_status'] }}</td>
            @endif
        </tr>
        <tr>
          <td align="left">&nbsp;Winner</td>
          <td>&nbsp;{{ $data['name']}}</td>
        </tr>
        <tr>
          <td align="left">&nbsp;Comments</td>
          <td>&nbsp;{{ $data['comments']}}</td>
        </tr>  
    </thead>
</table>
