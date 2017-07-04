package com.aecor.eurosports.activity;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.util.AppConstants;

import butterknife.BindView;
import butterknife.OnClick;

/**
 * Created by system-local on 03-07-2017.
 */

public class VenueDetailActivity extends BaseAppCompactActivity {
    private Context mContext;
    private TeamFixturesModel mTeamFixturesModel;
    @BindView(R.id.tv_pitch_name)
    protected TextView tv_pitch_name;
    @BindView(R.id.tv_address)
    protected TextView tv_address;
    @BindView(R.id.tv_playing_surface)
    protected TextView tv_playing_surface;

    @OnClick(R.id.ll_view_on_map)
    protected void onViewOnMapClicked() {

    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.venue_detail);
        super.onCreate(savedInstanceState);
        mTeamFixturesModel = getIntent().getParcelableExtra(AppConstants.ARG_MATCH_INFO);
        mContext = this;
        initView();
    }

    private void getVenueDetailFromId() {

    }

    @Override
    protected void initView() {
        showBackButton(getString(R.string.venue));
        getVenueDetailFromId();
    }

    @Override
    protected void setListener() {

    }
}
