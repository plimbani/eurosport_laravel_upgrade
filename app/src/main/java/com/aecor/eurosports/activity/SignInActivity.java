package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import com.aecor.eurosports.R;

public class SignInActivity extends BaseActivity {

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
        setContentView(R.layout.activity_sign_in);
        mContext = this;
        startActivity(new Intent(mContext, HomeActivity.class));
    }
}
