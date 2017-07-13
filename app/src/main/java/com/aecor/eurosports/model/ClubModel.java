package com.aecor.eurosports.model;

/**
 * Created by system-local on 29-06-2017.
 */

public class ClubModel {
    private String ClubId;
    private String clubName;
    private String countryId;
    private String CountryName;
    private String CountryLogo;

    public String getCountryId() {
        return countryId;
    }

    public void setCountryId(String countryId) {
        this.countryId = countryId;
    }

    public String getCountryName() {
        return CountryName;
    }

    public void setCountryName(String countryName) {
        CountryName = countryName;
    }

    public String getCountryLogo() {
        return CountryLogo;
    }

    public void setCountryLogo(String countryLogo) {
        CountryLogo = countryLogo;
    }

    public String getClubId() {
        return ClubId;
    }

    public void setClubId(String clubId) {
        ClubId = clubId;
    }

    public String getClubName() {
        return clubName;
    }

    public void setClubName(String clubName) {
        this.clubName = clubName;
    }
}
