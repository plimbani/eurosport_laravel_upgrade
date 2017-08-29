package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
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

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

import butterknife.ButterKnife;
import io.fabric.sdk.android.Fabric;

import static com.aecor.eurosports.util.AppConstants.SPLASH_TIME_OUT;

public class SplashActivity extends BaseActivity {
    private final String TAG = SplashActivity.class.getSimpleName();
    private Context mContext = this;
    private AppPreference mAppSharedPref;

    @Override
    public void initView() {
        mAppSharedPref = AppPreference.getInstance(mContext);

        printLocaleMonthName("2017-01-15 09:00:00");
        printLocaleMonthName("2017-02-15 09:00:00");
        printLocaleMonthName("2017-03-15 09:00:00");
        printLocaleMonthName("2017-04-15 09:00:00");
        printLocaleMonthName("2017-05-15 09:00:00");
        printLocaleMonthName("2017-06-15 09:00:00");
        printLocaleMonthName("2017-07-15 09:00:00");
        printLocaleMonthName("2017-08-15 09:00:00");
        printLocaleMonthName("2017-09-15 09:00:00");
        printLocaleMonthName("2017-10-15 09:00:00");
        printLocaleMonthName("2017-11-15 09:00:00");
        printLocaleMonthName("2017-12-15 09:00:00");
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                isUserLogin();
            }
        }, SPLASH_TIME_OUT);
    }

    private void printLocaleMonthName(String dateTime) {
        String language = mAppSharedPref.getString(AppConstants.LANGUAGE_SELECTION);

        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", new Locale(language));
        try {
            Date d = df.parse(dateTime);
            df = new SimpleDateFormat("MMMM | MMM ", new Locale(language));
//            AppLogger.LogE(TAG, new String(df.format(d).getBytes("UTF-8"), "ISO-8859-1"));
            AppLogger.LogE(TAG, df.format(d));
        } catch (ParseException e) {
            e.printStackTrace();
        }

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
        String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
        String password = mAppSharedPref.getString(AppConstants.PREF_PASSWORD);

        if (Utility.isNullOrEmpty(email) && Utility.isNullOrEmpty(password)) {
            startActivity(new Intent(mContext, LandingActivity.class));
            finish();
        } else {
            checkuser();
        }
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
}
