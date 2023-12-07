<template>
	<div class="main-body">
		<div class="page-header">
            <div class="page-container">
                <div class="left-area">
                    <div class="date-lable">Date</div>
                    <div class="date">{{ currentDate }}</div>
                </div>
                <div class="middle-area">
                    <div class="title">Matches</div>
                </div>
                <div class="right-area">
                    <div>
                        <span class="active-page">{{ currentPage + 1 }}</span> of {{ totalPages }}
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <table class="match-table">
                <thead>
                    <tr>
                        <th class="time">Time</th>
                        <th class="group">Group</th>
                        <th class="code">Code</th>
                        <th class="placing">Placing</th>
                        <th class="venue">Venue</th>
                        <th class="matches" colspan="2">Matches</th>
                        <th class="score">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="typeof currentPageInformation.records !== 'undefined'" v-for="match in currentPageInformation.records">
                        <td class="time">{{ match.match_datetime | formatMatchDate }}</td>
                        <td class="group">{{ match.competition_name | formatCompetitionName }}</td>
                        <td class="code">{{ displayMatch(match.display_match_number) }}</td>
                        <td class="placing">{{ match.position != null ? match.position : 'N/A' }}</td>
                        <td class="venue">{{ match.venue_name + ' (' + match.pitch_number + ')' + ' (' + match.venue_country + ')' }}</td>
                        <td class="matches">
                            <span class="team">
                                <span class="team-info team-a">
                                    <span class="team-name" v-if="(match.home_id == '0')">
                                        {{ getHoldingName(match.competition_actual_name, match.display_home_team_placeholder_name) }}
                                    </span>
                                    <span class="team-name" v-else>
                                        {{ match.home_team }}
                                    </span>
                                    <span class="team-kit" v-if="match.home_team_shorts_color && match.home_team_shirt_color">
                                    	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.home_team_shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.home_team_shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
                                    </span>
                                </span>
                            </span>
                        </td>
                        <td class="matches">
                            <span class="team">
                                <span class="team-info team-b">
                                    <span class="team-name" v-if="(match.away_id == '0')">
                                        {{ getHoldingName(match.competition_actual_name, match.display_away_team_placeholder_name) }}
                                    </span>
                                    <span class="team-name" v-else>
                                        {{ match.away_team }}
                                    </span>
                                    <span class="team-kit" v-if="match.away_team_shorts_color && match.away_team_shirt_color">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.away_team_shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.away_team_shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
                                    </span>
                                </span>
                            </span>
                        </td>
                        <td class="score">
                        	<span v-if="(match.is_result_override == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned') && match.match_winner == match.home_id)">*</span>
        					{{ (match.home_score !== null && match.away_score !== null ? (match.home_score + '  -  ' + match.away_score) : '-') }}
        					<span v-if="(match.is_result_override == '1' && (match.match_status == 'Walk-over' || match.match_status == 'Abandoned') && match.match_winner == match.away_id)">*</span>
                        </td>
                    </tr>
                    <tr v-else>
                        <td>
                            <span class="text-muted">Fetching matches ...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="page-footer"></div>
	</div>
</template>
<script type="text/babel">
	import _ from 'lodash';
	export default {
		props: ['currentDate', 'currentCategoryName', 'currentPage', 'totalPages', 'currentPageInformation'],
		data() {
            return {
            	
            }
        },
        computed: {
        	
        },
        filters: {
        	formatCompetitionName(value) {
        		if(value) {
			        if(!isNaN(value.slice(-1))) {
			        	return value.substring(0, value.length - 1)
			        } else {
			        	return value
			        }
			    }
        	},
        	formatMatchDate(date) {
			    if(date != null ) {
			        return moment(date).format("HH:mm");
			    } else {
			        return  '-';
			    }
		    },
        },
        methods: {
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
		    },
            getHoldingName(competitionActualName, placeholder) {
                if(competitionActualName.indexOf('Group') !== -1){
                    return placeholder;
                } else if(competitionActualName.indexOf('Pos') !== -1){
                    return 'Pos-' + placeholder;
                }
            },
        }
	}
</script>