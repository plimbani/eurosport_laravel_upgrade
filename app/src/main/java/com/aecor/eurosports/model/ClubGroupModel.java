package com.aecor.eurosports.model;

/**
 * Created by system-local on 29-06-2017.
 */

public class ClubGroupModel {
    private String id;
    private String tournament_competation_template_id;
    private String tournament_id;
    private String name;
    private String team_size;
    private String competation_type;
    private String created_at;
    private String updated_at;
    private String group_name;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTournament_competation_template_id() {
        return tournament_competation_template_id;
    }

    public void setTournament_competation_template_id(String tournament_competation_template_id) {
        this.tournament_competation_template_id = tournament_competation_template_id;
    }

    public String getTournament_id() {
        return tournament_id;
    }

    public void setTournament_id(String tournament_id) {
        this.tournament_id = tournament_id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getTeam_size() {
        return team_size;
    }

    public void setTeam_size(String team_size) {
        this.team_size = team_size;
    }

    public String getCompetation_type() {
        return competation_type;
    }

    public void setCompetation_type(String competation_type) {
        this.competation_type = competation_type;
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

    public String getGroup_name() {
        return group_name;
    }

    public void setGroup_name(String group_name) {
        this.group_name = group_name;
    }
}
