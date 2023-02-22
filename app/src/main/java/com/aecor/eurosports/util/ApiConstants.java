package com.aecor.eurosports.util;

/**
 * Created by asoni on 06-06-2016.
 */
public class ApiConstants {
    //    www.esrtmp.com -> live
    //    qa.esrtmp.com -> qa
//           public static final String BASE_URL = "https://www.esrtmp.com/api/"; // ESR Live
    public static final String BASE_URL = "https://qa.wot.esrtmp.com/api/"; // ESR QA
//            public static final String BASE_URL = "https://tournament-qa.easymatchmanager.com/api/"; // EasyMatchManager QA
//     public static final String BASE_URL = "http://mukesh-wot.dev.aecortech.com/api/"; // Local Test
    public static final String DEEPLINK_URL = "comm-qa.wot.esrtmp.com";
    //    public static final String DEEPLINK_URL = "usama-eurosport.dev.aecortech.com";
    public static final String ACCESS_CODE = BASE_URL + "tournament/access_code";
    public static final String GET_TOURNAMENTS = BASE_URL + "tournaments";
     public static final String PROJECT_CONFIGURATION = BASE_URL + "get_project_configurations";
    public static final String SIGN_IN = BASE_URL + "auth/login";
    public static final String REGISTER = BASE_URL + "user/create";
    public static final String RESEND_EMAIL = BASE_URL + "userResendEmail";
    public static final String FORGOT_PASSWORD = BASE_URL + "password/email";
    public static final String CHECK_USER = BASE_URL + "auth/check";
    public static final String GET_ALL_COUNTRY = BASE_URL + "getCountries";
    public static final String GET_GRAPHIC_IMAGE_URL = BASE_URL + "viewGraphicImage";
    public static final String GET_SETTINGS_ATTRIBUTE = BASE_URL + "users/getSetting";
    public static final String UPDATE_USER_SETTINGS = BASE_URL + "users/postSetting";
    public static final String UPDATE_PROFILE = BASE_URL + "user/update/";
    public static final String SET_TOURNAMENT_AS_FAVOURITE = BASE_URL + "users/setFavourite";
    public static final String REMOVE_TOURNAMENT_FROM_FAVOURITE = BASE_URL + "users/removeFavourite";
    public static final String GET_USER_FAVOURITE_LIST = BASE_URL + "users/getLoginUserFavouriteTournament";
    public static final String SET_DEFAULT_TOURNAMENET = BASE_URL + "users/setDefaultFavourite";
    public static final String AGE_CATEGORIES = BASE_URL + "age_group/getCompetationFormat";
    public static final String TOURNAMENT_GROUP = BASE_URL + "match/getDraws";
    public static final String GET_FINAL_PLACING_MATCHES = BASE_URL + "age_group/getPlacingsData";
    public static final String TOURNAMENT_CLUBS = BASE_URL + "tournaments/getTournamentClub";
    public static final String GET_TEAM_LIST = BASE_URL + "teams/getTeamsList";
    public static final String GET_TEAM_FIXTURES = BASE_URL + "match/getFixtures";
    public static final String GET_GROUP_STANDING = BASE_URL + "match/getStanding/yes";
    public static final String POST_FCM_TOKEN = BASE_URL + "users/updatefcm";
    public static final String GET_TEAM_DETAILS = BASE_URL + "getTournamentTeamDetails";
    public static final String FACEBOOK_LOGIN = BASE_URL + "auth/social/login";
    public static final String POST_USER_DETAILS = BASE_URL + "updateAppDeviceVersion";
}

