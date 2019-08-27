package com.aecor.eurosports.model;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class DivisionGroupModel implements Parcelable {
    private String title;
    private List<ClubGroupModel> data;
    private boolean isRowExpanded;

    protected DivisionGroupModel(Parcel in) {
        title = in.readString();
        data = in.createTypedArrayList(ClubGroupModel.CREATOR);
        isRowExpanded = in.readByte() != 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(title);
        dest.writeTypedList(data);
        dest.writeByte((byte) (isRowExpanded ? 1 : 0));
    }

    @Override
    public int describeContents() {
        return 0;
    }

    public static final Creator<DivisionGroupModel> CREATOR = new Creator<DivisionGroupModel>() {
        @Override
        public DivisionGroupModel createFromParcel(Parcel in) {
            return new DivisionGroupModel(in);
        }

        @Override
        public DivisionGroupModel[] newArray(int size) {
            return new DivisionGroupModel[size];
        }
    };

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public List<ClubGroupModel> getData() {
        return data;
    }

    public void setData(List<ClubGroupModel> data) {
        this.data = data;
    }

    public boolean isRowExpanded() {
        return isRowExpanded;
    }

    public void setRowExpanded(boolean rowExpanded) {
        isRowExpanded = rowExpanded;
    }
}
