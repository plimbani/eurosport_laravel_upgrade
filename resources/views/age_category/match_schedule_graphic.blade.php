<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <style type="text/css">
      html {
        font-family: sans-serif;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
      }
      body {
        /*font-family: Lato, sans-serif;
        color: #595959;*/
      }
      td,
      th {
        /*border-width: 0;
        border-color: #000;*/
          border: 1px solid #000;
          border-collapse: collapse;
          text-align: center;
          padding: 2px;
          line-height: 1.5;
          font-size: 12px;
      }
      table {
        border-color: #000;
        color: #595959;
      },
      th {
          padding: 5px 2px;
      }
      .row-round+.row-round {
          margin-top: 20px;
      }
      .round-img-wrapper {
          position: relative;
      }
      .round-number {
          position: absolute;
          right: 35px;
          color: #fff;
          top: 14px;
          font-weight: bold;
          font-size: 13px;
      }
      .break {
        page-break-before: always;
      }
    </style>
  </head>
  <body>
    <center>
      @if($tournamentData->tournamentLogo != null)
        <img src="{{ $tournamentData->tournamentLogo }}" class="hidden-sm-down text-center" alt="Laraspace Logo" width="200px">
      @elseif(Config::get('config-variables.current_layout') == 'tmp')
        <img  src="{{ asset('assets/img/tmplogo.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
      @elseif(Config::get('config-variables.current_layout') == 'commercialisation')
        <img  src="{{ asset('assets/img/easy-match-manager/emm.svg')}}" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px">
      @endif
      <h3 style="margin-top: 30px; margin-bottom: 30px;">Match Schedule – Template {{ $templateData['tournament_name'] }}</h3>
    </center>
    @php($colorCodes = getColorCodeOfMatches($allMatches))
    @foreach(rounds($templateData) as $roundIndex=>$round)
      @php($pageBreakClass = ($roundIndex > 0 ? "break" : ""))
      <table border="0" cellpadding="0" cellspacing="0" align="center" class="{{ $pageBreakClass }}">
        <tr>
            <td style="border:0;width: 150px;">
                <div class="round-img-wrapper">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAU4AAABmCAYAAACk23iGAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NTRBMzk5NERCNzYzMTFFOUIzNTJDMDEyQjgyRTIyQzkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTRBMzk5NEVCNzYzMTFFOUIzNTJDMDEyQjgyRTIyQzkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1NEEzOTk0QkI3NjMxMUU5QjM1MkMwMTJCODJFMjJDOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1NEEzOTk0Q0I3NjMxMUU5QjM1MkMwMTJCODJFMjJDOSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Ph+vyCEAABKsSURBVHja7J0LbBTHGcfnzncGg2P7DIbaOBjOoARIFRFXPERqIdWoEUnaUBlKUVFNG5lQtVKQWkwrgniFGqgQoYSCITGQJk2gaZzIbqExBeTWxWmwCS+/csbmaRuDcc5v36M79V1Ylt27fc3O7N33kz7Jx7Ezc/P47zezs/MhBACAmZnM2VLOjnHWwplfpjUHrvkhZ1OhGgEAiFRGcvZ9zu4qEEilhtPODeQFAABgSmI5q+TMS1AspczH2blAGQAAAJjnYEC4/IwYLssBaBYAAFgjhbNahsRSynAZx0JzAQBAkyTOek0gmELr4yw5GhvMAn0WAKiCxcfsD2IGUJQ9TLJCvwUAKvw14LVFguCMCPyWY9CsAACQ4HETTsmVWjo0MwAAelGsVITmzZt322KxeGgK4bPPPtuh4rpiaG4AALSiSHguXLjwuZ9Ha2vrBaMFc/v27Wf5ZWhsbKxRkQ4AAIBikpQITVVVVYs/BO+++24jacHMy8urC1WG6urq6wrTTIJuAACAXJbKFZfCwsKbfgWsWrWqSW/BnDVr1lWfz+eRW4YdO3bcUJD+UugOAACEY6scQYmPj+/2ayArK0vze+vjxo27zyU1pLYMCQkJ3TLz2grdAgAAKd6RIyQnT56s8+uDz263DyoVTKvVOsTRrUcBzpw5Uycz3yPQPQAAEPKeHAHhBMvj1xm3290nVzTb2tq69c7fwyEz/6PQTQAAkD09T01Nve8nTGNj422p/EtLS2+Qzn/s2LFumLYDACCHJeHEYtWqVdf8xiB5qtLg4OB9Iwrw8ssvX5Mhnkug2wBA9JIcTiSKiooa/MZBXTgx+/btk7N1ygHdBwCiD0s4caisrKz3GwsTwonBvx3BJnkAAASEPGy4uLi4wW88zAgnBnvbIJ4AAAQ5GEoM8vPzW/x0YEo4Za55vmW2aQYAAMrBJwBdl/oyNTXVfevWrccolc1v4RD7ghPOLrvdnkijUCkpKT0dHR2jw9TpTTM0PpzHCQDquB7qy5KSkn6oooepra11a6lTEE4AMDdhD+wtLCzshGp6mKKiokEZM+APYKoOAJGJrIcZfj+1Zx5MTtVtNtuA1+sdEQm6BB4nAChjEKpAHTJF0xR1bKOc/zfQ8NO0CdCtABMQw5ld7n/u6uryJiYmxhjubvr9kh5bd3d3vMPB/J5zXMeXOPMwWDZ8mMkK2sKJ31ddCOMRiER2794d89prrxmer8QsPej1xdCoi9LSUqWXzGC0WZ/m7COaawkTOftSyR0cAMzExIkTUUtLi+H5Tp48GTU3N4t+FxcXh3p7ew0vU3Z2NqqoqIiEZsUL19NoCuc+zlYG/q7lbBkMNYBhSjjLUDFtNqyAXV1d7qSkJFl7RxsaGtxTp041bJ9pTEyM1+fzKfV2sfovYqDt8VGB0wJ/46f+1E6zx94m/+BVOFYfMIOnodiM4o033qhUWrbZs2fXGVU+pP6EetrMF5TnaZqFeZNXkCsInu4DbHNA7cAfGhpqJS1Kdru9X2358CnwjAvnPsptf5pXlhKaBUnlrA+8TSDSvU1subm5V0mJUUtLyzWkU6C28vJyYmeFHjhw4JzG8tHi24JyzKTZCXfxCoIfDtlgXAIMY9My6GNjY/tIiNGyZcvqkM5RLjMyMm6TKGtSUlKvxrLFUGr746x6m3kwLgHG+a9WQdITHF0NEY6r3tPTM8DIND1on1Fo9zmCMsyh2Ql3gLcJmAyf1oHv8XgG9RCg8+fP15IWzaCtW7dOr8OXvTqUx0fZ2zxOswOO5Ywff/mnMCYBxhmphwgdOnToilb1mTJlyh2jRJO3zNCrtdxnz57VS+xHGNjuWSx5m4W8guBF7VgYlwDj/EBPIXr77bcVryG63e5uowVTaC6Xq11puU+cOHFT53J8z8B2L+Hl+w+WvM1XYEwCJqCThBAdOXLkohzx2b17dwNt0UQPdgc0yylzRUVFE6Ey3DOozWcK8p1PswNuBG8TMCFExWjjxo3VUgIUExMzyIpo8tcacVgOCQ/znAH5G+1tnqbZ+ZI4u88rzC9gPAIgnA9s+fLlbUEBam9vv8OgYAqjdjbxQgDXGpg3aaaz5G1u4BXkNmdxMB4BE5BmtCBlZ2c3sy6aQZsxY0YrhXwnI7I7cd7n5fUvmp0vUeBtvgrjETAJ39E60DMzMzvNIoSkLTU1tUuHdPBbhqMJepv8rWfP0ex8a8HbBEzKBi2DfPXq1fW8N2e6olUwcbiM4Lro9u3br2pM7yhnKQZ4m2dpdrx4zjp4hVljUL77VTTIp5wVcOZUmFdO4DqXSJquwHc5MtP6VHB9gYxr8kXyFCujsGyFMtJ2ySiPkrq+F0hjsUmE8wMNU+6GRwKd+3xDCxYsaIgWwZwwYUKrx+PpF9bDihUrLmlIFy9lpBNo6ylo+LR55rzNjoCQsiqcfMuXkQfeIPu5gjQ/lyGgRgqnX8ZNQm/hFN6onIwL52k1v83hcHwVZseOjxOPLyNVMLmbRhO+SYSqgMzMzHYNeUwi0NaHeOmfo9npRgm8zbUG5r1fhw4QyitarCHdAoaE8yhF4QyW1RlJwhkXF9evZJM4J6A3I0Uw586de1fJbx8zZkwPI8Ip9DZfotnpXqXkbeolnK4QU3OtaRcwIpz+MF4waeEMeuIRIZxWq9Wj4RAMn5lFUy24zhgQzrd4adcgmaGJSRwgHCcYZH9Aw28N0aIoUBlShsN3dAqucQam43wcIbw07FEnC9LF/9Yk8n8LRdKmxX4CyzNidfwtzrZJLHnkowjA6/WqHktdXV2tZv3dH3744b811FkM5eLjSBTLeZ+DL+pQ9zbxVqQkCmLAv0PJEYd8GWud+yU8pnDTzaMSa3wseJyh1nTVeJzhypylwLs3jccZbk3PoKPXaHmbPi2/m6s7pV6nnh7nPqQyEoXeHid+lfLXvM+7AuLJOk1hvneIrHtiL3WJjGvF/k8OQ15nYeD3GcE5kfVuJ0N1oQh8ZBrWPIvFonlT9tDQ0B2z/f6qqqov5E5tpeDqLgbXoZFB7XjeJv+Etk1IwfF1egsnLkha4G93QDjNgJjX2CkQOofIEkCTzPS3SXiCLOCQ6d3quXTCal3IprOzsw3peDK5zWZLMVsdzJo1S9fAZV1dXR0GFh9vjwyGJq9F4R+WEhNO7G3+lvd5r0m8zQKJ6Xx5GGEtV5DHMYlpKy2OidSBUU+4O0VuOE4zCcbFixdvJSUljdc73ZycnNtmqYP09HTdPeSEhISxly9fNqIOcCSKn/E+b0YKD0vWUzixt/l44G+81eD3jLRxfpg1k0IJD7EzzMA+Z2Kx2CZSnv0G5m9a4czMzGx56qmn0kikXVZWNsIs9cBN0/tIpDt9+vTUJ5544qoBztLIwN8uCcfGEOG0oYffDNqDhrchmRHsScrZd9qpwtMSTpGpzTRFfmOOGafMBGkT+0eXy5VBKsPY2Nhks1ROWlraRFJp19fXT5b4qhkN77nU6m2u5H3eoiZNvYTzx2j45BJMP0PeplKwmCyIEmE4JnKn3Y+AILVUOuDatbWsV0x2dnYjpaxx0LYBjWmsFnibf1KTiB7Cib3NdYLBZzZvE4ulBYk/xJFCqcfo0OixkrpRCJckChCAqaCR6ebNmzNYr5hPPvlkDKWsP+KsV8P1OBLFz3mff6fWg9VDOLG3mcnzNrcx1s78DfDJEuU7GmaaKvb0XMm01oEeXcdrCvNZzVReqRg3idRHgQHLCE4GbyKyPU6fz9dHKlObzTaKdeFMTEwktqTg9XpD1W2VRo/zV+jBsXTXOXtHbUJahRNfv0bgbbL8ZDC4trdWRKTwBvQsQsIp9u57uIdLcsRLDy92m6AspLcnid1EzjHYV25JfVFTUzNEMuPi4uIqVgdQfn5+Dcn0z58/Pxji66tI/Ron9jb50Se2cjZIqx7xwaLBp9O4EBMYaFu5bw4VIvG3WBwSg/2eyP/PkikULhnXFiDl73EL30o6KiHw4U5GEvs/wt+rx5tD/x97KuuRBlInAOkVc1yvt2kMM71ixEuB6xaRCZ2xATES9wx7m1d4hdnHSGdX8sql2NFwR2WmK+eEn6AnK+eVSzmvfQqnu0JxK1QpnFK/T2/hdIrcRFh95TLowet6sIVc8AHArImm1WodIv27EZkol8K4Z7+k2amWCLzNiSYUziwk/1g5p4TXGRQMp4gH6ULyTyVyIul9pmJp35NZbrnC6Qjx+/QQTqkys3zIx0shhNNLUkDKyspqWBPOnTt3fkFYN0N52lriqvO9TaqRKPCDlhoGvU2lwik2RQ41ZV+MyB0rJzb1JnUUnlPBNDpaj5XDjJQq+969e9soel+mOj5OLrt27WoLkb/alwMSEENxz/h3YnyXyDSxcEpN2aWuI3WQcaj1UDmWpYNwIomlhWg8yDiI1FmZPtJCEh8ff58V0bTb7T0UbxReDe3HTNwzobd5iLGOrkY4nUjZQb9ZIQRGSiTkPoV36Jy2UuF0GiCcnyLzvGb5GS0P7MKFC8zEKDp16lQDReFUGzxNGPeM6h7lFwTe5pQIEM5QU3YUZo20QMJj1RqcLCfEumBw7TNfZjpK4w0VEBDOYPA6sx0hZ5P6TcuWLYua6TppXnzxxTsh8ld7ChWtuGeinOUV5jACgMiHmqCkpaW10xbN5OTkexRvED6VbRaH6MU9e4TnBD9oOowpIAqQ9KxbW1uJ7umsrKy8TVs4y8vLiXrW9fX1oXYQ/FFlm9GMexbS23wfxhMAXifRh0SDrEzVfT5fN6XlCLXeJv+Gs4Fmx/kOeJtAFHNZanBfuXKlHbYjqaOqqipUiORLOnibNOKePcRpJD8mNwBEGg4jRSUmJmYQsffmkMfgm4MawcOvUt5gxducL/hBM2EcAVFIn9Qg37Nnj27rgA6HI9QTZv/8+fNvc1Pnr0jYwoULw66p6oHX6+0vKChoDZFPv8o2eoWXhpslb7MExg8AXicZQdm6dWtdGOG6S/oJd1xc3I1QZcjNzb2iNM3e3t6rixYtumixWLwyPVw1R9dhb/MaCn12g2HMAW8TAL5G8vANzlPU9AClurr6FqK8n1Lu+urhw4dvSl3rcrku5eTkdNhsth6VywJ6eJvdaPgoOSLIiYl8nLPvBv7+G2fPw9gBopxQT3u/Gjdu3EBKSspARkaGJz09fURqauqoJ598smfatGlDaWlplsTExDi73T6K875Gf52g399jtVpHh8qUm0oPctcYdRyan8srpD50dHTc4ETSV1hYiD7++OM0rnw2nfJWE6sd542PpAu+3IHPmaW2d1Pobc6BMQMAmg5jUWU9PT2DfoMZGhqi8YBK7TbHPF4aeC2aapz6v/MKcxzGCwA8cACNEpPKysozfkoMDAw0Gyiaat8Swt7ml7x0dtHsGDPB2wQASSYYISYLFiy45afMli1bag0STrXx6n8i8DZTaXaMEl5h/gnjBAAe4SAie5Rbu58R5s6de42waB5U2QbCSBRUvc35iLHNt2Bg0WZr1qxhRTfNUmd4TTbdCIGUenqFo+zNAocCAOgyrFl0cbvdKCEhwQzVhQ9jeYWWcOJ30C8hdVsCAACIMOGMi4tD/f39rFdVMBLFNSMyE9t3tZ4nmviA3gXQfQEgLDicTLHeia5fv/7apk2bqAZCJCiaK5D6NxGxRp3h7JuBz28ZJZpIwtvkb7N4DsYDAMjmdaT/ul0PzbXN8ePH30Vk1iNf1+FGxUwkineQ9jgfABDNvIdMFrZCCp/P50VkRPMDjXXMVNyzKQHlDhZmIYwBAFDFIT2Fpqys7BQN4XzmmWf+Q0A0j+hQv8+z5G3yG7sGwcMhAGBi2o4Py4iQLUiv61S3Z3UWYt28zZeg3wOAZhbrOF33GSmaO3fubNJZNH+kU50yEfcs6FXi/U/BULP49ap5SH28DwAAHoA3QLZoTcTlcrU4nc4Mw4TBouuEcxJnXTqlhc/MmB34G6+VLqXVsI+j4R338LYGGBjDZhTbtm0zQ31QjXtmCSj2n8ExAAC2MWozvM7eJin+ElgKoQLeAH8CDS+2ToOuCQDEwYdSPKbmwpUrV/refPNNq81mI1a46upqX6CMWnAj9UfEyQFHxvwNdCUAiD6OaZmqTpo06XxFRUWHx+PR/MS9v7+/s7S09CoaXovVMn2G6LcAABhCH9Jx7c9qtV4uKCgoq6qqOtnZ2VnX3d19o6en5yb3d21lZWV5Xl4ePpy8Dum73tgPzQgAgNGM0VtADbL+QNkBAACogcMPXzKBYF5G6sL3AgAAEKUIGRjXCMnb/rMfmgUAADOAwwFXckbqwI1wYomPlrRDMwAAYFZGcvYCZx0ExfIOZ7mBvAAAACISfMjxEjS8FahZgUA2B67B12ZCNSrDwsLR/AAAAGbifwIMALl2imOcz8VCAAAAAElFTkSuQmCC" style="width: 100%;">
                    <span class="round-number">{{ $roundIndex + 1 }}</span>
                </div>
            </td>
        </tr>
      </table>
      <table border="1" align="center" cellpadding="0" cellspacing="5" style="margin-top: 20px;"> 
        <tr>
          <td style="border: 0;padding: 0;">
            <div class="grid-round-wrapper">
              <div class="grid-round">
                <div class="col-round">
                  <div class="round-details-wrapper">
                    @foreach(getAllRoundGroups($roundIndex, $round['match_type']) as $groupIndex=>$group)
                      <div class="row-round">
                        <table cellpadding="0" cellspacing="0">

                          <!-- Round 2 - PM -->
                          @if(getGroupType($group) == 'PM')
                            @foreach($group['groups']['match'] as $matchIndex=>$match)
                              <tr>
                                <td style="border: 0;padding: 0;">
                                  <table cellpadding="0" cellspacing="5">
                                    <tr>
                                      <td style="width: 150px; background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}">
                                        <span style="font-weight: bold;">Match {{ getMatchNumber($match['display_match_number']) }}</span>
                                        @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                                        @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                                          <br/>{{ $matchDetail['venue_name'] }}
                                          <br/>{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}
                                        @endif
                                      </td>

                                      <td style="padding: 0;border:0;">
                                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                          <tr>
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                                              <td style="width: 100px; font-weight: bold;">
                                                {{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}
                                              </td>
                                            @endif
                                            
                                            @if(!checkIfWinnerLoserMatch($match['match_number']))
                                              <td style="width: 100px;">
                                                {{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                                                  {{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                                                  {{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            @endforeach
                          @endif

                          <!-- round 2 - RR -->
                          @if(getGroupType($group) == 'RR')

                            <tr>
                              <td style="border: 0;padding: 0;">
                                <table cellpadding="0" cellspacing="5">
                                  <tr>
                                    <!-- Column 1 -->
                                    @if($roundIndex == 0)
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">{{ "Group " . getGroupName($group['groups']['group_name']) }}
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            <tr>
                                              <td style="width: 100px;">
                                                {{ getRoundRobinAssignedTeam($assignedTeams, getGroupName($group['groups']['group_name']), $teamIndex) }}
                                              </td>
                                            </tr>
                                          @endfor
                                        </table>
                                      </td>
                                    @endif

                                    @if($roundIndex >= 1)
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <!-- Column 2 -->
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              {{ "Group " . getGroupName($group['groups']['group_name']) }}
                                            </td>
                                          </tr>
                                          @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                                            <tr>
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
                                                {{ $team['name'] }}
                                              </td>
                                            </tr>
                                          @endforeach
                                        </table>
                                      </td>
                                    @endif

                                    @if($roundIndex >= 1)
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <!-- Column 3 -->
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              &nbsp;
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            <tr>
                                              <td style="width: 100px;">
                                                {{ ($teamIndex) . getGroupName($group['groups']['group_name']) }}
                                              </td>
                                            </tr>
                                          @endfor
                                        </table>
                                      </td>
                                    @endif

                                    <!-- Column 4 -->
                                    @if(isAnyRankingInPosition($templateData, getGroupName($group['groups']['group_name']), $group['group_count']))
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              Ranking
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                                              <tr>
                                                <td style="width: 100px;">
                                                  {{ checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])) }}
                                                </td>
                                              </tr>
                                            @endif
                                          @endfor
                                        </table>
                                      </td>
                                    @endif
                                  </tr>
                                </table>
                              </td>
                            </tr>

                          @endif
                        </table>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>
    @endforeach
    @foreach(getDivisionRounds($templateData) as $roundIndex=>$round)
      <table border="0" cellpadding="0" cellspacing="0" align="center" class="break">
        <tr>
            <td style="border:0;width: 150px;">
                <div class="round-img-wrapper">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAU4AAABmCAYAAACk23iGAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NTRBMzk5NERCNzYzMTFFOUIzNTJDMDEyQjgyRTIyQzkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTRBMzk5NEVCNzYzMTFFOUIzNTJDMDEyQjgyRTIyQzkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1NEEzOTk0QkI3NjMxMUU5QjM1MkMwMTJCODJFMjJDOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1NEEzOTk0Q0I3NjMxMUU5QjM1MkMwMTJCODJFMjJDOSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Ph+vyCEAABKsSURBVHja7J0LbBTH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" style="width: 100%;">
                    <span class="round-number">{{ getMainNoOfRoundCount($templateData) + $roundIndex + 1 }}</span>
                </div>
            </td>
        </tr>
      </table>
      @foreach($round['match_type'] as $groupIndex=>$group)
        <table border="1" align="center" cellpadding="0" cellspacing="5" style="margin-top: 20px;"> 
          <tr>
            <td style="border: 0;padding: 0;">
              <div class="grid-round-wrapper">
                <div class="grid-round">
                  <div class="col-round">
                    <div class="round-details-wrapper">
                      <div class="row-round">
                        <table cellpadding="0" cellspacing="0">
                          <!-- Round 2 - PM -->
                          @if(getGroupType($group) == 'PM')
                            @foreach($group['groups']['match'] as $matchIndex=>$match)
                              <tr>
                                <td style="border: 0;padding: 0;">
                                  <table cellpadding="0" cellspacing="5">
                                    <tr>
                                      <td style="width: 150px; background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}">
                                        <span style="font-weight: bold;">Match {{ getMatchNumber($match['display_match_number']) }}</span>
                                        @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                                        @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                                          <br/>{{ $matchDetail['venue_name'] }}
                                          <br/>{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}
                                        @endif
                                      </td>
                                      <td style="padding: 0;border:0;">
                                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                          <tr>
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                                              <td style="width: 100px;font-weight: bold;">
                                                {{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}
                                              </td>
                                            @endif

                                            @if(!checkIfWinnerLoserMatch($match['match_number']))
                                              <td style="width: 100px;">
                                                {{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                                                {{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}
                                              </td>
                                            @endif

                                            @if(checkIfWinnerLoserMatch($match['match_number']))
                                              @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                                              <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                                                {{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                              </td>
                                            @endif
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            @endforeach
                          @endif

                          <!-- round 2 - RR -->
                          @if(getGroupType($group) == 'RR')
                            <tr>
                              <td style="border: 0;padding: 0;">
                                <table cellpadding="0" cellspacing="5">
                                  <tr>
                                    <td style="border: 0;padding: 0;">
                                      <table cellpadding="0" cellspacing="0">
                                        <!-- Column 1 -->
                                        <tr>
                                          <td style="font-weight: bold;width: 100px;">
                                            {{ "Group " . getGroupName($group['groups']['group_name']) }}
                                          </td>
                                        </tr>
                                        @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                                          <tr>
                                            <td style="width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
                                                {{ $team['name'] }}
                                            </td>
                                          </tr>
                                        @endforeach
                                      </table>
                                    </td>

                                    <td style="border: 0;padding: 0;">
                                      <table cellpadding="0" cellspacing="0">
                                        <!-- Column 2 -->
                                        <tr>
                                          <td style="font-weight: bold;width: 100px;">
                                            &nbsp;
                                          </td>
                                        </tr>
                                        @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                          <tr>
                                            <td style="width: 100px;">
                                              {{ ($teamIndex) . getGroupName($group['groups']['group_name']) }}
                                            </td>
                                          </tr>
                                        @endfor
                                      </table>
                                    </td>

                                    <!-- Column 3 -->
                                    @if(isAnyRankingInPosition($templateData, getGroupName($group['groups']['group_name']), $group['group_count']))
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td style="font-weight: bold;width: 100px;">
                                              Ranking
                                            </td>
                                          </tr>
                                          @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                            @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                                              <tr>
                                                <td style="width: 100px;">
                                                  {{ checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])) }}
                                                </td>
                                              </tr>
                                            @endif
                                          @endfor
                                        </table>
                                      </td>
                                    @endif
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          @endif
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        </table>
      @endforeach
    @endforeach
  </body>
</html>