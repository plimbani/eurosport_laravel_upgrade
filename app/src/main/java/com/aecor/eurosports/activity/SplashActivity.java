package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

import com.aecor.eurosports.R;
import com.crashlytics.android.Crashlytics;

import butterknife.ButterKnife;
import io.fabric.sdk.android.Fabric;

import static com.aecor.eurosports.util.AppConstants.SPLASH_TIME_OUT;

public class SplashActivity extends BaseActivity {

    private Context mContext = this;

    @Override
    public void initView() {

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

        new Handler().postDelayed(new Runnable() {

            @Override
            public void run() {
                startActivity(new Intent(mContext, LandingActivity.class));
                finish();
            }
        },SPLASH_TIME_OUT);
    }

}
