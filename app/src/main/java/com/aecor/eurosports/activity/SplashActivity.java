package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.RequiresApi;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ProfileModel;
import com.aecor.eurosports.ui.ViewDialog;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.crashlytics.android.Crashlytics;

import org.json.JSONException;
import org.json.JSONObject;

import butterknife.ButterKnife;
import io.fabric.sdk.android.Fabric;

import static com.aecor.eurosports.util.AppConstants.SPLASH_TIME_OUT;

public class SplashActivity extends BaseActivity {
    private final String TAG = SplashActivity.class.getSimpleName();
    private Context mContext = this;
    private AppPreference mAppSharedPref;
    private String installedAppVersion = "";

    @Override
    public void initView() {
        mAppSharedPref = AppPreference.getInstance(mContext);

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                checkAppVersion();
            }
        }, SPLASH_TIME_OUT);
    }


    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Fabric.with(this, new Crashlytics());
        setContentView(R.layout.activity_splash_screen);
        ButterKnife.bind(this);
        initView();

    }

    private void isUserLogin() {
        checkStoreCredentials();


    }

    private void checkuser() {

        if (Utility.isInternetAvailable(mContext)) {
            String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
            String password = mAppSharedPref.getString(AppConstants.PREF_PASSWORD);
//            mAppSharedPref.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, "");
            String url = ApiConstants.SIGN_IN;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("email", email);
                requestJson.put("password", password);
            } catch (JSONException e) {
                e.printStackTrace();
            }

            AppLogger.LogE(TAG, "***** Splash screen request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
//                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Splash Screen response *****" + response.toString());
                        String token = response.get(AppConstants.PREF_TOKEN).toString();
                        mAppSharedPref.setString(AppConstants.PREF_TOKEN, token);
                        validate_user();
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
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });
            mQueue.add(jsonRequest);
        } else {
            ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.no_internet), mContext.getString(R.string.internet_message), mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN)
                @Override
                public void onPositiveButtonClicked() {
                    if (mContext instanceof SplashActivity) {
                        finishAffinity();
                    }
                }

            });
        }
    }

    private void validate_user() {


        if (Utility.isInternetAvailable(mContext)) {
            String url = ApiConstants.CHECK_USER;
            final JSONObject requestJson1 = new JSONObject();
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest1 = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson1, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    try {
                        AppLogger.LogE(TAG, "***** Sign in response *****" + response.toString());

                        if (response.getString("authenticated").equalsIgnoreCase("true")) {
                            ProfileModel profileModel = GsonConverter.getInstance().decodeFromJsonString(response.get("userData").toString(), ProfileModel.class);
                            String profile = GsonConverter.getInstance().encodeToJsonString(profileModel);
                            JSONObject jsonObject = new JSONObject(response.get("userData").toString());
                            mAppSharedPref.setString(AppConstants.PREF_PROFILE, profile);
                            mAppSharedPref.setString(AppConstants.PREF_USER_ID, jsonObject.getString("user_id"));
                            if (!BuildConfig.isEasyMatchManager)
                                mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
                            mAppSharedPref.setString(AppConstants.PREF_IMAGE_URL, jsonObject.getString("profile_image_url"));
                            if (jsonObject.has("role")) {
                                mAppSharedPref.setString(AppConstants.PREF_ROLE, jsonObject.getString("role"));
                            }
                            if (jsonObject.has("country_id")) {
                                mAppSharedPref.setString(AppConstants.PREF_COUNTRY_ID, jsonObject.getString("country_id"));
                            }
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
                            if (BuildConfig.isEasyMatchManager) {
                                if (jsonObject.has("tournament_id") && !Utility.isNullOrEmpty(jsonObject.getString("tournament_id"))) {
                                    mAppSharedPref.setString(AppConstants.PREF_TOURNAMENT_ID, jsonObject.getString("tournament_id"));
                                }
                            }
                            postUserDeviceDetails(mContext);
                        } else {
//                            {"authenticated":false,"message":"Account de-activated please contact your administrator."}
                            if (response.has("message") && !Utility.isNullOrEmpty(response.getString("message"))) {
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

    private void launchHome() {
        if (BuildConfig.isEasyMatchManager) {
            if (!Utility.isNullOrEmpty(mAppSharedPref.getString(AppConstants.PREF_TOURNAMENT_ID))) {
                if (mAppSharedPref.getString(AppConstants.PREF_COUNTRY_ID) == null) {
                    //profile screen
                    startActivity(new Intent(mContext, ProfileActivity.class));
                } else {
                    // home screen
                    startActivity(new Intent(mContext, HomeActivity.class));
                }
            } else {
                // get started screen
                startActivity(new Intent(mContext, GetStartedActivity.class));
            }
        } else {
            if (mAppSharedPref.getString(AppConstants.PREF_COUNTRY_ID) == null) {
                //profile screen
                startActivity(new Intent(mContext, ProfileActivity.class));
            } else {
                // home screen
                startActivity(new Intent(mContext, HomeActivity.class));
            }
        }
        finish();
    }

    private void checkAppVersion() {
        if (Utility.isInternetAvailable(mContext)) {
            String url = ApiConstants.APP_VERSION;
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
                        AppLogger.LogD("TAG", "server apk version " + serverVersion);
                        PackageManager manager = getPackageManager();
                        PackageInfo info;
                        try {
                            info = manager.getPackageInfo(getPackageName(), 0);
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
                            showUpdateDialog();
                        } else {
                            isUserLogin();
                        }
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
                        checkStoreCredentials();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });
            mQueue.add(jsonRequest);
        } else {
            ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.no_internet), mContext.getString(R.string.internet_message), mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN)
                @Override
                public void onPositiveButtonClicked() {
                    if (mContext instanceof SplashActivity) {
                        finishAffinity();
                    }
                }

            });
        }
    }

    private void showUpdateDialog() {

        ViewDialog.showTwoButtonDialog(this,
                getString(R.string.update_available),
                getString(R.string.update_message_new),
                getString(R.string.update),
                getString(R.string.cancel),
                new ViewDialog.UpdateDialogInterface() {
                    @Override
                    public void onPositiveButtonClicked() {

                        final String my_package_name = getApplicationContext()
                                .getPackageName(); // <- HERE YOUR PACKAGE NAME!!
                        String url = "";

                        try {
                            // Check whether Google Play store is installed or not:
                            getPackageManager().getPackageInfo(
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
                        startActivity(intent);
                    }

                    @Override
                    public void onNegativeButtonClicked() {
//                        Intent j = new Intent(Intent.ACTION_MAIN);
//                        j.addCategory(Intent.CATEGORY_HOME);
//                        j.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                        startActivity(j);
//                        finish();
//                        System.exit(0);
                        checkStoreCredentials();


                    }
                });
    }

    private void checkStoreCredentials() {
        if (Utility.isInternetAvailable(mContext)) {

            String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
            String password = mAppSharedPref.getString(AppConstants.PREF_PASSWORD);

            if (Utility.isNullOrEmpty(email) && Utility.isNullOrEmpty(password)) {
                startActivity(new Intent(mContext, LandingActivity.class));
                finish();
            } else {
                checkuser();
            }
        } else {
            ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.no_internet), mContext.getString(R.string.internet_message), mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN)
                @Override
                public void onPositiveButtonClicked() {
                    if (mContext instanceof SplashActivity) {
                        finishAffinity();
                    }
                }

            });
        }
    }

    private void postUserDeviceDetails(final Context mContext) {

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
                        AppLogger.LogE("TAG", "***** Post User details response *****" + response.toString());

                        launchHome();
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
