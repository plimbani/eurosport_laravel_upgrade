package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

import com.aecor.eurosports.R;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
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

    @Override
    public void initView() {
        mAppSharedPref = AppPreference.getInstance(mContext);
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

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                isUserLogin();
            }
        },SPLASH_TIME_OUT);
    }

    private void isUserLogin() {
        String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
        String password = mAppSharedPref.getString(AppConstants.PREF_PASSWORD);

        if(Utility.isNullOrEmpty(email) && Utility.isNullOrEmpty(password)){
            startActivity(new Intent(mContext, LandingActivity.class));
            finish();
        }
        else {
            checkuser();
        }
    }

    private void checkuser() {
        String email = mAppSharedPref.getString(AppConstants.PREF_EMAIL);
        String password = mAppSharedPref.getString(AppConstants.PREF_PASSWORD);
//        Utility.startProgress(mContext);
        String url = ApiConstants.SIGN_IN;
        final JSONObject requestJson = new JSONObject();
        try {
            requestJson.put("email", email);
            requestJson.put("password", password);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if (Utility.isInternetAvailable(mContext)) {
            AppLogger.LogE(TAG, "***** Splash screen request *****" + requestJson.toString());
            final RequestQueue mQueue = VolleySingeltone.getInstance(mContext).getRequestQueue();
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
//                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "***** Splash Screen response *****" + response.toString());
                        String token = response.get(AppConstants.PREF_TOKEN).toString();
                        mAppSharedPref.setString(AppConstants.PREF_TOKEN,token);
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
                        Utility.StopProgress();
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });
            mQueue.add(jsonRequest);
        }
    }
}
