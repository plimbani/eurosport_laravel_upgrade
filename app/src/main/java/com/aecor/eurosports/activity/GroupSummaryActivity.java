package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.Nullable;
import android.support.v4.content.ContextCompat;
import android.text.Html;
import android.view.Gravity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.GroupsSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeGroupModel;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.model.LeagueModel;
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
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;

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

    @BindView(R.id.tv_view_full_league_table)
    protected TextView tv_view_full_league_table;

    @BindView(R.id.tv_standing_selector)
    protected View tv_standing_selector;
    @BindView(R.id.tv_matches_selector)
    protected View tv_matches_selector;
    @BindView(R.id.ll_matches_tab)
    protected FrameLayout ll_matches_tab;
    @BindView(R.id.ll_standing_tab)
    protected FrameLayout ll_standing_tab;
    @BindView(R.id.tv_standing)
    protected TextView tv_standing;
    @BindView(R.id.ll_matches_view)
    protected LinearLayout ll_matches_view;
    @BindView(R.id.ll_standings)
    protected LinearLayout ll_standings;
    @BindView(R.id.ll_standings_content)
    protected LinearLayout ll_standings_content;
    @BindView(R.id.ll_match_content)
    protected LinearLayout ll_match_content;
    @BindView(R.id.sp_groups)
    protected Spinner sp_groups;
    @BindView(R.id.mParentScroll)
    protected ScrollView mParentScroll;
    private List<ClubGroupModel> mGroupList;
    private AgeGroupModel mAgeGroupData;
    private boolean isApiAlreadyCalled = false;
    private boolean isFixApiAlreadyCalled = false;
    private boolean isShowDivisionAlso = false;
    private HashMap<String, ArrayList<TeamFixturesModel>> mFixturesResult;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.group_summary);
        super.onCreate(savedInstanceState);
        mContext = this;

        mGroupModel = getIntent().getParcelableExtra(AppConstants.ARG_GROUP_DETAIL);

        if (getIntent() != null && getIntent().getExtras() != null) {
            Bundle bundle = getIntent().getExtras();
            if (bundle.containsKey(AppConstants.ARG_ALL_GROUP_LIST)) {
                mGroupList = bundle.getParcelableArrayList(AppConstants.ARG_ALL_GROUP_LIST);
            }
            if (bundle.containsKey(AppConstants.ARG_GROUP_DETAIL_WITH_DIVISION)) {
                mAgeGroupData = bundle.getParcelable(AppConstants.ARG_GROUP_DETAIL_WITH_DIVISION);
                isShowDivisionAlso = true;
            }
        }
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

    private void addGroupLeagueHeaderRow() {
        View teamLeagueHeaderView = getLayoutInflater().inflate(R.layout.standing_header, null);
        TextView tv_group_table_title = (TextView) teamLeagueHeaderView.findViewById(R.id.tv_group_table_title);
        String groupTableTitle = mGroupModel.getDisplay_name() + " " + getString(R.string.league_table);
        tv_group_table_title.setText(groupTableTitle);

        tl_group_rows.addView(teamLeagueHeaderView);
//        View seperatorView = getLayoutInflater().inflate(R.layout.table_row_seperator, null);
//        tl_group_rows.addView(seperatorView);
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
        initUI();
        if (isShowDivisionAlso) {
            if (mAgeGroupData != null) {
                mGroupList = new ArrayList<>();
                if (mAgeGroupData.getRound_robin_groups() != null
                        && mAgeGroupData.getRound_robin_groups().size() > 0) {
                    mGroupList.addAll(mAgeGroupData.getRound_robin_groups());
                }
                if (mAgeGroupData.getDivision_groups() != null
                        && mAgeGroupData.getDivision_groups().size() > 0) {
                    for (int i = 0; i < mAgeGroupData.getDivision_groups().size(); i++) {
                        ClubGroupModel mDivisionModel = new ClubGroupModel();
                        mDivisionModel.setShowDivisionOnly(true);
                        mDivisionModel.setDivisionName(mAgeGroupData.getDivision_groups().get(i).getTitle());
                        mGroupList.add(mDivisionModel);
                        mGroupList.addAll(mAgeGroupData.getDivision_groups().get(i).getData());
                    }
                }
            }
        }
        if (mGroupModel.getActual_competition_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ELIMINATION)) {
            ll_standing_tab.setBackgroundColor(Color.GRAY);
            ll_standing_tab.setEnabled(false);
            onMatchesClicked();
        } else {
            ll_standing_tab.setBackgroundColor(Color.WHITE);
            ll_standing_tab.setEnabled(true);
            onStandingClicked();
        }
        if (mGroupList != null && mGroupList.size() > 0) {
            int selectedGroupPos = 0;

            for (int i = 0; i < mGroupList.size(); i++) {
                if (!Utility.isNullOrEmpty(mGroupList.get(i).getId()) &&
                        mGroupList.get(i).getId().equalsIgnoreCase(mGroupModel.getId())) {
                    AppLogger.LogE(TAG, "selected pos" + selectedGroupPos);
                    selectedGroupPos = i;
                    break;
                }
            }
            GroupsSpinnerAdapter adapter = new GroupsSpinnerAdapter((Activity) mContext,
                    mGroupList);
            sp_groups.setAdapter(adapter);
            sp_groups.setSelection(selectedGroupPos);


        }
        setListener();
    }

    private void initUI() {
        tv_view_all_club_matches.setVisibility(View.GONE);

        ll_standings_content.setVisibility(View.GONE);
        ll_match_content.setVisibility(View.GONE);
        if (mGroupModel.getActual_competition_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ELIMINATION)) {
            showBackButton(getString(R.string.placing_matches_summary));
        } else {
            showBackButton(getString(R.string.group_summary));
        }
        tv_view_full_league_table.setVisibility(View.GONE);

        if (mGroupModel.getCompetation_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ROUND_ROBIN)) {
            tl_group_rows.setVisibility(View.VISIBLE);
            if (!isApiAlreadyCalled) {
                isApiAlreadyCalled = true;
                getGroupStanding();
            } else {
                isApiAlreadyCalled = false;
            }
        } else if (mGroupModel.getCompetation_type() != null && !Utility.isNullOrEmpty(mGroupModel.getCompetation_type()) && mGroupModel.getCompetation_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ELIMINATION) && mGroupModel.getActual_competition_type() != null && !Utility.isNullOrEmpty(mGroupModel.getActual_competition_type()) && mGroupModel.getActual_competition_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ROUND_ROBIN)) {
            tl_group_rows.setVisibility(View.VISIBLE);

            if (!isApiAlreadyCalled) {
                isApiAlreadyCalled = true;
                getGroupStanding();
            } else {
                isApiAlreadyCalled = false;
            }
        } else {
            tl_group_rows.setVisibility(View.GONE);
            ll_standing_tab.setBackgroundColor(Color.GRAY);
            ll_standing_tab.setEnabled(false);
            onMatchesClicked();
        }

        if (!isFixApiAlreadyCalled) {
            isFixApiAlreadyCalled = true;
            getTeamFixtures();
        } else {
            isFixApiAlreadyCalled = false;
        }

    }

    @Override
    protected void setListener() {
        sp_groups.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (mGroupList != null && mGroupList.get(position) != null
                        && !mGroupList.get(position).isShowDivisionOnly()) {
                    mGroupModel = mGroupList.get(position);
                }
                if (!mGroupList.get(position).isShowDivisionOnly()) {
                    if (mGroupModel.getActual_competition_type().equalsIgnoreCase(AppConstants.GROUP_COMPETATION_TYPE_ELIMINATION)) {
                        ll_standing_tab.setBackgroundColor(Color.GRAY);
                        ll_standing_tab.setEnabled(false);
                    } else {
                        ll_standing_tab.setBackgroundColor(Color.WHITE);
                        ll_standing_tab.setEnabled(true);
                    }

                    initUI();
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> arg0) {

            }
        });

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
//                mTournamentData.put("is_scheduled", "1");
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
                        isFixApiAlreadyCalled = false;
                        AppLogger.LogE(TAG, "getTeamFixtures Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TeamFixturesModel mTeamFixtureData[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamFixturesModel[].class);
                                ll_match_content.setVisibility(View.VISIBLE);
                                if (mTeamFixtureData != null && mTeamFixtureData.length > 0) {
                                    Collections.sort(Arrays.asList(mTeamFixtureData), new Comparator<TeamFixturesModel>() {
                                        public int compare(TeamFixturesModel o1, TeamFixturesModel o2) {
                                            if (o1.getCompetation_round_no() == null) {
                                                return (o2.getCompetation_round_no() == null) ? 0 : -1;
                                            }
                                            if (o2.getCompetation_round_no() == null) {
                                                return 1;
                                            }
                                            return o1.getCompetation_round_no().compareTo(o2.getCompetation_round_no());
                                        }
                                    });
                                    ll_matches.removeAllViews();

                                    mFixturesResult = new HashMap<>();
                                    if (mTeamFixtureData != null && mTeamFixtureData[0] != null &&

                                            (!Utility.isNullOrEmpty(mTeamFixtureData[0].getIsDivExist())
                                                    && mTeamFixtureData[0].getIsDivExist().equalsIgnoreCase("1")) || (!Utility.isNullOrEmpty(mTeamFixtureData[0].getIsKnockoutPlacingMatches())
                                            && mTeamFixtureData[0].getIsKnockoutPlacingMatches().equalsIgnoreCase("true"))) {
                                        for (int i = 0; i < mTeamFixtureData.length; i++) {
                                            if (mTeamFixtureData[i] != null && !Utility.isNullOrEmpty(mTeamFixtureData[i].getCompetation_name())) {
                                                if (mFixturesResult != null && mFixturesResult.size() > 0
                                                        && mFixturesResult.containsKey(mTeamFixtureData[i].getCompetation_name())) {
                                                    ArrayList<TeamFixturesModel> mExistingList = mFixturesResult.get(mTeamFixtureData[i].getCompetation_name());
                                                    mExistingList.add(mTeamFixtureData[i]);
                                                    mFixturesResult.put(mTeamFixtureData[i].getCompetation_name(), mExistingList);
                                                } else {
                                                    ArrayList<TeamFixturesModel> mNewData = new ArrayList<>();
                                                    mNewData.add(mTeamFixtureData[i]);
                                                    mFixturesResult.put(mTeamFixtureData[i].getCompetation_name(), mNewData);
                                                }
                                            }
                                        }
                                        Map<String, ArrayList<TeamFixturesModel>> treeMap = new TreeMap<String, ArrayList<TeamFixturesModel>>(mFixturesResult);

                                        for (Map.Entry<String, ArrayList<TeamFixturesModel>> item : treeMap.entrySet()) {
                                            String mGroupName = item.getKey();
                                            ArrayList<TeamFixturesModel> teamFixturesModels = item.getValue();
                                            if (!Utility.isNullOrEmpty(mGroupName) && teamFixturesModels != null
                                                    && teamFixturesModels.size() > 0) {
                                                addMatchHeader(teamFixturesModels.get(0));

                                                Collections.sort(teamFixturesModels, new Comparator<TeamFixturesModel>() {
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
                                                for (TeamFixturesModel aMTeamFixtureData : teamFixturesModels) {
                                                    addMatchesRow(aMTeamFixtureData);
                                                }
                                            }
                                        }


                                    } else {
                                        for (TeamFixturesModel aMTeamFixtureData : mTeamFixtureData) {
                                            addMatchesRow(aMTeamFixtureData);
                                        }
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
                        isApiAlreadyCalled = false;
                        AppLogger.LogE(TAG, "getGroupStanding Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                tl_group_rows.removeAllViews();
                                mLeagueModelData = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), LeagueModel[].class);

                                ll_standings_content.setVisibility(View.VISIBLE);
                                if (mLeagueModelData != null && mLeagueModelData.length > 0) {
                                    int curPos = 0;
                                    for (LeagueModel aMLeagueModelData : mLeagueModelData) {
                                        if (curPos == 0) {
                                            // add header first
                                            addGroupLeagueHeaderRow();
                                        }
                                        addGroupLeagueRow(aMLeagueModelData);
                                        curPos = curPos + 1;
                                    }
                                    tv_view_full_league_table.setVisibility(View.VISIBLE);
                                } else {
                                    addNoItemGroupLeagueView();
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
            if (!Utility.isNullOrEmpty(mFixtureModel.getHomeTeamName())
                    && mFixtureModel.getHomeTeamName().equalsIgnoreCase(AppConstants.TEAM_NAME_PLACE_HOLDER)) {
                if (!Utility.isNullOrEmpty(mFixtureModel.getCompetition_actual_name())
                        && mFixtureModel.getCompetition_actual_name().contains(AppConstants.COMPETATION_NAME_GROUP)) {
                    team1_name.setText(mFixtureModel.getHomePlaceholder());
                } else if (!Utility.isNullOrEmpty(mFixtureModel.getCompetition_actual_name())
                        && mFixtureModel.getCompetition_actual_name().contains(AppConstants.COMPETATION_NAME_POS)) {
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
        tv_view_all_club_matches.setVisibility(View.GONE);
    }

    private void addMatchHeader(TeamFixturesModel mFixtureModel) {
        final View mMatchDivisionHeader = getLayoutInflater().inflate(R.layout.match_division_header, null);
        TextView tv_match_division_name = (TextView) mMatchDivisionHeader.findViewById(R.id.tv_match_division_name);
        if (mFixtureModel != null && !Utility.isNullOrEmpty(mFixtureModel.getIsDivExist()) &&
                mFixtureModel.getIsDivExist().equalsIgnoreCase("1")) {
            tv_match_division_name.setText(mGroupModel.getDivisionName() + " - " + mFixtureModel.getCompetation_name() + " (" + mFixtureModel.getCompetation_round_no() + ")");
        } else {
            tv_match_division_name.setText(mFixtureModel.getCompetation_name() + " (" + mFixtureModel.getCompetation_round_no() + ")");
        }
        ll_matches.addView(mMatchDivisionHeader);
        AppLogger.LogE(TAG, "match division name" + tv_match_division_name.getText().toString());
        AppLogger.LogE(TAG, "spinner groups " + mGroupModel.getDisplay_name());

        if (sp_groups.getSelectedItemPosition() > 0 && tv_match_division_name.getText().toString().contains(mGroupModel.getDisplay_name())) {
            new Handler().post(new Runnable() {
                @Override
                public void run() {
                    mParentScroll.smoothScrollTo(0, mMatchDivisionHeader.getTop());
                }
            });
        }


    }

    @OnClick(R.id.tv_view_full_league_table)
    protected void onFullLeagueTableClicked() {
        Intent mFullLeagueTableIntent = new Intent(mContext, FullLeageTableActivity.class);
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_FULL_LEAGUE_TABLE_DETAIL, new ArrayList<>(Arrays.asList(mLeagueModelData)));
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_GROUP_NAME, mGroupModel.getDisplay_name());
        startActivity(mFullLeagueTableIntent);
    }

    @OnClick(R.id.ll_standing_tab)
    protected void onStandingClicked() {
        tv_standing_selector.setVisibility(View.VISIBLE);
        ll_standings.setVisibility(View.VISIBLE);
        tv_matches_selector.setVisibility(View.GONE);
        ll_matches_view.setVisibility(View.GONE);


    }

    @OnClick(R.id.ll_matches_tab)
    protected void onMatchesClicked() {
        tv_standing_selector.setVisibility(View.GONE);
        ll_standings.setVisibility(View.GONE);
        tv_matches_selector.setVisibility(View.VISIBLE);
        ll_matches_view.setVisibility(View.VISIBLE);
    }

}