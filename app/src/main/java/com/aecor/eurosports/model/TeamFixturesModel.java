package com.aecor.eurosports.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by system-local on 30-06-2017.
 */

public class TeamFixturesModel implements Parcelable{
    private String fid;
    private String match_number;
    private String round;
    private String competation_name;
    private String team_size;
    private String match_datetime;
    private String match_endtime;
    private String venueId;
    private String competitionId;
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
    private String AwayCountryFlag;
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
    private String full_game;

    protected TeamFixturesModel(Parcel in) {
        fid = in.readString();
        match_number = in.readString();
        round = in.readString();
        competation_name = in.readString();
        team_size = in.readString();
        match_datetime = in.readString();
        match_endtime = in.readString();
        venueId = in.readString();
        competitionId = in.readString();
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
        AwayCountryFlag = in.readString();
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
        full_game = in.readString();
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

    public String getRound() {
        return round;
    }

    public void setRound(String round) {
        this.round = round;
    }

    public String getCompetation_name() {
        return competation_name;
    }

    public void setCompetation_name(String competation_name) {
        this.competation_name = competation_name;
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

    public String getAwayCountryFlag() {
        return AwayCountryFlag;
    }

    public void setAwayCountryFlag(String awayCountryFlag) {
        AwayCountryFlag = awayCountryFlag;
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

    public String getFull_game() {
        return full_game;
    }

    public void setFull_game(String full_game) {
        this.full_game = full_game;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(fid);
        dest.writeString(match_number);
        dest.writeString(round);
        dest.writeString(competation_name);
        dest.writeString(team_size);
        dest.writeString(match_datetime);
        dest.writeString(match_endtime);
        dest.writeString(venueId);
        dest.writeString(competitionId);
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
        dest.writeString(AwayCountryFlag);
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
        dest.writeString(full_game);
    }
}
