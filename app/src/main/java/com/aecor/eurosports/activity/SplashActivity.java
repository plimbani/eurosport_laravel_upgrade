package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.crashlytics.android.Crashlytics;

import butterknife.ButterKnife;
import io.fabric.sdk.android.Fabric;

import static com.aecor.eurosports.util.AppConstants.SPLASH_TIME_OUT;

public class SplashActivity extends BaseActivity {

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
            startActivity(new Intent(mContext, HomeActivity.class));
            finish();
        }
    }
}
