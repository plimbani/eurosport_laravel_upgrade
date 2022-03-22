package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.text.Html;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import androidx.core.content.ContextCompat;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.TeamSpinnerAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.LeagueModel;
import com.aecor.eurosports.model.TeamDetailModel;
import com.aecor.eurosports.model.TeamFixturesModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.ViewDialog;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.AppPreference;
import com.aecor.eurosports.util.Utility;
import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

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

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

import static com.android.volley.Request.Method.POST;

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
    @BindView(R.id.tv_view_all_rounds)
    protected TextView tv_view_all_rounds;
    @BindView(R.id.tv_view_all_club_matches)
    protected TextView tv_view_all_club_matches;
    @BindView(R.id.tl_group_rows)
    protected TableLayout tl_group_rows;
    @BindView(R.id.ll_matches)
    protected LinearLayout ll_matches;
    @BindView(R.id.tv_view_graphic)
    protected TextView tv_view_graphic;
    @BindView(R.id.ll_match_header)
    protected LinearLayout ll_match_header;
    @BindView(R.id.ll_schedule)
    protected LinearLayout ll_schedule;
    @BindView(R.id.tr_group_header)
    protected TableRow tr_group_header;
    private TeamDetailModel mTeamDetailModel;
    private Context mContext;
    private AppPreference mPreference;
    private LeagueModel mLeagueModelData[];
    @BindView(R.id.view_seperator)
    protected View view_seperator;
    @BindView(R.id.ll_standings_view)
    protected LinearLayout ll_standings_view;
    @BindView(R.id.ll_matches_view)
    protected LinearLayout ll_matches_view;
    private String mImageUrl;
    private List<TeamDetailModel> mTeamList;


    @BindView(R.id.ll_matches_tab)
    protected FrameLayout ll_matches_tab;
    @BindView(R.id.ll_standing_tab)
    protected FrameLayout ll_standing_tab;
    @BindView(R.id.tv_standing)
    protected TextView tv_standing;
    @BindView(R.id.tv_standing_selector)
    protected View tv_standing_selector;
    @BindView(R.id.tv_matches_selector)
    protected View tv_matches_selector;


    @BindView(R.id.sp_team)
    protected Spinner sp_team;
    private TeamSpinnerAdapter teamSpinnerAdapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_team);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        mContext = this;
        mTeamDetailModel = getIntent().getParcelableExtra(AppConstants.ARG_TEAM_DETAIL);

        if (getIntent() != null && getIntent().getExtras() != null) {
            Bundle bundle = getIntent().getExtras();
            if (bundle.containsKey(AppConstants.ARG_ALL_TEAM_LIST)) {
                //mTeamList = bundle.getParcelableArrayList(AppConstants.ARG_ALL_TEAM_LIST);
            }
        }

        initView();
        setListener();

        onStandingClicked();
    }

    private void getGraphicImageUrl(final String mId) {
        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_GRAPHIC_IMAGE_URL;
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            AppLogger.LogE(TAG, "url" + url);

            StringRequest stringRequest = new StringRequest(POST, url,
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            Utility.StopProgress(mProgressHUD);

                            AppLogger.LogE(TAG, "Success  Image " + response);
                            if (!Utility.isNullOrEmpty(response)) {
                                mImageUrl = response;
                                tv_view_graphic.setVisibility(View.VISIBLE);
                            } else {
                                tv_view_graphic.setVisibility(View.GONE);
                            }
                        }
                    }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    Utility.StopProgress(mProgressHUD);

                    AppLogger.LogE(TAG, "Error Image " + error);
                    tv_view_graphic.setVisibility(View.GONE);
                }
            }) {
                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    HashMap<String, String> headers = new HashMap<>();
                    headers.put("Content-Type", "application/json; charset=utf-8");
                    headers.put("IsMobileUser", "true");
                    if (!Utility.isNullOrEmpty(mPreference.getString(AppConstants.PREF_TOKEN))) {
                        headers.put("Authorization", "Bearer " + mPreference.getString(AppConstants.PREF_TOKEN));
                    }
                    String locale = mPreference.getString(AppConstants.PREF_USER_LOCALE);
                    if (!Utility.isNullOrEmpty(locale)) {
                        headers.put("locale", locale);
                    }

                    return headers;
                }

                @Override
                public byte[] getBody() throws AuthFailureError {

                    final JSONObject requestJson = new JSONObject();
                    try {
                        requestJson.put("age_category", mId);

                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                    AppLogger.LogE(TAG, "requestJson" + requestJson.toString());
                    return requestJson.toString().getBytes();
                }
            };

            mQueue.add(stringRequest);


        }
    }

    @OnClick(R.id.tv_view_full_league_table)
    protected void onFullLeagueViewClicked() {
        Intent mFullLeagueTableIntent = new Intent(mContext, FullLeageTableActivity.class);
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_FULL_LEAGUE_TABLE_DETAIL, new ArrayList<>(Arrays.asList(mLeagueModelData)));
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_GROUP_NAME, mTeamDetailModel.getGroup_name());
        mFullLeagueTableIntent.putExtra(AppConstants.ARG_TEAM_ID, mTeamDetailModel.getId());
        startActivity(mFullLeagueTableIntent);
    }

    @OnClick(R.id.tv_view_all_club_matches)
    protected void onViewAllClubMatchesClicked() {
        Intent mAllClubMatchesActivity = new Intent(mContext, AllClubMatchesActivity.class);
        mAllClubMatchesActivity.putExtra(AppConstants.ARG_TEAM_DETAIL, mTeamDetailModel);
        startActivity(mAllClubMatchesActivity);
    }

    @OnClick(R.id.ll_schedule)
    protected void onViewGraphicClicked() {
        if (!Utility.isNullOrEmpty(mImageUrl)) {
            if (Utility.isInternetAvailable(mContext)) {
                Intent mFullScreenImageIntent = new Intent(mContext, FullScreenImageActivity.class);
//                mFullScreenImageIntent.putExtra(AppConstants.KEY_IMAGE_URL, mImageUrl);
                mFullScreenImageIntent.putExtra(AppConstants.KEY_AGE_CATEGORIES_ID, mTeamDetailModel.getAge_group_id());
                mContext.startActivity(mFullScreenImageIntent);
            } else {
                Utility.showToast(mContext, mContext.getString(R.string.no_internet));
            }

        }

    }

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);
        tr_group_header.setVisibility(View.GONE);
        ll_match_header.setVisibility(View.GONE);
        tv_view_graphic.setVisibility(View.GONE);

        updateUI();

        showBackButton(getString(R.string.team));


        getTeamList();
/*        getTeamFixtures();
        getGroupStanding();
        getGraphicImageUrl(mTeamDetailModel.getAge_group_id() + "");*/
    }

    private void updateUI() {
        if (mTeamDetailModel != null) {
            if (!Utility.isNullOrEmpty(mTeamDetailModel.getName())) {
                tv_team_name.setText(mTeamDetailModel.getName());
            } else {
                tv_team_name.setText("");
            }

            if (!Utility.isNullOrEmpty(mTeamDetailModel.getCountryLogo())) {
                Glide.with(mContext)
                        .load(mTeamDetailModel.getCountryLogo())
                        .diskCacheStrategy(DiskCacheStrategy.NONE)
                        .skipMemoryCache(true)
                        .dontAnimate()
                        .override(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT)
                        .into(iv_team_flag);

            } else {
                Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                        R.drawable.globe);
                iv_team_flag.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH_1, AppConstants.MAX_IMAGE_HEIGHT_1));
            }

            if (!Utility.isNullOrEmpty(mTeamDetailModel.getCountryName())) {
                tv_countryName.setText(mTeamDetailModel.getCountryName());
            } else {
                tv_countryName.setText("");
            }
            String groupName = "";
            if (!Utility.isNullOrEmpty(mTeamDetailModel.getAgeGroupName())) {
                groupName = mTeamDetailModel.getAgeGroupName();
            }
            if (!Utility.isNullOrEmpty(mTeamDetailModel.getGroup_name())) {
                if (!Utility.isNullOrEmpty(groupName)) {
                    groupName = groupName + ", " + mTeamDetailModel.getGroup_name();
                } else {
                    groupName = mTeamDetailModel.getGroup_name();
                }
            }
            tv_team_member_desc.setText(groupName);
            if (!Utility.isNullOrEmpty(mTeamDetailModel.getGroup_name())) {
                tv_group_table_title.setText(mTeamDetailModel.getGroup_name() + " " + getString(R.string.league_table));
            } else {
                tv_group_table_title.setText("");
            }
        }
    }

    @Override
    protected void setListener() {
        sp_team.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (mTeamList != null && mTeamList.get(position) != null) {
                    mTeamDetailModel = mTeamList.get(position);

                    tl_group_rows.removeAllViews();
                    ll_matches.removeAllViews();
                    updateUI();
                    getTeamFixtures();
                    getGroupStanding();
                    getGraphicImageUrl(mTeamDetailModel.getAge_group_id() + "");
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> arg0) {

            }
        });
    }


    private void getGroupStanding() {


        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_GROUP_STANDING;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                JSONObject mTournamentData = new JSONObject();
                mTournamentData.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                mTournamentData.put("competitionId", mTeamDetailModel.getGroupId());
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
                                tr_group_header.setVisibility(View.VISIBLE);
                                if (mLeagueModelData != null && mLeagueModelData.length > 0) {
                                    for (LeagueModel aMLeagueModelData : mLeagueModelData) {
                                        addGroupLeagueRow(aMLeagueModelData);
                                    }
                                    tv_view_full_league_table.setVisibility(View.VISIBLE);
                                    view_seperator.setVisibility(View.VISIBLE);
                                    tv_view_all_rounds.setVisibility(View.VISIBLE);
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


    private void addGroupLeagueRow(LeagueModel mLeagueModel) {

        View teamLeagueView = getLayoutInflater().inflate(R.layout.row_team_leaguetable, null);
        LinearLayout ll_groupRow = (LinearLayout) teamLeagueView.findViewById(R.id.ll_groupRow);
        Log.e("ids", mLeagueModel.getId() + "    " + mTeamDetailModel.getId());
        if (!Utility.isNullOrEmpty(mLeagueModel.getId()) && !Utility.isNullOrEmpty(mTeamDetailModel.getId()) && mLeagueModel.getId().equalsIgnoreCase(mTeamDetailModel.getId())) {
            ll_groupRow.setBackgroundColor(ContextCompat.getColor(mContext, R.color.light_green));
        } else {
            ll_groupRow.setBackgroundColor(ContextCompat.getColor(mContext, R.color.white));
        }
        TextView tv_group_name = (TextView) teamLeagueView.findViewById(R.id.tv_group_name);
        TextView tv_points = (TextView) teamLeagueView.findViewById(R.id.tv_points);
        TextView tv_games = (TextView) teamLeagueView.findViewById(R.id.tv_games);
        TextView tv_goalDifference = (TextView) teamLeagueView.findViewById(R.id.tv_goalDifference);
        final ImageView team_flag = (ImageView) teamLeagueView.findViewById(R.id.team_flag);


        if (!Utility.isNullOrEmpty(mLeagueModel.getName())) {
            tv_group_name.setText(Html.fromHtml("<u>" + mLeagueModel.getName() + "</u>"));
        } else {
            tv_group_name.setText("");
        }
        tv_group_name.setTag(mLeagueModel.getId());
        tv_group_name.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Log.e("tags", view.getTag().toString());

                int selectedGroupPos = 0;
                boolean isTeamAvailable = false;
                for (int i = 0; i < mTeamList.size(); i++) {
                    if (mTeamList.get(i).getId().equalsIgnoreCase(view.getTag().toString())) {
                        AppLogger.LogE(TAG, "selected pos" + selectedGroupPos);
                        selectedGroupPos = i;
                        isTeamAvailable = true;
                        break;
                    }
                }
                if (isTeamAvailable && mTeamList.size() > 1 && !mTeamList.get(sp_team.getSelectedItemPosition()).getId().equalsIgnoreCase(view.getTag().toString())) {
                    mTeamDetailModel = mTeamList.get(selectedGroupPos);
                    sp_team.setSelection(selectedGroupPos);
                } else {
                    getTournamentDetail(view.getTag().toString());
                }

            }
        });

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
        team_flag.setImageResource(android.R.color.transparent);


        if (!Utility.isNullOrEmpty(mLeagueModel.getTeamFlag())) {
            Glide.with(mContext)
                    .load(mLeagueModel.getTeamFlag())
                    .diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .dontAnimate()
                    .override(AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT)
                    .into(team_flag);
        } else {
            Bitmap icon = BitmapFactory.decodeResource(mContext.getResources(),
                    R.drawable.globe);
            team_flag.setImageBitmap(Utility.scaleBitmap(icon, AppConstants.MAX_IMAGE_WIDTH, AppConstants.MAX_IMAGE_HEIGHT));
        }
        tl_group_rows.addView(teamLeagueView);
        View seperatorView = getLayoutInflater().inflate(R.layout.table_row_seperator, null);
        tl_group_rows.addView(seperatorView);
    }

    private void getTournamentDetail(String team_id) {
        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_TEAM_DETAILS;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                JSONObject mTournamentData = new JSONObject();
                mTournamentData.put("tournament_id", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                mTournamentData.put("team_id", team_id);
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
                        AppLogger.LogE(TAG, "getteamdetail Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TeamDetailModel[] mTeamDetailModelList = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamDetailModel[].class);
                                if (mTeamDetailModelList != null && mTeamDetailModelList.length > 0) {
                                    mTeamDetailModel = mTeamDetailModelList[0];

                                    tl_group_rows.removeAllViews();
                                    ll_matches.removeAllViews();
                                    updateUI();
                                    getTeamFixtures();
                                    getGroupStanding();
                                    getGraphicImageUrl(mTeamDetailModel.getAge_group_id() + "");
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

    private void addNoItemGroupLeagueView() {
        View noMatchesView = getLayoutInflater().inflate(R.layout.no_item_textview, null);
        TextView tv_noMatchesView = (TextView) noMatchesView.findViewById(R.id.tv_no_item);
        tv_noMatchesView.setText(getString(R.string.no_league_data_available));
        tv_view_full_league_table.setVisibility(View.GONE);
        view_seperator.setVisibility(View.GONE);
        tv_view_all_rounds.setVisibility(View.GONE);

        tl_group_rows.addView(noMatchesView);
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
        tv_view_all_club_matches.setVisibility(View.GONE);
    }

    private void getTeamFixtures() {


        if (Utility.isInternetAvailable(mContext)) {

            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_TEAM_FIXTURES;
            final JSONObject requestJson = new JSONObject();
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
                        if (response.has("status_code")) {
                            Log.e("status code", response.getString("status_code"));
                        }
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
                                    AppLogger.LogE(TAG, "mTeamFixtureData" + mTeamFixtureData.length);
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

    @OnClick(R.id.tv_view_all_rounds)
    protected void onViewAllRoundsClicked() {
        Intent mAgeGroupIntent = new Intent(mContext, AgeGroupActivity.class);
        mAgeGroupIntent.putExtra(AppConstants.ARG_AGE_CATEGORY_ID, mTeamDetailModel.getAge_group_id());
        mContext.startActivity(mAgeGroupIntent);
    }

    @OnClick(R.id.ll_standing_tab)
    protected void onStandingClicked() {
        tv_standing_selector.setVisibility(View.VISIBLE);
        ll_standings_view.setVisibility(View.VISIBLE);
        tv_matches_selector.setVisibility(View.GONE);
        ll_matches_view.setVisibility(View.GONE);


    }

    @OnClick(R.id.ll_matches_tab)
    protected void onMatchesClicked() {
        tv_standing_selector.setVisibility(View.GONE);
        ll_standings_view.setVisibility(View.GONE);
        tv_matches_selector.setVisibility(View.VISIBLE);
        ll_matches_view.setVisibility(View.VISIBLE);
    }


    private void getTeamList() {

        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.GET_TEAM_LIST;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put(AppConstants.PREF_TOURNAMENT_ID, mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                requestJson.put("age_group_id", mTeamDetailModel.getAge_group_id());
//                requestJson.put("group_id", mTeamDetailModel.getGroupId());
            } catch (JSONException e) {
                e.printStackTrace();
            }
            AppLogger.LogE(TAG, "url" + url);
            AppLogger.LogE(TAG, "requestJson " + requestJson.toString());
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        if (response != null && !Utility.isNullOrEmpty(response.toString())) {
                            AppLogger.LogE(TAG, "get team list" + response.toString());
                            if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                                if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                    TeamDetailModel mTeamListArray[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamDetailModel[].class);
                                    if (mTeamListArray != null && mTeamListArray.length > 0) {
                                        mTeamList = Arrays.asList(mTeamListArray);

                                        int selectedGroupPos = 0;

                                        for (int i = 0; i < mTeamList.size(); i++) {
                                            if (mTeamList.get(i).getId().equalsIgnoreCase(mTeamDetailModel.getId())) {
                                                AppLogger.LogE(TAG, "selected pos" + selectedGroupPos);
                                                selectedGroupPos = i;
                                                break;
                                            }
                                        }

                                        teamSpinnerAdapter = new TeamSpinnerAdapter((Activity) mContext,
                                                mTeamList);
                                        sp_team.setAdapter(teamSpinnerAdapter);
                                        sp_team.setSelection(selectedGroupPos);
                                        mTeamDetailModel = mTeamList.get(selectedGroupPos);
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
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    try {
                        Utility.StopProgress();
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
