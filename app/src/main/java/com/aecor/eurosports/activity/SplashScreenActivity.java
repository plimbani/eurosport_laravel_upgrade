package com.aecor.eurosports.activity;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;

import com.aecor.eurosports.R;
import com.crashlytics.android.Crashlytics;

import butterknife.ButterKnife;
import butterknife.OnClick;
import io.fabric.sdk.android.Fabric;

public class SplashScreenActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Fabric.with(this, new Crashlytics());
        setContentView(R.layout.activity_splash_screen);
        ButterKnife.bind(this);
    }

    @OnClick(R.id.cm)
    public void submit(View v) {
        startActivity(new Intent(this, VenueMapActivity.class));
    }
}
