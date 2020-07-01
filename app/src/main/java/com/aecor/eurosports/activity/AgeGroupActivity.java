package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Html;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.widget.AppCompatTextView;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.DivisionAdapter;
import com.aecor.eurosports.adapter.GroupAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeGroupModel;
import com.aecor.eurosports.model.ClubGroupModel;
import com.aecor.eurosports.model.DivisionGroupModel;
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
 * Created by system-local on 03-07-2017.
 */

public class AgeGroupActivity extends BaseAppCompactActivity {
    public final String TAG = "AgeGroupActivity";
    private Context mContext;
    @BindView(R.id.ll_no_item_view)
    protected LinearLayout ll_no_item_view;
    @BindView(R.id.rv_groups)
    protected RecyclerView rv_groups;
    @BindView(R.id.rv_divisions)
    protected RecyclerView rv_divisions;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    private AppPreference mPreference;
    private GroupAdapter adapter;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    @BindView(R.id.tv_final_placing)
    protected AppCompatTextView tv_final_placing;
    private String mAgeGroupId;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        setContentView(R.layout.age_categories_with_division);
        super.onCreate(savedInstanceState);
        Bundle extras = getIntent().getExtras();
        if (extras == null) {
        } else {
            mAgeGroupId = (String) extras.get(AppConstants.ARG_AGE_CATEGORY_ID);
        }
//        mAgeGroupId = getIntent().getExtras().getString(AppConstants.ARG_AGE_CATEGORY_ID);
        mContext = this;
        initView();
        setListener();
    }

    @Override
    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        mPreference = AppPreference.getInstance(mContext);
        ll_no_item_view.setVisibility(View.GONE);
        tv_final_placing.setVisibility(View.VISIBLE);
        tv_final_placing.setText(Html.fromHtml("<u>" + tv_final_placing.getText().toString() + "</u>"));
        getAgeGroup();
        rv_groups.setLayoutManager(new LinearLayoutManager(mContext));
        rv_groups.setItemAnimator(new DefaultItemAnimator());
        rv_groups.addItemDecoration(new SimpleDividerItemDecoration(mContext));
        rv_divisions.setLayoutManager(new LinearLayoutManager(mContext));
        rv_divisions.setItemAnimator(new DefaultItemAnimator());
        rv_divisions.addItemDecoration(new SimpleDividerItemDecoration(mContext));
        rv_groups.setNestedScrollingEnabled(false);
        rv_divisions.setNestedScrollingEnabled(false);
        showBackButton(getString(R.string.GROUPS));
    }

    @Override
    protected void setListener() {
        tv_final_placing.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent mFinalPlacingMatchesIntent = new Intent(mContext, FinalPlacingMatchesActivity.class);
                mFinalPlacingMatchesIntent.putExtra(AppConstants.ARG_AGE_CATEGORY_ID, mAgeGroupId + "");
                mContext.startActivity(mFinalPlacingMatchesIntent);
            }
        });
    }

    private void setClubGroupAdapter(List<ClubGroupModel> list, AgeGroupModel mAgeGroupData) {
        adapter = new GroupAdapter((Activity) mContext, list, mAgeGroupData);
        rv_groups.setAdapter(adapter);
        rv_groups.setVisibility(View.VISIBLE);
    }

    private void setDivisionAdapter(List<DivisionGroupModel> list, AgeGroupModel mAgeGroupData) {
        DivisionAdapter adapter = new DivisionAdapter((Activity) mContext, list, mAgeGroupData);
        rv_divisions.setAdapter(adapter);
        rv_divisions.setVisibility(View.VISIBLE);
    }

    private void showNoItemView() {
        ll_no_item_view.setVisibility(View.VISIBLE);
        tv_no_item.setVisibility(View.VISIBLE);
        tv_no_item.setText(getResources().getString(R.string.no_data_available));
        rv_groups.setVisibility(View.GONE);
        rv_divisions.setVisibility(View.GONE);
    }

    private void getAgeGroup() {

        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
            String url = ApiConstants.TOURNAMENT_GROUP;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put("tournamentId", mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
                requestJson.put("competationFormatId", mAgeGroupId);
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
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code"))
                                && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                AgeGroupModel mAgeGroupData = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), AgeGroupModel.class);
                                if (mAgeGroupData != null) {
                                    if (mAgeGroupData.getRound_robin_groups() != null
                                            && mAgeGroupData.getRound_robin_groups().size() > 0) {
                                        setClubGroupAdapter(mAgeGroupData.getRound_robin_groups(), mAgeGroupData);
                                    }
                                    if (mAgeGroupData.getDivision_groups() != null
                                            && mAgeGroupData.getDivision_groups().size() > 0) {
                                        setDivisionAdapter(mAgeGroupData.getDivision_groups(), mAgeGroupData);
                                    }
                                } else {
                                    showNoItemView();
                                }
                            }
                        } else if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code"))
                                && response.getString("status_code").equalsIgnoreCase("500")) {
                            String msg = getString(R.string.selected_tournament_has_expired);
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
