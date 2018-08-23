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
	<h4>Fair play report</h4>
    <tr>
        <th align="center">TeamID</th>
        <th align="center">Team</th>
        <th align="center">Club</th>
        <th align="center">Country</th>
        <th align="center">Age category</th>
        <th align="center">Yellow cards</th>
        <th align="center">Red cards</th>
    </tr>

    <tbody>
    @foreach($data as $report)
    	<tr>
            <td align="center">{{ $report->team_id }}</td>
    		<td align="center">{{ $report->name }}</td>
    		<td align="center">{{ $report->club_name}}</td>
    		<td align="center">{{ $report->country_name}}</td>
            <td align="center">{{ $report->age_name }}</td>
            <td align="center">{{ $report->total_yellow_cards == null ? 0 : $report->total_yellow_cards }}</td>
            <td align="center">{{ $report->total_red_cards == null ? 0 : $report->total_red_cards }}</td>
    	</tr>
    @endforeach
    </tbody>
</table>
