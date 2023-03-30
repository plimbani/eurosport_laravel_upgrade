package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Parcelable;

import android.view.View;
import android.widget.AdapterView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import androidx.annotation.Nullable;

import com.aecor.eurosports.BuildConfig;
import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.TeamAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TeamDetailModel;
import com.aecor.eurosports.model.TournamentModel;
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

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import butterknife.BindView;

/**
 * Created by system-local on 29-06-2017.
 */

public class TeamListingActivity extends BaseAppCompactActivity {
    private final String TAG = "TeamListingActivity";
    @BindView(R.id.iv_teamFlag)
    protected ImageView iv_teamFlag;
    @BindView(R.id.lv_team)
    protected ListView lv_team;
    private Context mContext;
    private AppPreference mPreference;
    private String clubId;
    private String groupId;
    private String ageGroupId;
    private List<TeamDetailModel> list;
    @BindView(R.id.ll_no_data)
    protected LinearLayout ll_no_data;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    private TeamAdapter adapter;

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);

        ll_no_data.setVisibility(View.GONE);
        showBackButton(getString(R.string.team));
        getTeamList();
        setListener();
    }


    @Override
    protected void setListener() {
        lv_team.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent mTeamDetailIntent = new Intent(mContext, TeamActivity.class);
                mTeamDetailIntent.putExtra(AppConstants.ARG_TEAM_DETAIL, list.get(position));
                Bundle bundle = new Bundle();
                bundle.putParcelableArrayList(AppConstants.ARG_ALL_TEAM_LIST, (ArrayList<? extends Parcelable>) list);
                mTeamDetailIntent.putExtras(bundle);
                startActivity(mTeamDetailIntent);
            }
        });
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.team_listing);
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_CLUBS;
        super.onCreate(savedInstanceState);
        mContext = this;

        Intent intent = getIntent();
        Bundle bundle = intent.getExtras();

        if (bundle != null) {

            if (bundle.containsKey(AppConstants.ARG_AGE_GROUP_ID)) {
                ageGroupId = bundle.getString(AppConstants.ARG_AGE_GROUP_ID);
                clubId = "";
                groupId = "";
            }

            if (bundle.containsKey(AppConstants.ARG_GROUP_ID)) {
                ageGroupId = "";
                clubId = "";
                groupId = bundle.getString(AppConstants.ARG_GROUP_ID);
            }

            if (bundle.containsKey(AppConstants.ARG_CLUB_ID)) {
                ageGroupId = "";
                clubId = bundle.getString(AppConstants.ARG_CLUB_ID);
                groupId = "";
            }
        }

        initView();
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
                if (!Utility.isNullOrEmpty(ageGroupId)) {
                    requestJson.put("age_group_id", ageGroupId);
                }
                if (!Utility.isNullOrEmpty(clubId)) {
                    requestJson.put("club_id", clubId);
                }
                if (!Utility.isNullOrEmpty(groupId)) {
                    requestJson.put("group_id", groupId);
                }
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
                                    TeamDetailModel mTeamList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamDetailModel[].class);
                                    if (mTeamList != null && mTeamList.length > 0) {
                                        setTeamListAdapter(mTeamList);
                                    } else {
                                        showNoDataView();
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
                        } else {
                            showNoDataView();
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


    private void setTeamListAdapter(TeamDetailModel mTeamList[]) {
        list = new ArrayList<>();
        list.addAll(Arrays.asList(mTeamList));

        Collections.sort(list, new Comparator<TeamDetailModel>() {
            @Override
            public int compare(TeamDetailModel lhs, TeamDetailModel rhs) {
                //here getTitle() method return app name...

                if (lhs.getName() == null) {
                    return (rhs.getName() == null) ? 0 : -1;
                }
                if (rhs.getName() == null) {
                    return 1;
                }
                return lhs.getName().compareTo(rhs.getName());
            }
        });
        adapter = new TeamAdapter(mContext, list, new TeamAdapter.OnFavClick() {
            @Override
            public void onFavClick(int position, boolean isFavAdded) {
                if (isFavAdded) {
                    addFavTeam(list.get(position));
                } else {
                    removeFavTeam(list.get(position));
                }
            }
        });
        lv_team.setAdapter(adapter);
    }

    private void addFavTeam(TeamDetailModel teamDetailModel) {
        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.ADD_USER_FAVOURITE_TEAM;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
                requestJson.put("team_id", teamDetailModel.getId());
                requestJson.put("tournament_id", teamDetailModel.getTournament_id());
            } catch (Exception e) {
                e.printStackTrace();
            }
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("message")) {
                                Utility.showToast(TeamListingActivity.this, response.getString("message"));
                                TournamentModel[] temp = AppPreference.getInstance(TeamListingActivity.this).getTournamentList(TeamListingActivity.this);
                                if (temp != null) {
                                    for (int i = 0; i < temp.length; i++) {
                                        if (String.valueOf(temp[i].getTournamentId()).equals(teamDetailModel.getTournament_id())) {
                                            temp[i].setTeamId(Integer.parseInt(teamDetailModel.getId()));
                                            temp[i].setClubId(Integer.parseInt(teamDetailModel.getClub_id()));
                                        }
                                    }
                                    AppPreference.getInstance(TeamListingActivity.this).setString(AppConstants.TOURNAMENT_LIST, GsonConverter.getInstance().encodeToJsonString(temp));
                                }
                                if (adapter != null) {
                                    adapter.notifyDataSetChanged();
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
                        Utility.StopProgress();
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

    private void removeFavTeam(TeamDetailModel teamDetailModel) {
        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.REMOVE_USER_FAVOURITE_TEAM;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
                requestJson.put("team_id", teamDetailModel.getId());
                requestJson.put("tournament_id", teamDetailModel.getTournament_id());
            } catch (Exception e) {
                e.printStackTrace();
            }
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("message")) {
                                Utility.showToast(TeamListingActivity.this, response.getString("message"));
                                TournamentModel[] temp = AppPreference.getInstance(TeamListingActivity.this).getTournamentList(TeamListingActivity.this);
                                if (temp != null) {
                                    for (int i = 0; i < temp.length; i++) {
                                        if (String.valueOf(temp[i].getTournamentId()).equals(teamDetailModel.getTournament_id())) {
                                            temp[i].setTeamId(0);
                                            temp[i].setClubId(0);
                                        }
                                    }
                                    AppPreference.getInstance(TeamListingActivity.this).setString(AppConstants.TOURNAMENT_LIST, GsonConverter.getInstance().encodeToJsonString(temp));
                                }
                                if (adapter != null) {
                                    adapter.notifyDataSetChanged();
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
                        Utility.StopProgress();
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

    private void showNoDataView() {
        ll_no_data.setVisibility(View.VISIBLE);
        tv_no_item.setText(getString(R.string.no_data_available));
    }
}
