<template>
	<tbody>
        <tr v-for="(match,index1) in matchData">
    			<td>{{match.match_datetime | formatDate}}</td>
    			<td>
	          <a class="pull-left text-left text-primary" href=""
	          v-if="getCurrentScheduleView != 'drawDetails'"
	          @click.prevent="changeDrawDetails(match)"><u>{{match.competation_name | formatGroup}}</u>
	          </a>
	          <span v-else>{{match.competation_name | formatGroup(match.round)}}</span>
	        </td>
          <td v-if="isHideLocation !=  false">
            <a class="pull-left text-left">
            {{ match.is_scheduled == 1 ? (match.venue_name + '-' + match.pitch_number) : '-' }}
            </a>
          </td>
	        <td>{{displayMatch(match.displayMatchNumber)}}</td>
	        <td align="right">
	          <div class="matchteam-details flex-row-reverse">
	            <span :class="'flag-icon flag-icon-'+match.HomeCountryFlag" class="line-height-initial matchteam-flag"></span>
	            <div class="matchteam-dress" v-if="match.HomeTeamShortsColor && match.HomeTeamShirtColor">
	              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.HomeTeamShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.HomeTeamShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
	            </div>
	            <span class="text-center matchteam-name" v-if="(match.Home_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayHomeTeamPlaceholderName) }}</span>
	            <span class="text-center matchteam-name" v-else><a class="text-primary" href="javascript:void(0)" @click.prevent="changeTeam(match.Home_id, match.HomeTeam)">{{ match.HomeTeam }}</a></span>
	          </div>
	        </td>
	        <td align="left">
	          <div class="matchteam-details">
	            <span :class="'flag-icon flag-icon-'+match.AwayCountryFlag" class="line-height-initial matchteam-flag"></span>
	            <div class="matchteam-dress" v-if="match.AwayTeamShortsColor && match.AwayTeamShirtColor">
	              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.AwayTeamShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.AwayTeamShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
	            </div>
	            <span class="text-center matchteam-name" v-if="(match.Away_id == '0' )">{{ getHoldingName(match.competition_actual_name, match.displayAwayTeamPlaceholderName) }}</span>
	            <span class="text-center matchteam-name" v-else><a class="text-primary" href="javascript:void(0)" @click.prevent="changeTeam(match.Away_id, match.AwayTeam)">{{ match.AwayTeam }}</a></span>
	          </div>
	        </td>
	        <td class="text-center js-match-list">
	            <div class="d-inline-flex position-relative">
	              <input type="text" class="scoreUpdate" v-model="match.homeScore" :name="'home_score['+match.fid+']'" style="width: 25px; text-align: center;" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'" :readonly="(match.is_scheduled == '0') || (match.isResultOverride == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned')) || (match.Home_id == 0 || match.Away_id == 0)" @keyup="updateScore(match,index1)">

	              <span v-else>{{match.homeScore}}</span>

	              <span class="circle-badge" :class="{'left-input': (isUserDataExist && getCurrentScheduleView != 'teamDetails'), 'left-text': (!isUserDataExist || getCurrentScheduleView == 'teamDetails') }" v-if="(match.isResultOverride == '1' && match.match_winner == match.Home_id)"><a data-toggle="popover" :class="'result-override-home-popover-' + match.fid" href="#" data-placement="top" data-trigger="hover" :data-content="match.result_override_popover" data-animation="false"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></a></span>
	            </div> -
	            <div class="d-inline-flex position-relative">
	              <input type="text" class="scoreUpdate" v-model="match.AwayScore" :name="'away_score['+match.fid+']'" style="width: 25px; text-align: center;"  v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'" :readonly="(match.is_scheduled == '0') || (match.isResultOverride == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned')) || (match.Home_id == 0 || match.Away_id == 0)" @keyup="updateScore(match,index1)">

	              <span class="circle-badge" :class="{'right-input': (isUserDataExist && getCurrentScheduleView != 'teamDetails'), 'right-text': (!isUserDataExist || getCurrentScheduleView == 'teamDetails') }" v-if="(match.isResultOverride == '1' && match.match_winner == match.Away_id)"><a :class="'result-override-away-popover-' + match.fid" href="#" data-toggle="popover" data-placement="top" data-trigger="hover" :data-content="match.result_override_popover" data-animation="false"><i class="fas fa-asterisk text-white" aria-hidden="true"></i></a></span>

	              <span v-if="(!isUserDataExist || getCurrentScheduleView == 'teamDetails')">{{match.AwayScore}}</span>
	            </div>
	        </td>
	        <td class="text-center" v-if="showPlacingForMatch">
	          {{ match.position != null ? match.position : 'N/A' }}
	        </td>
	        <td class="text-center" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'">
	          <span class="align-middle">
	            <span v-if="match.is_scheduled == '0'">-
	            </span>
	            <span v-else>
	              <a class="text-primary js-edit-match" href="javascript:void(0);"  v-bind:data-id="match.fid"
	              @click="openPitchModal(match,match.fid)"><i class="fas fa-pencil"></i>
	              </a>
	              <a v-if="match.matchRemarks" class="text-primary" href="javascript:void(0);" @click="openPitchModal(match, match.fid)"><i class="fas fa-comment-dots"></i></a>
	            </span>
	          </span>
	        </td>
	    </tr>
	</tbody>
</template>
<script>

export default {
  props: ['getCurrentScheduleView', 'showPlacingForMatch','isHideLocation','isUserDataExist','matchData','isDivExist'],
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
    openPitchModal(match,matchId) {
   		this.$emit('openPitchModal',match,matchId);
    },
    changeLocation(matchData) {
      // here we dispatch Method
      this.$store.dispatch('setCurrentScheduleView','locationList')
      this.$root.$emit('changeComp',matchData);
      //this.$store.dispatch('setCurrentScheduleView','locationList')
    },
    changeTeam(Id, Name) {
      window.changeTeamId = Id;
      window.changeTeamname = Name;
      // here we dispatch Method
      this.$store.dispatch('setCurrentScheduleView','teamDetails')
      this.$root.$emit('changeComp', Id, Name);
    },
    changeDrawDetails(competition) {
    	this.$emit('changeDrawDetails',competition);
    },
    changeTeamDetails() {
      this.$store.dispatch('setCurrentScheduleView','teamDetails')
    },
    updateScore(match,index) {
    	this.$emit('updateScore',match,index);	
    },
    getHoldingName(competitionActualName, placeholder) {
      if(competitionActualName.indexOf('Group') !== -1){
        return placeholder;
      } else if(competitionActualName.indexOf('Pos') !== -1){
        if(placeholder.indexOf('wrs.') !== -1 || placeholder.indexOf('lrs.') !== -1) {
          return placeholder;
        }
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