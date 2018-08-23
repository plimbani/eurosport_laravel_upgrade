<!DOCTYPE html>
<html>
<head>
    <title>Pitch planner - {{ $pitchPlannerPrintData['tournamentData']->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <style>
        html,body {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <center>
      <img src="{{ asset('assets/img/logo-desk.svg')}}" alt="Euro-Sportring Logo" width="100px" height="50px"/>
    </center>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Pitch planner</h4>
                <hr>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @foreach($pitchPlannerPrintData['matches'] as $match)
                @if($match->match_datetime)
                    <div class="col-md-12" style="page-break-inside: avoid">
                        <div class="card">
                            @if (!in_array(Carbon\Carbon::parse($match->match_datetime)->format('Y-m-d'), $initialDate))
                                @php array_push($initialDate,Carbon\Carbon::parse($match->match_datetime)->format('Y-m-d')) @endphp
                                <div class="card-header text-white bg-primary css-date-header">
                                    <span>
                                        Day {{ 1 + array_search(Carbon\Carbon::parse($match->match_datetime)->format('Y-m-d'), $initialDate) }}
                                    </span>
                                    <span class="float-right">
                                        {{ Carbon\Carbon::parse($match->match_datetime)->format('D d M Y') }}
                                    </span>
                                </div>
                            @endif

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-6 d-print-inline-flex float-left">
                                            <div>{{ Carbon\Carbon::parse($match->match_datetime)->format('H:i') }} - {{ Carbon\Carbon::parse($match->match_datetime)->format('H:i') }}</div>
                                        </div>
                                        <div class="col-6 d-print-inline-flex float-right text-right">
                                            <div><span class="font-weight-bold">Score</span>&nbsp;<span class="badge badge-primary">{{ $match->hometeam_score }} - {{ $match->awayteam_score }}</span></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div>
                                                {{ str_replace('@AWAY', $match->away_team_name, str_replace('@HOME', $match->home_team_name, $match->display_match_number)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div>
                                                {{ $match->first_name }} {{ $match->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
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
