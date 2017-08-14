package com.aecor.eurosports.fragment;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.ClubAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.ClubModel;
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
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

/**
 * Created by system-local on 29-06-2017.
 */

public class ClubsClubFragment extends Fragment {
    private final String TAG = ClubsClubFragment.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_age_search)
    protected EditText et_age_search;
    @BindView(R.id.age_categories_list)
    protected RecyclerView rv_clubList;
    private AppPreference mPreference;
    private ClubAdapter adapter;
    @BindView(R.id.ll_no_item_view)
    protected LinearLayout ll_no_item_view;
    @BindView(R.id.tv_no_item)
    protected TextView tv_no_item;
    @BindView(R.id.rl_search)
    protected RelativeLayout rl_search;
    @BindView(R.id.ll_main_layout)
    protected LinearLayout ll_main_layout;
    @BindView(R.id.iv_close)
    protected ImageView iv_close;


    protected void initView() {
        Utility.setupUI(mContext, ll_main_layout);
        mPreference = AppPreference.getInstance(mContext);
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(mContext);
        rv_clubList.setLayoutManager(mLayoutManager);
        rv_clubList.setItemAnimator(new DefaultItemAnimator());
        rv_clubList.addItemDecoration(new SimpleDividerItemDecoration(mContext));

        ll_no_item_view.setVisibility(View.GONE);
        tv_no_item.setVisibility(View.GONE);
        rl_search.setVisibility(View.GONE);
        getClubList();
        setListener();
        iv_close.setVisibility(View.GONE);
        et_age_search.setHint(getString(R.string.hint_search_club));

    }

    protected void setListener() {
        GenericTextMatcher mTextWatcher = new GenericTextMatcher();
        et_age_search.addTextChangedListener(mTextWatcher);

    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.club_content, container, false);
        ButterKnife.bind(this, view);
        mContext = getActivity();
        initView();
        return view;
    }

    private void getClubList() {
        final ProgressHUD mProgressHUD = Utility.getProgressDialog(mContext);
        String url = ApiConstants.TOURNAMENT_CLUBS;
        final JSONObject requestJson = new JSONObject();
        if (Utility.isInternetAvailable(mContext)) {
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
                    Utility.StopProgress(mProgressHUD);
                    try {
                        if (response != null && !Utility.isNullOrEmpty(response.toString())) {
                            AppLogger.LogE(TAG, "Get Tournament Club  List response" + response.toString());
                            if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                                if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                    ClubModel mClubList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), ClubModel[].class);
                                    if (mClubList != null && mClubList.length > 0) {
                                        setClubAdapter(mClubList);
                                    } else {
                                        showNoItemView();
                                    }
                                }
                            }
                        } else {
                            showNoItemView();
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

    private void setClubAdapter(ClubModel mClubList[]) {
        List<ClubModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mClubList));
        Collections.sort(list, new Comparator<ClubModel>() {
            @Override
            public int compare(ClubModel lhs, ClubModel rhs) {
                //here getTitle() method return app name...
                return lhs.getClubName().compareTo(rhs.getClubName());
            }
        });
        adapter = new ClubAdapter((Activity) mContext, list);
        rv_clubList.setAdapter(adapter);
        rl_search.setVisibility(View.VISIBLE);
        rv_clubList.setVisibility(View.VISIBLE);
    }

    private void showNoItemView() {
        ll_no_item_view.setVisibility(View.VISIBLE);
        tv_no_item.setVisibility(View.VISIBLE);
        tv_no_item.setText(getResources().getString(R.string.no_data_available));
        rl_search.setVisibility(View.GONE);
        rv_clubList.setVisibility(View.GONE);
    }

    private class GenericTextMatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            if (adapter != null && adapter.getFilter() != null) {
                adapter.getFilter().filter(s.toString());
                if (s.toString().length() > 0) {
                    iv_close.setVisibility(View.VISIBLE);
                } else {
                    iv_close.setVisibility(View.GONE);
                }
            }
        }

        @Override
        public void afterTextChanged(Editable s) {
        }
    }

    @OnClick(R.id.iv_close)
    protected void onCloseButtonClicked() {
        et_age_search.setText("");
    }
}
