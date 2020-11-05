<!DOCTYPE html>
<html>
<head>
    <title>Match planner - {{ $pitchPlannerPrintData['tournamentData']->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ env('APP_SCHEME') . '://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css' }}" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <style>
        html,body {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <center>
      @if($tournamentLogo != null)  
        <img src="{{ $tournamentLogo }}" class="hidden-sm-down text-center" alt="Laraspace Logo" width="200px">
      @elseif(Config::get('config-variables.current_layout') == 'tmp')
        <img  src="{{ asset('assets/img/tmplogo.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
      @elseif(Config::get('config-variables.current_layout') == 'commercialisation')
        <img  src="{{ asset('assets/img/easy-match-manager/emm.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
      @endif 
    </center>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Match planner</h4>
                <hr>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @foreach($tournamentDates as $date)
                <div class="col-md-12" style="page-break-inside: avoid">
                    <div class="card w-100">
                        <div class="card-header text-white bg-primary css-date-header">
                            <span>
                                Day {{ $loop->index + 1 }}
                            </span>
                            <span class="float-right">
                                {{ $date->format('D d M Y') }}
                            </span>
                        </div>
                        @foreach($tournamentPitches as $pitchKey => $pitch)
                            <ul class="list-group list-group-flush">
                                @foreach($matches->where('pitch_id',$pitchKey)->where('match_day',$date->format('Y-m-d')) as $match)
                                    @if($loop->index == 0) 
                                        @if($matches->where('pitch_id',$pitchKey)->where('match_day',$date->format('Y-m-d'))->count() > 0)
                                            <li class="list-group-item bg-light"><strong>{{ $match['venues_name'] }} - {{ $pitch }}</strong></li>
                                        @endif
                                    @endif                                
                                    <li class="list-group-item" style="page-break-inside: avoid">
                                        <table class="w-100">
                                            <tbody class="w-100">
                                                <tr class="w-100">
                                                    <td colspan="2">
                                                        {{ $match['referre_name'] }}
                                                    </td>
                                                </tr>
                                                <tr class="w-100">
                                                    <td class="w-50">
                                                        {{ $match['match_datetime']->format('H:i') }} - {{ $match['match_endtime']->format('H:i') }}
                                                        {{$match['competation_type'] == 'Round Robin' ? '(' .$match['actual_name']. ')' : ''}}
                                                    </td>
                                                    <td class="w-50">
                                                        @if($match['hometeam_score'] !== null && $match['awayteam_score'] !== null)
                                                            <div class="text-right"><span class="font-weight-bold">Score</span>&nbsp;<span class="badge badge-primary">{{ $match['hometeam_score'] }} - {{ $match['awayteam_score'] }}</span></div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="w-100">
                                                    <td colspan="2">
                                                        {{ $match['match_name'] }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- <div class="row">
                                            <div class="col-6">
                                                <div>{{ $match['match_datetime']->format('H:i') }} - {{ $match['match_endtime']->format('H:i') }}</div>
                                            </div>
                                            <div class="col-6 text-right">
                                                @if($match['hometeam_score'] !== null && $match['awayteam_score'] !== null)
                                                    <div><span class="font-weight-bold">Score</span>&nbsp;<span class="badge badge-primary">{{ $match['hometeam_score'] }} - {{ $match['awayteam_score'] }}</span></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div>
                                                    {{ $match['match_name'] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div>
                                                    {{ $match['referre_name'] }}
                                                </div>
                                            </div>
                                        </div> -->
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                        @if($matches->where('match_day',$date->format('Y-m-d'))->count() == 0)
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">No record</li>
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
<script type="text/javascript">
    var cssDateHeader = document.getElementsByClassName("css-date-header");
    for (var i = 0; i < cssDateHeader.length; i++) {
        cssDateHeader[i].parentElement.parentElement.classList.add("mt-4");
    }        
</script>
</html>
