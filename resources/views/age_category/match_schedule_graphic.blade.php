<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Match Schedule – Template {{ $templateData['tournament_name'] }}</title>
    <style type="text/css">
      /* http://meyerweb.com/eric/tools/css/reset/
         v2.0-modified | 20110126
         License: none (public domain)
      */

      html, body, div, span, applet, object, iframe,
      h1, h2, h3, h4, h5, h6, p, blockquote, pre,
      a, abbr, acronym, address, big, cite, code,
      del, dfn, em, img, ins, kbd, q, s, samp,
      small, strike, strong, sub, sup, tt, var,
      b, u, i, center,
      dl, dt, dd, ol, ul, li,
      fieldset, form, label, legend,
      table, caption, tbody, tfoot, thead, tr, th, td,
      article, aside, canvas, details, embed,
      figure, figcaption, footer, header, hgroup,
      menu, nav, output, ruby, section, summary,
      time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
      }

      /* make sure to set some focus styles for accessibility */
      :focus {
          outline: 0;
      }

      /* HTML5 display-role reset for older browsers */
      article, aside, details, figcaption, figure,
      footer, header, hgroup, menu, nav, section {
        display: block;
      }

      body {
        line-height: 1;
      }

      ol, ul {
        list-style: none;
      }

      blockquote, q {
        quotes: none;
      }

      blockquote:before, blockquote:after,
      q:before, q:after {
        content: '';
        content: none;
      }

      /*table {
        border-collapse: collapse;
        border-spacing: 0;
      }*/

      input[type=search]::-webkit-search-cancel-button,
      input[type=search]::-webkit-search-decoration,
      input[type=search]::-webkit-search-results-button,
      input[type=search]::-webkit-search-results-decoration {
          -webkit-appearance: none;
          -moz-appearance: none;
      }

      input[type=search] {
          -webkit-appearance: none;
          -moz-appearance: none;
          -webkit-box-sizing: content-box;
          -moz-box-sizing: content-box;
          box-sizing: content-box;
      }

      textarea {
          overflow: auto;
          vertical-align: top;
          resize: vertical;
      }

      /**
       * Correct `inline-block` display not defined in IE 6/7/8/9 and Firefox 3.
       */

      audio,
      canvas,
      video {
          display: inline-block;
          *display: inline;
          *zoom: 1;
          max-width: 100%;
      }

      /**
       * Prevent modern browsers from displaying `audio` without controls.
       * Remove excess height in iOS 5 devices.
       */

      audio:not([controls]) {
          display: none;
          height: 0;
      }

      /**
       * Address styling not present in IE 7/8/9, Firefox 3, and Safari 4.
       * Known issue: no IE 6 support.
       */

      [hidden] {
          display: none;
      }

      /**
       * 1. Correct text resizing oddly in IE 6/7 when body `font-size` is set using
       *    `em` units.
       * 2. Prevent iOS text size adjust after orientation change, without disabling
       *    user zoom.
       */

      html {
          font-size: 100%; /* 1 */
          -webkit-text-size-adjust: 100%; /* 2 */
          -ms-text-size-adjust: 100%; /* 2 */
      }

      /**
       * Address `outline` inconsistency between Chrome and other browsers.
       */

      a:focus {
          outline: thin dotted;
      }

      /**
       * Improve readability when focused and also mouse hovered in all browsers.
       */

      a:active,
      a:hover {
          outline: 0;
      }

      /**
       * 1. Remove border when inside `a` element in IE 6/7/8/9 and Firefox 3.
       * 2. Improve image quality when scaled in IE 7.
       */

      img {
          border: 0; /* 1 */
          -ms-interpolation-mode: bicubic; /* 2 */
      }

      /**
       * Address margin not present in IE 6/7/8/9, Safari 5, and Opera 11.
       */

      figure {
          margin: 0;
      }

      /**
       * Correct margin displayed oddly in IE 6/7.
       */

      form {
          margin: 0;
      }

      /**
       * Define consistent border, margin, and padding.
       */

      fieldset {
          border: 1px solid #c0c0c0;
          margin: 0 2px;
          padding: 0.35em 0.625em 0.75em;
      }

      /**
       * 1. Correct color not being inherited in IE 6/7/8/9.
       * 2. Correct text not wrapping in Firefox 3.
       * 3. Correct alignment displayed oddly in IE 6/7.
       */

      legend {
          border: 0; /* 1 */
          padding: 0;
          white-space: normal; /* 2 */
          *margin-left: -7px; /* 3 */
      }

      /**
       * 1. Correct font size not being inherited in all browsers.
       * 2. Address margins set differently in IE 6/7, Firefox 3+, Safari 5,
       *    and Chrome.
       * 3. Improve appearance and consistency in all browsers.
       */

      button,
      input,
      select,
      textarea {
          font-size: 100%; /* 1 */
          margin: 0; /* 2 */
          vertical-align: baseline; /* 3 */
          *vertical-align: middle; /* 3 */
      }

      /**
       * Address Firefox 3+ setting `line-height` on `input` using `!important` in
       * the UA stylesheet.
       */

      button,
      input {
          line-height: normal;
      }

      /**
       * Address inconsistent `text-transform` inheritance for `button` and `select`.
       * All other form control elements do not inherit `text-transform` values.
       * Correct `button` style inheritance in Chrome, Safari 5+, and IE 6+.
       * Correct `select` style inheritance in Firefox 4+ and Opera.
       */

      button,
      select {
          text-transform: none;
      }

      /**
       * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
       *    and `video` controls.
       * 2. Correct inability to style clickable `input` types in iOS.
       * 3. Improve usability and consistency of cursor style between image-type
       *    `input` and others.
       * 4. Remove inner spacing in IE 7 without affecting normal text inputs.
       *    Known issue: inner spacing remains in IE 6.
       */

      button,
      html input[type="button"], /* 1 */
      input[type="reset"],
      input[type="submit"] {
          -webkit-appearance: button; /* 2 */
          cursor: pointer; /* 3 */
          *overflow: visible;  /* 4 */
      }

      /**
       * Re-set default cursor for disabled elements.
       */

      button[disabled],
      html input[disabled] {
          cursor: default;
      }

      /**
       * 1. Address box sizing set to content-box in IE 8/9.
       * 2. Remove excess padding in IE 8/9.
       * 3. Remove excess padding in IE 7.
       *    Known issue: excess padding remains in IE 6.
       */

      input[type="checkbox"],
      input[type="radio"] {
          box-sizing: border-box; /* 1 */
          padding: 0; /* 2 */
          *height: 13px; /* 3 */
          *width: 13px; /* 3 */
      }

      /**
       * 1. Address `appearance` set to `searchfield` in Safari 5 and Chrome.
       * 2. Address `box-sizing` set to `border-box` in Safari 5 and Chrome
       *    (include `-moz` to future-proof).
       */

      input[type="search"] {
          -webkit-appearance: textfield; /* 1 */
          -moz-box-sizing: content-box;
          -webkit-box-sizing: content-box; /* 2 */
          box-sizing: content-box;
      }

      /**
       * Remove inner padding and search cancel button in Safari 5 and Chrome
       * on OS X.
       */

      input[type="search"]::-webkit-search-cancel-button,
      input[type="search"]::-webkit-search-decoration {
          -webkit-appearance: none;
      }

      /**
       * Remove inner padding and border in Firefox 3+.
       */

      button::-moz-focus-inner,
      input::-moz-focus-inner {
          border: 0;
          padding: 0;
      }

      /**
       * 1. Remove default vertical scrollbar in IE 6/7/8/9.
       * 2. Improve readability and alignment in all browsers.
       */

      textarea {
          overflow: auto; /* 1 */
          vertical-align: top; /* 2 */
      }

      /**
       * Remove most spacing between table cells.
       */

      /*table {
          border-collapse: collapse;
          border-spacing: 0;
      }*/

      html,
      button,
      input,
      select,
      textarea {
          color: #222;
      }


      ::-moz-selection {
          background: #b3d4fc;
          text-shadow: none;
      }

      ::selection {
          background: #b3d4fc;
          text-shadow: none;
      }

      img {
          vertical-align: middle;
      }

      fieldset {
          border: 0;
          margin: 0;
          padding: 0;
      }

      textarea {
          resize: vertical;
      }

      .chromeframe {
          margin: 0.2em 0;
          background: #ccc;
          color: #000;
          padding: 0.2em 0;
      }
    </style>
    <style type="text/css">
      * {
        box-sizing: border-box;
      }

      html,body {
        font-family: sans-serif;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        font-size: 16px;
        width: 100%;
      }

      table tr td,
      table tbody tr td {
        border: 1px solid #ccc;
        border-collapse: collapse;
        padding: 2px;
        line-height: 1.5;
        font-size: 10px;
        page-break-inside: avoid;
        page-break-before: avoid;
      }

      table tr td > table tr td,
      table tbody tr td > table tbody tr td {
        border: 1px solid #ededed;
        border-collapse: collapse;
        font-size: 10px;
      }

      /*table tr td  table tr,
      table tbody tr td  table tbody tr {
        page-break-inside: avoid;
        page-break-before: avoid;
      }*/

      .row-round+.row-round {
          margin-top: 20px;
      }
      .row-round {
        page-break-inside: avoid;
        page-break-before: avoid;
      }
      .round-img-wrapper {
          position: relative;
          display: inline-block;
      }
      .round-number {
          position: absolute;
          right: 22px;
          color: #fff;
          top: 8px;
          font-weight: bold;
          font-size: 10px;
      }
      .break {
        page-break-before: always;
      }
    </style>
  </head>
  <body>
    <table cellpadding="0" cellspacing="5" style="width: 100%; border-color: transparent; font-size: 14px">
      <tbody>
        <tr>
          <td style="vertical-align: middle; border-color: transparent; text-align: left; min-width: 150px;">
            @if($tournamentData->tournamentLogo != null)
              <img src="{{ $tournamentData->tournamentLogo }}" alt="Laraspace Logo" height="20px">
            @elseif(Config::get('config-variables.current_layout') == 'tmp')
              <img  src="{{ asset('assets/img/tmplogo.svg')}}" alt="Laraspace Logo" height="20px">
            @elseif(Config::get('config-variables.current_layout') == 'commercialisation')
              <img  src="{{ asset('assets/img/easy-match-manager/emm.svg')}}" alt="Laraspace Logo"  height="20px">
            @endif
          </td>
          <td style="vertical-align: middle; border-color: transparent; text-align: center; width: 100%">
            <h4>Match Schedule – Template {{ $templateData['tournament_name'] }}</h4>
          </td>
          <td style="vertical-align: middle; border-color: transparent; text-align: right; min-width: 150px;">
            {{ $date }}
          </td>
        </tr>
      </tbody>
    </table>
    @php($colorCodes = getColorCodeOfMatches($allMatches))
    @php($roundCount=0)
    @php($istableEnd=1)

    <!-- <table cellpadding="10" cellspacing="10">
      <tbody>
        <tr> -->
          @foreach(rounds($templateData) as $roundIndex=>$round)
            @if($roundCount % 4 === 0)
              <table class="{{ $roundCount !== 0 ? 'break' : '' }}" cellpadding="10" cellspacing="10">
                <tbody>
                  <tr>
              @php($istableEnd=0)
            @endif
            <td style="vertical-align: top;">
              <table border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%">
                <tbody>
                  <tr>
                      <td style="border:0 solid transparent; width: 100px; text-align: center;" align="center">
                          <div class="round-img-wrapper">
                              <img src="{{ asset('assets/img/img-round.png') }}" style="width: 100px;">
                              <span class="round-number">{{ $roundIndex + 1 }}</span>
                          </div>
                      </td>
                  </tr>
                </tbody>
              </table>
              <table align="center" cellpadding="0" cellspacing="5" style="width: 100%; margin-top: 20px;"> 
                <tbody>
                  <tr>
                    <td style="border: 0;padding: 0">
                      <div class="round-details-wrapper">
                        @foreach(getAllRoundGroups($roundIndex, $round['match_type']) as $groupIndex=>$group)
                          <div class="row-round">
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                              <tbody>
                                <!-- Round 2 - PM -->
                                @if(getGroupType($group) == 'PM')
                                  @foreach($group['groups']['match'] as $matchIndex=>$match)
                                    <tr>
                                      <td style="border: 0;padding: 0">
                                        <table cellpadding="0" cellspacing="5">
                                          <tr>
                                            <td style="min-width: 150px; background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}">
                                              @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                                              <span style="font-weight: bold;">Match {{ getMatchNumber($match['display_match_number']) . ($matchDetail && $matchDetail['is_scheduled'] === 1 ? " (" . date("d/m/Y", strtotime($matchDetail['match_datetime'])) . ")" : "") }}</span>
                                              @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                                                <br>{{ $matchDetail['venue_name'] }}
                                                <br>{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}
                                              @endif
                                            </td>

                                            <td style="padding: 0;border:0 solid transparent; width: 100%">
                                              <table cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                                                      <td style="min-width: 100px; font-weight: bold;">
                                                        {{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}
                                                      </td>
                                                    @endif
                                                    
                                                    @if(!checkIfWinnerLoserMatch($match['match_number']))
                                                      <?php
                                                        $homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge);
                                                        $awayTemScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge);
                                                      ?>
                                                      <td style="min-width: 100px;">
                                                        {{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                                        @if($homeTeamScoreValue != null && $awayTemScoreValue != null)
                                                          <br>{{ $matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['home_team'] ? '*' : '' }}{{ $homeTeamScoreValue }}-{{ $awayTemScoreValue }}{{ $matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['away_team'] ? '*' : '' }}
                                                        @endif
                                                      </td>
                                                    @endif

                                                    @if(checkIfWinnerLoserMatch($match['match_number']))
                                                      @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                                                      <td style="min-width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                                                          @php($homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge))
                                                          {{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}
                                                          @if($homeTeamScoreValue != null)
                                                            <br>{{ $homeTeamScoreValue }}
                                                          @endif
                                                      </td>
                                                    @endif

                                                    @if(checkIfWinnerLoserMatch($match['match_number']))
                                                      @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                                                      <td style="min-width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                                                          {{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                                          @php($awayTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge))
                                                          @if($awayTeamScoreValue != null)
                                                            <br>{{ $awayTeamScoreValue }}
                                                          @endif
                                                      </td>
                                                    @endif
                                                  </tr>
                                                </tbody>
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
                                                  <td style="font-weight: bold; min-width: 100px;">{{ "Group " . getGroupName($group['groups']['group_name']) }}
                                                  </td>
                                                </tr>
                                                @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                                  <tr>
                                                    <td style="min-width: 100px;">
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
                                                  <td style="font-weight: bold; min-width: 100px;">
                                                    {{ "Group " . getGroupName($group['groups']['group_name']) }}
                                                  </td>
                                                </tr>
                                                @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                                                  <tr>
                                                    <td style="min-width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
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
                                                  <td style="font-weight: bold; min-width: 100px;">
                                                    &nbsp;
                                                  </td>
                                                </tr>
                                                @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                                  <tr>
                                                    <td style="min-width: 100px;">
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
                                                  <td style="font-weight: bold; min-width: 100px;">
                                                    Ranking
                                                  </td>
                                                </tr>
                                                @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                                  @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                                                    <tr>
                                                      <td style="min-width: 100px;">
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
                              </tbody>
                            </table>
                          </div>
                        @endforeach
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
            @if($roundCount % 4 === 3)
                  </tr>
                </tbody>
              </table>
              @php($istableEnd=1)
            @endif
            @php($roundCount++)
          @endforeach

          @foreach(getDivisionRounds($templateData) as $roundIndex=>$round)
            @if($roundCount % 4 === 0)
              <table class="{{ $roundCount !== 0 ? 'break' : '' }}" cellpadding="10" cellspacing="10">
                <tbody>
                  <tr>
              @php($istableEnd=0)
            @endif
            <td style="vertical-align: top;">
              <table border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%">
                <tbody>
                  <tr>
                      <td style="border:0 solid transparent; width: 100px; text-align: center;" align="center">
                          <div class="round-img-wrapper">
                              <img src="{{ asset('assets/img/img-round.png') }}" style="width: 100px;">
                              <span class="round-number">{{ getMainNoOfRoundCount($templateData) + $roundIndex + 1 }}</span>
                          </div>
                      </td>
                  </tr>
                </tbody>
              </table>
              
                <table align="center" cellpadding="0" cellspacing="5" style="width: 100%; margin-top: 20px;"> 
                  <tbody>
                    <tr>
                      <td style="border: 0;padding: 0;">
                        <div class="round-details-wrapper">
                          @foreach($round['match_type'] as $groupIndex=>$group)
                          <div class="row-round">
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                              <tbody>
                                <!-- Round 2 - PM -->
                                @if(getGroupType($group) == 'PM')
                                  @foreach($group['groups']['match'] as $matchIndex=>$match)
                                    <tr>
                                      <td style="border: 0;padding: 0;">
                                        <table cellpadding="0" cellspacing="5">
                                          <tr>
                                            <td style="min-width: 150px; background-color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['background'] : '' }}; color: {{ isset($colorCodes['matchesWithColorCode'][$match['match_number']]) ? $colorCodes['matchesWithColorCode'][$match['match_number']]['text'] : '' }}; white-space: nowrap">
                                              @php($matchDetail = getMatchDetail($fixtures, $match, $groupName, $categoryAge))
                                              <span style="font-weight: bold;">Match {{ getMatchNumber($match['display_match_number']) . ($matchDetail && $matchDetail['is_scheduled'] === 1 ? " (" . date("d/m/Y", strtotime($matchDetail['match_datetime'])) . ")" : "") }}</span>
                                              @if($matchDetail && $matchDetail['is_scheduled'] === 1)
                                                <br>{{ $matchDetail['venue_name'] }}
                                                <br>{{ $matchDetail['pitch_name'] . ', ' . date("H:i", strtotime($matchDetail['match_datetime'])) }}
                                              @endif
                                            </td>
                                            <td style="padding: 0;border:0 solid transparent;">
                                              <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                                <tbody>
                                                  <tr>
                                                    @if(checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']))
                                                      <td style="min-width: 100px; font-weight: bold;">
                                                        {{ checkForMatchNumberOrRankingInPosition($templateData, 'placing_match', $match['match_number']) }}
                                                      </td>
                                                    @endif

                                                    @if(!checkIfWinnerLoserMatch($match['match_number']))
                                                      <?php
                                                        $homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge);
                                                        $awayTemScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge);
                                                      ?>
                                                      <td style="min-width: 100px;">
                                                        {{ getPlacingTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}-{{ getPlacingTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                                        @if($homeTeamScoreValue != null && $awayTemScoreValue != null)
                                                          <br>{{ $matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['home_team'] ? '*' : '' }}{{ $homeTeamScoreValue }}-{{ $awayTemScoreValue }}{{ $matchDetail['is_result_override'] == 1 && $matchDetail['match_winner'] == $matchDetail['away_team'] ? '*' : '' }}
                                                        @endif
                                                      </td>
                                                    @endif

                                                    @if(checkIfWinnerLoserMatch($match['match_number']))
                                                      @php($homeTeamCode = getTeamCodeInSearch($match, 'home'))
                                                      <td style="min-width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$homeTeamCode]['text'] : '' }}">
                                                        @php($homeTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'home', $groupName, $categoryAge))
                                                        {{ getPlacingWinnerLoserTeam($fixtures, $match, 'home', $groupName, $categoryAge) }}
                                                        @if($homeTeamScoreValue != null)
                                                          <br>{{ $homeTeamScoreValue }}
                                                        @endif
                                                      </td>
                                                    @endif

                                                    @if(checkIfWinnerLoserMatch($match['match_number']))
                                                      @php($awayTeamCode = getTeamCodeInSearch($match, 'away'))
                                                      <td style="min-width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]) ? $colorCodes['homeAwayTeamWithColorCode'][$awayTeamCode]['text'] : '' }}">
                                                        @php($awayTeamScoreValue = getHomeAndAwayTeamScore($fixtures, $match, 'away', $groupName, $categoryAge))
                                                        {{ getPlacingWinnerLoserTeam($fixtures, $match, 'away', $groupName, $categoryAge) }}
                                                        @if($awayTeamScoreValue != null)
                                                          <br>{{ $awayTeamScoreValue }}
                                                        @endif
                                                      </td>
                                                    @endif
                                                  </tr>
                                                </tbody>
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
                                                <td style="font-weight: bold; min-width: 100px;">
                                                  {{ "Group " . getGroupName($group['groups']['group_name']) }}
                                                </td>
                                              </tr>
                                              @foreach(getRoundRobinUniqueTeams($fixtures, $group['groups']['match'], $groupName, $categoryAge) as $teamIndex=>$team)
                                                <tr>
                                                  <td style="min-width: 100px; background-color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['background'] : '' }}; color: {{ isset($colorCodes['homeAwayTeamWithColorCode'][$team['code']]) ? $colorCodes['homeAwayTeamWithColorCode'][$team['code']]['text'] : '' }}">
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
                                                <td style="font-weight: bold; min-width: 100px;">
                                                  &nbsp;
                                                </td>
                                              </tr>
                                              @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                                <tr>
                                                  <td style="min-width: 100px;">
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
                                                  <td style="font-weight: bold; min-width: 100px;">
                                                    Ranking
                                                  </td>
                                                </tr>
                                                @for($teamIndex=1; $teamIndex <= $group['group_count']; $teamIndex++)
                                                  @if(checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($teamIndex) . getGroupName($group['groups']['group_name'])))
                                                    <tr>
                                                      <td style="min-width: 100px;">
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
                              </tbody>
                            </table>
                          </div>
                          @endforeach
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              
            </td>
            @if($roundCount % 4 === 3)
                  </tr>
                </tbody>
              </table>
              @php($istableEnd=1)
            @endif
            @php($roundCount++)
          @endforeach
          
          @if($istableEnd === 0)
                </tr>
              </tbody>
            </table>
          @endif
        <!-- </tr>
      </tbody>
    </table> -->

  </body>
</html>