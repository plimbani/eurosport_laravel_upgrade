package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.GroupAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeCategoriesModel;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.SimpleDividerItemDecoration;
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

/**
 * Created by system-local on 03-07-2017.
 */

public class AgeGroupActivity extends BaseAppCompactActivity {
    public final String TAG = "AgeGroupActivity";
    private Context mContext;
    @BindView(R.id.rl_search)
    protected RelativeLayout rl_search;
    @BindView(R.id.ll_no_item_view)
    protected LinearLayout ll_no_item_view;
    @BindView(R.id.age_categories_list)
    protected RecyclerView rv_groupList;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    private AgeCategoriesModel mSelectedAgeCategoryModel;
    private AppPreference mPreference;
    private GroupAdapter adapter;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.acticity_age_group);
        super.onCreate(savedInstanceState);
        mSelectedAgeCategoryModel = getIntent().getParcelableExtra(AppConstants.ARG_AGE_CATEGORY);

        mContext = this;
        initView();
    }

    @Override
    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        mPreference = AppPreference.getInstance(mContext);
        ll_no_item_view.setVisibility(View.GONE);
        rl_search.setVisibility(View.GONE);
        getAgeGroup();
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(mContext);
        rv_groupList.setLayoutManager(mLayoutManager);
        rv_groupList.setItemAnimator(new DefaultItemAnimator());
        rv_groupList.addItemDecoration(new SimpleDividerItemDecoration(mContext));
        showBackButton(getString(R.string.GROUPS));
    }

    @Override
    protected void setListener() {

    }

    private void setClubGroupAdapter(ClubGroupModel mClubModel[]) {
        List<ClubGroupModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mClubModel));
        adapter = new GroupAdapter((Activity) mContext, list);
        rv_groupList.setAdapter(adapter);

        rv_groupList.setVisibility(View.VISIBLE);

    }

    private void showNoItemView() {
        ll_no_item_view.setVisibility(View.VISIBLE);
        tv_no_item.setVisibility(View.VISIBLE);
        tv_no_item.setText(getResources().getString(R.string.no_data_available));
        rl_search.setVisibility(View.GONE);
        rv_groupList.setVisibility(View.GONE);
    }

    private void getAgeGroup() {
        final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
        String url = ApiConstants.TOURNAMENT_GROUP;
        final JSONObject requestJson = new JSONObject();

        if (Utility.isInternetAvailable(mContext)) {
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                requestJson.put("competationFormatId", mSelectedAgeCategoryModel.getId());
            } catch (JSONException e) {
                e.printStackTrace();
            }

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressHUD);
                    try {
                        AppLogger.LogE(TAG, "get Tournament Group Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                ClubGroupModel clubList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), ClubGroupModel[].class);
                                if (clubList != null && clubList.length > 0) {
                                    setClubGroupAdapter(clubList);
                                } else {
                                    showNoItemView();
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
