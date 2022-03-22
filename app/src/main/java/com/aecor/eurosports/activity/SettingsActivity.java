package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.Nullable;

import com.aecor.eurosports.R;
import com.aecor.eurosports.ui.ViewDialog;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;

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

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent mIntent = new Intent(mContext, HomeActivity.class);
        mIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(mIntent);
    }

    @OnClick(R.id.tv_help)
    protected void onHelpClicked() {
        Intent mHelpIntent = new Intent(mContext, HelpActivity.class);
        startActivity(mHelpIntent);
    }

    @OnClick(R.id.tv_privacy_terms)
    protected void onPrivacyTermsClicked() {
        Intent mHelpIntent = new Intent(mContext, PrivacyAndTermsActivity.class);
        startActivity(mHelpIntent);
    }

    @OnClick(R.id.tv_profile)
    protected void onProfileClicked() {
        Intent mHelpIntent = new Intent(mContext, ProfileActivity.class);
        startActivity(mHelpIntent);
        finish();

    }

    @OnClick(R.id.tv_notification)
    protected void onNotificationClicked() {
        Intent mHelpIntent = new Intent(mContext, NotificationAndSoundActivity.class);
        startActivity(mHelpIntent);
    }

    @OnClick(R.id.tv_logout)
    protected void onLogoutClicked() {
        logoutFromApp();
    }

    private void logoutFromApp() {
        ViewDialog.showTwoButtonDialog(((Activity) mContext), getString(R.string.confirm), getString(R.string.are_you_sure_you_want_to_log_out), getString(R.string.logout), getString(R.string.cancel), new ViewDialog.CustomDialogInterface() {
            @Override
            public void onPositiveButtonClicked() {
                AppPreference mAppPref = AppPreference.getInstance(mContext);
                mAppPref.clear();
                Utility.setLocale(mContext, "en");

                Intent intent = new Intent(mContext, LandingActivity.class);
                ComponentName cn = intent.getComponent();
                Intent mainIntent = Intent.makeRestartActivityTask(cn);
                startActivity(mainIntent);
                finish();
            }
        });
    }


}
