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

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
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

import java.util.regex.Pattern;

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
                isUserLogin();
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
        checkAppVersion();

    }

    private void checkuser() {

        if (Utility.isInternetAvailable(mContext)) {
            String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
            String password = mAppSharedPref.getString(AppConstants.PREF_PASSWORD);
            mAppSharedPref.setString(AppConstants.PREF_SESSION_TOURNAMENT_ID, "");
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
                        startActivity(new Intent(mContext, HomeActivity.class));
                        finish();
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

    private String normalisedVersion(String version) {
        return normalisedVersion(version, ".", 4);
    }

    private String normalisedVersion(String version, String sep, int maxWidth) {
        String[] split = Pattern.compile(sep, Pattern.LITERAL).split(version);
        StringBuilder sb = new StringBuilder();
        for (String s : split) {
            sb.append(String.format("%" + maxWidth + 's', s));
        }
        return sb.toString();
    }

    private boolean compare(String v1, String v2) {
        boolean returnFlag = false;
        String s1 = normalisedVersion(v1);
        String s2 = normalisedVersion(v2);
        int cmp = s1.compareTo(s2);
        String cmpStr = cmp < 0 ? "<" : cmp > 0 ? ">" : "==";
        System.out.printf("result: " + "'%s' %s '%s'%n", v1, cmpStr, v2);
        if (cmpStr.contains("<")) {
            returnFlag = true;

        }
        if (cmpStr.contains(">") || cmpStr.contains("==")) {
            returnFlag = false;
        }
        return returnFlag;
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
                                && compare(installedAppVersion, serverVersion)) {
                            showUpdateDialog();
                        } else {
                            checkStoreCredentials();
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
                getString(R.string.update),
                getString(R.string.update_message),
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
                        Intent j = new Intent(Intent.ACTION_MAIN);
                        j.addCategory(Intent.CATEGORY_HOME);
                        j.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        startActivity(j);
                        finish();
                        System.exit(0);
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
}
