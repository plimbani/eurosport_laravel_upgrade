<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
	html {
		font-family: sans-serif;
		-webkit-text-size-adjust: 100%;
		-ms-text-size-adjust: 100%;

	}
	.tblpage{
		width: 100%;
		min-height: 22.7cm;
		margin: 0cm auto;
	}
  .flex-container {
    display: -webkit-box; /* wkhtmltopdf uses this one */
    display: flex;
    box-align: center; /* As specified */
    -moz-box-align: center; /* Mozilla */
    
  }
</style>
<center>
@if($tournamentData->tournamentLogo != null)
    <?php
        $arrContextOptions=[
                      "ssl"=>[
                          "verify_peer"=>false,
                          "verify_peer_name"=>false,
                      ],
                  ];
        $type = pathinfo($tournamentData->tournamentLogo, PATHINFO_EXTENSION);
        $tournamentLogoData = file_get_contents($tournamentData->tournamentLogo, false, stream_context_create($arrContextOptions));
        $tournamentLogoBase64Data = base64_encode($tournamentLogoData);
        $imageData = 'data:image/' . $type . ';base64,' . $tournamentLogoBase64Data;
    ?>
    <img src="{{ $imageData }}" class="hidden-sm-down text-center" alt="Laraspace Logo" width="200px">
  @elseif(Config::get('config-variables.current_layout') == 'tmp')
    <img  src="{{ asset('assets/img/tmplogo.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
  @elseif(Config::get('config-variables.current_layout') == 'commercialisation')
    <img  src="{{ asset('assets/img/easy-match-manager/emm.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
  @endif 
</center>

<table class="tblpage" border="1" cellpadding="1" cellspacing="0" width="100%" style="font-size: 70%">
	<h4>Reports</h4>
            <tr>
                <th align="center">Date and time</th>
                <th align="center">Age category</th>
                <th align="center">Venue</th>
                <th align="center">Pitch</th>
                <th align="center">Referee</th>
                <th align="center">Match Code</th>
                <th align="center">Team</th>
                <th align="center">Team</th>
                <th align="center">Placing</th>
            </tr>

    <tbody>
    @foreach($data as $report)
    	<tr>
            @if($report->match_datetime != null)
    		    <td align="center">{{ Carbon\Carbon::parse($report->match_datetime)->format('H:i D d M Y') }}</td>
            @else
                <td align="center"></td>
            @endif

    		<td align="center">{{ $report->group_name }}</td>
    		<td align="center">{{ $report->venue_name}}</td>
    		<td align="center">{{ $report->pitch_number}}</td>
    		@if( $report->referee_last_name && $report->referee_first_name)
    		<td align="center">{{ $report->referee_last_name}}, {{ $report->referee_first_name}}</td>
    		@else
    		<td align="center"></td>
    		@endif
    		<td>{{ str_replace('@HOME',$report->displayHomeTeamPlaceholder,str_replace('@AWAY',$report->displayAwayTeamPlaceholder,$report->displayMatchNumber)) }}</td>
          <td align="right" width="180px;">
            <table>
              <tr>
                <td align="right">
                @if($report->homeTeam == '0')
                    @if((strpos($report->displayMatchNumber, 'wrs') != false) || (strpos($report->displayMatchNumber, 'lrs') != false)) 
                      <?php
                          if(strpos($report->displayHomeTeamPlaceholder, '#')  !== false ){
                            $homeTeam = $report->displayHomeTeamPlaceholder;
                          } else {
                            if(strpos($report->displayMatchNumber, 'wrs') != false ){
                              $matchPrec = 'wrs.'; 
                            } if(strpos($report->displayMatchNumber, 'lrs') != false){
                              $matchPrec = 'lrs.'; 
                            }
                            $homeTeam = $matchPrec.$report->displayHomeTeamPlaceholder;
                          }
                        
                          $homeTeamDisplay =  $homeTeam;
                      ?>
                    @else
                      <?php $homeTeamDisplay = $report->displayHomeTeamPlaceholder ?>
                    @endif 
                      
                    <?php if(strpos($report->competition_actual_name, 'Pos') !== false && strpos($report->displayHomeTeamPlaceholder, "." ) == false)
                      $homeTeamDisplay = 'Pos-' . $report->displayHomeTeamPlaceholder; ?>  
                  @else
                    <?php  $homeTeamDisplay = $report->HomeTeam; ?>  
                  @endif

                  <span style="font-size: 9px;"><?php echo $homeTeamDisplay; ?></span>
                </td>
                <td>
                  <img src="{{ $report->HomeFlagLogo }}" width="20">
                </td>
              </tr>
            </table>
          </td>
          <td align="left" width="180px;">
            <table>
                <tr>
                  <td>
                    <img src="{{ $report->AwayFlagLogo }}" width="20">
                  </td>
                  <td align="left">
                    @if($report->awayTeam == '0')        
                      @if((strpos($report->displayMatchNumber, 'wrs') != false) || (strpos($report->displayMatchNumber, 'lrs') != false)) 
                        <?php
                            if(strpos($report->displayAwayTeamPlaceholder, '#')  !== false ){
                              $awayTeam = $report->displayAwayTeamPlaceholder;
                            } else {
                              if(strpos($report->displayMatchNumber, 'wrs') != false ){
                                $matchPrec = 'wrs.'; 
                              } if(strpos($report->displayMatchNumber, 'lrs') != false){
                                $matchPrec = 'lrs.'; 
                              }
                              $awayTeam = $matchPrec.$report->displayAwayTeamPlaceholder;
                            }
                            
                            $awayTeamDisplay =  $awayTeam;
                        ?>  
                      @else
                        <?php $awayTeamDisplay = $report->displayAwayTeamPlaceholder ?>
                      @endif
                        <?php if(strpos($report->competition_actual_name, 'Pos') !== false && strpos($report->displayAwayTeamPlaceholder, "." ) == false)
                            $awayTeamDisplay = 'Pos-' . $report->displayAwayTeamPlaceholder; ?>
                    @else
                      <?php  $awayTeamDisplay =$report->AwayTeam; ?>
                    @endif
                    <span style="font-size: 9px;"><?php echo $awayTeamDisplay; ?></span>
                  </td>
                </tr>
            </table>
          </td>
            <td align="center">
                @if($report->position != null)
                    {{$report->position}}
                @else
                    N/A
                @endif
            </td>
    	</tr>
    @endforeach
    </tbody>
</table>
