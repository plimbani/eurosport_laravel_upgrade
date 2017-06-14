package com.aecor.eurosports.util;

/**
 * Created by asoni on 06-06-2016.
 */
public class ApiConstants {
    public static final String URL_BASE = "http://hitesha-jobmaster-api.dev.aecortech.com/"; // Live
     public static final String URL_STARTUP = URL_BASE + "/api/v1/startup";
    public static final String URL_REGISTER = URL_BASE + "/api/v1/users/notification/register";
    public static final String URL_ACKNOWLEDGE = URL_BASE + "/api/v1/push_message/acknowledge";
    public static final String URL_GET_MESSAGE_DETAILS = URL_BASE + "/api/v1/message/text";
    public static final String URL_POST_MESSAGE_DETAILS = URL_BASE + "/api/v1/message/storeResponse";
    public static final String URL_GET_ALL_MESSAGES = URL_BASE + "/api/v1/message/fetchAll";
    public static final String URL_SYNC_API = URL_BASE + "/api/v1/syncPush";
    public static final String URL_SEND_ALERT_EMAIL = URL_BASE + "/api/v1/message/alert";
    public static final String URL_GET_SETTINGS_ATTRIBUTE = URL_BASE + "/api/v1/users/getSettingParam";
    public static final String URL_POST_SETTINGS_ATTRIBUTE = URL_BASE + "/api/v1/users/postSettingParam";
}
