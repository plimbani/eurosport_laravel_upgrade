package com.aecor.eurosports.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.View;
import android.widget.ListView;

import com.aecor.eurosports.R;
import com.aecor.eurosports.adapter.FavouriteListAdapter;
import com.aecor.eurosports.gson.GsonConverter;
import com.aecor.eurosports.http.VolleyJsonObjectRequest;
import com.aecor.eurosports.http.VolleySingeltone;
import com.aecor.eurosports.model.TournamentModel;
import com.aecor.eurosports.ui.ProgressHUD;
import com.aecor.eurosports.util.ApiConstants;
import com.aecor.eurosports.util.AppConstants;
import com.aecor.eurosports.util.AppLogger;
import com.aecor.eurosports.util.Utility;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;

import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class FavouritesActivity extends BaseAppCompactActivity {
    private final String TAG = FavouritesActivity.class.getSimpleName();
    private Context mContext;
    @BindView(R.id.favourite_list)
    protected ListView favouriteList;
    private TournamentModel mAllTournamentList[];
    @BindView(R.id.v_seperator)
    protected View v_seperator;

    @Override
    protected void initView() {

        getTournamentList();
    }

    @Override
    protected void setListener() {

    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        BaseAppCompactActivity.selectedTabName = AppConstants.SCREEN_CONSTANT_FAVOURITES;
        setContentView(R.layout.activity_favourites);
        super.onCreate(savedInstanceState);
        ButterKnife.bind(this);
        mContext = this;
        initView();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent mIntent = new Intent(mContext, HomeActivity.class);
        mIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(mIntent);
    }

    private void getTournamentList() {
        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressDialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_TOURNAMENTS;
            final JSONObject requestJson = new JSONObject();

            RequestQueue mQueue = VolleySingeltone.getInstance(mContext)
                    .getRequestQueue();

            final VolleyJsonObjectRequest jsonRequest = new VolleyJsonObjectRequest(mContext, Request.Method
                    .GET, url,
                    requestJson, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    Utility.StopProgress(mProgressDialog);
                    try {
                        AppLogger.LogE(TAG, "Get Tournament List response" + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            if (response.has("data") && !Utility.isNullOrEmpty(response.getString("data"))) {
                                mAllTournamentList = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                                if (mAllTournamentList != null && mAllTournamentList.length > 0) {
                                    getLoggedInUserFavouriteTournamentList();
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
                        Utility.StopProgress(mProgressDialog);
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

    private void getLoggedInUserFavouriteTournamentList() {

        if (Utility.isInternetAvailable(mContext)) {
            final ProgressHUD mProgressDialog = Utility.getProgressDialog(mContext);
            String url = ApiConstants.GET_USER_FAVOURITE_LIST;
            final JSONObject requestJson = new JSONObject();
            try {
                requestJson.put("user_id", Utility.getUserId(mContext));
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
                    Utility.StopProgress(mProgressDialog);
                    try {
                        AppLogger.LogE(TAG, "Get Logged in user favourite tournamenet list " + response.toString());
                        if (response.has("status_code") && !Utility.isNullOrEmpty(response.getString("status_code")) && response.getString("status_code").equalsIgnoreCase("200")) {
                            TournamentModel mFavTournamentList[] = GsonConverter.getInstance().decodeFromJsonString(response.getString("data"), TournamentModel[].class);
                            if (mFavTournamentList != null && mFavTournamentList.length > 0) {
                                setTournamnetAdapter(mAllTournamentList, mFavTournamentList);
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
                        Utility.StopProgress(mProgressDialog);
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


    private void setTournamnetAdapter(TournamentModel mTournamentList[], TournamentModel mFavTournamentList[]) {
        List<TournamentModel> list = new ArrayList<>();
        List<TournamentModel> favList = new ArrayList<>();
        list.addAll(Arrays.asList(mTournamentList));
        if (mFavTournamentList != null) {
            favList.addAll(Arrays.asList(mFavTournamentList));
        }
        Collections.reverse(list);

//        if (list.size() > 5) {
//            v_seperator.setVisibility(View.GONE);
//        }else{
//            v_seperator.setVisibility(View.VISIBLE);
//        }
        if (list.size() > 5) {
            favouriteList.setOverscrollFooter(new ColorDrawable(Color.TRANSPARENT));
        }
        FavouriteListAdapter adapter = new FavouriteListAdapter((Activity) mContext, list, favList);
        favouriteList.setAdapter(adapter);


    }
}
