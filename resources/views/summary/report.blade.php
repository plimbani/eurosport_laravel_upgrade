<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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

@if($tournamentData->tournamentLogo)
<center>
<img src="{{ $tournamentData->tournamentLogo }}" height="100">
</center>
@endif

<table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%" style="font-size: 70%">
	<h4>Reports</h4>
            <tr>
                <th align="center">Date and time</th>
                <th align="center">Age category</th>
                <th align="center">Location</th>
                <th align="center">Pitch</th>
                <th align="center">Referee</th>
                <!--<th align="center">Game</th>-->
                <th align="center">Team</th>
                <th align="center">Team</th>
            </tr>

    <tbody>
    @foreach($data as $report)
    	<tr>
    		<td align="center">{{ Carbon\Carbon::parse($report->match_datetime)->format('H:i D d M Y') }}</td>
    		<td align="center">{{ $report->group_name }}</td>
    		<td align="center">{{ $report->venue_name}}</td>
    		<td align="center">{{ $report->pitch_number}}</td>
    		@if( $report->referee_last_name && $report->referee_first_name)
    		<td align="center">{{ $report->referee_last_name}}, {{ $report->referee_first_name}}</td>
    		@else
    		<td align="center"></td>
    		@endif
    		<!--<td align="center">{{ $report->full_game }}</td>-->
            <td align="right">
               <span class="text-center">{{ ($report->homeTeam == '0' && $report->homeTeamName == '@^^@') ? 'Group-'. $report->homePlaceholder : $report->homeTeam }}</span>
               <img src="{{ $report->HomeFlagLogo }}" width="20">&nbsp;
            </td>
            <td align="left">
            &nbsp;<img src="{{ $report->AwayFlagLogo }}" width="20">
            <span class="text-center">{{ ($report->awayTeam == '0' && $report->awayTeamName == '@^^@') ? 'Group-'. $report->awayPlaceholder : $report->awayTeam }}</span>
            </td>
    	</tr>
    @endforeach
    </tbody>
</table>
