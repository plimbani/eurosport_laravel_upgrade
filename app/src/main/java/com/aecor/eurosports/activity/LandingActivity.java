package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppPreference;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class LandingActivity extends BaseActivity {

    private Context mContext;
    private AppPreference mAppPref;

    @BindView(R.id.tvAppVersion) TextView tvAppVersion;

    @Override
    public void initView() {

    }

    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_landing);

        ButterKnife.bind(this);
        mContext = this;
        mAppPref = AppPreference.getInstance(mContext);
        mAppPref.clear();

        // Adds application version at the bottom of the screen
        try {
            PackageInfo pInfo = getPackageManager().getPackageInfo(getPackageName(), 0);
            String version = pInfo.versionName;
            tvAppVersion.setText(String.format(getString(R.string.app_version), version));
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }
    }

    @OnClick(R.id.signin)
    protected void signin() {
        startActivity(new Intent(mContext, SignInActivity.class));
        finish();
    }

    @OnClick(R.id.register)
    protected void register() {
        startActivity(new Intent(mContext, RegisterActivity.class));
        finish();
    }


}
