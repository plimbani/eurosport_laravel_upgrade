<template>
  <tbody>
    <tr v-for="match in matchData">
      <td>{{ match.match_datetime | formatDate }}</td>
      <td>
        <a href="" v-if="currentView != 'Competition'" @click.prevent="showCompetitionData(match)">
          <u>{{ match.competation_name | formatGroup }}</u>
        </a>
        <span v-else>{{ match.competation_name | formatGroup(match.round) }}</span>
      </td>
      <td>{{displayMatch(match.displayMatchNumber)}}</td>
      <td class="match-teams-details">
        <div class="matchteam-details">                  
          <span class="matchteam-flag" v-if="(match.Home_id != 0 )" :class="'flag-icon flag-icon-' + match.HomeCountryFlag"></span>
          <div class="matchteam-dress" v-if="match.HomeTeamShortsColor && match.HomeTeamShirtColor">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.HomeTeamShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.HomeTeamShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
          </div>
          <span class="text-center matchteam-name" v-if="(match.Home_id == 0 )">{{ getHoldingName(match.competition_actual_name, match.displayHomeTeamPlaceholderName) }}</span>
          <span class="text-center matchteam-name" v-else>{{ match.HomeTeam }}</span>
        </div>
      </td>
      <td class="match-teams-details">
        <div class="matchteam-details">                  
          <span class="matchteam-flag" v-if="(match.Away_id != 0 )" :class="'flag-icon flag-icon-' + match.AwayCountryFlag"></span>
          <div class="matchteam-dress" v-if="match.AwayTeamShortsColor && match.AwayTeamShirtColor">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.AwayTeamShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.AwayTeamShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
          </div>
          <span class="text-center matchteam-name" v-if="(match.Away_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayAwayTeamPlaceholderName) }}</span>
          <span class="text-center matchteam-name" v-else>{{ match.AwayTeam }}</span>
        </div>
      </td>
      <td>
        <span v-if="(match.isResultOverride == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned') && match.match_winner == match.Home_id)">*</span>
        {{ (match.homeScore !== null && match.AwayScore !== null ? (match.homeScore + '-' + match.AwayScore) : '-') }}
        <span v-if="(match.isResultOverride == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned') &&match.match_winner == match.Away_id)">*</span>
      </td>
      <td v-if="showPlacingForMatch">
        {{ match.position != null ? match.position : $t('matches.n_a') }}
      </td>
      <td>
        {{ match.venue_name }} - {{ match.pitch_number }}
      </td>
    </tr>
  </tbody>
</template>
<script>

export default {
  props: ['showPlacingForMatch','matchData','isDivExist','currentView'],
  filters: {
    formatDate: function(date) {
      if(date != null ) {
        return moment(date).format("Do MMM YYYY HH:mm");
      } else {
        return  '-';
      }
    },
    formatGroup:function (value,round) {
      if(round == 'Round Robin') {
        return value
      }
      if(value) {
        if(!isNaN(value.slice(-1))) {
          return value.substring(0,value.length-1)
        } else {
          return value
        }
      }
    }
  },
  methods: {
    showCompetitionData(match) {
      this.$emit('showCompetitionData',match);
    },
    getHoldingName(competitionActualName, placeholder) {
      if(competitionActualName.indexOf('Group') !== -1){
        return placeholder;
      } else if(competitionActualName.indexOf('Pos') !== -1){
        return 'Pos-' + placeholder;
      }
    },
    displayMatch(displayMatchNumber) {
      if ( typeof displayMatchNumber !== 'undefined' )
      {
        var displayMatchText = displayMatchNumber.split('.');
        if(displayMatchNumber.indexOf("wrs") > 0 || displayMatchNumber.indexOf("lrs") > 0) {
          if(displayMatchText[3] == 'wrs' || displayMatchText[3] == 'lrs') {
            if(displayMatchNumber.indexOf('(@HOME-@AWAY)') > 0) {
              return displayMatchText[1] + '.' + displayMatchText[2] + '.' + displayMatchText[3];
            }
          }
        }
        return displayMatchText[1] + '.' + displayMatchText[2];
      }
      return displayMatchNumber;
    }
  },
}
</script>