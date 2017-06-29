package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.Toolbar;
import android.widget.ImageView;
import android.widget.ListView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.AgeAdapter;
import com.aecor.eurosports.adapter.ClubSectionsPagerAdapter;
import com.aecor.eurosports.adapter.TeamAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeCategoriesModel;
import com.aecor.eurosports.model.TeamDetailModel;
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
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

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

    @Override
    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);

        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(getString(R.string.team).toUpperCase());
        toolbar.setTitleTextColor(Color.WHITE);

        getTeamList();
    }

    @Override
    protected void setListener() {

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

        Utility.startProgress(mContext);
        String url = ApiConstants.GET_TEAM_LIST;
        final JSONObject requestJson = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
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

            AppLogger.LogE(TAG, "requestJson " + requestJson.toString());
            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "get team list" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                TeamDetailModel mTeamList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TeamDetailModel[].class);
                                if (mTeamList != null && mTeamList.length > 0) {
                                    setTeamListAdapter(mTeamList);
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
            }, mPreference.getString(AppConstants.PREF_TOKEN));
            mQueue.add(jsonRequest);
        }
    }

    private void setTeamListAdapter(TeamDetailModel mTeamList[]) {
        List<TeamDetailModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTeamList));
        TeamAdapter adapter = new TeamAdapter((Activity) mContext, list);
        lv_team.setAdapter(adapter);
    }
}
