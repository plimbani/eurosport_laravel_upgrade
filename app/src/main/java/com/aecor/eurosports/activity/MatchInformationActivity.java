package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.content.ContextCompat;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppPreference;
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
    @BindView(R.id.iv_team_flag_1)
    protected ImageView iv_team_flag_1;
    @BindView(R.id.tv_team_score_2)
    protected TextView tv_team_score_2;
    @BindView(R.id.tv_team_name_2)
    protected TextView tv_team_name_2;
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
    @BindView(R.id.tv_referee_name)
    protected TextView tv_referee_name;
    @BindView(R.id.tv_winner_status)
    protected TextView tv_winner_status;
    private TeamFixturesModel mTeamFixturesModel;
    private Context mContext;
    private AppPreference mPreference;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.match_info);

        super.onCreate(savedInstanceState);
        mTeamFixturesModel = getIntent().getParcelableExtra(AppConstants.ARG_MATCH_INFO);
        mContext = this;
        initView();
    }

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);
        showBackButton(getString(R.string.match_info));
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getHomeScore())) {
            tv_team_score_1.setText(mTeamFixturesModel.getHomeScore());
        } else {
            tv_team_score_1.setText("");
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getAwayScore())) {
            tv_team_score_2.setText(mTeamFixturesModel.getAwayScore());
        } else {
            tv_team_score_2.setText("");
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getHomeTeam())) {
            tv_team_name_1.setText(mTeamFixturesModel.getHomeTeam());
        } else {
            tv_team_name_1.setText("");
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getAwayTeam())) {
            tv_team_name_2.setText(mTeamFixturesModel.getAwayTeam());
        } else {
            tv_team_name_2.setText("");
        }
        String referee_name = "";
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getFirst_name())) {
            referee_name = mTeamFixturesModel.getFirst_name() + " ";
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getLast_name())) {
            referee_name = referee_name + mTeamFixturesModel.getLast_name();
        }
        if (!Utility.isNullOrEmpty(referee_name)) {
            tv_referee_name.setVisibility(View.VISIBLE);
            tv_referee_name.setText(referee_name);
        } else {
            tv_referee_name.setVisibility(View.GONE);
        }

        String mStatusAndWinnerStr = "";

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getMatch_status())) {
            mStatusAndWinnerStr = mTeamFixturesModel.getMatch_status();
        }

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getMatchWinner())) {
            mStatusAndWinnerStr = mStatusAndWinnerStr + " - " + mTeamFixturesModel.getMatchWinner();
        }

        if (!Utility.isNullOrEmpty(mStatusAndWinnerStr)) {
            mStatusAndWinnerStr = mStatusAndWinnerStr + " " + getString(R.string.is_the_winner);
            tv_winner_status.setText(mStatusAndWinnerStr);
            tv_winner_status.setVisibility(View.VISIBLE);
        } else {
            tv_winner_status.setText("");
            tv_winner_status.setVisibility(View.GONE);
        }

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getHomeFlagLogo())) {
            Glide.with(mContext)
                    .load(mTeamFixturesModel.getHomeFlagLogo())
                    .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .into(new SimpleTarget<Bitmap>() {
                        @Override
                        public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                            iv_team_flag_1.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH_1, AppConstants.MAX_IMAGE_HEIGHT_1));
                        }
                    });
        } else {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                    R.drawable.globe);
            iv_team_flag_1.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH_1, AppConstants.MAX_IMAGE_HEIGHT_1));
        }

        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getAwayFlagLogo())) {
            Glide.with(mContext)
                    .load(mTeamFixturesModel.getAwayFlagLogo())
                    .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .into(new SimpleTarget<Bitmap>() {
                        @Override
                        public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                            iv_team_flag_2.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH_1, AppConstants.MAX_IMAGE_HEIGHT_1));
                        }
                    });
        } else {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                    R.drawable.globe);
            iv_team_flag_2.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH_1, AppConstants.MAX_IMAGE_HEIGHT_1));
        }

        try {
            if (!Utility.isNullOrEmpty(mTeamFixturesModel.getMatch_datetime())) {
                String language = mPreference.getString(AppConstants.LANGUAGE_SELECTION);
                if (Utility.isNullOrEmpty(language)) {
                    language = "en";
                }
                tv_dateTime.setText(Utility.getDateTimeFromServerDate(mTeamFixturesModel.getMatch_datetime(), language, mContext));
            } else {
                tv_dateTime.setText("");
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getGroup_name())) {
            tv_age_and_group_info.setText(mTeamFixturesModel.getGroup_name());
        } else {
            tv_age_and_group_info.setText("");
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getMatch_number())) {
            tv_match_id.setText(getString(R.string.match_id) + " " + mTeamFixturesModel.getMatch_number());
        } else {
            tv_match_id.setText("");
        }

        String mVenueDetail = "";
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getVenue_name())) {
            mVenueDetail = mTeamFixturesModel.getVenue_name();
        }
        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getPitch_number())) {
            mVenueDetail = mVenueDetail + " - " + mTeamFixturesModel.getPitch_number();
        }

        if (!Utility.isNullOrEmpty(mVenueDetail)) {
            tv_venue.setText(mVenueDetail);
        } else {
            tv_venue.setText("");
        }


        if (!Utility.isNullOrEmpty(mTeamFixturesModel.getHomeScore()) && !Utility.isNullOrEmpty(mTeamFixturesModel.getAwayScore()) && mTeamFixturesModel.getHomeScore().equalsIgnoreCase(mTeamFixturesModel.getAwayScore())) {
            tv_team_score_1.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            tv_team_name_1.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            tv_team_score_2.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            tv_team_name_2.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));

        } else if (!Utility.isNullOrEmpty(mTeamFixturesModel.getHomeScore()) && !Utility.isNullOrEmpty(mTeamFixturesModel.getAwayScore()) && Integer.parseInt(mTeamFixturesModel.getHomeScore()) > Integer.parseInt(mTeamFixturesModel.getAwayScore())) {
            tv_team_score_1.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            tv_team_name_1.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            tv_team_score_2.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            tv_team_name_2.setTextColor(ContextCompat.getColor(mContext, R.color.black));
        } else if (!Utility.isNullOrEmpty(mTeamFixturesModel.getHomeScore()) && !Utility.isNullOrEmpty(mTeamFixturesModel.getAwayScore()) && Integer.parseInt(mTeamFixturesModel.getHomeScore()) < Integer.parseInt(mTeamFixturesModel.getAwayScore())) {
            tv_team_score_1.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            tv_team_name_1.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            tv_team_score_2.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            tv_team_name_2.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
        } else {
            tv_team_score_1.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            tv_team_name_1.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            tv_team_score_2.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            tv_team_name_2.setTextColor(ContextCompat.getColor(mContext, R.color.black));
        }
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

}
