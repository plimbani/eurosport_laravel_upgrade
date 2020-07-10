package com.aecor.eurosports.util;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Build;

import androidx.annotation.RequiresApi;

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.LandingActivity;
import com.aecor.eurosports.activity.SignInActivity;
import com.aecor.eurosports.activity.UpdateAppDialogPopup;
import com.aecor.eurosports.application.ApplicationClass;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ProfileModel;
import com.aecor.eurosports.ui.ViewDialog;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.testfairy.TestFairy;

import org.json.JSONException;
import org.json.JSONObject;

import okhttp3.internal.Util;

public class AutoLoginUtils {
    private final static String TAG = "AutoLoginUtils";
    private static AppPreference mAppSharedPref;
    private static String installedAppVersion = "";

    public interface AutoLoginInterface {
        void onAutoLoginComplete();
    }


    public static void checkAppVersion(final Context mContext) {
        if (Utility.isInternetAvailable(mContext)) {
            String url = ApiConstants.PROJECT_CONFIGURATION;
            final JSONObject requestJson = new JSONObject();

            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
//                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** App Version response *****" + response.toString());
                        String serverVersion = "1";
                        if (response.has("android_app_version")) {
                            serverVersion = response.getString("android_app_version");
                        }

                        if (response != null && response.has("enable_testfairy_android")) {
                            mAppSharedPref.setString(AppConstants.KEY_ENABLE_TF_ANDROID, response.getString("enable_testfairy_android"));
                        }
                        if (response != null && response.has("enable_testfairy_video_capture_android")) {
                            mAppSharedPref.setString(AppConstants.KEY_ENABLE_TF_VIDEO_ANDROID, response.getString("enable_testfairy_video_capture_android"));
                        }
                        if (response != null && response.has("enable_testfairy_feedback_android")) {
                            mAppSharedPref.setString(AppConstants.KEY_ENABLE_TF_FEEDBACK_ANDROID, response.getString("enable_testfairy_feedback_android"));
                        }
                        Utility.setTFFlags(mContext);
                        AppLogger.LogD("TAG", "server apk version " + serverVersion);
                        PackageManager manager = mContext.getPackageManager();
                        PackageInfo info;
                        try {
                            info = manager.getPackageInfo(mContext.getPackageName(), 0);
                            installedAppVersion = info.versionName;
                        } catch (PackageManager.NameNotFoundException e) {
                            // TODO Auto-generated catch block
                            e.printStackTrace();
                        }
                        // add check here for version comparison
                        if (installedAppVersion != null
                                && !installedAppVersion.equals("")
                                && serverVersion != null
                                && !serverVersion.equals("")
                                && Utility.compare(installedAppVersion, serverVersion)) {
                            showUpdateDialog(ApplicationClass.getInstance().getmActivity().get());
                        }
                        checkStoreCredentials(mContext);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
//                        Utility.StopProgress();
                        Utility.parseVolleyError(mContext, error);
                        checkStoreCredentials(mContext);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });
            mQueue.add(jsonRequest);
        } else {
            if (mContext instanceof Activity) {
                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.no_internet), mContext.getString(R.string.internet_message), mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                    @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN)
                    @Override
                    public void onPositiveButtonClicked() {

                        System.exit(0);

                    }

                });
            }
        }
    }

    private static void checkStoreCredentials(Context mContext) {
        if (Utility.isInternetAvailable(mContext)) {
            mAppSharedPref = AppPreference.getInstance(mContext);
            if (mAppSharedPref.getBoolean(AppConstants.IS_LOGIN_USING_FB) && !Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_TOKEN))) {
                validate_user(mContext);
            } else {
                checkuser(mContext);
            }
        } else {
            ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.no_internet), mContext.getString(R.string.internet_message), mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN)
                @Override
                public void onPositiveButtonClicked() {

                }

            });
        }
    }

    private static void showUpdateDialog(final Activity mActivity) {

        ViewDialog.showTwoButtonDialog(mActivity,
                mActivity.getString(R.string.update_available),
                mActivity.getString(R.string.update_message_new),
                mActivity.getString(R.string.update),
                mActivity.getString(R.string.cancel),
                new ViewDialog.UpdateDialogInterface() {
                    @Override
                    public void onPositiveButtonClicked() {

                        final String my_package_name = mActivity.getApplicationContext()
                                .getPackageName(); // <- HERE YOUR PACKAGE NAME!!
                        String url = "";

                        try {
                            // Check whether Google Play store is installed or not:
                            mActivity.getPackageManager().getPackageInfo(
                                    "com.android.vending", 0);

                            url = "market://details?id=" + my_package_name;
                        } catch (final Exception e) {
                            url = "https://play.google.com/store/apps/details?id="
                                    + my_package_name;
                        }

                        // Open the app page in Google Play store:
                        final Intent intent = new Intent(Intent.ACTION_VIEW,
                                Uri.parse(url));
                        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                                | Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        mActivity.startActivity(intent);
                    }

                    @Override
                    public void onNegativeButtonClicked() {
//                        Intent j = new Intent(Intent.ACTION_MAIN);
//                        j.addCategory(Intent.CATEGORY_HOME);
//                        j.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                        mActivity.startActivity(j);
//                        System.exit(0);


                    }
                });
    }

    private static void showUpdateAppDialog(final Context mContext) {

        Intent updateItent = new Intent(mContext,
                UpdateAppDialogPopup.class);
        updateItent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        mContext.startActivity(updateItent);


    }

    public static void checkuser(final Context mContext) {

        String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
        String password = mAppSharedPref.getString(AppConstants.PREF_PASSWORD);

        if (!Utility.isNullOrEmpty(email) && !Utility.isNullOrEmpty(password)) {
//            mAppSharedPref.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, "");
            String url = ApiConstants.SIGN_IN;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("email", email);
                requestJson.put("password", password);
            } catch (JSONException e) {
                e.printStackTrace();
            }

            AppLogger.LogE(TAG, "***** CHECK USER screen request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    try {
                        AppLogger.LogE(TAG, "*****  CHECK USER Screen response *****" + response.toString());
                        String token = response.get(AppConstants.PREF_TOKEN).toString();
                        mAppSharedPref.setString(AppConstants.PREF_TOKEN, token);
                        validate_user(mContext);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        AppLogger.LogE(TAG, "***** Check user api error *****" + error);
                        Intent launcherIntent = new Intent(mContext,
                                SignInActivity.class);
                        launcherIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                        mContext.startActivity(launcherIntent);
                        ((Activity) mContext).finish();

                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });
            mQueue.add(jsonRequest);
        }
    }

    private static void validate_user(final Context mContext) {


        if (Utility.isInternetAvailable(mContext)) {
            String url = ApiConstants.CHECK_USER;
            final JSONObject requestJson1 = new JSONObject();
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson1, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Sign in response *****" + response.toString());

                        if (response.getString("authenticated").equalsIgnoreCase("true")) {
                            ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(response.get("userData").toString(), ProfileModel.class);
                            String profile = GsonConverter.getInstance().encodeToJsonString(profileModel);
                            JSONObject jsonObject = new JSONObject(response.get("userData").toString());
                            mAppSharedPref.setString(AppConstants.PREF_PROFILE, profile);
                            mAppSharedPref.setString(AppConstants.PREF_USER_ID, jsonObject.getString("user_id"));
//                            mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
                            mAppSharedPref.setString(AppConstants.PREF_IMAGE_URL, jsonObject.getString("profile_image_url"));
                            if (jsonObject.has("locale") && !Utility.isNullOrEmpty(jsonObject.getString("locale"))) {
                                mAppSharedPref.setString(AppConstants.PREF_USER_LOCALE, jsonObject.getString("locale"));
                                mAppSharedPref.setString(AppConstants.LANGUAGE_SELECTION, jsonObject.getString("locale"));
                                Utility.setLocale(mContext, jsonObject.getString("locale"));
                            }
                            if (jsonObject.has("settings")) {
                                JSONObject mSettingsJson = jsonObject.getJSONObject("settings");
                                if (mSettingsJson.has("value") && !Utility.isNullOrEmpty(mSettingsJson.getString("value"))) {
                                    JSONObject mValue = new JSONObject(mSettingsJson.getString("value"));
                                    if (mValue.has("is_sound") && !Utility.isNullOrEmpty(mValue.getString("is_sound")) && mValue.getString("is_sound").equalsIgnoreCase("true")) {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, true);
                                    } else {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_SOUND, false);
                                    }

                                    if (mValue.has("is_vibration") && !Utility.isNullOrEmpty(mValue.getString("is_vibration")) && mValue.getString("is_vibration").equalsIgnoreCase("true")) {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, true);
                                    } else {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_VIBRATION, false);
                                    }
                                    if (mValue.has("is_notification") && !Utility.isNullOrEmpty(mValue.getString("is_notification")) && mValue.getString("is_notification").equalsIgnoreCase("true")) {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, true);
                                    } else {
                                        mAppSharedPref.setBoolean(AppConstants.KEY_IS_NOTIFICATION, false);
                                    }
                                }
                            }
                            postUserDeviceDetails(mContext);
                        } else {
//                            {"authenticated":false,"message":"Account de-activated please contact your administrator."}
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.email_verification), response.getString("message"), mContext.getString(R.string.resend_email), new ViewDialog.CustomDialogInterface() {
                                    @Override
                                    public void onPositiveButtonClicked() {
                                        if (mAppSharedPref.getString(AppConstants.PREF_EMAIL) != null) {
                                            resendEmail(mContext);
                                        }
                                    }
                                });
                            } else {
                                Intent launcherIntent = new Intent(mContext,
                                        LandingActivity.class);
                                launcherIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                                mContext.startActivity(launcherIntent);
                                ((Activity) mContext).finish();
                            }
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
                        Intent launcherIntent = new Intent(mContext,
                                SignInActivity.class);
                        launcherIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                        mContext.startActivity(launcherIntent);
                        ((Activity) mContext).finish();

                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest1);
        }
    }


    private static void resendEmail(final Context mContext) {
        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.RESEND_EMAIL;
            final JSONObject requestJson1 = new JSONObject();
            try {
                requestJson1.put("email", mAppSharedPref.getString(AppConstants.PREF_EMAIL));
            } catch (JSONException e) {
                e.printStackTrace();
            }
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson1, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Resend email response *****" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
                                String messgae = response.getString("message");
                                Utility.showToast(mContext, messgae);
                            }
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest1);
        }
    }



    private static void postUserDeviceDetails(final Context mContext) {
        Utility.setTFFlags(mContext);
        if (Utility.isInternetAvailable(mContext)) {
            PackageManager manager = mContext.getPackageManager();
            PackageInfo info;
            String installedAppVersion = "";
            try {
                info = manager.getPackageInfo(mContext.getPackageName(), 0);
                installedAppVersion = info.versionName;
            } catch (PackageManager.NameNotFoundException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }
            Utility.startProgress(mContext);
            String url = ApiConstants.POST_USER_DETAILS;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("device", "Android");
                requestJson.put("app_version", installedAppVersion);
                requestJson.put("os_version", Utility.getOsVersion(mContext));
                requestJson.put("user_id", mAppSharedPref.getString(AppConstants.PREF_USER_ID));
            } catch (JSONException e) {
                e.printStackTrace();
            }
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Post User details response *****" + response.toString());


                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest1);
        }
    }

}
