package com.aecor.eurosports.activity;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.model.LeagueModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.Utility;
import com.bumptech.glide.Glide;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;

import java.util.ArrayList;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 30-06-2017.
 */

public class FullLeageTableActivity extends AppCompatActivity {
    private ArrayList<LeagueModel> mLeagueModelData;
    @BindView(R.id.ll_group_rows)
    protected LinearLayout ll_group_rows;
    @BindView(R.id.tv_group_table_title)
    protected TextView tv_group_table_title;
    private Context mContext;
    private String mGroupName;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.full_league_table_view);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        Bundle b = getIntent().getExtras();

        if (b != null) {
            mGroupName = b.getString(AppConstants.ARG_GROUP_NAME);
        }

        mContext = this;
        mLeagueModelData = getIntent().getParcelableArrayListExtra(AppConstants.ARG_FULL_LEAGUE_TABLE_DETAIL);
        initView();

    }


    protected void showBackButton(String title) {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(title.toUpperCase());
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeAsUpIndicator(R.drawable.left_arrow_white);
        toolbar.setTitleTextColor(Color.WHITE);
    }

    protected void initView() {
        if (mLeagueModelData != null && mLeagueModelData.size() > 0) {
            showBackButton(mGroupName);
            tv_group_table_title.setText(mGroupName);
            for (int i = 0; i < mLeagueModelData.size(); i++) {
                addGroupLeagueRow(mLeagueModelData.get(i));
            }
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                // app icon in action bar clicked; go home
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private void addGroupLeagueRow(LeagueModel mLeagueModel) {
        View teamLeagueView = getLayoutInflater().inflate(R.layout.row_full_team_leaguetable, null);
        TextView tv_games = (TextView) teamLeagueView.findViewById(R.id.tv_games);
        TextView tv_w = (TextView) teamLeagueView.findViewById(R.id.tv_w);
        TextView tv_d = (TextView) teamLeagueView.findViewById(R.id.tv_d);
        TextView tv_l = (TextView) teamLeagueView.findViewById(R.id.tv_l);
        TextView tv_f_a = (TextView) teamLeagueView.findViewById(R.id.tv_f_a);
        TextView tv_goal_diff = (TextView) teamLeagueView.findViewById(R.id.tv_goal_diff);
        TextView tv_p = (TextView) teamLeagueView.findViewById(R.id.tv_p);
        TextView tv_group_table_title = (TextView) teamLeagueView.findViewById(R.id.tv_group_table_title);
        final ImageView team_flag = (ImageView) teamLeagueView.findViewById(R.id.team_flag);

        tv_group_table_title.setText(mLeagueModel.getName());
        tv_games.setText(mLeagueModel.getPlayed());
        tv_w.setText(mLeagueModel.getWon());
        tv_d.setText(mLeagueModel.getDraws());
        tv_l.setText(mLeagueModel.getLost());
        tv_f_a.setText(mLeagueModel.getGoal_for() + "-" + mLeagueModel.getGoal_against());
        tv_p.setText(mLeagueModel.getPoints());

        int goalDifferenece = Integer.parseInt(mLeagueModel.getGoal_for()) - Integer.parseInt(mLeagueModel.getGoal_against());
        String goalText = "";
        if (goalDifferenece > 0) {
            goalText = "+";
        }
        goalText = goalText + goalDifferenece;
        tv_goal_diff.setText(goalText);
        Glide.with(mContext)
                .load(mLeagueModel.getTeamFlag())
                .asBitmap()
                .into(new SimpleTarget<Bitmap>() {
                    @Override
                    public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                        team_flag.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
                    }
                });

        ll_group_rows.addView(teamLeagueView);
    }

    protected void setListener() {

    }
}
