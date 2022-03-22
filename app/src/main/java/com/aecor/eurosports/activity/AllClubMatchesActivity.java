package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Html;
import android.view.Gravity;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.core.content.ContextCompat;

import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TeamDetailModel;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.ViewDialog;
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
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;

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
        if (mTeamDetailModel!=null && !Utility.isNullOrEmpty(mTeamDetailModel.getName())) {
            showBackButton(mTeamDetailModel.getName());
        } else {
            showBackButton(getString(R.string.team_matches));
        }
        getAllClubMatches();
        setListener();
    }

    @Override
    protected void setListener() {

    }

    private void getAllClubMatches() {


        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_TEAM_FIXTURES;
            final JSONObject requestJson = new JSONObject();
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
                                    Collections.sort(Arrays.asList(mTeamFixtureData), new Comparator<TeamFixturesModel>() {
                                        public int compare(TeamFixturesModel o1, TeamFixturesModel o2) {
                                            if (o1.getMatch_datetime() == null) {
                                                return (o2.getMatch_datetime() == null) ? 0 : -1;
                                            }
                                            if (o2.getMatch_datetime() == null) {
                                                return 1;
                                            }
                                            return o1.getMatch_datetime().compareTo(o2.getMatch_datetime());
                                        }
                                    });
                                    for (TeamFixturesModel aMTeamFixtureData : mTeamFixtureData) {
                                        addMatchesRow(aMTeamFixtureData);
                                    }
                                } else {
                                    addNoItemTeamFixtureView();
                                }

                            }
                        } else if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("500")) {
                            String msg = "Selected tournament has expired";
                            if (response.has("message")) {
                                msg = response.getString("message");
                            }
                            ViewDialog.showSingleButtonDialog((Activity) mContext, mContext.getString(R.string.error), msg, mContext.getString(R.string.button_ok), new ViewDialog.CustomDialogInterface() {
                                @Override
                                public void onPositiveButtonClicked() {
                                    Intent intent = new Intent(mContext, HomeActivity.class);
                                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                    mContext.startActivity(intent);
                                    ((Activity) mContext).finish();
                                }

                            });
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
        } else {
            checkConnection();
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
            if (!Utility.isNullOrEmpty(mFixtureModel.getMatch_datetime())) {
                String language = mPreference.getString(AppConstants.LANGUAGE_SELECTION);
                if (Utility.isNullOrEmpty(language)) {
                    language = "en";
                }
                team_match_date.setText(Utility.getDateFromDateTime(mFixtureModel.getMatch_datetime(), mPreference.getString(language), mContext));
            } else {
                team_match_date.setText("");
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }
        String mPitchDetail = "";
        if (!Utility.isNullOrEmpty(mFixtureModel.getVenue_name())) {
            mPitchDetail = mFixtureModel.getVenue_name();
        }
        if (!Utility.isNullOrEmpty(mFixtureModel.getPitch_number())) {
            mPitchDetail = mPitchDetail + " - " + mFixtureModel.getPitch_number();
        }
        team_venue.setText(mPitchDetail);
        if (!Utility.isNullOrEmpty(mFixtureModel.getDisplayMatchNumber())) {
            String mMatchId = mFixtureModel.getDisplayMatchNumber();
            mMatchId = mMatchId.replace(AppConstants.KEY_HOME, mFixtureModel.getDisplayHomeTeamPlaceholderName());
            mMatchId = mMatchId.replace(AppConstants.KEY_AWAY, mFixtureModel.getDisplayAwayTeamPlaceholderName());
            team_match_id.setText(mMatchId);
        } else {
            team_match_id.setText("");
        }


        if (!Utility.isNullOrEmpty(mFixtureModel.getGroup_name())) {
            team_age_category.setText(mFixtureModel.getGroup_name());
        } else {
            team_age_category.setText("");
        }
        if (!Utility.isNullOrEmpty(mFixtureModel.getRound())) {
            team_round.setText(mFixtureModel.getRound());
        } else {
            team_round.setText("");
        }
        if (!Utility.isNullOrEmpty(mFixtureModel.getHomeScore())) {
            team1_score.setText(mFixtureModel.getHomeScore());
        } else {
            team1_score.setText("");
        }
        if (!Utility.isNullOrEmpty(mFixtureModel.getAwayScore())) {
            team2_score.setText(mFixtureModel.getAwayScore());
        } else {
            team2_score.setText("");
        }
        if (mFixtureModel.getHome_id().equalsIgnoreCase("0")) {
            if (!Utility.isNullOrEmpty(mFixtureModel.getHomeTeamName()) && mFixtureModel.getHomeTeamName().equalsIgnoreCase(AppConstants.TEAM_NAME_PLACE_HOLDER)) {
                if (!Utility.isNullOrEmpty(mFixtureModel.getCompetition_actual_name()) && mFixtureModel.getCompetition_actual_name().contains(AppConstants.COMPETATION_NAME_GROUP)) {
                    team1_name.setText(mFixtureModel.getHomePlaceholder());
                } else if (!Utility.isNullOrEmpty(mFixtureModel.getCompetition_actual_name()) && mFixtureModel.getCompetition_actual_name().contains(AppConstants.COMPETATION_NAME_POS)) {
                    team1_name.setText(AppConstants.COMPETATION_NAME_POS + "-" + mFixtureModel.getHomePlaceholder());
                } else {
                    if (!Utility.isNullOrEmpty(mFixtureModel.getHomeTeam())) {
                        team1_name.setText(mFixtureModel.getHomeTeam());
                    } else {
                        team1_name.setText("");
                    }
                }
            } else {
                if (!Utility.isNullOrEmpty(mFixtureModel.getDisplayHomeTeamPlaceholderName())) {
                    team1_name.setText(mFixtureModel.getDisplayHomeTeamPlaceholderName());
                } else {
                    team1_name.setText("");
                }
            }

        } else {
            if (!Utility.isNullOrEmpty(mFixtureModel.getHomeTeam())) {
                team1_name.setText(mFixtureModel.getHomeTeam());
            } else {
                team1_name.setText("");
            }
        }
        if (mFixtureModel.getAway_id().equalsIgnoreCase("0")) {
            if (!Utility.isNullOrEmpty(mFixtureModel.getAwayTeamName()) && mFixtureModel.getAwayTeamName().equalsIgnoreCase(AppConstants.TEAM_NAME_PLACE_HOLDER)) {
                if (!Utility.isNullOrEmpty(mFixtureModel.getCompetition_actual_name()) && mFixtureModel.getCompetition_actual_name().contains(AppConstants.COMPETATION_NAME_GROUP)) {
                    team2_name.setText(mFixtureModel.getAwayPlaceholder());
                } else if (!Utility.isNullOrEmpty(mFixtureModel.getCompetition_actual_name()) && mFixtureModel.getCompetition_actual_name().contains(AppConstants.COMPETATION_NAME_POS)) {
                    team2_name.setText(AppConstants.COMPETATION_NAME_POS + "-" + mFixtureModel.getAwayPlaceholder());
                } else {
                    if (!Utility.isNullOrEmpty(mFixtureModel.getAwayTeam())) {
                        team2_name.setText(mFixtureModel.getAwayTeam());
                    } else {
                        team2_name.setText("");
                    }
                }
            } else {
                if (!Utility.isNullOrEmpty(mFixtureModel.getDisplayAwayTeamPlaceholderName())) {
                    team2_name.setText(mFixtureModel.getDisplayAwayTeamPlaceholderName());
                } else {
                    team2_name.setText("");
                }
            }
        } else {
            if (!Utility.isNullOrEmpty(mFixtureModel.getAwayTeam())) {
                team2_name.setText(mFixtureModel.getAwayTeam());
            } else {
                team2_name.setText("");
            }
        }


        if (!Utility.isNullOrEmpty(mFixtureModel.getHomeScore()) && !Utility.isNullOrEmpty(mFixtureModel.getAwayScore()) && mFixtureModel.getHomeScore().equalsIgnoreCase(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));
            team1_name.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));
            team2_name.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));

        } else if (!Utility.isNullOrEmpty(mFixtureModel.getHomeScore()) && !Utility.isNullOrEmpty(mFixtureModel.getAwayScore()) && Integer.parseInt(mFixtureModel.getHomeScore()) > Integer.parseInt(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team1_name.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));
            team2_name.setTextColor(ContextCompat.getColor(mContext, R.color.black));
        } else if (!Utility.isNullOrEmpty(mFixtureModel.getHomeScore()) && !Utility.isNullOrEmpty(mFixtureModel.getAwayScore()) && Integer.parseInt(mFixtureModel.getHomeScore()) < Integer.parseInt(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));
            team1_name.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team2_name.setTextColor(ContextCompat.getColor(mContext, R.color.appColorPrimary));
        } else {
            team1_name.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team2_name.setTextColor(ContextCompat.getColor(mContext, R.color.black));
        }
        matchesView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent mMatchInfoIntent = new Intent(mContext, MatchInformationActivity.class);
                mMatchInfoIntent.putExtra(AppConstants.ARG_MATCH_INFO, mFixtureModel);
                if (!Utility.isNullOrEmpty(mFixtureModel.getPosition())) {
                    mMatchInfoIntent.putExtra(AppConstants.ARG_POSITION, mFixtureModel.getPosition());
                }
                startActivity(mMatchInfoIntent);
            }
        });
        if (!Utility.isNullOrEmpty(mFixtureModel.getIsResultOverride())
                && mFixtureModel.getIsResultOverride().equalsIgnoreCase("1")) {
            if (!Utility.isNullOrEmpty(mFixtureModel.getMatch_status())) {
                if (mFixtureModel.getMatch_status().equalsIgnoreCase("Walk-over") ||
                        mFixtureModel.getMatch_status().equalsIgnoreCase("Penalties") ||
                        mFixtureModel.getMatch_status().equalsIgnoreCase("Abandoned")) {
                    if (!Utility.isNullOrEmpty(mFixtureModel.getMatch_winner()) && !Utility.isNullOrEmpty(mFixtureModel.getHome_id()) && mFixtureModel.getMatch_winner().equalsIgnoreCase(mFixtureModel.getHome_id())) {
                        team1_score.setText(Html.fromHtml(team1_score.getText().toString().trim() + "*"));
                        team2_score.setText(Html.fromHtml(team2_score.getText().toString().trim() + "  "));
                        team2_score.setGravity(Gravity.LEFT);
                    } else if (!Utility.isNullOrEmpty(mFixtureModel.getMatch_winner()) && !Utility.isNullOrEmpty(mFixtureModel.getAway_id()) && mFixtureModel.getMatch_winner().equalsIgnoreCase(mFixtureModel.getAway_id())) {
                        team2_score.setText(Html.fromHtml(team2_score.getText().toString().trim() + "*"));
                        team1_score.setText(Html.fromHtml(team1_score.getText().toString().trim() + "  "));
                        team1_score.setGravity(Gravity.LEFT);
                    }
                }
            }
        }
        ll_matches.addView(matchesView);
    }

    private void addNoItemTeamFixtureView() {
        View noMatchesView = getLayoutInflater().inflate(R.layout.no_item_textview, null);
        TextView tv_noMatchesView = (TextView) noMatchesView.findViewById(R.id.tv_no_item);
        tv_noMatchesView.setText(getString(R.string.no_matches_available));
        ll_matches.addView(noMatchesView);
    }
}
