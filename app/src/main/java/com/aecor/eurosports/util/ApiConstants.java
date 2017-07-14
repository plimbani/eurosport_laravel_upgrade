package com.aecor.eurosports.util;

/**
 * Created by asoni on 06-06-2016.
 */
public class ApiConstants {


    //    public static final String BASE_URL = "http://192.168.0.6:8180/api/";
//        public static final String BASE_URL = "http://kamal-eurosport.dev.aecortech.com/api/";
//    public static final String BASE_URL = "http://rishab-eurosport.dev.aecortech.com/api/";
    public static final String BASE_URL = "http://esr.aecordigitalqa.com/api/";
    public static final String GET_TOURNAMENTS = BASE_URL + "tournaments";
    public static final String SIGN_IN = BASE_URL + "auth/login";
    public static final String REGISTER = BASE_URL + "user/create";
    public static final String GET_SETTINGS_ATTRIBUTE = BASE_URL + "users/getSetting";
    public static final String UPDATE_USER_SETTINGS = BASE_URL + "users/postSetting";
    public static final String FORGOT_PASSWORD = BASE_URL + "password/email";
    public static final String RESET_PASSWORD = BASE_URL + "password/reset";
    public static final String UPDATE_PROFILE = BASE_URL + "user/update/";
    public static final String CHECK_USER = BASE_URL + "auth/check";
    public static final String SET_TOURNAMENT_AS_FAVOURITE = BASE_URL + "users/setFavourite";
    public static final String REMOVE_TOURNAMENT_FROM_FAVOURITE = BASE_URL + "users/removeFavourite";
    public static final String GET_USER_FAVOURITE_LIST = BASE_URL + "users/getLoginUserFavouriteTournament";
    public static final String SET_DEFAULT_TOURNAMENET = BASE_URL + "users/setDefaultFavourite";
    public static final String AGE_CATEGORIES = BASE_URL + "age_group/getCompetationFormat";
    public static final String TOURNAMENT_GROUP = BASE_URL + "match/getDraws";
    public static final String TOURNAMENT_CLUBS = BASE_URL + "tournaments/getTournamentClub";
    public static final String GET_TEAM_LIST = BASE_URL + "teams/getTeamsList";
    public static final String GET_TEAM_FIXTURES = BASE_URL + "match/getFixtures";
    public static final String GET_GROUP_STANDING = BASE_URL + "match/getStanding";
    public static final String UPDATE_PROFILE_IMAGE = BASE_URL + "users/updateProfileImage";
}
    
