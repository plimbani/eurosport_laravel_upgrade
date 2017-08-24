package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import com.aecor.eurosports.R;
import com.aecor.eurosports.util.AppPreference;

import butterknife.ButterKnife;
import butterknife.OnClick;

public class LandingActivity extends BaseActivity {

    private Context mContext;
    private AppPreference mAppPref;

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
