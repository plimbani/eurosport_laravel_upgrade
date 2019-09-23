package com.aecor.eurosports.model;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class AgeGroupModel implements Parcelable {
    private List<ClubGroupModel> round_robin_groups;
    private List<DivisionGroupModel> division_groups;

    protected AgeGroupModel(Parcel in) {
        round_robin_groups = in.createTypedArrayList(ClubGroupModel.CREATOR);
        division_groups = in.createTypedArrayList(DivisionGroupModel.CREATOR);
    }

    public static final Creator<AgeGroupModel> CREATOR = new Creator<AgeGroupModel>() {
        @Override
        public AgeGroupModel createFromParcel(Parcel in) {
            return new AgeGroupModel(in);
        }

        @Override
        public AgeGroupModel[] newArray(int size) {
            return new AgeGroupModel[size];
        }
    };

    public List<ClubGroupModel> getRound_robin_groups() {
        return round_robin_groups;
    }

    public void setRound_robin_groups(List<ClubGroupModel> round_robin_groups) {
        this.round_robin_groups = round_robin_groups;
    }

    public List<DivisionGroupModel> getDivision_groups() {
        return division_groups;
    }

    public void setDivision_groups(List<DivisionGroupModel> division_groups) {
        this.division_groups = division_groups;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeTypedList(round_robin_groups);
        dest.writeTypedList(division_groups);
    }
}
