package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.appcompat.widget.AppCompatTextView;
import androidx.appcompat.widget.Toolbar;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.AgeAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeCategoriesModel;
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

public class AgeCategoriesActivity extends BaseAppCompactActivity {

    private final String TAG = AgeCategoriesActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_age_search)
    protected EditText et_age_search;
    @BindView(R.id.age_categories_list)
    protected RecyclerView rv_ageList;
    @BindView(R.id.ll_no_item_view)
    protected LinearLayout ll_no_item_view;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    @BindView(R.id.rl_search)
    protected RelativeLayout rl_search;
    private AppPreference mPreference;
    private AgeAdapter adapter;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    private String mTitle;
    private boolean isShowFinalPlacing = false;

    @Override
    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        mPreference = AppPreference.getInstance(mContext);
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(getApplicationContext());
        rv_ageList.setLayoutManager(mLayoutManager);
        rv_ageList.setItemAnimator(new DefaultItemAnimator());
        rv_ageList.addItemDecoration(new SimpleDividerItemDecoration(mContext));

        getAgeCategories();
        setListener();
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        ((AppCompatTextView) findViewById(R.id.tv_title)).setText("AGE CATEGORIES");
        if (getSupportActionBar() != null) {
            if (!Utility.isNullOrEmpty(mTitle)) {
                showBackButton(mTitle);
                isShowFinalPlacing = true;
            } else {
                BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_AGE_CATEGORIES;
                getSupportActionBar().setTitle(getString(R.string.age_categories).toUpperCase());
                isShowFinalPlacing = false;
            }
        }
        toolbar.setTitleTextColor(Color.WHITE);
        ll_no_item_view.setVisibility(View.GONE);
        tv_no_item.setVisibility(View.GONE);
        rl_search.setVisibility(View.GONE);
    }

    private void showNoItemView() {
        ll_no_item_view.setVisibility(View.VISIBLE);
        tv_no_item.setVisibility(View.VISIBLE);
        tv_no_item.setText(getResources().getString(R.string.no_data_available));
        rl_search.setVisibility(View.GONE);
        rv_ageList.setVisibility(View.GONE);
    }

    @Override
    protected void setListener() {
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_age_categories);
        super.onCreate(savedInstanceState);
        mContext = this;
        Bundle extras = getIntent().getExtras();
        if (extras == null) {
        } else {
            mTitle = (String) extras.get(AppConstants.KEY_SCREEN_TITLE);
        }
        initView();
    }

    private void getAgeCategories() {


        if (Utility.isInternetAvailable(mContext)) {
            Utility.startProgress(mContext);
            String url = ApiConstants.AGE_CATEGORIES;
            final JSONObject requestJson = new JSONObject();
            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();
            try {
                requestJson.put(AppConstants.PREF_TOURNAMENT_ID, mPreference.getString(AppConstants.PREF_SESSION_TOURNAMENT_ID));
            } catch (JSONException e) {
                e.printStackTrace();
            }

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Age categories List response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                AgeCategoriesModel mAgeList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), AgeCategoriesModel[].class);
                                if (mAgeList != null && mAgeList.length > 0) {
                                    setAgeAdapter(mAgeList);
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

    private void setAgeAdapter(AgeCategoriesModel mTournamentList[]) {
        List<AgeCategoriesModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        adapter = new AgeAdapter((Activity) mContext, list, isShowFinalPlacing, true);
        rv_ageList.setAdapter(adapter);
        rv_ageList.setVisibility(View.VISIBLE);

    }

    @Override
    public void onBackPressed() {
        Intent mIntent = new Intent(mContext, HomeActivity.class);
        mIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(mIntent);
    }
}
