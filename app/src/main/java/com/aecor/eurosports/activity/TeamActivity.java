package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.content.ContextCompat;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.LeagueModel;
import com.aecor.eurosports.model.TeamDetailModel;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.util.ArrayList;
import java.util.Arrays;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class TeamActivity extends BaseAppCompactActivity {
    private final String TAG = "TeamActivity";
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
    private TeamDetailModel mTeamDetailModel;
    private Context mContext;
    private AppPreference mPreference;
    private LeagueModel mLeagueModelData[];

    @OnClick(R.id.tv_view_full_league_table)
    protected void onFullLeagueViewClicked() {
        Intent mFullLeagueTableIntent = new Intent(mContext, FullLeageTableActivity.class);
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_FULL_LEAGUE_TABLE_DETAIL, new ArrayList<LeagueModel>(Arrays.asList(mLeagueModelData)));
        startActivity(mFullLeagueTableIntent);

    }

    @OnClick(R.id.tv_view_all_club_matches)
    protected void onViewAllClubMatchesClicked() {
        Intent mAllClubMatchesActivity = new Intent(mContext, AllClubMatchesActivity.class);
        mAllClubMatchesActivity.putExtra(AppConstants.ARG_TEAM_DETAIL, mTeamDetailModel);
        startActivity(mAllClubMatchesActivity);
    }

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);

        tv_team_name.setText(mTeamDetailModel.getName());
        if (!Utility.isNullOrEmpty(mTeamDetailModel.getCountryLogo())) {
            Picasso.with(mContext).load(mTeamDetailModel.getCountryLogo()).resize(40, 25).into(iv_team_flag);
        }
        tv_countryName.setText(mTeamDetailModel.getCountryName());
        tv_team_member_desc.setText(mTeamDetailModel.getAge_group_id() + ", " + mTeamDetailModel.getGroup_name());
        showBackButton(getString(R.string.team));
        getTeamFixtures();
        getGroupStanding();

    }

    @Override
    protected void setListener() {

    }


    private void getGroupStanding() {

        final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
        String url = ApiConstants.GET_GROUP_STANDING;
        final JSONObject requestJson = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                JSONObject mTournamentData = new JSONObject();
                mTournamentData.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                mTournamentData.put("competationId", mTeamDetailModel.getGroupId());
                requestJson.put("tournamentData", mTournamentData);
            } catch (JSONException e) {
                e.printStackTrace();
            }
            AppLogger.LogE(TAG, "url" + url);
            AppLogger.LogE(TAG, "requestJson" + requestJson.toString());

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressHUD);
                    try {
                        AppLogger.LogE(TAG, "getGroupStanding Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                mLeagueModelData = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), LeagueModel[].class);
                                if (mLeagueModelData != null && mLeagueModelData.length > 0) {
                                    for (int i = 0; i < mLeagueModelData.length; i++) {
                                        addGroupLeagueRow(mLeagueModelData[i]);
                                    }
                                } else {
                                    addNoItemGroupLeagueView();
                                }

                            }
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress(mProgressHUD);
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest);
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_team);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        mContext = this;
        mTeamDetailModel = getIntent().getParcelableExtra(AppConstants.ARG_TEAM_DETAIL);
        initView();
    }


    private void addGroupLeagueRow(LeagueModel mLeagueModel) {
        tv_group_table_title.setText(mLeagueModel.getAssigned_group());
        View teamLeagueView = getLayoutInflater().inflate(R.layout.row_team_leaguetable, null);
        TextView tv_group_name = (TextView) teamLeagueView.findViewById(R.id.tv_group_name);
        TextView tv_points = (TextView) teamLeagueView.findViewById(R.id.tv_points);
        TextView tv_games = (TextView) teamLeagueView.findViewById(R.id.tv_games);
        TextView tv_goalDifference = (TextView) teamLeagueView.findViewById(R.id.tv_goalDifference);
        ImageView team_flag = (ImageView) teamLeagueView.findViewById(R.id.team_flag);
        String groupTableTitle = mLeagueModel.getName() + " " + getString(R.string.league_table);
        tv_group_table_title.setText(groupTableTitle);
        tv_group_name.setText(mLeagueModel.getName());
        tv_points.setText(mLeagueModel.getPoints());
        tv_games.setText(mLeagueModel.getPlayed());
        int goalDifferenece = Integer.parseInt(mLeagueModel.getGoal_for()) - Integer.parseInt(mLeagueModel.getGoal_against());
        String goalText = "";
        if (goalDifferenece > 0) {
            goalText = "+";
        }
        goalText = goalText + goalDifferenece;
        tv_goalDifference.setText(goalText);
        Picasso.with(mContext).load(mLeagueModel.getTeamFlag()).resize(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT).into(team_flag);

        ll_group_rows.addView(teamLeagueView);
    }

    private void addNoItemGroupLeagueView() {
        View noMatchesView = getLayoutInflater().inflate(R.layout.no_item_textview, null);
        TextView tv_noMatchesView = (TextView) noMatchesView.findViewById(R.id.tv_no_item);
        tv_noMatchesView.setText(getString(R.string.no_league_data_available));
        tv_view_full_league_table.setVisibility(View.GONE);

        ll_group_rows.addView(noMatchesView);
    }

    private void addMatchesRow(final TeamFixturesModel mFixtureModel) {
        tv_view_all_club_matches.setVisibility(View.VISIBLE);

        View matchesView = getLayoutInflater().inflate(R.layout.row_team_matches, null);
        TextView team_match_date = (TextView) matchesView.findViewById(R.id.team_match_date);
        TextView team_venue = (TextView) matchesView.findViewById(R.id.team_venue);
        TextView team_match_id = (TextView) matchesView.findViewById(R.id.team_match_id);
        TextView team_age_category = (TextView) matchesView.findViewById(R.id.team_age_category);
        TextView team_round = (TextView) matchesView.findViewById(R.id.team_round);
        TextView team1_score = (TextView) matchesView.findViewById(R.id.team1_score);
        TextView team2_score = (TextView) matchesView.findViewById(R.id.team2_score);
        try {
            team_match_date.setText(Utility.getDateFromDateTime(mFixtureModel.getMatch_datetime()));
        } catch (ParseException e) {
            e.printStackTrace();
        }

        team_venue.setText(mFixtureModel.getVenue_name());
        team_match_id.setText(mFixtureModel.getMatch_number());
        team_age_category.setText(mFixtureModel.getGroup_name());
        team_round.setText(mFixtureModel.getRound());
        team1_score.setText(mFixtureModel.getHomeScore() + " " + mFixtureModel.getHomeTeam());
        team2_score.setText(mFixtureModel.getAwayScore() + " " + mFixtureModel.getAwayTeam());

        if (mFixtureModel.getHomeScore().equalsIgnoreCase(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
        } else if (Integer.parseInt(mFixtureModel.getHomeScore()) > Integer.parseInt(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.black));
        } else {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
        }
        matchesView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent mMatchInfoIntent = new Intent(mContext, MatchInformationActivity.class);
                mMatchInfoIntent.putExtra(AppConstants.ARG_MATCH_INFO, mFixtureModel);
                startActivity(mMatchInfoIntent);
            }
        });

        ll_matches.addView(matchesView);
    }

    private void addNoItemTeamFixtureView() {
        View noMatchesView = getLayoutInflater().inflate(R.layout.no_item_textview, null);
        TextView tv_noMatchesView = (TextView) noMatchesView.findViewById(R.id.tv_no_item);
        tv_noMatchesView.setText(getString(R.string.no_matches_available));
        ll_matches.addView(noMatchesView);
        tv_view_all_club_matches.setVisibility(View.GONE);
    }

    private void getTeamFixtures() {

        final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
        String url = ApiConstants.GET_TEAM_FIXTURES;
        final JSONObject requestJson = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                JSONObject mTournamentData = new JSONObject();
                mTournamentData.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                mTournamentData.put("teamId", mTeamDetailModel.getId());
                mTournamentData.put("is_scheduled", "1");
                requestJson.put("tournamentData", mTournamentData);
            } catch (JSONException e) {
                e.printStackTrace();
            }
            AppLogger.LogE(TAG, "url" + url);
            AppLogger.LogE(TAG, "requestJson" + requestJson.toString());


            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressHUD);
                    try {
                        AppLogger.LogE(TAG, "getTeamFixtures Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TeamFixturesModel mTeamFixtureData[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamFixturesModel[].class);
                                if (mTeamFixtureData != null && mTeamFixtureData.length > 0) {
                                    for (int i = 0; i < mTeamFixtureData.length; i++) {
                                        addMatchesRow(mTeamFixtureData[i]);
                                    }
                                } else {
                                    addNoItemTeamFixtureView();
                                }

                            }
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress(mProgressHUD);
                        Utility.parseVolleyError(mContext, error);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            });
            mQueue.add(jsonRequest);
        }
    }
}
