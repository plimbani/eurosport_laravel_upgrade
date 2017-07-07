package com.aecor.eurosports.activity;

import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.content.IntentCompat;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;

import butterknife.OnClick;

/**
 * Created by system-local on 25-04-2017.
 */

public class SettingsActivity extends BaseAppCompactActivity {
    private final String TAG = "SettingsActivity";
    private Context mContext;

    @Override
    protected void initView() {

    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_USER_SETTINGS;
        setContentView(R.layout.activity_settings);
        super.onCreate(savedInstanceState);
        mContext = this;
    }

    @OnClick(R.id.ll_help)
    protected void onHelpClicked() {
        Intent mHelpIntent = new Intent(mContext, HelpActivity.class);
        startActivity(mHelpIntent);
    }

    @OnClick(R.id.ll_privacy_terms)
    protected void onPrivacyTermsClicked() {
        Intent mHelpIntent = new Intent(mContext, PrivacyAndTermsActivity.class);
        startActivity(mHelpIntent);
    }

    @OnClick(R.id.ll_profile)
    protected void onProfileClicked() {
        Intent mHelpIntent = new Intent(mContext, ProfileActivity.class);
        startActivity(mHelpIntent);

    }

    @OnClick(R.id.ll_notification)
    protected void onNotificationClicked() {
        Intent mHelpIntent = new Intent(mContext, NotificationAndSoundActivity.class);
        startActivity(mHelpIntent);
    }

    @OnClick(R.id.ll_logout)
    protected void onLogoutClicked() {
        logoutFromApp();
    }

    private void logoutFromApp() {
        AppPreference mAppPref = AppPreference.getInstance(mContext);
        mAppPref.clear();
        Intent intent = new Intent(mContext, LandingActivity.class);
        ComponentName cn = intent.getComponent();
        Intent mainIntent = IntentCompat.makeRestartActivityTask(cn);
        startActivity(mainIntent);
        finish();
    }


    @Override
    protected void onResume() {
        super.onResume();
    }
}
