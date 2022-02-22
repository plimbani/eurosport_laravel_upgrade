package com.aecor.eurosports.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by system-local on 29-06-2017.
 */

public class ClubGroupModel implements Parcelable {
    private String id;
    private String tournament_competation_template_id;
    private String tournament_id;
    private String name;
    private String team_size;
    private String competation_type;
    private String created_at;
    private String updated_at;
    private String group_name;
    private String actual_competition_type;
    private String display_name;
    private String age_category_division_id;
    private String actual_name;
    private String is_manual_override_standing;
    private String color_code;
    private String competation_round_no;
    private String divisionName;
    private String divisionId;
    private boolean isShowDivisionOnly;

    public ClubGroupModel() {

    }

    protected ClubGroupModel(Parcel in) {
        id = in.readString();
        tournament_competation_template_id = in.readString();
        tournament_id = in.readString();
        name = in.readString();
        team_size = in.readString();
        competation_type = in.readString();
        created_at = in.readString();
        updated_at = in.readString();
        group_name = in.readString();
        actual_competition_type = in.readString();
        display_name = in.readString();
        age_category_division_id = in.readString();
        actual_name = in.readString();
        is_manual_override_standing = in.readString();
        color_code = in.readString();
        competation_round_no = in.readString();
        divisionName = in.readString();
        divisionId = in.readString();
        isShowDivisionOnly = in.readByte() != 0;
    }

    public static final Creator<ClubGroupModel> CREATOR = new Creator<ClubGroupModel>() {
        @Override
        public ClubGroupModel createFromParcel(Parcel in) {
            return new ClubGroupModel(in);
        }

        @Override
        public ClubGroupModel[] newArray(int size) {
            return new ClubGroupModel[size];
        }
    };

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

    public String getActual_competition_type() {
        return actual_competition_type;
    }

    public void setActual_competition_type(String actual_competition_type) {
        this.actual_competition_type = actual_competition_type;
    }

    public String getDisplay_name() {
        return display_name;
    }

    public void setDisplay_name(String display_name) {
        this.display_name = display_name;
    }

    public String getAge_category_division_id() {
        return age_category_division_id;
    }

    public void setAge_category_division_id(String age_category_division_id) {
        this.age_category_division_id = age_category_division_id;
    }

    public String getActual_name() {
        return actual_name;
    }

    public void setActual_name(String actual_name) {
        this.actual_name = actual_name;
    }

    public String getIs_manual_override_standing() {
        return is_manual_override_standing;
    }

    public void setIs_manual_override_standing(String is_manual_override_standing) {
        this.is_manual_override_standing = is_manual_override_standing;
    }

    public String getColor_code() {
        return color_code;
    }

    public void setColor_code(String color_code) {
        this.color_code = color_code;
    }

    public String getCompetation_round_no() {
        return competation_round_no;
    }

    public void setCompetation_round_no(String competation_round_no) {
        this.competation_round_no = competation_round_no;
    }

    public String getDivisionName() {
        return divisionName;
    }

    public void setDivisionName(String divisionName) {
        this.divisionName = divisionName;
    }

    public String getDivisionId() {
        return divisionId;
    }

    public void setDivisionId(String divisionId) {
        this.divisionId = divisionId;
    }

    public boolean isShowDivisionOnly() {
        return isShowDivisionOnly;
    }

    public void setShowDivisionOnly(boolean showDivisionOnly) {
        isShowDivisionOnly = showDivisionOnly;
    }

    @Override
    public int describeContents() {
        return 0;
    }


    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(id);
        dest.writeString(tournament_competation_template_id);
        dest.writeString(tournament_id);
        dest.writeString(name);
        dest.writeString(team_size);
        dest.writeString(competation_type);
        dest.writeString(created_at);
        dest.writeString(updated_at);
        dest.writeString(group_name);
        dest.writeString(actual_competition_type);
        dest.writeString(display_name);
        dest.writeString(age_category_division_id);
        dest.writeString(actual_name);
        dest.writeString(is_manual_override_standing);
        dest.writeString(color_code);
        dest.writeString(competation_round_no);
        dest.writeString(divisionName);
        dest.writeString(divisionId);
        dest.writeByte((byte) (isShowDivisionOnly ? 1 : 0));
    }
}
