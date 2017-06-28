package com.aecor.eurosports.activity;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;

import org.w3c.dom.Text;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class TeamActivity extends BaseAppCompactActivity {
    @BindView(R.id.tv_team_name)
    protected TextView tv_team_name;
    @BindView(R.id.iv_team_flag)
    protected ImageView iv_team_flag;
    @BindView(R.id.tv_countryName)
    protected TextView tv_countryName;
    @BindView(R.id.tv_team_member_desc)
    protected TextView tv_team_member_desc;
    @BindView(R.id.tv_group_table_title)
    protected TextView tv_group_table_title;
    @BindView(R.id.tv_view_full_league_table)
    protected TextView tv_view_full_league_table;
    @BindView(R.id.tv_view_all_club_matches)
    protected TextView tv_view_all_club_matches;
    @BindView(R.id.ll_group_rows)
    protected LinearLayout ll_group_rows;
    @BindView(R.id.ll_matches)
    protected LinearLayout ll_matches;

    @OnClick(R.id.tv_view_full_league_table)
    protected void onFullLeagueViewClicked() {

    }   @OnClick(R.id.tv_view_all_club_matches)
    protected void onViewAllClubMatchesClicked() {

    }

    @Override
    protected void initView() {
        addGroupLeagueRow(0, false);
        addGroupLeagueRow(1, false);
        addGroupLeagueRow(2, false);
        addGroupLeagueRow(3, true);
        addMatchesRow(0);
        addMatchesRow(1);
        addMatchesRow(2);
        addMatchesRow(3);
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_team);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        initView();
    }

    private void addGroupLeagueRow(int pos, boolean isRowSelected) {
        View teamLeagueView = getLayoutInflater().inflate(R.layout.row_team_leaguetable, null);
        ll_group_rows.addView(teamLeagueView);
    }

    private void addMatchesRow(int pos) {
        View matchesView = getLayoutInflater().inflate(R.layout.row_team_matches, null);
        ll_matches.addView(matchesView);
    }
}
