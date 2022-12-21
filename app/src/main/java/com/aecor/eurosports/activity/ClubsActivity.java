package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.ViewTreeObserver;
import android.widget.LinearLayout;

import androidx.appcompat.widget.Toolbar;
import androidx.viewpager.widget.ViewPager;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.ClubSectionsPagerAdapter;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;
import com.google.android.material.tabs.TabLayout;

import butterknife.BindView;


public class ClubsActivity extends BaseAppCompactActivity {


    private ClubSectionsPagerAdapter mSectionsPagerAdapter;
    private Context mContext;
    @BindView(R.id.container)
    protected ViewPager mViewPager;
    @BindView(R.id.tabs)
    protected TabLayout tabLayout;
    @BindView(R.id.home_footer)
    protected LinearLayout home_footer;

    @Override
    public void initView() {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        mSectionsPagerAdapter = new ClubSectionsPagerAdapter(mContext, getSupportFragmentManager());
        mViewPager.setOffscreenPageLimit(3);
        mViewPager.setAdapter(mSectionsPagerAdapter);
        mViewPager.setOffscreenPageLimit(3);
        tabLayout.setupWithViewPager(mViewPager);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setTitle(getString(R.string.teams).toUpperCase());
        }
        final View activityRootView = findViewById(R.id.ll_main_layout);
        activityRootView.getViewTreeObserver().addOnGlobalLayoutListener(new ViewTreeObserver.OnGlobalLayoutListener() {
            @Override
            public void onGlobalLayout() {
                int heightDiff = activityRootView.getRootView().getHeight() - activityRootView.getHeight();
                if (heightDiff > Utility.dpToPx(mContext, 200)) { // if more than 200 dp, it's probably a keyboard...
                    // ... do something here
                    home_footer.setVisibility(View.GONE);
                } else {
                    home_footer.setVisibility(View.VISIBLE);
                }
            }
        });

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
