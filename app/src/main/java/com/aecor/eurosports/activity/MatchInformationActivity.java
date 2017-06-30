package com.aecor.eurosports.activity;

import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.Toolbar;
import android.widget.ImageView;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;
import com.squareup.picasso.Picasso;

import java.text.ParseException;

import butterknife.BindView;

/**
 * Created by system-local on 30-06-2017.
 */

public class MatchInformationActivity extends BaseAppCompactActivity {
    @BindView(R.id.tv_team_score_1)
    protected TextView tv_team_score_1;
    @BindView(R.id.tv_team_name_1)
    protected TextView tv_team_name_1;
    @BindView(R.id.tv_team_country_1)
    protected TextView tv_team_country_1;
    @BindView(R.id.iv_team_flag_1)
    protected ImageView iv_team_flag_1;
    @BindView(R.id.tv_team_score_2)
    protected TextView tv_team_score_2;
    @BindView(R.id.tv_team_name_2)
    protected TextView tv_team_name_2;
    @BindView(R.id.tv_team_country_2)
    protected TextView tv_team_country_2;
    @BindView(R.id.iv_team_flag_2)
    protected ImageView iv_team_flag_2;
    @BindView(R.id.tv_dateTime)
    protected TextView tv_dateTime;
    @BindView(R.id.tv_age_and_group_info)
    protected TextView tv_age_and_group_info;
    @BindView(R.id.tv_match_id)
    protected TextView tv_match_id;
    @BindView(R.id.tv_venue)
    protected TextView tv_venue;
    private TeamFixturesModel mTeamFixturesModel;
    private Context mContext;

    @Override
    protected void initView() {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(getString(R.string.match_info).toUpperCase());
        toolbar.setTitleTextColor(Color.WHITE);
        tv_team_score_1.setText(mTeamFixturesModel.getHomeScore());
        tv_team_score_2.setText(mTeamFixturesModel.getAwayScore());
        tv_team_name_1.setText(mTeamFixturesModel.getHomeTeam());
        tv_team_name_2.setText(mTeamFixturesModel.getAwayTeam());
        Picasso.with(mContext).load(mTeamFixturesModel.getHomeCountryFlag()).resize(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT).into(iv_team_flag_1);
        Picasso.with(mContext).load(mTeamFixturesModel.getAwayCountryFlag()).resize(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT).into(iv_team_flag_2);
        try {
            tv_dateTime.setText(Utility.getDateTimeFromServerDate(mTeamFixturesModel.getMatch_datetime()));
        } catch (ParseException e) {
            e.printStackTrace();
        }
        tv_age_and_group_info.setText(mTeamFixturesModel.getGroup_name());
        tv_match_id.setText(getString(R.string.match_id) + mTeamFixturesModel.getMatch_number());
        tv_venue.setText(mTeamFixturesModel.getVenue_name());
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.match_info);
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_CLUBS;

        super.onCreate(savedInstanceState);
        mTeamFixturesModel = getIntent().getParcelableExtra(AppConstants.ARG_MATCH_INFO);
        mContext = this;
        initView();
    }
}
