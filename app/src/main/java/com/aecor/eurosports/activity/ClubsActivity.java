package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.v4.view.ViewPager;
import android.support.v7.widget.Toolbar;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.ClubSectionsPagerAdapter;
import com.aecor.eurosports.util.AppConstants;

import butterknife.BindView;


public class ClubsActivity extends BaseAppCompactActivity {


    private ClubSectionsPagerAdapter mSectionsPagerAdapter;
    private Context mContext;
    @BindView(R.id.container)
    protected ViewPager mViewPager;
    @BindView(R.id.tabs)
    protected TabLayout tabLayout;

    @Override
    public void initView() {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        mViewPager.setOffscreenPageLimit(1);
        mSectionsPagerAdapter = new ClubSectionsPagerAdapter(mContext, getSupportFragmentManager());
        mViewPager.setAdapter(mSectionsPagerAdapter);
        tabLayout.setupWithViewPager(mViewPager);
        getSupportActionBar().setTitle(getString(R.string.teams).toUpperCase());

        setListener();
    }

    @Override
    public void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_CLUBS;
        setContentView(R.layout.activity_clubs);
        super.onCreate(savedInstanceState);
        mContext = this;
        initView();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent mIntent = new Intent(mContext, HomeActivity.class);
        mIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(mIntent);
    }
}
