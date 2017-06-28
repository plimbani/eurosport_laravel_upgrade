package com.aecor.eurosports.util;

/**
 * Created by asoni on 06-06-2016.
 */
public class ApiConstants {
    public static final String BASE_URL = "http://kamal-eurosport.dev.aecortech.com/api/";
    public static final String GET_TOURNAMENTS = BASE_URL + "tournaments";
    public static final String SIGN_IN = BASE_URL + "auth/login";
    public static final String REGISTER = BASE_URL + "user/create";
    public static final String URL_GET_SETTINGS_ATTRIBUTE = BASE_URL + "user/create";
    public static final String CHECK_USER = BASE_URL + "auth/check";
    public static final String SET_TOURNAMENT_AS_FAVOURITE = BASE_URL + "users/setFavourite";
    public static final String REMOVE_TOURNAMENT_FROM_FAVOURITE = BASE_URL + "users/removeFavourite";
    public static final String GET_USER_FAVOURITE_LIST = BASE_URL + "users/removeFavourite";
    public static final String GET_LOGGEDIN_USER_DEFAULT_TOURNAMENT = BASE_URL + "users/getLoginUserDefaultTournament";
}
    