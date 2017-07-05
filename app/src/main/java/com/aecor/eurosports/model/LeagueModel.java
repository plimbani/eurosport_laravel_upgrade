package com.aecor.eurosports.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by system-local on 30-06-2017.
 */

public class LeagueModel implements Parcelable {
    private String id;
    private String tournament_id;
    private String competition_id;
    private String team_id;
    private String points;
    private String played;
    private String won;
    private String draws;
    private String lost;
    private String goal_for;
    private String goal_against;
    private String assigned_group;
    private String user_id;
    private String age_group_id;
    private String club_id;
    private String group_name;
    private String name;
    private String place;
    private String shirt_color;
    private String esr_reference;
    private String country_id;
    private String teamFlag;
    private String teamCountryFlag;

    protected LeagueModel(Parcel in) {
        id = in.readString();
        tournament_id = in.readString();
        competition_id = in.readString();
        team_id = in.readString();
        points = in.readString();
        played = in.readString();
        won = in.readString();
        draws = in.readString();
        lost = in.readString();
        goal_for = in.readString();
        goal_against = in.readString();
        assigned_group = in.readString();
        user_id = in.readString();
        age_group_id = in.readString();
        club_id = in.readString();
        group_name = in.readString();
        name = in.readString();
        place = in.readString();
        shirt_color = in.readString();
        esr_reference = in.readString();
        country_id = in.readString();
        teamFlag = in.readString();
        teamCountryFlag = in.readString();
    }

    public static final Creator<LeagueModel> CREATOR = new Creator<LeagueModel>() {
        @Override
        public LeagueModel createFromParcel(Parcel in) {
            return new LeagueModel(in);
        }

        @Override
        public LeagueModel[] newArray(int size) {
            return new LeagueModel[size];
        }
    };

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTournament_id() {
        return tournament_id;
    }

    public void setTournament_id(String tournament_id) {
        this.tournament_id = tournament_id;
    }

    public String getCompetition_id() {
        return competition_id;
    }

    public void setCompetition_id(String competition_id) {
        this.competition_id = competition_id;
    }

    public String getTeam_id() {
        return team_id;
    }

    public void setTeam_id(String team_id) {
        this.team_id = team_id;
    }

    public String getPoints() {
        return points;
    }

    public void setPoints(String points) {
        this.points = points;
    }

    public String getPlayed() {
        return played;
    }

    public void setPlayed(String played) {
        this.played = played;
    }

    public String getWon() {
        return won;
    }

    public void setWon(String won) {
        this.won = won;
    }

    public String getDraws() {
        return draws;
    }

    public void setDraws(String draws) {
        this.draws = draws;
    }

    public String getLost() {
        return lost;
    }

    public void setLost(String lost) {
        this.lost = lost;
    }

    public String getGoal_for() {
        return goal_for;
    }

    public void setGoal_for(String goal_for) {
        this.goal_for = goal_for;
    }

    public String getGoal_against() {
        return goal_against;
    }

    public void setGoal_against(String goal_against) {
        this.goal_against = goal_against;
    }

    public String getAssigned_group() {
        return assigned_group;
    }

    public void setAssigned_group(String assigned_group) {
        this.assigned_group = assigned_group;
    }

    public String getUser_id() {
        return user_id;
    }

    public void setUser_id(String user_id) {
        this.user_id = user_id;
    }

    public String getAge_group_id() {
        return age_group_id;
    }

    public void setAge_group_id(String age_group_id) {
        this.age_group_id = age_group_id;
    }

    public String getClub_id() {
        return club_id;
    }

    public void setClub_id(String club_id) {
        this.club_id = club_id;
    }

    public String getGroup_name() {
        return group_name;
    }

    public void setGroup_name(String group_name) {
        this.group_name = group_name;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getPlace() {
        return place;
    }

    public void setPlace(String place) {
        this.place = place;
    }

    public String getShirt_color() {
        return shirt_color;
    }

    public void setShirt_color(String shirt_color) {
        this.shirt_color = shirt_color;
    }

    public String getEsr_reference() {
        return esr_reference;
    }

    public void setEsr_reference(String esr_reference) {
        this.esr_reference = esr_reference;
    }

    public String getCountry_id() {
        return country_id;
    }

    public void setCountry_id(String country_id) {
        this.country_id = country_id;
    }

    public String getTeamFlag() {
        return teamFlag;
    }

    public void setTeamFlag(String teamFlag) {
        this.teamFlag = teamFlag;
    }

    public String getTeamCountryFlag() {
        return teamCountryFlag;
    }

    public void setTeamCountryFlag(String teamCountryFlag) {
        this.teamCountryFlag = teamCountryFlag;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(id);
        dest.writeString(tournament_id);
        dest.writeString(competition_id);
        dest.writeString(team_id);
        dest.writeString(points);
        dest.writeString(played);
        dest.writeString(won);
        dest.writeString(draws);
        dest.writeString(lost);
        dest.writeString(goal_for);
        dest.writeString(goal_against);
        dest.writeString(assigned_group);
        dest.writeString(user_id);
        dest.writeString(age_group_id);
        dest.writeString(club_id);
        dest.writeString(group_name);
        dest.writeString(name);
        dest.writeString(place);
        dest.writeString(shirt_color);
        dest.writeString(esr_reference);
        dest.writeString(country_id);
        dest.writeString(teamFlag);
        dest.writeString(teamCountryFlag);
    }
}
