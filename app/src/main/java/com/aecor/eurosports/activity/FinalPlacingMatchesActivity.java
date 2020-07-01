package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.FinalPlacingMatchesAdapter;
import com.aecor.eurosports.adapter.GroupAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.model.FinalPlacingModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.ui.SimpleDividerItemDecoration;
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
import java.util.List;

import butterknife.BindView;

/**
 * Created by asoni on 05-02-2018.
 * asoni@aecordigital.com
 * Aecor Digital
 */

public class FinalPlacingMatchesActivity  extends BaseAppCompactActivity {
    public final String TAG = "FinalPlacingMatchesActivity";
    private Context mContext;
    @BindView(R.id.rl_search)
    protected RelativeLayout rl_search;
    @BindView(R.id.ll_no_item_view)
    protected LinearLayout ll_no_item_view;
    @BindView(R.id.age_categories_list)
    protected RecyclerView rv_groupList;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    private AppPreference mPreference;
    private FinalPlacingMatchesAdapter adapter;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    private String mAgeGroupId;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.acticity_age_group);
        super.onCreate(savedInstanceState);
        Bundle extras = getIntent().getExtras();
        if (extras == null) {
        } else {
            mAgeGroupId = (String) extras.get(AppConstants.ARG_AGE_CATEGORY_ID);
        }
         mContext = this;
        initView();
    }

    @Override
    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        mPreference = AppPreference.getInstance(mContext);
        ll_no_item_view.setVisibility(View.GONE);
        rl_search.setVisibility(View.GONE);
        getFinalPlacingMatches();
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(mContext);
        rv_groupList.setLayoutManager(mLayoutManager);
        rv_groupList.setItemAnimator(new DefaultItemAnimator());
        rv_groupList.addItemDecoration(new SimpleDividerItemDecoration(mContext));
        showBackButton(getString(R.string.final_placings_title));
    }

    @Override
    protected void setListener() {

    }

    private void setFinalPlacingMatchesAdapter(FinalPlacingModel mFinalPlacingList[]) {
        List<FinalPlacingModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mFinalPlacingList));
        adapter = new FinalPlacingMatchesAdapter((Activity) mContext, list);
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

    private void getFinalPlacingMatches() {

        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_FINAL_PLACING_MATCHES;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                requestJson.put("ageCategoryId", mAgeGroupId);
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
                        AppLogger.LogE(TAG, "get final placing matches Response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                FinalPlacingModel finalPlacingData[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), FinalPlacingModel[].class);
                                if (finalPlacingData != null && finalPlacingData.length > 0) {
                                    setFinalPlacingMatchesAdapter(finalPlacingData);
                                } else {
                                    showNoItemView();
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

}