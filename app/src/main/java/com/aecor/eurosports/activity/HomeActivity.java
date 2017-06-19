package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import com.aecor.eurosports.R;

import butterknife.ButterKnife;
import butterknife.OnClick;

public class HomeActivity extends BaseAppCompactActivity {

    private Context mContext;

    @Override
    public void initView() {

    }

    @Override
    public void setListener() {
        setListener();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }

    @OnClick(R.id.lv_clubs)
    protected void submit() {
        startActivity(new Intent(mContext, ClubsActivity.class));
    }
}

