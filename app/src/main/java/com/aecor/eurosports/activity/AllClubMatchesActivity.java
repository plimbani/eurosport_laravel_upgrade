package com.aecor.eurosports.activity;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.content.ContextCompat;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
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

import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;

import butterknife.BindView;

/**
 * Created by system-local on 03-07-2017.
 */

public class AllClubMatchesActivity extends BaseAppCompactActivity {
    private final String TAG = "AllClubMatchesActivity";
    private Context mContext;
    @BindView(R.id.ll_matches)
    protected LinearLayout ll_matches;
    private AppPreference mPreference;
    private TeamDetailModel mTeamDetailModel;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.all_club_matches);
        super.onCreate(savedInstanceState);
        mContext = this;
        mTeamDetailModel = getIntent().getParcelableExtra(AppConstants.ARG_TEAM_DETAIL);

        initView();
    }

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);
        showBackButton(mTeamDetailModel.getName());
        getAllClubMatches();
        setListener();
    }

    @Override
    protected void setListener() {

    }

    private void getAllClubMatches() {

        final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
        String url = ApiConstants.GET_TEAM_FIXTURES;
        final JSONObject requestJson = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                JSONObject mTournamentData = new JSONObject();
                mTournamentData.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                mTournamentData.put("club_id", mTeamDetailModel.getClub_id());
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
                                    for (TeamFixturesModel aMTeamFixtureData : mTeamFixtureData) {
                                        addMatchesRow(aMTeamFixtureData);
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

    private void addMatchesRow(final TeamFixturesModel mFixtureModel) {

        View matchesView = getLayoutInflater().inflate(R.layout.row_team_matches, null);
        TextView team_match_date = (TextView) matchesView.findViewById(R.id.team_match_date);
        TextView team_venue = (TextView) matchesView.findViewById(R.id.team_venue);
        TextView team_match_id = (TextView) matchesView.findViewById(R.id.team_match_id);
        TextView team_age_category = (TextView) matchesView.findViewById(R.id.team_age_category);
        TextView team_round = (TextView) matchesView.findViewById(R.id.team_round);
        TextView team1_score = (TextView) matchesView.findViewById(R.id.team1_score);
        TextView team2_score = (TextView) matchesView.findViewById(R.id.team2_score);
        TextView team1_name = (TextView) matchesView.findViewById(R.id.team1_name);
        TextView team2_name = (TextView) matchesView.findViewById(R.id.team2_name);

        try {
            team_match_date.setText(Utility.getDateFromDateTime(mFixtureModel.getMatch_datetime()));
        } catch (ParseException e) {
            e.printStackTrace();
        }

        team_venue.setText(mFixtureModel.getVenue_name());
        team_match_id.setText(mFixtureModel.getMatch_number());
        team_age_category.setText(mFixtureModel.getGroup_name());
        team_round.setText(mFixtureModel.getRound());

        team1_score.setText(mFixtureModel.getHomeScore());
        team2_score.setText(mFixtureModel.getAwayScore());
        team1_name.setText(mFixtureModel.getHomeTeam());
        team2_name.setText(mFixtureModel.getAwayTeam());

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
    }
}
