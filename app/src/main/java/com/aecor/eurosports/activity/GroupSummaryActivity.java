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
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.model.LeagueModel;
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
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;

import butterknife.BindView;
import butterknife.OnClick;

/**
 * Created by system-local on 30-06-2017.
 */

public class GroupSummaryActivity extends BaseAppCompactActivity {
    private final String TAG = "GroupSummaryActivity";
    private Context mContext;
    private AppPreference mPreference;
    @BindView(R.id.tv_view_all_club_matches)
    protected TextView tv_view_all_club_matches;
    @BindView(R.id.ll_matches)
    protected LinearLayout ll_matches;
    private ClubGroupModel mGroupModel;
    private LeagueModel mLeagueModelData[];
    @BindView(R.id.tl_group_rows)
    protected TableLayout tl_group_rows;
    @BindView(R.id.tv_group_table_title)
    protected TextView tv_group_table_title;
    @BindView(R.id.tv_view_full_league_table)
    protected TextView tv_view_full_league_table;
    @BindView(R.id.ll_match_header)
    protected LinearLayout ll_match_header;
    @BindView(R.id.tr_group_header)
    protected TableRow tr_group_header;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.group_summary);
        super.onCreate(savedInstanceState);
        mContext = this;

        mGroupModel = getIntent().getParcelableExtra(AppConstants.ARG_GROUP_DETAIL);
        initView();
    }

    private void addGroupLeagueRow(LeagueModel mLeagueModel) {
        View teamLeagueView = getLayoutInflater().inflate(R.layout.row_team_leaguetable, null);
        TextView tv_group_name = (TextView) teamLeagueView.findViewById(R.id.tv_group_name);
        TextView tv_points = (TextView) teamLeagueView.findViewById(R.id.tv_points);
        TextView tv_games = (TextView) teamLeagueView.findViewById(R.id.tv_games);
        TextView tv_goalDifference = (TextView) teamLeagueView.findViewById(R.id.tv_goalDifference);
        final ImageView team_flag = (ImageView) teamLeagueView.findViewById(R.id.team_flag);
        if (!Utility.isNullOrEmpty(mLeagueModel.getName())) {
            tv_group_name.setText(mLeagueModel.getName());
        } else {
            tv_group_name.setText("");
        }
        if (!Utility.isNullOrEmpty(mLeagueModel.getPoints())) {
            tv_points.setText(mLeagueModel.getPoints());
        } else {
            tv_points.setText("");
        }

        if (!Utility.isNullOrEmpty(mLeagueModel.getPlayed())) {
            tv_games.setText(mLeagueModel.getPlayed());
        } else {
            tv_games.setText("");
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
        tv_goalDifference.setText(goalText);

        if (!Utility.isNullOrEmpty(mLeagueModel.getTeamFlag())) {
            Glide.with(mContext)
                    .load(mLeagueModel.getTeamFlag())
                    .asBitmap().diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .into(new SimpleTarget<Bitmap>() {
                        @Override
                        public void onResourceReady(Bitmap resource, GlideAnimation<? super Bitmap> glideAnimation) {
                            team_flag.setImageBitmap(Utility.scaleBitmap(resource, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
                        }
                    });
        } else {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                    R.drawable.globe);
            team_flag.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
        }

        tl_group_rows.addView(teamLeagueView);
        View seperatorView = getLayoutInflater().inflate(R.layout.table_row_seperator, null);
        tl_group_rows.addView(seperatorView);
    }

    private void addNoItemGroupLeagueView() {
        View noMatchesView = getLayoutInflater().inflate(R.layout.no_item_textview, null);
        TextView tv_noMatchesView = (TextView) noMatchesView.findViewById(R.id.tv_no_item);
        tv_noMatchesView.setText(getString(R.string.no_league_data_available));
        tv_view_full_league_table.setVisibility(View.GONE);
        tl_group_rows.addView(noMatchesView);
    }

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);
        tv_view_all_club_matches.setVisibility(View.GONE);
        tr_group_header.setVisibility(View.GONE);
        ll_match_header.setVisibility(View.GONE);
        String groupTableTitle = mGroupModel.getName() + " " + getString(R.string.league_table);
        tv_group_table_title.setText(groupTableTitle);
        if (mGroupModel.getActual_competition_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ELIMINATION)) {
            showBackButton(getString(R.string.placing_matches_summary));
        } else {
            showBackButton(getString(R.string.group_summary));
        }
        tv_view_full_league_table.setVisibility(View.GONE);

        if (mGroupModel.getCompetation_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ROUND_ROBIN)) {
            tl_group_rows.setVisibility(View.VISIBLE);
            getGroupStanding();
        } else if (mGroupModel.getCompetation_type() != null && !Utility.isNullOrEmpty(mGroupModel.getCompetation_type()) && mGroupModel.getCompetation_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ELIMINATION) && mGroupModel.getActual_competition_type() != null && !Utility.isNullOrEmpty(mGroupModel.getActual_competition_type()) && mGroupModel.getActual_competition_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ROUND_ROBIN)) {
            tl_group_rows.setVisibility(View.VISIBLE);
            getGroupStanding();
        } else {
            tl_group_rows.setVisibility(View.GONE);
        }
        getTeamFixtures();
    }

    @Override
    protected void setListener() {

    }

    private void getTeamFixtures() {


        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressdialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_TEAM_FIXTURES;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                JSONObject mTournamentData = new JSONObject();
                mTournamentData.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                mTournamentData.put("competitionId", mGroupModel.getId());
                mTournamentData.put("is_scheduled", "1");
                requestJson.put("tournamentData", mTournamentData);
            } catch (JSONException e) {
                e.printStackTrace();
            }

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressdialog);
                    try {
                        AppLogger.LogE(TAG, "getTeamFixtures Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TeamFixturesModel mTeamFixtureData[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamFixturesModel[].class);
                                ll_match_header.setVisibility(View.VISIBLE);
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
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress(mProgressdialog);
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

    private void getGroupStanding() {


        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressdialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_GROUP_STANDING;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                JSONObject mTournamentData = new JSONObject();
                mTournamentData.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                mTournamentData.put("competitionId", mGroupModel.getId());
                requestJson.put("tournamentData", mTournamentData);
            } catch (JSONException e) {
                e.printStackTrace();
            }

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressdialog);
                    try {
                        AppLogger.LogE(TAG, "getGroupStanding Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                mLeagueModelData = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), LeagueModel[].class);
                                tr_group_header.setVisibility(View.VISIBLE);
                                if (mLeagueModelData != null && mLeagueModelData.length > 0) {
                                    for (LeagueModel aMLeagueModelData : mLeagueModelData) {
                                        addGroupLeagueRow(aMLeagueModelData);
                                    }
                                    tv_view_full_league_table.setVisibility(View.VISIBLE);
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
                        Utility.StopProgress(mProgressdialog);
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
        tv_view_all_club_matches.setVisibility(View.GONE);

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
                team_match_date.setText(Utility.getDateFromDateTime(mFixtureModel.getMatch_datetime(), language, mContext));
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


//        if (!Utility.isNullOrEmpty(mFixtureModel.getMatch_number())) {
//            team_match_id.setText(mFixtureModel.getMatch_number());
//        } else {
//            team_match_id.setText("");
//        }


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
        if (mFixtureModel.getHome_id().equalsIgnoreCase("0") && !Utility.isNullOrEmpty(mFixtureModel.getHomeTeamName()) && mFixtureModel.getHomeTeamName().equalsIgnoreCase(AppConstants.TEAM_NAME_PLACE_HOLDER)) {
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
            if (!Utility.isNullOrEmpty(mFixtureModel.getHomeTeam())) {
                team1_name.setText(mFixtureModel.getHomeTeam());
            } else {
                team1_name.setText("");
            }
        }
        if (mFixtureModel.getAway_id().equalsIgnoreCase("0") && !Utility.isNullOrEmpty(mFixtureModel.getAwayTeamName()) && mFixtureModel.getAwayTeamName().equalsIgnoreCase(AppConstants.TEAM_NAME_PLACE_HOLDER)) {
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
            if (!Utility.isNullOrEmpty(mFixtureModel.getAwayTeam())) {
                team2_name.setText(mFixtureModel.getAwayTeam());
            } else {
                team2_name.setText("");
            }
        }

        if (!Utility.isNullOrEmpty(mFixtureModel.getHomeScore()) && !Utility.isNullOrEmpty(mFixtureModel.getAwayScore()) && mFixtureModel.getHomeScore().equalsIgnoreCase(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team1_name.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team2_name.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));

        } else if (!Utility.isNullOrEmpty(mFixtureModel.getHomeScore()) && !Utility.isNullOrEmpty(mFixtureModel.getAwayScore()) && Integer.parseInt(mFixtureModel.getHomeScore()) > Integer.parseInt(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team1_name.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team2_name.setTextColor(ContextCompat.getColor(mContext, R.color.black));
        } else if (!Utility.isNullOrEmpty(mFixtureModel.getHomeScore()) && !Utility.isNullOrEmpty(mFixtureModel.getAwayScore()) && Integer.parseInt(mFixtureModel.getHomeScore()) < Integer.parseInt(mFixtureModel.getAwayScore())) {
            team1_score.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team2_score.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
            team1_name.setTextColor(ContextCompat.getColor(mContext, R.color.black));
            team2_name.setTextColor(ContextCompat.getColor(mContext, R.color.colorPrimary));
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

        ll_matches.addView(matchesView);
    }

    private void addNoItemTeamFixtureView() {
        View noMatchesView = getLayoutInflater().inflate(R.layout.no_item_textview, null);
        TextView tv_noMatchesView = (TextView) noMatchesView.findViewById(R.id.tv_no_item);
        tv_noMatchesView.setText(getString(R.string.no_matches_available));
        ll_matches.addView(noMatchesView);
        tv_view_all_club_matches.setVisibility(View.GONE);
    }

    @OnClick(R.id.tv_view_full_league_table)
    protected void onFullLeagueTableClicked() {
        Intent mFullLeagueTableIntent = new Intent(mContext, FullLeageTableActivity.class);
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_FULL_LEAGUE_TABLE_DETAIL, new ArrayList<>(Arrays.asList(mLeagueModelData)));
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_GROUP_NAME, mGroupModel.getName());
        startActivity(mFullLeagueTableIntent);
    }


//    @OnClick(R.id.tv_view_all_rounds)
//    protected void onViewAllRoundsClicked() {
//        Intent mAgeGroupIntent = new Intent(mContext, AgeGroupActivity.class);
//        mAgeGroupIntent.putExtra(AppConstants.ARG_AGE_CATEGORY_ID, mGroupModel.get);
//        mContext.startActivity(mAgeGroupIntent);
//    }
}
