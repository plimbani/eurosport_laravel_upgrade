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
	<h4>Reports</h4>
        <tr>
            <th align="center">Date and Time</th>
            <th align="center">Age category</th>
            <th align="center">Location</th>
            <th align="center">Pitch</th>
            <th align="center">Refree</th>
            <th align="center">Game</th>
        </tr>
    <tbody>
    @foreach($data as $report)
    	<tr>
    		<td align="center">{{ Carbon\Carbon::parse($report->match_datetime)->format('H:m D d M Y') }}</td>
    		<td align="center">{{ $report->group_name }}</td>
    		<td align="center">{{ $report->venue_name}}</td>
    		<td align="center">{{ $report->pitch_number}}</td>
    		@if( $report->referee_last_name && $report->referee_first_name)
    		<td align="center">{{ $report->referee_last_name}}, {{ $report->referee_first_name}}</td>
    		@else
    		<td align="center"></td>
    		@endif
    		<td align="center">{{ $report->full_game }}</td> 
    	</tr>
    @endforeach	
    </tbody>
</table>