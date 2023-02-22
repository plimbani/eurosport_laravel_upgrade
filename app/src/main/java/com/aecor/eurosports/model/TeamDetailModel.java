package com.aecor.eurosports.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by system-local on 29-06-2017.
 */

public class TeamDetailModel implements Parcelable {


    private String id;
    private String assigned_group;
    private String tournament_id;
    private String user_id;
    private String age_group_id;
    private String club_id;
    private String group_name;
    private String name;
    private String place;
    private String shirt_color;
    private String esr_reference;
    private String countryId;
    private String countryLogo;
    private String CountryName;
    private String ageGroupId;
    private String ageGroupName;
    private String CategoryAge;
    private String GroupId;
    private boolean isFavorite;

    public boolean isFavorite() {
        return isFavorite;
    }

    public void setFavorite(boolean favorite) {
        isFavorite = favorite;
    }

    protected TeamDetailModel(Parcel in) {
        id = in.readString();
        assigned_group = in.readString();
        tournament_id = in.readString();
        user_id = in.readString();
        age_group_id = in.readString();
        club_id = in.readString();
        group_name = in.readString();
        name = in.readString();
        place = in.readString();
        shirt_color = in.readString();
        esr_reference = in.readString();
        countryId = in.readString();
        countryLogo = in.readString();
        CountryName = in.readString();
        ageGroupId = in.readString();
        ageGroupName = in.readString();
        CategoryAge = in.readString();
        GroupId = in.readString();
    }

    public static final Creator<TeamDetailModel> CREATOR = new Creator<TeamDetailModel>() {
        @Override
        public TeamDetailModel createFromParcel(Parcel in) {
            return new TeamDetailModel(in);
        }

        @Override
        public TeamDetailModel[] newArray(int size) {
            return new TeamDetailModel[size];
        }
    };

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getAssigned_group() {
        return assigned_group;
    }

    public void setAssigned_group(String assigned_group) {
        this.assigned_group = assigned_group;
    }

    public String getTournament_id() {
        return tournament_id;
    }

    public void setTournament_id(String tournament_id) {
        this.tournament_id = tournament_id;
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

    public String getCountryId() {
        return countryId;
    }

    public void setCountryId(String countryId) {
        this.countryId = countryId;
    }

    public String getCountryLogo() {
        return countryLogo;
    }

    public void setCountryLogo(String countryLogo) {
        this.countryLogo = countryLogo;
    }

    public String getCountryName() {
        return CountryName;
    }

    public void setCountryName(String countryName) {
        CountryName = countryName;
    }

    public String getAgeGroupId() {
        return ageGroupId;
    }

    public void setAgeGroupId(String ageGroupId) {
        this.ageGroupId = ageGroupId;
    }

    public String getAgeGroupName() {
        return ageGroupName;
    }

    public void setAgeGroupName(String ageGroupName) {
        this.ageGroupName = ageGroupName;
    }

    public String getCategoryAge() {
        return CategoryAge;
    }

    public void setCategoryAge(String categoryAge) {
        CategoryAge = categoryAge;
    }

    public String getGroupId() {
        return GroupId;
    }

    public void setGroupId(String groupId) {
        GroupId = groupId;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(id);
        dest.writeString(assigned_group);
        dest.writeString(tournament_id);
        dest.writeString(user_id);
        dest.writeString(age_group_id);
        dest.writeString(club_id);
        dest.writeString(group_name);
        dest.writeString(name);
        dest.writeString(place);
        dest.writeString(shirt_color);
        dest.writeString(esr_reference);
        dest.writeString(countryId);
        dest.writeString(countryLogo);
        dest.writeString(CountryName);
        dest.writeString(ageGroupId);
        dest.writeString(ageGroupName);
        dest.writeString(CategoryAge);
        dest.writeString(GroupId);
    }
}
