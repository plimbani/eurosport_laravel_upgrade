package com.aecor.eurosports.model;

import java.util.List;

public class AgeGroupModel {
    private List<ClubGroupModel> round_robin_groups;
    private List<DivisionGroupModel> division_groups;

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
}
