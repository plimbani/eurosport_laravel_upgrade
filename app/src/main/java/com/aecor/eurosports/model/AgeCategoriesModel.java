package com.aecor.eurosports.model;

/**
 * Created by karan on 6/28/2017.
 */

public class AgeCategoriesModel {
    private int id;
    private int total_teams;
    private int tournament_id;
    private int min_matches;
    private String group_name;
    private String category_age;
    private String disp_format_name;
    private int tournament_template_id;
    private int total_match;
    private int total_time;
    private int game_duration_RR;
    private int game_duration_FM;
    private int halftime_break_RR;
    private int halftime_break_FM;
    private int match_interval_RR;
    private int match_interval_FM;
    private String created_at;
    private String updated_at;
    private String deleted_at;
    private String template_name;

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getTotal_teams() {
        return total_teams;
    }

    public void setTotal_teams(int total_teams) {
        this.total_teams = total_teams;
    }

    public int getTournament_id() {
        return tournament_id;
    }

    public void setTournament_id(int tournament_id) {
        this.tournament_id = tournament_id;
    }

    public int getMin_matches() {
        return min_matches;
    }

    public void setMin_matches(int min_matches) {
        this.min_matches = min_matches;
    }

    public String getGroup_name() {
        return group_name;
    }

    public void setGroup_name(String group_name) {
        this.group_name = group_name;
    }

    public String getCategory_age() {
        return category_age;
    }

    public void setCategory_age(String category_age) {
        this.category_age = category_age;
    }

    public String getDisp_format_name() {
        return disp_format_name;
    }

    public void setDisp_format_name(String disp_format_name) {
        this.disp_format_name = disp_format_name;
    }

    public int getTournament_template_id() {
        return tournament_template_id;
    }

    public void setTournament_template_id(int tournament_template_id) {
        this.tournament_template_id = tournament_template_id;
    }

    public int getTotal_match() {
        return total_match;
    }

    public void setTotal_match(int total_match) {
        this.total_match = total_match;
    }

    public int getTotal_time() {
        return total_time;
    }

    public void setTotal_time(int total_time) {
        this.total_time = total_time;
    }

    public int getGame_duration_RR() {
        return game_duration_RR;
    }

    public void setGame_duration_RR(int game_duration_RR) {
        this.game_duration_RR = game_duration_RR;
    }

    public int getGame_duration_FM() {
        return game_duration_FM;
    }

    public void setGame_duration_FM(int game_duration_FM) {
        this.game_duration_FM = game_duration_FM;
    }

    public int getHalftime_break_RR() {
        return halftime_break_RR;
    }

    public void setHalftime_break_RR(int halftime_break_RR) {
        this.halftime_break_RR = halftime_break_RR;
    }

    public int getHalftime_break_FM() {
        return halftime_break_FM;
    }

    public void setHalftime_break_FM(int halftime_break_FM) {
        this.halftime_break_FM = halftime_break_FM;
    }

    public int getMatch_interval_RR() {
        return match_interval_RR;
    }

    public void setMatch_interval_RR(int match_interval_RR) {
        this.match_interval_RR = match_interval_RR;
    }

    public int getMatch_interval_FM() {
        return match_interval_FM;
    }

    public void setMatch_interval_FM(int match_interval_FM) {
        this.match_interval_FM = match_interval_FM;
    }

    public String getCreated_at() {
        return created_at;
    }

    public void setCreated_at(String created_at) {
        this.created_at = created_at;
    }

    public String getUpdated_at() {
        return updated_at;
    }

    public void setUpdated_at(String updated_at) {
        this.updated_at = updated_at;
    }

    public String getDeleted_at() {
        return deleted_at;
    }

    public void setDeleted_at(String deleted_at) {
        this.deleted_at = deleted_at;
    }

    public String getTemplate_name() {
        return template_name;
    }

    public void setTemplate_name(String template_name) {
        this.template_name = template_name;
    }
}
