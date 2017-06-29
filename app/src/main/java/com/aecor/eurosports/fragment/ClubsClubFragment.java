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

import com.aecor.eurosports.R;
import com.aecor.eurosports.activity.AgeCategoriesActivity;
import com.aecor.eurosports.adapter.AgeAdapter;
import com.aecor.eurosports.adapter.ClubAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.AgeCategoriesModel;
import com.aecor.eurosports.model.ClubModel;
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

public class ClubsClubFragment extends Fragment {
    private final String TAG = AgeCategoriesActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.et_age_search)
    protected EditText et_age_search;
    @BindView(R.id.age_categories_list)
    protected RecyclerView rv_clubList;
    private AppPreference mPreference;
    private ClubAdapter adapter;

    protected void initView() {
        mPreference = AppPreference.getInstance(mContext);
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(mContext);
        rv_clubList.setLayoutManager(mLayoutManager);
        rv_clubList.setItemAnimator(new DefaultItemAnimator());
        getClubList();
        setListener();
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
        Utility.startProgress(mContext);
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

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(Request.Method
                    .POST, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress();
                    try {
                        AppLogger.LogE(TAG, "Get Tournament Club  List response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                ClubModel mClubList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), ClubModel[].class);
                                if (mClubList != null && mClubList.length > 0) {
                                    setClubAdapter(mClubList);
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

    private void setClubAdapter(ClubModel mClubList[]) {
        List<ClubModel> list = new ArrayList<>();
        list.addAll(Arrays.asList(mClubList));
        adapter = new ClubAdapter((Activity) mContext, list);
        rv_clubList.setAdapter(adapter);
    }

    private class GenericTextMatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {

        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            if (adapter != null && adapter.getFilter() != null) {
                adapter.getFilter().filter(s.toString());
            }
        }

        @Override
        public void afterTextChanged(Editable s) {
        }
    }
}
