package com.aecor.eurosports.activity;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.util.AppConstants;

/**
 * Created by system-local on 03-07-2017.
 */

public class VenueDetailActivity extends BaseAppCompactActivity {
    private Context mContext;
    private TeamFixturesModel mTeamFixturesModel;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.venue_detail);
        super.onCreate(savedInstanceState);
        mTeamFixturesModel = getIntent().getParcelableExtra(AppConstants.ARG_MATCH_INFO);
        mContext = this;
        initView();
    }

    @Override
    protected void initView() {
        showBackButton(getString(R.string.venue));
    }

    @Override
    protected void setListener() {

    }
}
