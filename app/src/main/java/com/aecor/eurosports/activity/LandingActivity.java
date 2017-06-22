package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import com.aecor.eurosports.R;

import butterknife.ButterKnife;
import butterknife.OnClick;

public class LandingActivity extends BaseActivity {

    private Context mContext;

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
    }

    @OnClick(R.id.signin)
    protected void signin() {
        startActivity(new Intent(mContext, SignInActivity.class));
    }

    @OnClick(R.id.register)
    protected void register() {
        startActivity(new Intent(mContext, RegisterActivity.class));
    }
}
