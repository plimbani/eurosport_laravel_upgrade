package com.aecor.eurosports.activity;

import android.annotation.SuppressLint;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.content.ContextCompat;

import com.aecor.eurosports.R;
import com.aecor.eurosports.application.ApplicationClass;
import com.aecor.eurosports.model.LeagueModel;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.ConnectivityChangeReceiver;
import com.aecor.eurosports.util.Utility;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.DataSource;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.load.engine.GlideException;
import com.bumptech.glide.request.RequestListener;
import com.bumptech.glide.request.target.Target;

import java.util.ArrayList;

import butterknife.BindView;
import butterknife.ButterKnife;

/**
 * Created by system-local on 30-06-2017.
 */

public class FullLeageTableActivity extends AppCompatActivity implements ConnectivityChangeReceiver.ConnectivityReceiverListener {
    private ArrayList<LeagueModel> mLeagueModelData;
    @BindView(R.id.tl_group_rows)
    protected TableLayout tl_group_rows;
    @BindView(R.id.tv_group_table_title)
    protected TextView tv_group_table_title;
    //    @BindView(R.id.iv_testFlag)
//    protected ImageView iv_testFlag;
    private Context mContext;
    private String mGroupName;
    private String mTeamId;
    @BindView(R.id.tv_no_internet)
    protected TextView tv_no_internet;


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.full_league_table_view);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        Bundle b = getIntent().getExtras();

        if (b != null && b.containsKey(AppConstants.ARG_GROUP_NAME)) {
            mGroupName = b.getString(AppConstants.ARG_GROUP_NAME);
        }
        if (b != null && b.containsKey(AppConstants.ARG_TEAM_ID)) {
            mTeamId = b.getString(AppConstants.ARG_TEAM_ID);
        }

        mContext = this;
        mLeagueModelData = getIntent().getParcelableArrayListExtra(AppConstants.ARG_FULL_LEAGUE_TABLE_DETAIL);
        initView();

    }


    protected void showBackButton(String title) {
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        toolbar.setTitleTextColor(Color.WHITE);
        toolbar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        if (getSupportActionBar() != null) {
            getSupportActionBar().setTitle(title.toUpperCase());
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setHomeAsUpIndicator(R.drawable.left_arrow_white);
        }
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

    @SuppressLint("CheckResult")
    private void addGroupLeagueRow(LeagueModel mLeagueModel) {
        View teamLeagueView = getLayoutInflater().inflate(R.layout.row_full_team_leaguetable, null);
        LinearLayout ll_row_background = (LinearLayout) teamLeagueView.findViewById(R.id.ll_row_background);
        if (!Utility.isNullOrEmpty(mTeamId) && !Utility.isNullOrEmpty(mLeagueModel.getId())) {
            if (mLeagueModel.getId().equalsIgnoreCase(mTeamId)) {
                ll_row_background.setBackgroundColor(ContextCompat.getColor(mContext, R.color.light_green));
            } else {
                ll_row_background.setBackgroundColor(ContextCompat.getColor(mContext, R.color.white));
            }
        } else {
            ll_row_background.setBackgroundColor(ContextCompat.getColor(mContext, R.color.white));
        }

        TextView tv_games = (TextView) teamLeagueView.findViewById(R.id.tv_games);
        TextView tv_w = (TextView) teamLeagueView.findViewById(R.id.tv_w);
        TextView tv_d = (TextView) teamLeagueView.findViewById(R.id.tv_d);
        TextView tv_l = (TextView) teamLeagueView.findViewById(R.id.tv_l);
        TextView tv_f = (TextView) teamLeagueView.findViewById(R.id.tv_f);
        TextView tv_a = (TextView) teamLeagueView.findViewById(R.id.tv_a);
        TextView tv_goal_diff = (TextView) teamLeagueView.findViewById(R.id.tv_goal_diff);
        TextView tv_p = (TextView) teamLeagueView.findViewById(R.id.tv_p);
        TextView tv_group_table_title = (TextView) teamLeagueView.findViewById(R.id.tv_group_table_title);
        final ImageView team_flag = (ImageView) teamLeagueView.findViewById(R.id.team_flag);
        if (!Utility.isNullOrEmpty(mLeagueModel.getName())) {
            tv_group_table_title.setText(mLeagueModel.getName());
        }
        if (!Utility.isNullOrEmpty(mLeagueModel.getPlayed())) {
            tv_games.setText(mLeagueModel.getPlayed());
        }
        if (!Utility.isNullOrEmpty(mLeagueModel.getWon())) {
            tv_w.setText(mLeagueModel.getWon());
        }
        if (!Utility.isNullOrEmpty(mLeagueModel.getDraws())) {
            tv_d.setText(mLeagueModel.getDraws());
        }
        if (!Utility.isNullOrEmpty(mLeagueModel.getLost())) {
            tv_l.setText(mLeagueModel.getLost());
        }

        if (!Utility.isNullOrEmpty(mLeagueModel.getGoal_for())) {
            tv_f.setText(mLeagueModel.getGoal_for());
        }
        if (!Utility.isNullOrEmpty(mLeagueModel.getGoal_against())) {
            tv_a.setText(mLeagueModel.getGoal_against());
        }

        if (!Utility.isNullOrEmpty(mLeagueModel.getPoints())) {
            tv_p.setText(mLeagueModel.getPoints());
        }

        int goalDifferenece = 0;
        if (!Utility.isNullOrEmpty(mLeagueModel.getGoal_for()) && !Utility.isNullOrEmpty(mLeagueModel.getGoal_against())) {
            goalDifferenece = Integer.parseInt(mLeagueModel.getGoal_for()) - Integer.parseInt(mLeagueModel.getGoal_against());
        } else if (!Utility.isNullOrEmpty(mLeagueModel.getGoal_for())) {
            goalDifferenece = Integer.parseInt(mLeagueModel.getGoal_for());
        } else if (!Utility.isNullOrEmpty(mLeagueModel.getGoal_against())) {
            goalDifferenece = 0 - Integer.parseInt(mLeagueModel.getGoal_against());
        }
        String goalText = "";
        if (goalDifferenece > 0) {
            goalText = "+";
        }
        goalText = goalText + goalDifferenece;
        tv_goal_diff.setText(goalText);
        if (!Utility.isNullOrEmpty(mLeagueModel.getTeamFlag())) {
            Glide.with(mContext)
                    .asBitmap()
                    .load(mLeagueModel.getTeamFlag())
                    .diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .listener(new RequestListener<Bitmap>() {
                        @Override
                        public boolean onLoadFailed(@Nullable GlideException e, Object model, Target<Bitmap> target, boolean isFirstResource) {
                            return false;
                        }

                        @Override
                        public boolean onResourceReady(Bitmap resource, Object model, Target<Bitmap> target, DataSource dataSource, boolean isFirstResource) {
                            // resource is your loaded Bitmap
                            team_flag.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
                            return true;
                        }
                    });
        } else {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                    R.drawable.globe);
            team_flag.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
        }
        tl_group_rows.addView(teamLeagueView);
        View seperatorView = getLayoutInflater().inflate(R.layout.table_row_seperator_full, null);
        seperatorView.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, 1));

        tl_group_rows.addView(seperatorView);
    }

    @Override
    protected void onResume() {
        super.onResume();
        ApplicationClass.getInstance().setConnectivityListener(this);
        checkConnection();
    }

    @Override
    public void onNetworkConnectionChanged() {
        checkConnection();
    }

    // Method to manually check connection status
    protected void checkConnection() {
        boolean isConnected = ConnectivityChangeReceiver.isConnected();
        if (isConnected) {
            tv_no_internet.setVisibility(View.GONE);
        } else {
            tv_no_internet.setVisibility(View.VISIBLE);
        }
    }
}
