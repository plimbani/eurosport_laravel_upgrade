package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.widget.ImageView;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;

import java.text.ParseException;

import butterknife.BindView;
import butterknife.OnClick;

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
        showBackButton(getString(R.string.match_info));
        tv_team_score_1.setText(mTeamFixturesModel.getHomeScore());
        tv_team_score_2.setText(mTeamFixturesModel.getAwayScore());
        tv_team_name_1.setText(mTeamFixturesModel.getHomeTeam());
        tv_team_name_2.setText(mTeamFixturesModel.getAwayTeam());

        tv_team_country_1.setText(mTeamFixturesModel.getHomeCountryName());
        tv_team_country_2.setText(mTeamFixturesModel.getAwayCountryName());

        Glide.with(mContext)
                .load(mTeamFixturesModel.getHomeFlagLogo())
                .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                .skipMemoryCache(true)
                .into(new SimpleTarget<Bitmap>() {
                    @Override
                    public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                        iv_team_flag_1.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
                    }
                });

        Glide.with(mContext)
                .load(mTeamFixturesModel.getAwayFlagLogo())
                .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                .skipMemoryCache(true)
                .into(new SimpleTarget<Bitmap>() {
                    @Override
                    public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                        iv_team_flag_2.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
                    }
                });



        try {
            tv_dateTime.setText(Utility.getDateTimeFromServerDate(mTeamFixturesModel.getMatch_datetime()));
        } catch (ParseException e) {
            e.printStackTrace();
        }
        tv_age_and_group_info.setText(mTeamFixturesModel.getGroup_name());
        tv_match_id.setText(getString(R.string.match_id) + " " + mTeamFixturesModel.getMatch_number());
        tv_venue.setText(mTeamFixturesModel.getVenue_name());
    }

    @Override
    protected void setListener() {

    }

    @OnClick(R.id.tv_venue)
    protected void onVenueItemClicked() {
        Intent mVenueDetailIntent = new Intent(mContext, VenueDetailActivity.class);
        mVenueDetailIntent.putExtra(AppConstants.ARG_MATCH_INFO, mTeamFixturesModel);
        startActivity(mVenueDetailIntent);
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
