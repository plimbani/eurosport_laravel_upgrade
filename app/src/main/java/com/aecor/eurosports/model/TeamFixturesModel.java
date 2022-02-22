package com.aecor.eurosports.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by system-local on 30-06-2017.
 */

public class TeamFixturesModel implements Parcelable{

    private String fid;
    private String match_number;
    private String displayMatchNumber;
    private String round;
    private String actual_round;
    private String competation_name;
    private String competition_actual_name;
    private String team_size;
    private String match_datetime;
    private String match_endtime;
    private String competation_round_no;
    private String venueId;
    private String competitionId;
    private String venueCoordinates;
    private String pitchType;
    private String venueaddress;
    private String venueState;
    private String venueCounty;
    private String venueCity;
    private String venueCountry;
    private String venuePostcode;
    private String group_name;
    private String venue_name;
    private String pitch_number;
    private String referee_name;
    private String referee_id;
    private String first_name;
    private String last_name;
    private String HomeTeam;
    private String AwayTeam;
    private String Home_id;
    private String Away_id;
    private String HomeFlagLogo;
    private String AwayFlagLogo;
    private String HomeCountryFlag;
    private String HomeCountryName;
    private String AwayCountryName;
    private String homeScore;
    private String AwayScore;
    private String pitchId;
    private String is_scheduled;
    private String game_duration_RR;
    private String game_duration_FM;
    private String halftime_break_RR;
    private String halftime_break_FM;
    private String match_interval_RR;
    private String match_interval_FM;
    private String tid;
    private String full_game;
    private String match_status;
    private String MatchWinner;
    private String homePlaceholder;
    private String awayPlaceholder;
    private String homeTeamName;
    private String awayTeamName;
    private String displayHomeTeamPlaceholderName;
    private String displayAwayTeamPlaceholderName;
    private String position;
    private String isResultOverride;
    private String match_winner;
    private String HomeTeamShirtColor;
    private String AwayTeamShirtColor;
    private String HomeTeamShortsColor;
    private String AwayTeamShortsColor;
    private String isDivExist;
    private String isKnockoutPlacingMatches;

    protected TeamFixturesModel(Parcel in) {
        fid = in.readString();
        match_number = in.readString();
        displayMatchNumber = in.readString();
        round = in.readString();
        actual_round = in.readString();
        competation_name = in.readString();
        competition_actual_name = in.readString();
        team_size = in.readString();
        match_datetime = in.readString();
        match_endtime = in.readString();
        competation_round_no = in.readString();
        venueId = in.readString();
        competitionId = in.readString();
        venueCoordinates = in.readString();
        pitchType = in.readString();
        venueaddress = in.readString();
        venueState = in.readString();
        venueCounty = in.readString();
        venueCity = in.readString();
        venueCountry = in.readString();
        venuePostcode = in.readString();
        group_name = in.readString();
        venue_name = in.readString();
        pitch_number = in.readString();
        referee_name = in.readString();
        referee_id = in.readString();
        first_name = in.readString();
        last_name = in.readString();
        HomeTeam = in.readString();
        AwayTeam = in.readString();
        Home_id = in.readString();
        Away_id = in.readString();
        HomeFlagLogo = in.readString();
        AwayFlagLogo = in.readString();
        HomeCountryFlag = in.readString();
        HomeCountryName = in.readString();
        AwayCountryName = in.readString();
        homeScore = in.readString();
        AwayScore = in.readString();
        pitchId = in.readString();
        is_scheduled = in.readString();
        game_duration_RR = in.readString();
        game_duration_FM = in.readString();
        halftime_break_RR = in.readString();
        halftime_break_FM = in.readString();
        match_interval_RR = in.readString();
        match_interval_FM = in.readString();
        tid = in.readString();
        full_game = in.readString();
        match_status = in.readString();
        MatchWinner = in.readString();
        homePlaceholder = in.readString();
        awayPlaceholder = in.readString();
        homeTeamName = in.readString();
        awayTeamName = in.readString();
        displayHomeTeamPlaceholderName = in.readString();
        displayAwayTeamPlaceholderName = in.readString();
        position = in.readString();
        isResultOverride = in.readString();
        match_winner = in.readString();
        HomeTeamShirtColor = in.readString();
        AwayTeamShirtColor = in.readString();
        HomeTeamShortsColor = in.readString();
        AwayTeamShortsColor = in.readString();
        isDivExist = in.readString();
        isKnockoutPlacingMatches = in.readString();
    }

    public static final Creator<TeamFixturesModel> CREATOR = new Creator<TeamFixturesModel>() {
        @Override
        public TeamFixturesModel createFromParcel(Parcel in) {
            return new TeamFixturesModel(in);
        }

        @Override
        public TeamFixturesModel[] newArray(int size) {
            return new TeamFixturesModel[size];
        }
    };

    public String getFid() {
        return fid;
    }

    public void setFid(String fid) {
        this.fid = fid;
    }

    public String getMatch_number() {
        return match_number;
    }

    public void setMatch_number(String match_number) {
        this.match_number = match_number;
    }

    public String getDisplayMatchNumber() {
        return displayMatchNumber;
    }

    public void setDisplayMatchNumber(String displayMatchNumber) {
        this.displayMatchNumber = displayMatchNumber;
    }

    public String getRound() {
        return round;
    }

    public void setRound(String round) {
        this.round = round;
    }

    public String getActual_round() {
        return actual_round;
    }

    public void setActual_round(String actual_round) {
        this.actual_round = actual_round;
    }

    public String getCompetation_name() {
        return competation_name;
    }

    public void setCompetation_name(String competation_name) {
        this.competation_name = competation_name;
    }

    public String getCompetition_actual_name() {
        return competition_actual_name;
    }

    public void setCompetition_actual_name(String competition_actual_name) {
        this.competition_actual_name = competition_actual_name;
    }

    public String getTeam_size() {
        return team_size;
    }

    public void setTeam_size(String team_size) {
        this.team_size = team_size;
    }

    public String getMatch_datetime() {
        return match_datetime;
    }

    public void setMatch_datetime(String match_datetime) {
        this.match_datetime = match_datetime;
    }

    public String getMatch_endtime() {
        return match_endtime;
    }

    public void setMatch_endtime(String match_endtime) {
        this.match_endtime = match_endtime;
    }

    public String getCompetation_round_no() {
        return competation_round_no;
    }

    public void setCompetation_round_no(String competation_round_no) {
        this.competation_round_no = competation_round_no;
    }

    public String getVenueId() {
        return venueId;
    }

    public void setVenueId(String venueId) {
        this.venueId = venueId;
    }

    public String getCompetitionId() {
        return competitionId;
    }

    public void setCompetitionId(String competitionId) {
        this.competitionId = competitionId;
    }

    public String getVenueCoordinates() {
        return venueCoordinates;
    }

    public void setVenueCoordinates(String venueCoordinates) {
        this.venueCoordinates = venueCoordinates;
    }

    public String getPitchType() {
        return pitchType;
    }

    public void setPitchType(String pitchType) {
        this.pitchType = pitchType;
    }

    public String getVenueaddress() {
        return venueaddress;
    }

    public void setVenueaddress(String venueaddress) {
        this.venueaddress = venueaddress;
    }

    public String getVenueState() {
        return venueState;
    }

    public void setVenueState(String venueState) {
        this.venueState = venueState;
    }

    public String getVenueCounty() {
        return venueCounty;
    }

    public void setVenueCounty(String venueCounty) {
        this.venueCounty = venueCounty;
    }

    public String getVenueCity() {
        return venueCity;
    }

    public void setVenueCity(String venueCity) {
        this.venueCity = venueCity;
    }

    public String getVenueCountry() {
        return venueCountry;
    }

    public void setVenueCountry(String venueCountry) {
        this.venueCountry = venueCountry;
    }

    public String getVenuePostcode() {
        return venuePostcode;
    }

    public void setVenuePostcode(String venuePostcode) {
        this.venuePostcode = venuePostcode;
    }

    public String getGroup_name() {
        return group_name;
    }

    public void setGroup_name(String group_name) {
        this.group_name = group_name;
    }

    public String getVenue_name() {
        return venue_name;
    }

    public void setVenue_name(String venue_name) {
        this.venue_name = venue_name;
    }

    public String getPitch_number() {
        return pitch_number;
    }

    public void setPitch_number(String pitch_number) {
        this.pitch_number = pitch_number;
    }

    public String getReferee_name() {
        return referee_name;
    }

    public void setReferee_name(String referee_name) {
        this.referee_name = referee_name;
    }

    public String getReferee_id() {
        return referee_id;
    }

    public void setReferee_id(String referee_id) {
        this.referee_id = referee_id;
    }

    public String getFirst_name() {
        return first_name;
    }

    public void setFirst_name(String first_name) {
        this.first_name = first_name;
    }

    public String getLast_name() {
        return last_name;
    }

    public void setLast_name(String last_name) {
        this.last_name = last_name;
    }

    public String getHomeTeam() {
        return HomeTeam;
    }

    public void setHomeTeam(String homeTeam) {
        HomeTeam = homeTeam;
    }

    public String getAwayTeam() {
        return AwayTeam;
    }

    public void setAwayTeam(String awayTeam) {
        AwayTeam = awayTeam;
    }

    public String getHome_id() {
        return Home_id;
    }

    public void setHome_id(String home_id) {
        Home_id = home_id;
    }

    public String getAway_id() {
        return Away_id;
    }

    public void setAway_id(String away_id) {
        Away_id = away_id;
    }

    public String getHomeFlagLogo() {
        return HomeFlagLogo;
    }

    public void setHomeFlagLogo(String homeFlagLogo) {
        HomeFlagLogo = homeFlagLogo;
    }

    public String getAwayFlagLogo() {
        return AwayFlagLogo;
    }

    public void setAwayFlagLogo(String awayFlagLogo) {
        AwayFlagLogo = awayFlagLogo;
    }

    public String getHomeCountryFlag() {
        return HomeCountryFlag;
    }

    public void setHomeCountryFlag(String homeCountryFlag) {
        HomeCountryFlag = homeCountryFlag;
    }

    public String getHomeCountryName() {
        return HomeCountryName;
    }

    public void setHomeCountryName(String homeCountryName) {
        HomeCountryName = homeCountryName;
    }

    public String getAwayCountryName() {
        return AwayCountryName;
    }

    public void setAwayCountryName(String awayCountryName) {
        AwayCountryName = awayCountryName;
    }

    public String getHomeScore() {
        return homeScore;
    }

    public void setHomeScore(String homeScore) {
        this.homeScore = homeScore;
    }

    public String getAwayScore() {
        return AwayScore;
    }

    public void setAwayScore(String awayScore) {
        AwayScore = awayScore;
    }

    public String getPitchId() {
        return pitchId;
    }

    public void setPitchId(String pitchId) {
        this.pitchId = pitchId;
    }

    public String getIs_scheduled() {
        return is_scheduled;
    }

    public void setIs_scheduled(String is_scheduled) {
        this.is_scheduled = is_scheduled;
    }

    public String getGame_duration_RR() {
        return game_duration_RR;
    }

    public void setGame_duration_RR(String game_duration_RR) {
        this.game_duration_RR = game_duration_RR;
    }

    public String getGame_duration_FM() {
        return game_duration_FM;
    }

    public void setGame_duration_FM(String game_duration_FM) {
        this.game_duration_FM = game_duration_FM;
    }

    public String getHalftime_break_RR() {
        return halftime_break_RR;
    }

    public void setHalftime_break_RR(String halftime_break_RR) {
        this.halftime_break_RR = halftime_break_RR;
    }

    public String getHalftime_break_FM() {
        return halftime_break_FM;
    }

    public void setHalftime_break_FM(String halftime_break_FM) {
        this.halftime_break_FM = halftime_break_FM;
    }

    public String getMatch_interval_RR() {
        return match_interval_RR;
    }

    public void setMatch_interval_RR(String match_interval_RR) {
        this.match_interval_RR = match_interval_RR;
    }

    public String getMatch_interval_FM() {
        return match_interval_FM;
    }

    public void setMatch_interval_FM(String match_interval_FM) {
        this.match_interval_FM = match_interval_FM;
    }

    public String getTid() {
        return tid;
    }

    public void setTid(String tid) {
        this.tid = tid;
    }

    public String getFull_game() {
        return full_game;
    }

    public void setFull_game(String full_game) {
        this.full_game = full_game;
    }

    public String getMatch_status() {
        return match_status;
    }

    public void setMatch_status(String match_status) {
        this.match_status = match_status;
    }

    public String getMatchWinner() {
        return MatchWinner;
    }

    public void setMatchWinner(String matchWinner) {
        MatchWinner = matchWinner;
    }

    public String getHomePlaceholder() {
        return homePlaceholder;
    }

    public void setHomePlaceholder(String homePlaceholder) {
        this.homePlaceholder = homePlaceholder;
    }

    public String getAwayPlaceholder() {
        return awayPlaceholder;
    }

    public void setAwayPlaceholder(String awayPlaceholder) {
        this.awayPlaceholder = awayPlaceholder;
    }

    public String getHomeTeamName() {
        return homeTeamName;
    }

    public void setHomeTeamName(String homeTeamName) {
        this.homeTeamName = homeTeamName;
    }

    public String getAwayTeamName() {
        return awayTeamName;
    }

    public void setAwayTeamName(String awayTeamName) {
        this.awayTeamName = awayTeamName;
    }

    public String getDisplayHomeTeamPlaceholderName() {
        return displayHomeTeamPlaceholderName;
    }

    public void setDisplayHomeTeamPlaceholderName(String displayHomeTeamPlaceholderName) {
        this.displayHomeTeamPlaceholderName = displayHomeTeamPlaceholderName;
    }

    public String getDisplayAwayTeamPlaceholderName() {
        return displayAwayTeamPlaceholderName;
    }

    public void setDisplayAwayTeamPlaceholderName(String displayAwayTeamPlaceholderName) {
        this.displayAwayTeamPlaceholderName = displayAwayTeamPlaceholderName;
    }

    public String getPosition() {
        return position;
    }

    public void setPosition(String position) {
        this.position = position;
    }

    public String getIsResultOverride() {
        return isResultOverride;
    }

    public void setIsResultOverride(String isResultOverride) {
        this.isResultOverride = isResultOverride;
    }

    public String getMatch_winner() {
        return match_winner;
    }

    public void setMatch_winner(String match_winner) {
        this.match_winner = match_winner;
    }

    public String getHomeTeamShirtColor() {
        return HomeTeamShirtColor;
    }

    public void setHomeTeamShirtColor(String homeTeamShirtColor) {
        HomeTeamShirtColor = homeTeamShirtColor;
    }

    public String getAwayTeamShirtColor() {
        return AwayTeamShirtColor;
    }

    public void setAwayTeamShirtColor(String awayTeamShirtColor) {
        AwayTeamShirtColor = awayTeamShirtColor;
    }

    public String getHomeTeamShortsColor() {
        return HomeTeamShortsColor;
    }

    public void setHomeTeamShortsColor(String homeTeamShortsColor) {
        HomeTeamShortsColor = homeTeamShortsColor;
    }

    public String getAwayTeamShortsColor() {
        return AwayTeamShortsColor;
    }

    public void setAwayTeamShortsColor(String awayTeamShortsColor) {
        AwayTeamShortsColor = awayTeamShortsColor;
    }

    public String getIsDivExist() {
        return isDivExist;
    }

    public void setIsDivExist(String isDivExist) {
        this.isDivExist = isDivExist;
    }

    public String getIsKnockoutPlacingMatches() {
        return isKnockoutPlacingMatches;
    }

    public void setIsKnockoutPlacingMatches(String isKnockoutPlacingMatches) {
        this.isKnockoutPlacingMatches = isKnockoutPlacingMatches;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(fid);
        dest.writeString(match_number);
        dest.writeString(displayMatchNumber);
        dest.writeString(round);
        dest.writeString(actual_round);
        dest.writeString(competation_name);
        dest.writeString(competition_actual_name);
        dest.writeString(team_size);
        dest.writeString(match_datetime);
        dest.writeString(match_endtime);
        dest.writeString(competation_round_no);
        dest.writeString(venueId);
        dest.writeString(competitionId);
        dest.writeString(venueCoordinates);
        dest.writeString(pitchType);
        dest.writeString(venueaddress);
        dest.writeString(venueState);
        dest.writeString(venueCounty);
        dest.writeString(venueCity);
        dest.writeString(venueCountry);
        dest.writeString(venuePostcode);
        dest.writeString(group_name);
        dest.writeString(venue_name);
        dest.writeString(pitch_number);
        dest.writeString(referee_name);
        dest.writeString(referee_id);
        dest.writeString(first_name);
        dest.writeString(last_name);
        dest.writeString(HomeTeam);
        dest.writeString(AwayTeam);
        dest.writeString(Home_id);
        dest.writeString(Away_id);
        dest.writeString(HomeFlagLogo);
        dest.writeString(AwayFlagLogo);
        dest.writeString(HomeCountryFlag);
        dest.writeString(HomeCountryName);
        dest.writeString(AwayCountryName);
        dest.writeString(homeScore);
        dest.writeString(AwayScore);
        dest.writeString(pitchId);
        dest.writeString(is_scheduled);
        dest.writeString(game_duration_RR);
        dest.writeString(game_duration_FM);
        dest.writeString(halftime_break_RR);
        dest.writeString(halftime_break_FM);
        dest.writeString(match_interval_RR);
        dest.writeString(match_interval_FM);
        dest.writeString(tid);
        dest.writeString(full_game);
        dest.writeString(match_status);
        dest.writeString(MatchWinner);
        dest.writeString(homePlaceholder);
        dest.writeString(awayPlaceholder);
        dest.writeString(homeTeamName);
        dest.writeString(awayTeamName);
        dest.writeString(displayHomeTeamPlaceholderName);
        dest.writeString(displayAwayTeamPlaceholderName);
        dest.writeString(position);
        dest.writeString(isResultOverride);
        dest.writeString(match_winner);
        dest.writeString(HomeTeamShirtColor);
        dest.writeString(AwayTeamShirtColor);
        dest.writeString(HomeTeamShortsColor);
        dest.writeString(AwayTeamShortsColor);
        dest.writeString(isDivExist);
        dest.writeString(isKnockoutPlacingMatches);
    }
}
